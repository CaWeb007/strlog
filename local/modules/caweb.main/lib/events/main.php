<?
namespace Caweb\Main\Events;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper as CatalogHelper;
Loc::loadLanguageFile(__FILE__);
class Main{
    public static $instance = null;
    public function OnBeforeUserRegister($arFields){
        if (($arFields["PERSONAL_PROFESSION"]=="КП(ЮР)") && Helper::notUniqueLegalUser($arFields['UF_INN'], $arFields['UF_KPP'])){
            global $APPLICATION;
            $APPLICATION -> ThrowException(Loc::getMessage('CAWEB_NOT_UNIQUE_LEGAL_USER'));
            return false;
        }
    }
    public function OnAfterSetUserGroup($userId, $arFields){
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
}
