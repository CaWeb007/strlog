<?php


namespace Caweb\Main\Sale;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class SaleActionGiftCtrl extends \CSaleActionGiftCtrlGroup {
    public static function GetControlShow($arParams)
    {
        return array(
            'controlId' => static::GetControlID(),
            'group' => true,
            'containsOneAction' => true,
            'label' => Loc::getMessage('CAWEB_SALE_ACT_GIFT_LABEL'),
            'defaultText' => Loc::getMessage('CAWEB_SALE_ACT_GIFT_DEF_TEXT'),
            'showIn' => static::GetShowIn($arParams['SHOW_IN_GROUPS']),
            'control' => array(
                Loc::getMessage('CAWEB_SALE_ACT_GIFT_GROUP_PRODUCT_PREFIX'),
            )
        );
    }
    public static function GetControlDescr()
    {
        $controlDescr = parent::GetControlDescr();
        $controlDescr['FORCED_SHOW_LIST'] = array(
            'GifterCondIBElement',
            'GifterCondIBSection',
        );
        $controlDescr['SORT'] = 300;

        return $controlDescr;
    }
    public static function GetControlID()
    {
        return 'CawebGiftCondGroup';
    }
    public static function Generate($arOneCondition, $arParams, $arControl, $arSubs = false)
    {
        //I have to notice current method can work only with Gifter's. For example, it is CCatalogGifterProduct.
        //Probably in future we'll add another gifter's and create interface or class, which will tell about attitude to CSaleActionGiftCtrlGroup.
        $mxResult = '';
        $boolError = false;

        if (!isset($arSubs) || !is_array($arSubs) || empty($arSubs))
        {
            $boolError = true;
        }
        else
        {
            $mxResult = '\Caweb\Main\Sale\SaleActionGiftCtrl::applySimpleGift(' . $arParams['ORDER'] . ', ' . implode('; ',$arSubs) . ');';
        }
        return $mxResult;
    }

    /**
     * Simple gift action.
     *
     * @param array &$order			Order data.
     * @param callable $filter		Filter.
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @return void
     */
    public static function applySimpleGift(array &$order, $filter)
    {
        \Bitrix\Sale\Discount\Actions::increaseApplyCounter();

        $actionDescription = array(
            'ACTION_TYPE' => \Bitrix\Sale\OrderDiscountManager::DESCR_TYPE_SIMPLE,
            'ACTION_DESCRIPTION' => Loc::getMessage('BX_SALE_DISCOUNT_ACTIONS_SIMPLE_GIFT_DESCR')
        );
        \Bitrix\Sale\Discount\Actions::setActionDescription(\Bitrix\Sale\Discount\Actions::RESULT_ENTITY_BASKET, $actionDescription);

        if (!is_callable($filter))
            return;

        if (empty($order['BASKET_ITEMS']) || !is_array($order['BASKET_ITEMS']))
            return;

        \Bitrix\Sale\Discount\Actions::disableBasketFilter();

        $itemsCopy = $order['BASKET_ITEMS'];
        \Bitrix\Main\Type\Collection::sortByColumn($itemsCopy, 'PRICE', null, null, true);
        $filteredBasket = \Bitrix\Sale\Discount\Actions::getBasketForApply(
            $itemsCopy,
            $filter,
            array(
                'GIFT_TITLE' => Loc::getMessage('BX_SALE_DISCOUNT_ACTIONS_SIMPLE_GIFT_DESCR')
            )
        );
        unset($itemsCopy);

        \Bitrix\Sale\Discount\Actions::enableBasketFilter();

        if (empty($filteredBasket))
            return;

        $applyBasket = array_filter($filteredBasket, '\Bitrix\Sale\Discount\Actions::filterBasketForAction');
        unset($filteredBasket);
        if (empty($applyBasket))
            return;

        foreach ($applyBasket as $basketCode => $basketRow)
        {
            $basketRow['DISCOUNT_PRICE'] = $basketRow['BASE_PRICE'] - 1;
            $basketRow['PRICE'] = 1;
            $order['BASKET_ITEMS'][$basketCode] = $basketRow;

            $rowActionDescription = $actionDescription;
            $rowActionDescription['BASKET_CODE'] = $basketCode;
            \Bitrix\Sale\Discount\Actions::setActionResult(\Bitrix\Sale\Discount\Actions::RESULT_ENTITY_BASKET, $rowActionDescription);
            unset($rowActionDescription);
        }
        unset($basketCode, $basketRow);
    }
}