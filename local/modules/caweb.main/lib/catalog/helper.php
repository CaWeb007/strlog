<?
namespace Caweb\Main\Catalog;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Main\Loader;

Loader::includeModule('catalog');

class Helper{
    const SITE_PRICE_MODEL = array(14,9,10,11);
    public static $userPriceId = 0;
    public static function getUserPriceId(){
        if (!empty(static::$userPriceId)) return static::$userPriceId;
        global $USER;
        $userGroupsArray = $USER->GetUserGroupArray();
        $params['filter'] = array('GROUP_ID' => $userGroupsArray, 'ACCESS' => GroupAccessTable::ACCESS_BUY);
        $params['select'] = array('CATALOG_GROUP_ID');
        $priceArray = array();
        $db = GroupAccessTable::getList($params);
        while($ar = $db->fetch()){
            $priceArray[] = $ar['CATALOG_GROUP_ID'];
        }
        static::$userPriceId = static::getMinCatalogPrice($priceArray);
        return static::$userPriceId;

    }
    public static function getMinCatalogPrice(array $arPrices){
        $result = array();
        $model = self::SITE_PRICE_MODEL;
        $result = array_intersect($model, $arPrices);
        if (empty($result)) return array_pop($model);
        return array_shift($result);
    }
}