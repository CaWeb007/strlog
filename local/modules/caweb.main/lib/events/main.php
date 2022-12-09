<?
namespace Caweb\Main\Events;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper as CatalogHelper;
use Caweb\Main\Log\Write;

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
}
