<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 05.04.2019
 * Time: 16:12
 */

namespace Caweb\Main\Events;


use Caweb\Main\Log\Write;

class Iblock{
    public static $instance = null;
    const SCU_IBLOCK = 23;
    public function SortSku(&$arParams){
        $iblockId = (int)$arParams['IBLOCK_ID'];
        if ($iblockId !== 23) return $arParams;
        if ((int)$arParams['SORT'] === 123) return $arParams;
        Write::file('iblock_sku', $arParams, true);
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
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
}