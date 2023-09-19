<?
namespace Caweb\Main;


use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;

class Tools {
    private static $instance = null;
    private $userGroupID = null;
    private const STRLOG_GROUPS = array(9,10,11,12,13,14,15);
    private const GROUP_TO = 11;
    private const GROUP_KPSO = 15;
    private const GROUP_SO1 = 10;
    private const GROUP_SO2 = 12;
    private $host = null;
    public static function getInstance(){
        if (self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }
    public function getUserGroupId(){
        if ($this->userGroupID !== null)
            return $this->userGroupID;
        global $USER;
        $userGroups = $USER->GetUserGroupArray();
        $tmp = array_intersect(self::STRLOG_GROUPS, $userGroups);
        return $this->userGroupID = (int)array_shift($tmp);
    }
    public function isTO(){
        return ($this->getUserGroupId() === self::GROUP_TO);
    }
    public function isKPSO(){
        return ($this->getUserGroupId() === self::GROUP_KPSO);
    }
    public function isSO(){
        return (($this->getUserGroupId() === self::GROUP_SO1) || ($this->getUserGroupId() === self::GROUP_SO2));
    }
    static public function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        }
        else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }
    private function getHost(){
        if ($this->host) return $this->host;
        $this->host = Application::getInstance()->getContext()->getRequest()->getHttpHost();
        return $this->host;
    }
    public function getMarkerOrdUri(string $markerORD, string $link = null){
        if (empty($link)) return false;
        $uri = new Uri($link);
        if (empty($uri->getHost()))
            $uri->setHost($this->getHost());
        return $uri->addParams(array('erid' => $markerORD))->getUri();
    }
}