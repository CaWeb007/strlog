<?
namespace Caweb\Main\Events;
use Bitrix\Main\Application;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Main\Web\Uri;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper as CatalogHelper;
use Caweb\Main\Log\Write;
use Caweb\Main\ORD;
use Caweb\Main\Tools;

Loc::loadLanguageFile(__FILE__);
class Main{
    public static $instance = null;
    public function OnBeforeUserRegister(&$arFields){
        if (empty($arFields["PERSONAL_PROFESSION"]))
            $arFields["PERSONAL_PROFESSION"] = 'КП(ФИЗ)';
        if (($arFields["PERSONAL_PROFESSION"]=="КП(ЮР)") && !Helper::checkLegal($arFields)){
            return false;
        }
    }
    public function OnBeforeUserLogin(&$arFields){
        $user = \CUser::GetByLogin($arFields['LOGIN'])->Fetch();
        if ($user['ACTIVE'] === 'Y') return;
        $groupTO = !empty(UserGroupTable::getRow(array('filter' => array(
            'USER_ID' => (int)$user['ID'],
            'GROUP_ID' => 11
        ))));
        if ($groupTO){
            global $APPLICATION;
            $APPLICATION->throwException("Ваш аккаунт торговой организации был заблокирован. Пожалуйста обратитесь к Вашему менеджеру");
            return false;
        }
    }
    public function OnAfterSetUserGroup($userId, $arFields){
        if ((int)$userId === 8643) return;
        if ((int)$userId === 1) return;
        $systemGroups = CatalogHelper::SITE_GROUP_MODEL;
        $groups = array();
        $groups = array_keys($arFields);
        $check = array_intersect($groups, $systemGroups);
        if (!empty($check)) return;
        $props['filter'] = array('USER_ID' => $userId);
        $props['order'] = array('DATE_UPDATE' => 'desc');
        $props['limit'] = 1;
        $typeId = (int)UserPropsTable::getRow($props)['PERSON_TYPE_ID'];
        if (empty($typeId)) return;
        $addGroup = 9;
        if ($typeId === 2) $addGroup = 14;
        \CUser::SetUserGroup($userId, array_merge(array($addGroup), $groups));
    }

    public function updateUserGroups(){
        $db = UserTable::getList(array('select' => array('ID')));
        while ($ar = $db->fetch()){
            $g = \CUser::GetUserGroup($ar['ID']);
            $check = array_intersect($g, Helper::SITE_GROUP_MODEL);
            if (!empty($check)) continue;
            $props['filter'] = array('USER_ID' => $ar['ID']);
            $props['order'] = array('DATE_UPDATE' => 'desc');
            $props['limit'] = 1;
            $typeId = (int)UserPropsTable::getRow($props)['PERSON_TYPE_ID'];
            if (empty($typeId)) return;
            $new = 9;
            if ($typeId === 2) $new = 14;
            $arNew = array_merge($g, array($new));
            \CUser::SetUserGroup($ar['ID'], $arNew);
        }
    }
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
    public static function OnBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files, &$languageId){
        if (($event !== 'CATALOG_PRODUCT_SUBSCRIBE_NOTIFY') && ($event !== 'CATALOG_PRODUCT_SUBSCRIBE_NOTIFY_REPEATED')) return true;
        if (empty($arFields['PRODUCT_ID'])) return true;
        if (!Loader::includeModule('iblock')) return true;
        $db = \CIBlockElement::GetList(
                array(),
                array('ID' => (int)$arFields['PRODUCT_ID']),
                false,
                false,
                array('ID', 'ACTIVE', 'IBLOCK_ID')
            );
        $item = $db->GetNextElement();
        $itemFields = array();
        $itemFields = $item->GetFields();
        $itemFields['NOT_WORK'] = $item->GetProperty(533);
        $itemFields['CML2_TRAITS'] = $item->GetProperty(90);
        $notActive = $itemFields['ACTIVE'] !== 'Y';
        $disable = (int)$itemFields['NOT_WORK']['VALUE_ENUM_ID'] === 6036;
        $cmlDontWork = in_array(Loc::getMessage('CML_DONT_WORK'),$itemFields['CML2_TRAITS']['VALUE']);
        if ($notActive || $disable || $cmlDontWork){
            Helper::setNeedSubscribeSanding($arFields['USER_CONTACT'], $arFields['PRODUCT_ID']);
            return false;
        }
    }
    /**@var $oAdminList \CAdminList*/
    public static function addContextButtonInFiles(&$oAdminList){
        $type = Iblock::CONTENT_IBLOCK_TYPE;
        $iblock = Iblock::FILES_IBLOCK_ID;
        if ( $oAdminList->table_id == "tbl_iblock_list_".md5($type.".".$iblock) )
        {
            $arActions = $oAdminList->arActions;

            $arActions['custom_JS'] = Loc::getMessage('COPY_IBLOCK_ID');

            $oAdminList->AddGroupActionTable($arActions);
        }
    }
    public static function contextButtonInFilesHandler(){
        $oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        if ($oRequest->isAdminSection()){
            if (
                $oRequest->get('mode')=='frame'
                && $oRequest->get('IBLOCK_ID')==Iblock::FILES_IBLOCK_ID
                && $oRequest->get('action')=='custom_JS'
            ){
                $IDs = $oRequest->getPost('ID');
                $arId = array();
                foreach ($IDs as $item){
                    $arId[] = intval(substr($item, 1));
                }
                $db = \CIBlockElement::GetList(array('id' => 'asc'), array('ID' => $arId, 'IBLOCK_ID'=> Iblock::FILES_IBLOCK_ID), false, false, array('XML_ID'));
                $ids = array();
                while($ar = $db->Fetch()){
                    if (empty($ar['XML_ID'])) continue;
                    $ids[] = (int)$ar['XML_ID'];
                }
                echo "<script>prompt('".Loc::getMessage('COPY_MESSAGE')."', '".implode(',',$ids)."')</script>";
            }
        }
    }
    /**@var $oAdminList \CAdminList*/

    public static function addContextButtonForOrd(&$oAdminList){
        $checkIblock = false;
        $type = Iblock::ADV_IBLOCK_TYPE;
        $iblock = Iblock::MAIN_BANNERS_IBLOCK_ID;
        $tableId = md5($type.".".$iblock);
        $checkIblock = $oAdminList->table_id == "tbl_iblock_list_".$tableId;
        if (!$checkIblock){
            $type = Iblock::CONTENT_IBLOCK_TYPE;
            $iblock = Iblock::SALES_IBLOCK_ID;
            $tableId = md5($type.".".$iblock);
            $checkIblock = $oAdminList->table_id == "tbl_iblock_list_".$tableId;
        }
        if (!$checkIblock){
            $type = Iblock::CONTENT_IBLOCK_TYPE;
            $iblock = Iblock::NEWS_IBLOCK_ID;
            $tableId = md5($type.".".$iblock);
            $checkIblock = $oAdminList->table_id == "tbl_iblock_list_".$tableId;
        }
        if (!$checkIblock) return;
        if(isset($_REQUEST["del_filter"]) && $_REQUEST["del_filter"] != "")
            $find_section_section = -1;
        elseif(isset($_REQUEST["find_section_section"]))
            $find_section_section = $_REQUEST["find_section_section"];
        else
            $find_section_section = -1;
        $sThisSectionUrl = '&type='.urlencode($type).'&lang='.LANGUAGE_ID.'&IBLOCK_ID='.$iblock.'&find_section_section='.intval($find_section_section);
        $rows = $oAdminList->aRows;
        $propertyId = Tools::getInstance()->getPropertyIdByCode(Iblock::PROPERTY_MARKER_ORD_CODE, $iblock);
        /**@var $row \CAdminListRow*/
        foreach ($rows as $row){
            $fields = $row->aFields;
            $ID = $row->id;
            if (substr($ID,0,1) !== 'E') continue;
            $prop = $row->aFields['PROPERTY_'.$propertyId];
            if (empty($fields['PROPERTY_'.$propertyId])) continue;
            if ($row->arRes['ACTIVE'] !== 'Y') continue;
            $markerExist = !empty($prop['view']['value']);
            $arActions = $row->aActions;
            $arActions[] = array(
                'SEPARATOR' => true
            );
            if (!$markerExist){
                $arActions[] = array(
                    'TEXT'=> 'Создать креатив ОРД',
                    'ACTION' => $oAdminList->ActionDoGroup($row->id, 'createOrdCreative', $sThisSectionUrl),
                    'ONCLICK' => ''
                );
            }else{
                $arActions[] = array(
                    'TEXT'=> 'Обновить креатив ОРД',
                    'ACTION' => $oAdminList->ActionDoGroup($row->id, 'updateOrdCreative', $sThisSectionUrl),
                    'ONCLICK' => ''
                );
            }
            $row->AddActions($arActions);
        }

    }

    public static function adminOrdActionHandler(){
        $checkIblock = false;
        $propertyCode = Iblock::PROPERTY_MARKER_ORD_CODE;
        $oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        $create = $oRequest['action_button'] === 'createOrdCreative';
        $update = $oRequest['action_button'] === 'updateOrdCreative';
        $ID = $oRequest->get('ID');
        $type = substr($ID,0,1);
        $ID = intval(substr($ID, 1));
        if (!$create && !$update) return;
        $iblockId = Iblock::MAIN_BANNERS_IBLOCK_ID;
        $checkIblock = (int)$oRequest->get('IBLOCK_ID') == $iblockId;
        if (!$checkIblock){
            $iblockId = Iblock::SALES_IBLOCK_ID;
            $checkIblock = (int)$oRequest->get('IBLOCK_ID') == $iblockId;
        }
        if (!$checkIblock){
            $iblockId = Iblock::NEWS_IBLOCK_ID;
            $checkIblock = (int)$oRequest->get('IBLOCK_ID') == $iblockId;
        }
        if (!$checkIblock) return;
        if (!$oRequest->isAdminSection()) return;
        if ($type !== 'E') return;
        Loader::includeModule('iblock');
        $element = \CIBlockElement::GetByID($ID)->GetNextElement();
        if (empty($element)) return;
        $fields = $element->GetFields();
        $prop = $element->GetProperty($propertyCode)['VALUE'];
        if ($create && !empty($prop)) return;
        if ($update && empty($prop)) return;
        if ($create){
            $externalID = md5($fields['ID']);
        }
        if ($update){
            $externalID = $fields['XML_ID'];
        }
        try {
            Loader::includeModule('caweb.main');
            $ord = new ORD();
            $body = array(
                "name"=> $fields['NAME'],
                "brand"=> $fields['NAME'],
                "category"=> $fields['NAME'],
                "description"=> $fields['NAME'],
                "okveds" => array('46.73')
            );
            $ord->setBody($body);
            $ord->setExternalId($externalID);
            $ord->doQuery();
            $marker = $ord->getMarker();
            if ($create){
                $elementEntity = new \CIBlockElement();
                $elementEntity::SetPropertyValuesEx($ID, $iblockId, array(
                    $propertyCode => $marker
                ));
                $elementEntity->Update($ID, array('XML_ID' => $externalID));
            }
        }catch (\Exception $exception){
            echo '<script>alert("'.$exception->getMessage().'")</script>';
        }
    }
}
