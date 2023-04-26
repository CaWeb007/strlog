<?
namespace Caweb\Main\User;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Service\GeoIp\Manager as GeoIpManager;
use Bitrix\Main\UserTable;
use Bitrix\Main\Web\Cookie;
class Store {
    protected const COOKIE_NAME = 'STORE_ID';
    protected const CITY_STORE = array(49 => 'Иркутск', 88=>'Шелехово', 91 => 'Хомутово');
    protected static $instance = null;
    protected $storeId = null;
    public static function getInstance(){
        if (!(self::$instance instanceof self))
            self::$instance = new self;
        return self::$instance;
    }
    public function getUserStoreId(){
        $storeId = $this->getStoreIdFromCookie();
        if ($storeId) return (int)$storeId;
        $storeId = $this->getStoreIdFromUser();
        if ($storeId) return (int)$storeId;
        $storeId = $this->getStoreIdFromGeo();
        if ($storeId) return (int)$storeId;
        $storeId = 49;
        $this->setStoreId($storeId);
        return (int)$storeId;
    }
    protected function getStoreIdFromCookie(){
        return Context::getCurrent()->getRequest()->getCookie(self::COOKIE_NAME);
    }
    protected function getStoreIdFromUser(){
        /**@global $USER \CUser*/
        if (!($USER instanceof \CUser)) return false;
        $result = (int)UserTable::getRowById((int)$USER->GetID())['PERSONAL_CITY'];
        if (!empty($result)) $this->setStoreIdToCookie($result);
        return $result;
    }
    protected function getStoreIdFromGeo(){
        $ip = GeoIpManager::getRealIp();
        $geoData = GeoIpManager::getDataResult($ip,'ru');
        $cityName = $geoData->getGeoData()->cityName;
        $result = array_flip(self::CITY_STORE)[$cityName];
        if (!empty($result))
            $this->setStoreId($result);
        return $result;
    }
    protected function setStoreIdToCookie(int $storeId){
        global $APPLICATION;
        $APPLICATION->set_cookie(self::COOKIE_NAME, $storeId);
    }
    protected function setStoreIdToUser(int $storeId){
        /**@global $USER \CUser*/
        if (!($USER instanceof \CUser)) return false;
        UserTable::update((int)$USER->GetID(), array('PERSONAL_CITY' => $storeId));
    }
    public function setStoreId(int $storeId){
        $this->setStoreIdToUser($storeId);
        $this->setStoreIdToCookie($storeId);
    }
    public function getStoreData(){
        return self::CITY_STORE;
    }
}