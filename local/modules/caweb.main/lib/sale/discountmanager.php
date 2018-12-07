<?php
namespace Caweb\Main\Sale;

use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Diag\Debug;

class DiscountManager{
    const STATUS_END = 'end';
    const STATUS_START = 'start';
    const SESSION_KEY = 'CAWEB_DISCOUNT';
    public static $jsText = array();
    public static function add($keyword){
        $session = $_SESSION[self::SESSION_KEY];
        $status = $session[$keyword]['STATUS'];
        if (!empty($status) && ($status === self::STATUS_END))
            return false;
        $priceId = false;
        if (!empty($priceId = DiscountTable::getUserDiscountPrice($keyword))){
            $session[$keyword] = array(
                'KEYWORD' => $keyword,
                'STATUS' => self::STATUS_START,
                'PRICE' => $priceId
            );
            $_SESSION[self::SESSION_KEY] = $session;
            return true;
        }
        return false;
    }
    public static function delete($keyword){
        if (!empty($_SESSION[self::SESSION_KEY][$keyword])){
            unset($_SESSION[self::SESSION_KEY][$keyword]);
            return true;
        }
        return false;
    }
    public static function getDiscountInfo(){
        $session = $_SESSION[self::SESSION_KEY];
        if (empty($session)) return false;
        $result = array();
        foreach ($session as $discount){
            $result['COUPON'] = $discount['KEYWORD'];
            if ($session['STATUS'] === self::STATUS_START)
                $result['JS_STATUS'] = 'ENTERED';
            $result['JS_CHECK_CODE'] = static::$jsText[$discount['KEYWORD']];
        }
        return $result;
    }
    public static function discountProcess($item){
        $result = $item;
        if (empty($_SESSION[self::SESSION_KEY])) return false;
        $session = $_SESSION[self::SESSION_KEY];
        $price = 0;
        $currency = '';
        $keyword = '';
        foreach ($session as $discount){
            if ($discount['PRICE'] === $result['PRICE_TYPE_ID']){
                static::$jsText[$discount['KEYWORD']] = 'test1';
                continue;
            }
            $param['filter'] = array(
                'PRODUCT_ID' => $result['PRODUCT_ID'],
                'CATALOG_GROUP_ID' => $discount['PRICE']
            );
            $param['select'] = array('ID','PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
            $arPrice = PriceTable::getRow($param);
            if (empty($price) || ($arPrice['PRICE'] < $price)){
                $price = $arPrice['PRICE'];
                $currency = $arPrice['CURRENCY'];
                $keyword = $discount['KEYWORD'];
            }
        }

        $discountPrice = $result['BASE_PRICE'] - $price;

        if (($result['PRICE'] <= $price) || ($discountPrice <= 0)){
            static::$jsText[$keyword] = 'test2';
            return $result;
        }
        $result['PRICE'] = $price;
        $result["DISCOUNT_PRICE"] = $discountPrice;
        $result["SUM_DISCOUNT_PRICE"] = $price;
        $result["SUM_VALUE"] = $price * $result['QUANTITY'];
        $result["SUM_FULL_PRICE"] = $result["BASE_PRICE"] * $result['QUANTITY'];
        $result["SUM_DISCOUNT_PRICE"] = $discountPrice * $result['QUANTITY'];
        $result["SUM_DISCOUNT_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["SUM_DISCOUNT_PRICE"],$currency);
        $result["DISCOUNT_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["DISCOUNT_PRICE"],$currency);
        $result["PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat($result["PRICE"],$currency);
        $result["SUM"] = \CCurrencyLang::CurrencyFormat($result["SUM_VALUE"],$currency);
        $result['PRICE_TYPE_ID'] = $arPrice['CATALOG_GROUP_ID'];
        return $result;
    }
}