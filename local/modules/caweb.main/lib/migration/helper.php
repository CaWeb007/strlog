<?
namespace Caweb\Main\Migration;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Caweb\Main\Sale\Bonus;

/**
 * Class Helper
 * @package Caweb\Main\Migration
 */
class Helper{
    protected static $messages = array();
    protected static $CATALOG_ID = 16;
    public static function init($class){
        $object = Base::getInstance($class);
        if (!Application::getConnection()->isTableExists($object->getDBTableName()))
            $object->createDbTable();
    }
    /**usage in bitrix php console
     * \Bitrix\Main\Loader::includeModule('caweb.main');
     * \Caweb\Main\Migration\Helper::bonusMigrate();*/
    public static function bonusMigrate(){
        try{
            Loader::includeModule('iblock');
            self::setIblockProperties();
            self::setUserProperties();
        }catch (\Exception $exception){
            self::$messages[] = 'error '. $exception->getMessage();
        }
        echo implode('   |||   ', self::$messages);
    }
    protected static function setIblockProperties(){
        self::addIblockProperty(Bonus::IBLOCK_PROPERTIES_MODEL[3], array(
            'IBLOCK_ID' => self::$CATALOG_ID,
            'NAME' => 'Бонус КП 15%',
            'PROPERTY_TYPE' => PropertyTable::TYPE_NUMBER
        ));
        $iterator = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::$CATALOG_ID),
            false, false,
            array('ID', 'IBLOCK_ID','CATALOG_GROUP_9', 'CATALOG_GROUP_11','PROPERTY_'.Bonus::IBLOCK_PROPERTIES_MODEL[3]));
        while ($ar = $iterator->Fetch()){
            if ($ar['PROPERTY_'.Bonus::IBLOCK_PROPERTIES_MODEL[3].'_VALUE'] !== null) continue;
            $priceKP = $ar['CATALOG_PRICE_11'];
            $priceTO = $ar['CATALOG_PRICE_9'];
            $fields = array(
                Bonus::IBLOCK_PROPERTIES_MODEL[3] => roundEx((($priceKP - $priceTO) * 0.15), 2),
            );
            \CIBlockElement::SetPropertyValuesEx($ar['ID'], $ar['IBLOCK_ID'], $fields);
        }
    }
    private static function setUserProperties() {
        self::addUfProperty(array(
            'ENTITY_ID' => 'USER',
            'FIELD_NAME' => 'UF_BONUS_CARD',
            'USER_TYPE_ID' => 'integer',
            'EDIT_FORM_LABEL' => array('ru' => 'Тип бонусной карты')
        ));
        $by = 'ID';
        $order = 'asc';
        $db = \CUser::GetList($by, $order, array(), array('SELECT' => array('UF_*')));
        while ($ar = $db->Fetch()){
            $check = $ar['UF_BONUS_CARD'];
            if ($check !== null) continue;
            $id = (int)$ar['ID'];
            $group = (int)array_shift(array_intersect(\CUser::GetUserGroup($id), Bonus::SITE_GROUP_MODEL));
            $bonusCard = 0;
            if ($group === 9) $bonusCard = 1;
            if ($group === 15) $bonusCard = 2;
            if (($group === 15) && ((float)$ar['UF_ACCUMULATION'] >= 200000)) $bonusCard = 3;
            $object = new \CUser();
            $res = $object->Update($id, array('UF_BONUS_CARD' => $bonusCard));
            if ($res === false)
                throw new SystemException('user '.$ar['ID'].' error uf update woth error '.$object->LAST_ERROR);
        }
    }
    protected static function addIblockProperty($code, $fields){
        if ((int)PropertyTable::getCount(array('CODE' => $code)) !== 0){
            self::$messages[] = 'property '.$code.' already is exist';
            return;
        }
        $fields['CODE'] = $code;
        $require = array('IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE');
        foreach ($require as $item)
            if (empty($fields[$item])) throw new ArgumentNullException($item);
        $res = PropertyTable::add($fields);
        if ($res->isSuccess())
            self::$messages[] = 'create property '.$code.' is done';
        else
            throw new SystemException('dont create property '.$code.',with messages: '.implode(' || ', $res->getErrorMessages()));
    }
    protected static function addUfProperty($fields){
        global $APPLICATION;
        $require = array('ENTITY_ID', 'FIELD_NAME', 'USER_TYPE_ID', 'EDIT_FORM_LABEL');
        foreach ($require as $item)
            if (empty($fields[$item])) throw new ArgumentNullException($item);
        $objectUf = new \CUserTypeEntity();
        $db = $objectUf::GetList(array(), array('ENTITY_ID' => $fields['ENTITY_ID'], 'FIELD_NAME' => $fields['FIELD_NAME']));
        if (!empty($db->Fetch())){
            self::$messages[] = 'property '.$fields['FIELD_NAME'].' already is exist';
            return;
        }
        $res = $objectUf->Add($fields);
        if (!empty($res))
            self::$messages[] = 'create uf property '.$fields['FIELD_NAME'].' is done';
        else
            throw new SystemException('dont create property '.$fields['FIELD_NAME'].' with error: '.$APPLICATION->GetException()->GetString());
    }
}