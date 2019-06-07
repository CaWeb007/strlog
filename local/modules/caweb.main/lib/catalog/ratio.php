<?
namespace Caweb\Main\Catalog;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Catalog\MeasureRatioTable;
use Bitrix\Main\DB\Exception;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loader::includeModule('catalog');
Loc::loadLanguageFile(__FILE__);

class Ratio{
    const BRICKS_SECTION = 2193;
    const STRLOG_IBLOCK_ID = 16;
    protected $log = array(
        'COMPLETE' => '', 'ERRORS' => '', 'COUNT' => 0, 'COMPLETE_COUNT' => 0, 'CONTINUE_COUNT' => 0, 'ERRORS_COUNT' => 0);
    public static $instance = null;
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
    public function measureRatioConfig($sectionId){
        if ((int)$sectionId === static::BRICKS_SECTION) $this->setBricksRatio();
    }
    protected function setBricksRatio(){
        $arElements = $this->getBricks();
        foreach ($arElements as $fields){
            $this->setRatio($fields['ID'], $fields['COUNT'], $fields['NAME']);
        }
    }
    public function getLog(){
        $result = '';
        $log = $this->log;
        $count = $log['COUNT'];
        $d = "\r\n";
        $result .= $log['COMPLETE'].$d;
        $result .= Loc::getMessage(
            'RATIO_COMPLETE', array('#COMPLETE_COUNT#' => $log['COMPLETE_COUNT'], '#COUNT#' => $count)).$d;
        $result .= Loc::getMessage(
                'RATIO_CONTINUE', array('#CONTINUE_COUNT#' => $log['CONTINUE_COUNT'], '#COUNT#' => $count)).$d;
        $result .= Loc::getMessage(
                'ERRORS_COUNT', array('#ERRORS_COUNT#' => $log['ERRORS_COUNT'], '#COUNT#' => $count)).$d;
        $result .= $log['ERRORS'].$d;
        return $result;
    }
    protected function getBricks(){
        $result = array();
        $elementCount = 0;
        $filter = array('IBLOCK_ID' => static::STRLOG_IBLOCK_ID, 'IBLOCK_SECTION_ID' => static::BRICKS_SECTION);
        $select = array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_KOLICHESTVO_V_UPAKOVKE_SHT');
        $db = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($ar = $db->Fetch()){
            $elementCount++;
            $k = (float)$ar["PROPERTY_KOLICHESTVO_V_UPAKOVKE_SHT_VALUE"];
            if ($k <= 0){
                $this->log['ERRORS_COUNT'] = $this->log['ERRORS_COUNT'] + 1;
                $this->log['ERRORS'] .= Loc::getMessage('ERRORS_EMPTY_RATIO', array('#NAME#' => $ar['NAME']));
                continue;
            }
            $result[] = array('ID' => (int)$ar['ID'], 'COUNT' => $k, 'NAME' => $ar['NAME']);
        }
        $this->log['COUNT'] = $elementCount;
        return $result;
    }
    protected function setRatio($productId, $ratio, $name = ''){
        $id = null;
        $db = MeasureRatioTable::getRow(array('filter' => array('PRODUCT_ID' => $productId)));
        if ((float)$db['RATIO'] === (float)$ratio) {
            $this->log['CONTINUE_COUNT'] = $this->log['CONTINUE_COUNT'] + 1;
            return;
        };
        $id = (int)$db['ID'];
        $res = null;
        $fields = array('PRODUCT_ID' => (int)$productId, 'RATIO' => (float)$ratio, 'IS_DEFAULT' => true);
        try{
            if (!empty($id)){
                unset($fields['PRODUCT_ID'], $fields['IS_DEFAULT']);
                $res = MeasureRatioTable::update($id, $fields);
            }else{
                $res = MeasureRatioTable::add($fields);
            }
        }catch (Exception $e){
            $this->log['ERRORS_COUNT'] = $this->log['ERRORS_COUNT'] + 1;
            $this->log['ERRORS'] .= Loc::getMessage('ERRORS_EXCEPTION',
                array('#NAME#' => $name, 'TEXT' => $e->getMessage()));
        }
        if (empty($res)) return;
        if ($res->isSuccess()){
            $this->log['COMPLETE_COUNT'] = $this->log['COMPLETE_COUNT'] + 1;
            $this->log['COMPLETE'] .= Loc::getMessage('RATIO_CONFIG_LOG',
                array('#NAME#' => $name, '#RATIO#' => $ratio));
        }else{
            $this->log['ERRORS_COUNT'] = $this->log['ERRORS_COUNT'] + 1;
            $this->log['ERRORS'] .= Loc::getMessage('ERRORS_BD', array('#NAME#' => $name));
        }
    }
}