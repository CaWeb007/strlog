<?
namespace Caweb\Main\Catalog;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

class Search{
    public static $instance = null;
    protected $get = '';
    protected $elementArray = array();
    protected $sortArray = array();
    protected $arSectionIdWithElementId = array();
    protected $arElementIdWithSortKey = array();
    protected $arElementIdWithElementName = array();
    protected $arSectionIdWithSectionName = array();
    protected $arCustomRank = array();
    protected $arElementIdWithSectionId = array();
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
    public function initSort(){
        $this->get = Application::getInstance()->getContext()->getRequest()->get('q');
        Loader::includeModule('iblock');
    }
    protected function ifNoRequest(){
        return empty($this->get);
    }
    public function setElementArray($key, $arItem){
        if ($this->ifNoRequest()) return;
        /**$var array $arItem ['ID'=>string, 'IBLOCK_SECTION_ID'=>string, 'NAME'=>string]*/
        $this->elementArray[$key] = array('ID' => $arItem['ID'], 'NAME' => $arItem['NAME'],
            'IBLOCK_SECTION_ID' => $arItem['IBLOCK_SECTION_ID']);
    }
    public function sortElements($arItems){
        if ($this->ifNoRequest()) return $arItems;
        $this->initSectionSort();
        return $this->getSortedArray($arItems);
    }
    protected function initSectionSort(){
        $this->setVariables();
        $this->setSortArray();
    }
    protected function setSortArray(){
        $this->sortByElementName();
        $this->sortBySectionName();
        $this->sortByCustomRank();
    }
    protected function sortBySectionName(){
        $sortArray = $this->getSortArray();
        $tmpArray = array();
        $tmpArray2 = array();
        foreach ($this->arSectionIdWithSectionName as $id => $name){
            if (($i = strpos($name, $this->get)) !== false){
                $tmpArray[$i][$id] = '';
            }
        }
        ksort($tmpArray);
        foreach ($tmpArray as $array){
            $key = 0;
            foreach ($array as $key => $value) break;
            $tmpArray2 += array($key => $sortArray[$key]);
        }
        $this->sortArray = $tmpArray2 + $sortArray;
    }
    protected function sortByElementName(){
        $sortArray = $this->getSortArray();
        $tmpArray = array();
        $tmpArray2 = array();
        foreach ($this->arElementIdWithElementName as $id => $name){
            if (($i = strripos($name, $this->get)) !== false){
                $tmpArray[$i][$this->arElementIdWithSectionId[$id]] = $id;
            }
        }
        ksort($tmpArray);
        foreach ($tmpArray as $array){
            $key = 0;
            foreach ($array as $key => $value) break;
            $tmpArray2 += array($key => $sortArray[$key]);
        }
        $this->sortArray = $tmpArray2 + $sortArray;
    }
    protected function sortByCustomRank() {
        $sortArray = $this->getSortArray();
        $tmpArray = array();
        $tmpArray2 = array();
        foreach ($this->arSectionIdWithElementId as $sectionId => $arElementId){
            if (($i = $this->getSectionRank($arElementId)) !== false){
                $tmpArray[$i][$sectionId] = '';
            }
        }
        ksort($tmpArray);
        foreach ($tmpArray as $array){
            $key = 0;
            foreach ($array as $key => $value) break;
            $tmpArray2 += array($key => $sortArray[$key]);
        }
        $this->sortArray = $tmpArray2 + $sortArray;
    }
    protected function getSortArray(){
        if (!empty($this->sortArray)) return $this->sortArray;
        return $this->arSectionIdWithElementId;
    }
    protected function setVariables() {
        foreach ($this->elementArray as $key => $arItem){
            $this->arSectionIdWithElementId[$arItem['IBLOCK_SECTION_ID']][] = $arItem['ID'];
            $this->arElementIdWithSortKey[$arItem['ID']] = $key;
            $this->arElementIdWithElementName[$arItem['ID']] = $arItem['NAME'];
            $this->arElementIdWithSectionId[$arItem['ID']] = $arItem['IBLOCK_SECTION_ID'];
        }
        $this->setCustomRank();
        $this->setSectionArray();
    }
    protected function setSectionArray() {
        $param['filter'] = array('ID' => array_keys($this->arSectionIdWithElementId));
        $param['select'] = array('ID', 'NAME');
        $db = SectionTable::getList($param);
        while ($ar = $db->fetch()){
            $this->arSectionIdWithSectionName[$ar['ID']] = $ar['NAME'];
        }
    }
    protected function getSortedArray($arItem) {
        $sortArray = $this->getSortArray();
        $result = array();
        foreach ($sortArray as $sectionId => $arElementIds){
            $sectionName = $this->arSectionIdWithSectionName[$sectionId];
            foreach ($arElementIds as $intElementId){
                $elementKey = $this->arElementIdWithSortKey[$intElementId];
                $merge['groupper'] = array(
                    'TITLE' => $sectionName,
                    'VALUE' => $sectionId,
                    'SECTION' => 'Y'
                );
                $result[] = $arItem[$elementKey] + $merge;
            }
        }
        return $result;
    }
    protected function setCustomRank() {
        $filter = array('MODULE_ID' => 'iblock', 'PARAM_1' => '1c_catalog', 'PARAM_2'=> '16', 'ITEM_ID' => array_keys($this->arElementIdWithSortKey));
        $db = \CSearchCustomRank::GetList(array(), $filter);
        while ($ar = $db->Fetch()){
            $this->arCustomRank[$ar['RANK']][] = (int)$ar['ITEM_ID'];
        }
    }
    protected function getSectionRank($arElementId) {
        $rank = 10000000;
        foreach ($this->arCustomRank as $key => $arRankElementId) {
            if (!empty(array_intersect($arRankElementId, $arElementId)) && ($key < $rank))
                $rank = $key;
        }
        if ($rank === 10000000) $rank = false;
        return $rank;
    }
}