<?php


namespace Caweb\Main\Sale\Action;

use Bitrix\Catalog\GroupTable;
use Bitrix\Catalog\PriceTable;
use Caweb\Main\Sale\Action\Tools;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\OrderTable;

Loc::loadMessages(__FILE__);

class CSaleActionDiscountFromPrice extends \CSaleActionCtrlBasketGroup
{
    public static function GetClassName()
    {
        return __CLASS__;
    }

    public static function GetControlID()
    {
        return "DiscountFromPrice";
    }

    public static function GetControlDescr()
    {
        return parent::GetControlDescr();
    }

    public static function GetAtoms()
    {
        return static::GetAtomsEx(false, false);
    }

    public static function GetControlShow($arParams)
    {
        $arAtoms = static::GetAtomsEx(false, false);
        $arResult = [
            "controlId" => static::GetControlID(),
            "group" => false,
            "label" => Loc::getMessage('CDFP_NAME'),
            "defaultText" => "",
            "showIn" => static::GetShowIn($arParams["SHOW_IN_GROUPS"]),
            "control" => [
                Loc::getMessage('CDFP_NAME'),
                $arAtoms["HLB"]
            ]
        ];

        return $arResult;
    }

    public static function GetAtomsEx($strControlID = false, $boolEx = false)
    {
        $boolEx = (true === $boolEx ? true : false);
        Loader::includeModule('catalog');
        $db = GroupTable::getList();
        $hlbList = array();
        while ($ar = $db->fetch()){
            $hlbList[$ar['ID']] = $ar['NAME'];
        }
        $arAtomList = [
            "HLB" => [
                "JS" => [
                    "id" => "HLB",
                    "name" => "extra",
                    "type" => "select",
                    "values" => $hlbList,
                    "defaultText" => "...",
                    "defaultValue" => "",
                    "first_option" => "..."
                ],
                "ATOM" => [
                    "ID" => "HLB",
                    "FIELD_TYPE" => "string",
                    "FIELD_LENGTH" => 255,
                    "MULTIPLE" => "N",
                    "VALIDATE" => "list"
                ]
            ],
        ];
        if (!$boolEx)
        {
            foreach ($arAtomList as &$arOneAtom)
            {
                $arOneAtom = $arOneAtom["JS"];
            }
            if (isset($arOneAtom))
            {
                unset($arOneAtom);
            }
        }
        return $arAtomList;
    }

    public static function Generate($arOneCondition, $arParams, $arControl, $arSubs = false)
    {
        $mxResult = __CLASS__ . "::setPriceGroupDiscount(" . $arParams["ORDER"] . ", " . "\"" . $arOneCondition["HLB"] . "\"" . ");";

        return $mxResult;
    }

    /**
     * Применяет скидку из справочника к товарам из корзины
     * @param $arOrder
     */
    public static function setPriceGroupDiscount(&$arOrder, $priceGroupId)
    {
        $productIds = array();
        foreach ($arOrder['BASKET_ITEMS'] as &$product)
        {
            $productIds[] = (int)$product['PRODUCT_ID'];
        }
        unset($product);
        $productDiscountPrice = array();
        $db = PriceTable::getList(array('filter' => array('PRODUCT_ID' => $productIds, 'CATALOG_GROUP_ID' => (int)$priceGroupId)));
        while ($ar = $db->fetch()){
            $productDiscountPrice[$ar['PRODUCT_ID']] = $ar['PRICE'];
        }
        //Применяем скидку
        foreach ($arOrder['BASKET_ITEMS'] as &$product)
        {
            if ((float)$product['BASE_PRICE'] < $productDiscountPrice[(int)$product['PRODUCT_ID']]) continue;
            $product['DISCOUNT_PRICE'] = (float)$product['BASE_PRICE'] - $productDiscountPrice[(int)$product['PRODUCT_ID']];
            $product['PRICE'] = $product['BASE_PRICE'] - $product['DISCOUNT_PRICE'];
        }
        unset($product);

    }

}
