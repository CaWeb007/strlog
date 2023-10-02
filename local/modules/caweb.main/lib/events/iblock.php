<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 05.04.2019
 * Time: 16:12
 */

namespace Caweb\Main\Events;


use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Caweb\Main\Log\Write;
use \Bitrix\Main\Entity;
use Caweb\Main\ORD;

class Iblock{
    public static $instance = null;
    public static $disableEvents = false;
    const SCU_IBLOCK = 23;
    const CATALOG_IBLOCK = 16;
    const DO_NOT_DEACTIVATE_SECTION = array(2155);
    const FILES_IBLOCK_ID = 38;
    const MAIN_BANNERS_IBLOCK_ID = 2;
    const SALES_IBLOCK_ID = 12;
    const NEWS_IBLOCK_ID = 11;
    const CONTENT_IBLOCK_TYPE = 'aspro_optimus_content';
    const ADV_IBLOCK_TYPE= 'aspro_optimus_adv';
    const PROPERTY_MARKER_ORD_CODE = 'MARKER_ORD';
    const PROPERTY_BANNER_LINK_CODE = 'URL_STRING';
    const PROPERTY_RELATED_BANNER_ELEMENT_CODE = 'RELATED_ELEMENT';
    public function SortSku(&$arParams){
        $iblockId = (int)$arParams['IBLOCK_ID'];
        if ($iblockId !== 23) return $arParams;
        if ((int)$arParams['SORT'] === 123) return $arParams;
        //Write::file('iblock_sku', $arParams, true);
        $flag = null;
        $flag = $arParams["PROPERTY_VALUES"][609][0]['VALUE'];
        if (empty($flag)){
            $db = \CIBlockElement::GetProperty($iblockId, $arParams["ID"], array('CODE' => 'NAREZKA'));
            $ar = $db->Fetch();
            $flag = $ar['VALUE'];
        }
        if (empty($flag)) return $arParams;
        $arParams['SORT'] = 123;
        return $arParams;
    }
    public function setLinoMeasure(&$arParams){
        $iblockId = (int)$arParams['IBLOCK_ID'];
        if ($iblockId !== 23) return $arParams;
        $propertyM2Id = 348;
        $propertyM2Value = (int)$arParams['PROPERTY_VALUES'][$propertyM2Id]['n0']['VALUE'];
        if (!empty($propertyM2Value)){
            $arFields = Array(
                "PRODUCT_ID" => $arParams['ID'],
                "RATIO" => $propertyM2Value,
                "IS_DEFAULT" => 'Y'
            );

            $existRatio = \Bitrix\Catalog\MeasureRatioTable::getList(array(
                'select' => ['ID'],
                'filter' => ['=PRODUCT_ID' => $arParams['ID']]
            ))->Fetch();

            if($existRatio){
                \Bitrix\Catalog\MeasureRatioTable::Update($existRatio['ID'],$arFields);
            } else {
                \Bitrix\Catalog\MeasureRatioTable::Add($arFields);
            }
        }
    }
    public function makeSortScu(){
        $arFilter = array('IBLOCK_ID' => self::SCU_IBLOCK, '!SORT' => '123', '!PROPERTY_NAREZKA' => false);
        $arSelect = array('ID');
        $db = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if (empty($db)) return;
        $el = new \CIBlockElement();
        while ($ar = $db->Fetch()){
            $el->Update($ar['ID'], array('SORT' => 123));
        }
    }
    public function doNotDeactivate(&$arParams){
        if (!self::isImport()) return;
        $id = (int)$arParams['ID'];
        if (in_array($id, self::DO_NOT_DEACTIVATE_SECTION))
            $arParams['ACTIVE'] = 'Y';
    }
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
    protected static function isImport(){
        return (Application::getInstance()->getContext()->getRequest()->get('mode') === 'import');
    }
    public static function doNotUseFacet(&$arFields){
        if ((int)$arFields['ID'] !== self::CATALOG_IBLOCK) return;
        if ($arFields['PROPERTY_INDEX'] !== 'Y') return;
        $arFields['PROPERTY_INDEX'] = 'I';
    }
    public static function doNotUseFacetD7(Entity\Event $event){
        $result = new Entity\EventResult();
        $data = $event->getParameter('fields');
        $primary = $event->getParameter('primary');
        if ((int)$primary['ID'] !== self::CATALOG_IBLOCK) return;
        if ($data['PROPERTY_INDEX'] !== 'Y') return;
        $result->modifyFields(array('PROPERTY_INDEX' => 'I'));
        return $result;
    }
    public static function test(){
        Loader::includeModule('iblock');
        IblockTable::update(self::CATALOG_IBLOCK, array('PROPERTY_INDEX' => 'Y'));
        $res = IblockTable::getRow(array('filter' => array('ID' => self::CATALOG_IBLOCK), 'select' => array('ID', 'PROPERTY_INDEX')));
        var_dump($res);
    }
    public static function ecosystemFoolChecker(&$arParams){
        $iblockId = (int)$arParams['IBLOCK_ID'];
        if ($iblockId !== 36) return $arParams;
        global $APPLICATION;
        $APPLICATION->throwException("Там вообще то некуда девятый ставить");
        return false;
    }
    public static function ecosystemFoolChecker2($ID){
        $ids = array(43644, 43645, 43646, 43647, 43648, 43649, 43650, 43651);
        if (in_array($ID, $ids)){
            global $APPLICATION;
            $APPLICATION->throwException("да ну нафиг, некрасиво будет");
            return false;
        }
    }
    public static function filesIblockAction(&$arFields){
        $iblockId = (int)$arFields['IBLOCK_ID'];
        if ($iblockId !== self::FILES_IBLOCK_ID) return;
        $filesPropId = 813;
        $namesPropId = 816;
        $namesArray = array();
        $deleteArray = array();
        foreach ($arFields['PROPERTY_VALUES'][$filesPropId] as $propID => $arProp){
            if ($arProp['VALUE']['name']) $namesArray[]['VALUE'] = $arProp['VALUE']['name'];
            if ($arProp['VALUE']['del'] === 'Y') $deleteArray[] = (int)$propID;
        }
        if (!empty($arFields['ID'])){
            $db = \CIBlockElement::GetProperty(self::FILES_IBLOCK_ID, $arFields['ID'], array(), array('ID' => $filesPropId));
            while($ar = $db->GetNext()){
                if (in_array((int)$ar['PROPERTY_VALUE_ID'], $deleteArray)) continue;
                $file = \CFile::GetByID($ar['VALUE'])->Fetch();
                $namesArray[]['VALUE'] = $file['ORIGINAL_NAME'];
            }
        }
        $arFields['PROPERTY_VALUES'][$namesPropId] = $namesArray;
        if (empty($arFields['XML_ID'])){
            $id = (int)\CIBlockElement::GetList(
                array('id' => 'desc'),
                array('IBLOCK_ID' => self::FILES_IBLOCK_ID, '!XML_ID' => null),
                false,
                false,
                array('XML_ID')
            )->Fetch()['XML_ID'];
            if (!empty($id)){
                $arFields['XML_ID'] = $id + 1;
            }else{
                $arFields['XML_ID'] = 1;
            }
        }
    }
    public static function ordRelatedElements($fields){
        if (self::$disableEvents) return;
        $iblockId = (int)$fields['IBLOCK_ID'];
        switch ($iblockId){
            case self::MAIN_BANNERS_IBLOCK_ID:
            case self::NEWS_IBLOCK_ID:
            case self::SALES_IBLOCK_ID:
                self::$disableEvents = true;
                ORD::elementUpdateAction($fields);
                break;
            default:
                return;
        }
        self::$disableEvents = false;
    }
}