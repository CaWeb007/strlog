<?
namespace Caweb\Main\Sale;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Main\Loader;
use Caweb\Main\Catalog;

Loader::includeModule('catalog');

class Helper{
    const SITE_PRICE_MODEL = Catalog\Helper::SITE_PRICE_MODEL;
    const SITE_GROUP_MODEL = Catalog\Helper::SITE_GROUP_MODEL;
    const BONUS_ACCESS = array(9, 15);
    const KP_USER = 9;
    protected $userGroupsStrlog = array();
    public static $userPriceId = 0;
    public static $instance = null;
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        self::$instance = new self();
        return self::$instance;
    }
    public function checkBonusAccess($groups = array()){
        if (empty($groups))
            $groups = $this->getUsersStrlogGroups();
        if (!is_array($groups))
            $groups = array($groups);
        return (!empty(array_intersect($groups, self::BONUS_ACCESS)));
    }
    protected function getUsersStrlogGroups(){
        if (!empty($this->userGroupsStrlog)) return $this->userGroupsStrlog;
        global $USER;
        $this->userGroupsStrlog = array_intersect($USER->GetUserGroupArray(), self::SITE_GROUP_MODEL);
        return $this->userGroupsStrlog;
    }
    public function checkBonusForBasket($string = ''){
        $check = '';
        if (!$this->checkBonusAccess()) return false;
        $userGroups = $this->getUsersStrlogGroups();
        if (in_array(9, $userGroups)) $check = 'PROPERTY_BONUS_KP_VALUE';
        if (in_array(15, $userGroups)) $check = 'PROPERTY_BONUS_SO_VALUE';
        if (empty($string)) return $check;
        return ($string === $check);
    }
    public function isKpUser(){
        $userGroups = $this->getUsersStrlogGroups();
        if (in_array(self::KP_USER, $userGroups)) return true;
        return false;
    }
}