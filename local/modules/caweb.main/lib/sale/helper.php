<?
namespace Caweb\Main\Sale;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValue;
use Caweb\Main\Catalog;

Loader::includeModule('catalog');

class Helper{
    const SITE_PRICE_MODEL = Catalog\Helper::SITE_PRICE_MODEL;
    const SITE_GROUP_MODEL = Catalog\Helper::SITE_GROUP_MODEL;
    const BONUS_ACCESS = array(9, 15);
    const KP_USER = 9;
    protected $userGroupsStrlog = array();
    protected $userBonus = null;
    public static $userPriceId = 0;
    public static $instance = null;
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
    /**@deprecated Use \Caweb\Main\Sale\Bonus::getInstance()->isBonusAccess*/
    public function checkBonusAccess($groups = array()){
        //if (!empty($this->getUserBonus())) return true;
        if (empty($groups))
            $groups = $this->getUsersStrlogGroups();
        if (!is_array($groups))
            $groups = array($groups);
        return (!empty(array_intersect($groups, self::BONUS_ACCESS)));
    }
    /**@deprecated Use \Caweb\Main\Sale\Bonus::getInstance()->getUserGroupId*/
    protected function getUsersStrlogGroups(){
        if (!empty($this->userGroupsStrlog)) return $this->userGroupsStrlog;
        global $USER;
        $this->userGroupsStrlog = array_intersect($USER->GetUserGroupArray(), self::SITE_GROUP_MODEL);
        return $this->userGroupsStrlog;
    }
    /**@deprecated Use \Caweb\Main\Sale\Bonus::getInstance()->getIblockPropertyCode*/
    public function checkBonusForBasket($string = ''){
        $check = '';
        if (!$this->checkBonusAccess()) return false;
        $userGroups = $this->getUsersStrlogGroups();
        if (in_array(9, $userGroups)) $check = 'PROPERTY_BONUS_KP_VALUE';
        if (in_array(15, $userGroups)) $check = 'PROPERTY_BONUS_SO_VALUE';
        if (empty($string)) return $check;
        return ($string === $check);
    }
    /**@deprecated don not use*/
    public function isKpUser(){
        $userGroups = $this->getUsersStrlogGroups();
        if (in_array(self::KP_USER, $userGroups)) return true;
        return false;
    }
    /**@deprecated dont use anymore fio fields in order form*/
    public static function updateOrderPropertiesEx(Order $order){
        $properties = $order->getPropertyCollection();
        $personType = (int)$order->getPersonTypeId();
        if ($personType === 1){
            $fio = $properties->getItemByOrderPropertyId(1);
            $fio2 = $properties->getItemByOrderPropertyId(31);
            $name = $properties->getItemByOrderPropertyId(32);
            $family = $properties->getItemByOrderPropertyId(33);
            $lastName = $properties->getItemByOrderPropertyId(34);
        }else{
            $fio = $properties->getItemByOrderPropertyId(12);
            $fio2 = $properties->getItemByOrderPropertyId(35);
            $name = $properties->getItemByOrderPropertyId(36);
            $family = $properties->getItemByOrderPropertyId(37);
            $lastName = $properties->getItemByOrderPropertyId(38);
        }
        $explode = array();
        if (($fio2 instanceof PropertyValue) && !empty($fio2->getValue())){
            $fio->setValue($fio2->getValue());
            $explode = explode(' ', trim($fio2->getValue()));
        }elseif (($name instanceof PropertyValue) && !empty($name->getValue())){
            $string = implode(' ', array($family->getValue(), $name->getValue(), $lastName->getValue()));
            $fio->setValue($string);
            $fio2->setValue($string);
        }elseif (($fio instanceof PropertyValue) && !empty($fio->getValue())){
            $fio2->setValue($fio->getValue());
            $explode = explode(' ', trim($fio->getValue()));
        }
        if (!empty($explode)){
            $family->setValue($explode[0]);
            $name->setValue($explode[1]);
            $lastName->setValue($explode[2]);
        }
        return $order;
    }
    public static function updateOrderProperties(Order $order){
        $properties = $order->getPropertyCollection();
        $personType = (int)$order->getPersonTypeId();
        if ($personType === 1){
            $fio = $properties->getItemByOrderPropertyId(1);
            $name = $properties->getItemByOrderPropertyId(32);
            $family = $properties->getItemByOrderPropertyId(33);
            $lastName = $properties->getItemByOrderPropertyId(34);
        }else{
            $fio = $properties->getItemByOrderPropertyId(12);
            $name = $properties->getItemByOrderPropertyId(36);
            $family = $properties->getItemByOrderPropertyId(37);
            $lastName = $properties->getItemByOrderPropertyId(38);
        }
        if (($name instanceof PropertyValue) && ($fio instanceof PropertyValue) && !empty($name->getValue())){
            $string = implode(' ', array($family->getValue(), $name->getValue(), $lastName->getValue()));
            $fio->setValue($string);
        }
        return $order;
    }
    /**@deprecated Use \Caweb\Main\Sale\Bonus::getInstance()*/
    public function setUserBonus($userBonus = 0){
        $this->userBonus = (float)$userBonus;
    }
    /**@deprecated Use \Caweb\Main\Sale\Bonus::getInstance()->getBonusCount*/
    protected function getUserBonus(){
        if ($this->userBonus !== null) return $this->userBonus;
        global $USER;
        /**@var $USER \CUser*/
        $account = new \CSaleUserAccount();
        $this->userBonus = (float)$account->GetByUserID($USER->GetID(), "RUB")['CURRENT_BUDGET'];
        return $this->userBonus;
    }
}