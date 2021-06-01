<?
namespace Caweb\Main\Catalog;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loader::includeModule('catalog');
Loc::loadMessages(__FILE__);
class Helper{
    const SITE_PRICE_MODEL = array(14,9,15,10,11);
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
    public static function getProductPrice($id, $priceId) {
        $param['filter'] = array('PRODUCT_ID' => $id, 'CATALOG_GROUP_ID' => $priceId);
        $param['select'] = array('ID','PRICE');
        return PriceTable::getRow($param);
    }
    public static function  FormatCurrency($fSum = ""){
        if (empty($fSum)) return "";
        $fSumArray = explode(' ', $fSum);
        return Loc::getMessage('CAWEB_CATALOG_HELPER_CUR_FORMAT', array('#NUM#'=>$fSumArray[0],'#CUR#'=>$fSumArray[1]));

    }
}