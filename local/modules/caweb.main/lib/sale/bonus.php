<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 18.05.2020
 * Time: 13:49
 */

namespace Caweb\Main\Sale;

/**@var $USER \CUser*/
class Bonus {
    const SESSION_KEY = 'BX_BONUS_INFO';
    const SITE_GROUP_MODEL = array(9,10,11,12,14,15);
    const IBLOCK_PROPERTIES_MODEL = array(
        1 => "BONUS_KP",
        2 => "BONUS_SO",
        3 => "BONUS_SO15",
        4 => "BONUS_SO",
        5 => "BONUS_SO15",
        6 => "BONUS_SO20"
    );
    private static $instance = null;
    private $userData = array();
    private function __construct(){
        $this->setUserFields();
    }
    public static function getInstance(){
        if (self::$instance instanceof self) return self::$instance;
        return self::$instance = new self();
    }
    private function setUserFields(){
        if (empty($this->userData))
            $this->userData = $this->getUserData();
    }
    public function getUserData() {
        global $USER;
        if (!empty($_SESSION[self::SESSION_KEY]))
            return $_SESSION[self::SESSION_KEY];
        $result = array();
        $userDBData = \CUser::GetByID((int)$USER->GetID())->Fetch();
        $result = array(
            'BONUS_COUNT' => ((float)$userDBData['UF_BONUSES'] != 0)? (float)$userDBData['UF_BONUSES'] : false,
            'BONUS_CARD' => ((int)$userDBData['UF_BONUS_CARD'] != 0)? (int)$userDBData['UF_BONUS_CARD'] : false,
            'GROUP' => $this->getUserGroupId()
        );
        $_SESSION[self::SESSION_KEY] = $result;
        return $result;
    }
    public function getBonusCount(){
        return $this->userData['BONUS_COUNT'];
    }
    public function getBonusCard(){
        return $this->userData['BONUS_CARD'];
    }
    public function getUserGroupId() {
        if (!empty($this->userData['GROUP']))
            return $this->userData['GROUP'];
        global $USER;
        $dbUserGroup = $USER->GetUserGroupArray();
        return (int)array_shift(array_intersect($dbUserGroup, self::SITE_GROUP_MODEL));
    }
    public function isBonusAccess(){
        return (($this->getBonusCard() !== false) || $this->isKpUser());
    }
    public function getIblockPropertyCode(){
        if (!$this->isBonusAccess()) return '';
        if (!empty(self::IBLOCK_PROPERTIES_MODEL[$this->getBonusCard()]))
            return self::IBLOCK_PROPERTIES_MODEL[$this->getBonusCard()];
        else
            return self::IBLOCK_PROPERTIES_MODEL[1];
    }
    public static function updateSessionsData(){
        self::$instance = null;
        $_SESSION[self::SESSION_KEY] = array();
    }
    public function isKpUser(){
        return ($this->getUserGroupId() === self::SITE_GROUP_MODEL[0]);
    }
    public function isIndividualUser(){
        return (($this->getUserGroupId() === self::SITE_GROUP_MODEL[5]) || $this->isKpUser());
    }
    public function isBonusPropertyName($name){
        foreach (self::IBLOCK_PROPERTIES_MODEL as $item)
            if (strpos($name, $item) !== false) return true;
        return false;
    }
}