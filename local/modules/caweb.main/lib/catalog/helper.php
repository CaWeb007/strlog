<?
namespace Caweb\Main\Catalog;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;

Loader::includeModule('catalog');

class Helper{
    const SITE_PRICE_MODEL = array(14,9,10,11);
    const SITE_GROUP_MODEL = array(9,10,11,12,14,15);
    public static $userPriceId = 0;
    public static $instance = null;
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
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
    public static function setCanonicalLink($link){
        if (empty($link)) return;
        $link = ltrim($link, '/');
        $link = 'https://xn--80afpacjdwcqkhfi.xn--p1ai/'.$link;
        $string = "<link rel=\"canonical\" href=\"".$link."\" />";
        Asset::getInstance()->addString($string, true, 'BEFORE_CSS');
    }
}