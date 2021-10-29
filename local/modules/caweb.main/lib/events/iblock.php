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

class Iblock{
    public static $instance = null;
    const SCU_IBLOCK = 23;
    const CATALOG_IBLOCK = 16;
    const DO_NOT_DEACTIVATE_SECTION = array(2155);
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
}