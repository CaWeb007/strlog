<?php
namespace Caweb\Main\Sale;

use Bitrix\Catalog\GroupTable;
use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Event;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValue;
use Caweb\Main\Catalog\Helper;
use Caweb\Main\Log\Write;
use Bitrix\Sale\Fuser;
use Bitrix\Main;

Loc::loadMessages(__FILE__);

class DiscountManager{
    const STATUS_START = 'START';
    const STATUS_ENTER = 'ENTER';
    const STATUS_APPLY = 'APPLY';
    const STATUS_NEED_DELETE = 'NEED_DELETE';
    const STATUS_DELETE = 'DELETE';
    const STATUS_END = 'END';
    const STATUS_NOT_FOUND = 'NOT_FOUND';
    const STATUS_MAX_DISCOUNT = 'MAX_DISCOUNT';
    const SESSION_KEY = 'CAWEB_DISCOUNT';
    const DEFAULT_PRICE = 11;
    protected static $priceIDAsNameArray = array();
    protected static $exception = null;
    public static $jsText = array();
    public static $processedCoupon = array();
    private static function checkRights($dbDiscount) {
        if (empty($dbDiscount['NO_RIGHTS'])) return true;
        $intUserGroup = Bonus::getInstance()->getUserGroupId();
        if (array_search($intUserGroup, $dbDiscount['NO_RIGHTS']) === false) return true;
        self::$exception[$dbDiscount['KEYWORD']] = Loc::getMessage('DO_NOT_RIGHTS');
        return false;
    }
    public static function init(){
        $sessionContainer = $_SESSION[self::SESSION_KEY];
        if (!empty($sessionContainer)) return;
        $discount = DiscountUserTable::getUserDiscount(self::STATUS_ENTER, true);
        if (count($discount) === 1) return $_SESSION[self::SESSION_KEY] = $discount;
        $arPrice = array();
        foreach ($discount as $item){
            $arPrice[$discount['KEYWORD']] = $discount['PRICE_ID'];
        };
        $minPrice = Helper::getMinCatalogPrice($arPrice);
        $price = array_intersect($arPrice,array($minPrice));
        $discount = array_intersect_key($discount, $price);
        return $_SESSION[self::SESSION_KEY] = $discount;
    }
    public static function add($keyword){
        self::$exception = null;
        $coupon = self::checkInSessionForAdd($keyword);
        if (!$coupon) return false;
        if ($couponId = DiscountUserTable::addDiscount($coupon)){
            $coupon['COUPON_ID'] = $couponId;
        }else{
            return false;
        };
        $_SESSION[self::SESSION_KEY][$keyword] = $coupon;
        self::$processedCoupon = $coupon;
        return true;
    }

    public static function delete($keyword){
        $session = $_SESSION[self::SESSION_KEY][$keyword];
        if (!$session) return false;
        $status = $session['STATUS'];
        if ($status === self::STATUS_APPLY)
            return false;
        if ($status === self::STATUS_DELETE)
            return false;
        $coupon = $session;
        DiscountUserTable::deleteDiscount($coupon);
        $coupon['STATUS'] = self::STATUS_NEED_DELETE;
        $_SESSION[self::SESSION_KEY][$keyword] = $coupon;
        self::$processedCoupon = $coupon;
        return true;
    }
    static function checkInSessionForAdd($keyword){
        $session = $_SESSION[self::SESSION_KEY][$keyword];
        if ($session) {
            $status = $session['STATUS'];
            if (
                !empty($status)
                && ($status !== self::STATUS_START) && ($status !== self::STATUS_DELETE)
            )
                return false;
        }
        $coupon = array();
        $coupon['KEYWORD'] = $keyword;
        //$price = DiscountTable::getUserDiscountPrice($keyword);
        $discount = DiscountTable::getUserDiscount($keyword);
        $price = $discount['PRICE_ID'];
        if (!$price) {
            return false;
        }
        $old_price = '';
        list($price,$old_price) = self::checkPrice($price, $keyword);

        if (!self::checkRights($discount)) return false;

        if (!$price) {
            return false;
        }
        $coupon['STATUS'] = self::STATUS_START;
        $coupon['PRICE_ID'] = $price;
        $coupon['USER_PRICE_ID'] = $old_price;
        $coupon['DISCOUNT_ID'] = $discount['ID'];
        return $coupon;
    }
    public static function checkPrice($price, $keyword){
        $price = intval($price);
        if (empty($price)) return array('','');
        $old_price = Helper::getUserPriceId();
        if (empty($old_price)) $old_price = self::DEFAULT_PRICE;
        if (!$old_price) return array('','');
        $arPrices = array();
        $session = $_SESSION[self::SESSION_KEY];
        if (!empty($session)){
            foreach ($session as $coupon){
                $status = $coupon['STATUS'];
                if (($status === self::STATUS_ENTER) || ($status === self::STATUS_APPLY))
                    $arPrices[] = $coupon['PRICE_ID'];
            }
        }
        $arPrices = array_merge($arPrices, array($old_price,$price));
        $minPrice = Helper::getMinCatalogPrice($arPrices);
        if ($minPrice === $price) return array($price,$old_price);
        self::$exception[$keyword] = Loc::getMessage("DO_NOT_RIGHTS");
        return array('','');
    }

    public static function getDiscountInfo(){
        global $USER;
        $session = $_SESSION[self::SESSION_KEY];
        if (empty($session) && empty(self::$exception)) return false;
        $result = array();
        if (!empty(static::$processedCoupon) && empty(self::$exception[static::$processedCoupon['KEYWORD']]))
            $result['COUPON'] = static::$processedCoupon['KEYWORD'];

        foreach ($session as $discount){
            if (!in_array($discount['STATUS'],array(
                self::STATUS_APPLY, self::STATUS_ENTER,
            ))) continue;
            $list = array();
            $list['COUPON'] = $discount['KEYWORD'];
            if ($discount['STATUS'] === self::STATUS_ENTER){
                $list['JS_STATUS'] = 'APPLYED';
                $list['JS_CHECK_CODE'] = '"Бонусное слово" введён';
            }
            if ($discount['STATUS'] === self::STATUS_APPLY){
                $list['JS_STATUS'] = 'BAD';
                $list['JS_CHECK_CODE'] = '"Бонусное слово" использован';
            }
            $result['COUPON_LIST'][] = $list;
        }
        if (is_array(self::$exception))
            foreach (self::$exception as $keyword => $exceptionMess){
                $result['COUPON_LIST'][] = array(
                    'COUPON' => $keyword,
                    'JS_STATUS' => 'BAD',
                    'JS_CHECK_CODE' => $exceptionMess
                );
            }
        return $result;
    }
    public static function changeDiscount(){
        if(!empty(static::$processedCoupon)) return true;
        $session = $_SESSION[self::SESSION_KEY]; 
        if (empty($session)) return false;
        $discount = array();
        foreach ($session as $item) {
            if ($item['STATUS'] !== self::STATUS_ENTER) continue;
            $discount[$item['KEYWORD']] = $item;
        }
        if (count($discount) === 1){
            self::$processedCoupon = array_shift($discount);
            return true;
        }elseif (empty($discount)){
            return false;
        }
        $arPrice = array();
        foreach ($discount as $item){
            $arPrice[$discount['KEYWORD']] = $discount['PRICE_ID'];
        };
        $minPrice = Helper::getMinCatalogPrice($arPrice);
        $price = array_intersect($arPrice,array($minPrice));
        $discount = array_intersect_key($discount, $price);
        self::$processedCoupon = array_shift($discount);
        return true;
    }
    public static function discountProcess($item){
        $result = $item;
        $discount = static::$processedCoupon;

        if (empty($discount)) return false;
        $status = $discount['STATUS'];

        if (empty($status) || (($status !== self::STATUS_START) && ($status !== self::STATUS_NEED_DELETE)
                && ($status !== self::STATUS_ENTER)))
            return false;
        $boolDelete = $discount['STATUS'] === self::STATUS_NEED_DELETE;
        $priceID = $discount['PRICE_ID'];
        if ($boolDelete) $priceID = $discount['USER_PRICE_ID'];
        if (empty($priceID) || (!$boolDelete && ((int)$priceID === (int)$item['PRICE_TYPE_ID'])))
            return false;
        $param['filter'] = array(
            'PRODUCT_ID' => $result['PRODUCT_ID'],
            'CATALOG_GROUP_ID' => $priceID
        );
        $param['select'] = array('ID','PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
        $arPrice = PriceTable::getRow($param);
        if (empty($arPrice)) return false;
        $price = $arPrice['PRICE'];
        if (($status !== self::STATUS_NEED_DELETE) && ((float)$price > (float)$item['PRICE'])) return false;
        $currency = $arPrice['CURRENCY'];
        $discountPrice = $result['BASE_PRICE'] - $price;
        $result['PRICE'] = $price;
        $result["DISCOUNT_PRICE"] = $discountPrice;
        $result["SUM_DISCOUNT_PRICE"] = $price;
        $result["SUM_VALUE"] = $price * $result['QUANTITY'];
        $result["SUM_FULL_PRICE"] = $result["BASE_PRICE"] * $result['QUANTITY'];
        $result["SUM_DISCOUNT_PRICE"] = $discountPrice * $result['QUANTITY'];
        $result["SUM_DISCOUNT_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["SUM_DISCOUNT_PRICE"], $currency);
        $result["DISCOUNT_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["DISCOUNT_PRICE"], $currency);
        $result["PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["PRICE"], $currency);
        $result["SUM"] = \CCurrencyLang::CurrencyFormat($result["SUM_VALUE"], $currency);
        $result['PRICE_TYPE_ID'] = $arPrice['CATALOG_GROUP_ID'];
        return $result;
    }
    public static function changeStatus($apply = false){
        $result = '';
        $status = static::$processedCoupon['STATUS'];
        $keyword = static::$processedCoupon['KEYWORD'];
        $couponId = static::$processedCoupon['COUPON_ID'];
        $result = $status;
        if ($status === self::STATUS_START) $result = self::STATUS_ENTER;
        if ($status === self::STATUS_NEED_DELETE) $result = self::STATUS_DELETE;
        if ($apply && ($status === self::STATUS_ENTER)) $result = self::STATUS_APPLY;
        if (!empty($couponId))
            $r = DiscountUserTable::update($couponId, array('STATUS' => $status));
        $_SESSION[self::SESSION_KEY][$keyword]['STATUS'] = $result;
    }
    public static function OnSaleOrderSaved(Event $event){
        if (!$event->getParameter('IS_NEW')) return;
        self::init();
        $order = $event->getParameter('ENTITY');
        if (!($order instanceof Order)) return;
        $orderId = $order->getId();
        $userId = $order->getField('CREATED_BY');
        $coupon = array();
        if (!is_array($_SESSION[self::SESSION_KEY])) $_SESSION[self::SESSION_KEY] = array();
        foreach ($_SESSION[self::SESSION_KEY] as $item) {
            if ($item['STATUS'] !== self::STATUS_ENTER) continue;
            $coupon = $item;
            break;
        }
        $couponId = $coupon['COUPON_ID'];
        if (empty($orderId) || empty($couponId)) return;
        $_SESSION[self::SESSION_KEY][$coupon['KEYWORD']]['STATUS'] = self::STATUS_APPLY;
        $_SESSION[self::SESSION_KEY][$coupon['KEYWORD']]['ORDER_ID'] = $orderId;
        $fields = array('ORDER_ID' => $orderId, 'STATUS' => self::STATUS_APPLY, 'USER_ID' => $userId);
        $res = DiscountUserTable::update($couponId, $fields);
        if (!$res->isSuccess()){
            Write::file('OrderDiscount', $res->getErrorMessages());
        }
    }
    /**
    *Array $_SESSION[self::SESSION_KEY]
    (
    [мсм] => Array
    (
    [KEYWORD] => мсм
    [STATUS] => ENTER
    [PRICE_ID] => 15
    [USER_PRICE_ID] => 10
    [DISCOUNT_ID] => 16
    [COUPON_ID] => 239
    )

    )

     */
    public static function OnSaleOrderBeforeSaved(Event $event){
        self::init();
        $order = $event->getParameter('ENTITY');
        $properties = $order->getPropertyCollection();
        $promo = array(
            $properties->getItemByOrderPropertyId(39),
            $properties->getItemByOrderPropertyId(40)
        );
        $price = array(
            $properties->getItemByOrderPropertyId(41),
            $properties->getItemByOrderPropertyId(42)
        );
        if (!($order instanceof Order)) return;
        $coupon = array();
        if (!is_array($_SESSION[self::SESSION_KEY])) $_SESSION[self::SESSION_KEY] = array();
        foreach ($_SESSION[self::SESSION_KEY] as $item) {
            if ($item['STATUS'] !== self::STATUS_ENTER) continue;
            $coupon = $item;
            break;
        }
        $couponId = $coupon['COUPON_ID'];
        if (!empty($couponId)) {
            foreach ($promo as $item)
                if ($item instanceof PropertyValue)
                    $item->setValue($coupon['KEYWORD']);
            foreach ($price as $item)
                if ($item instanceof PropertyValue)
                    $item->setValue(self::getPriceXml((int)$coupon['PRICE_ID']));
        }elseif (($orderId = $order->getId()) && ($orderCoupon = DiscountUserTable::getRow(array('filter' => array('ORDER_ID' => $orderId), 'select' => array('*', 'PRICE_ID' => 'DISCOUNT.PRICE_ID', 'KEYWORD' => 'DISCOUNT.KEYWORD'))))){
            try {
                $items = $order->getBasket()->getOrderableItems();
                /**@var $item BasketItem */
                foreach ($items as $item) {
                    $params = array('filter' => array('PRODUCT_ID' => (int)$item->getProductId(), 'CATALOG_GROUP_ID' => (int)$orderCoupon['PRICE_ID']));
                    $price = PriceTable::getRow($params)['PRICE'];
                    $basePrice = (float)$item->getBasePrice();
                    if ($price > $basePrice) $price = $basePrice;
                    if ($price && ($price < $item->getPrice()))
                        $item->setPrice($price, true);
                }
                foreach ($promo as $item)
                    if ($item instanceof PropertyValue)
                        $item->setValue($orderCoupon['KEYWORD']);
                foreach ($price as $item)
                    if ($item instanceof PropertyValue)
                        $item->setValue(self::getPriceXml((int)$orderCoupon['PRICE_ID']));
            }catch(\Exception $exception){}

        }
        $event->addResult(new Main\EventResult(
            Main\EventResult::SUCCESS,
            $order
        ));
    }
    protected static function getPriceXml(int $priceId = 0){
        if (!empty(self::$priceIDAsNameArray[$priceId])) return self::$priceIDAsNameArray[$priceId];
        self::$priceIDAsNameArray[$priceId] = GroupTable::getRowById($priceId)['XML_ID'];
        return self::$priceIDAsNameArray[$priceId];
    }
}