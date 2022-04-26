<?php
namespace Caweb\Main\Sale\Action;
use Caweb\Main\Sale\Action\Tools;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\DiscountCouponTable;
use Bitrix\Sale\Internals\OrderCouponsTable;
use Bitrix\Sale\Internals\OrderTable;

Loc::loadMessages(__FILE__);

class CSaleActionCtrlOneUse extends \CGlobalCondCtrlComplex
{
    public static function GetClassName()
    {
        return __CLASS__;
    }
    /**
     * @return string|array
     */
    public static function GetControlID()
    {
        return array('CondOneUse');
    }


    /**
     * Функция добавляет категорию условий и добавляет в нее
     * сами условия описнные в функции GetControls()
     *
     * @param $arParams
     * @return array
     */
    public static function GetControlShow($arParams)
    {
        $arControls = static::GetControls();
        $arResult = array(
            'controlgroup' => true,
            'group' =>  false,
            'label' => static::getName(),
            'showIn' => static::GetShowIn($arParams['SHOW_IN_GROUPS']),
            'children' => array()
        );
        foreach ($arControls as &$arOneControl)
        {
            $arResult['children'][] = array(
                'controlId' => $arOneControl['ID'],
                'group' => false,
                'label' => $arOneControl['LABEL'],
                'showIn' => static::GetShowIn($arParams['SHOW_IN_GROUPS']),
                'control' => array(
                    array(
                        'id' => 'prefix',
                        'type' => 'prefix',
                        'text' => $arOneControl['PREFIX']
                    ),
                    static::GetLogicAtom($arOneControl['LOGIC']),
                    static::GetValueAtom($arOneControl['JS_VALUE'])
                )
            );
        }
        if (isset($arOneControl))
            unset($arOneControl);

        return $arResult;
    }

    /**
     * Функция добавления условий
     *
     * @param bool|string $strControlID
     * @return bool|array
     */
    public static function GetControls($strControlID = false)
    {
        $arControlList = array(
            'CondOneUse' => array(
                'ID' => 'CondOneUse',
                'FIELD' => 'ONE_USE',
                'FIELD_TYPE' => 'text',
                'LABEL' => static::getName(),
                'PREFIX' => static::getName(),
                'LOGIC' => static::GetLogic(array(BT_COND_LOGIC_EQ)),
                'JS_VALUE' => array(
                    'type' => 'select',
                    'values' => array('Y' => Loc::getMessage('CDOU_YES')),
                    'defaultValue' => 'Y'
                ),
                'PHP_VALUE' => ''
            ),
        );

        foreach ($arControlList as &$control)
        {
            if (!isset($control['PARENT']))
                $control['PARENT'] = true;

            $control['MULTIPLE'] = 'N';
        }
        unset($control);

        if ($strControlID === false)
        {
            return $arControlList;
        }
        elseif (isset($arControlList[$strControlID]))
        {
            return $arControlList[$strControlID];
        }
        else
        {
            return false;
        }
    }
    public static function getName(){
        return Loc::getMessage('CDOU_NAME');
    }
    /**
     * Функция подготавливает строчное представление метода проверки условий.
     * Эта строка запускается языковой конструкцией eval() в модуле скидок.
     *
     * @param $arOneCondition array Массив состояний
     * @param $arParams
     * @param $arControl
     * @param bool $arSubs
     * @return string
     */
    public static function Generate($arOneCondition, $arParams, $arControl, $arSubs = false)
    {
        $strResult  = '\Caweb\Main\Sale\Action\CSaleActionCtrlOneUse::OneUseCheck($arOrder, %STR_DISCOUNT_ID%)';
        return  $strResult;

    }

    /**
     * Функция выполняющая проверку условия (если возвращает true условие считается выполненым)
     *
     * @param array|array
     * @return bool
     */

    public static function OneUseCheck($arOrder = array(), $discountID = null)
    {
        if (empty($arOrder['USER_ID'])) return true;
        if (!empty($arOrder['ID'])) return true;
        $params = array(
            'filter' => array('USER_ID' => $arOrder['USER_ID'], '!CANCELED' => 'Y')
        );
        $arOrders = array();
        $db = OrderTable::getList($params);
        while($ar = $db->fetch()){
            $arOrders[] = (int)$ar['ID'];
        }
        if (empty($arOrders))return true;
        $coupon = DiscountCouponTable::getList(array('filter' => array('DISCOUNT_ID' => (int)$discountID, 'ACTIVE' => 'Y')))->fetch()['COUPON'];
        if (empty($coupon)) return true;
        $couponOrder = OrderCouponsTable::getList(array('filter' => array('=ORDER_ID' => $arOrders, 'COUPON' => $coupon)))->fetch();
        if (!empty($couponOrder)){
            Tools::setCustomDiscountCouponInfo($discountID, array(
                'JS_STATUS' => 'BAD',
                'JS_CHECK_CODE' => Loc::getMessage('CDOU_BAD_STATUS')
            ));
            return false;
        };
        return true;
    }
}
