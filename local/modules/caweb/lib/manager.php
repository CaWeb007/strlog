<?

namespace Caweb;
use Bitrix\Catalog\GroupTable;
use Bitrix\Catalog\StoreTable;

class Manager{
    static $pageTabIndex;
    static $catalogPriceCodes = array();
    static $catalogStoreCodes = array();
    public static function setPageTabIndex($value){
        static::$pageTabIndex = $value;
    }
    public static function getPageTabIndex(){
        return static::$pageTabIndex;
    }
    public static function getCatalogPriceCodes(){
        $catalogPriceCodes = static::$catalogPriceCodes;
        if (!empty($catalogPriceCodes)) return $catalogPriceCodes;
        $params['select'] = array('NAME');
        $db = StoreTable::getList($params);
        while ($ar = $db->fetch()){
            $catalogPriceCodes[] = $ar['NAME'];
        }
        return static::$catalogPriceCodes = $catalogPriceCodes;
    }
    public static function getCatalogStoreCodes(){
        $catalogStoreCodes = static::$catalogStoreCodes;
        if (!empty($catalogStoreCodes)) return $catalogStoreCodes;
        $params['filter'] = array('ACTIVE' => 'Y');
        $params['select'] = array('TITLE');
        $db = StoreTable::getList($params);
        while ($ar = $db->fetch()){
            $catalogStoreCodes[] = $ar['TITLE'];
        }
        return static::$catalogStoreCodes = $catalogStoreCodes;
    }
    public static function getNotEmptyIblocksID(){

    }
}