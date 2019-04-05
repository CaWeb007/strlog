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
    public function SortSku(&$arParams){
        //Write::file('iblock_sku', $arParams, true);
        $iblockId = (int)$arParams['IBLOCK_ID'];
        if ($iblockId !== 23) return;
        if ((int)$arParams['SORT'] === '123') return;
        $flag = null;
        $flag = $arParams["PROPERTY_VALUES"][609][0]['VALUE'];
        if (empty($flag)){
            $db = \CIBlockElement::GetProperty($iblockId, $arParams["ID"], array('CODE' => 'NAREZKA'));
            $ar = $db->Fetch();
            $flag = $ar['VALUE'];
        }
        if (empty($flag)) return;
        $arParams['SORT'] = 123;
        return $arParams;
    }
}