<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 05.04.2019
 * Time: 16:12
 */

namespace Caweb\Main\Events;


use Bitrix\Main\Application;
use Caweb\Main\Log\Write;
use \Bitrix\Main\Entity;

class Iblock{
    public static $instance = null;
    const SCU_IBLOCK = 23;
    const CATALOG_IBLOCK = 10;
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
        if ($arFields['PROPERTY_INDEX'] === 'N') return;
        $arFields['PROPERTY_INDEX'] = 'N';
    }
    public static function doNotUseFacetD7(Entity\Event $event){
        $result = new Entity\EventResult();
        $data = $event->getParameter('fields');
        if ((int)$data['ID'] !== self::CATALOG_IBLOCK) return;
        if ($data['PROPERTY_INDEX'] === 'N') return;
        $result->modifyFields(array('PROPERTY_INDEX' => 'N'));
        return $result;
    }
}