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
        global $USER;
        return array_intersect($USER->GetUserGroupArray(), self::SITE_GROUP_MODEL);
    }
}