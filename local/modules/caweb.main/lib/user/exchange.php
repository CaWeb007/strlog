<?
namespace Caweb\Main\User;
use Bitrix\Highloadblock\HighloadBlockTable as HL;
use Bitrix\Main\Authentication\ApplicationPasswordTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Caweb\Main\Log\Write;
use Caweb\Main\Sale\Bonus;
use Caweb\Main\Sale\Helper;

class Exchange{
    /**@var $hl DataManager*/
    protected $hl = array();
    protected $user = array();
    protected $test = 10;
    protected $errors = array();
    protected $warn = array();
    public static $doExchange = false;
    const HL_ID = 4;
    function __construct(){
        $this->hlConstruct();
    }
    protected function hlConstruct(){
        Loader::includeModule('highloadblock');
        $block = HL::getById(self::HL_ID)->fetch();
        $object = HL::compileEntity($block);
        $this->hl = $object->getDataClass();
    }
    public static function updateFromHlAgent(){
        static::updateUsers();
        return '\Caweb\Main\User\Exchange::updateFromHlAgent();';
    }
    public static function clearHLAgent(){
        static::delete();
        return '\Caweb\Main\User\Exchange::clearHLAgent();';
    }
    public static function updateUsers(){
        self::$doExchange = true;
        $_this = new self();
        $_this->init();
        self::$doExchange = false;
    }
    public static function delete(){
        $_this = new self();
        $class = $_this->hl;
        $hlResultList = $_this->getHlResult();
        foreach ($hlResultList as $email => $array){
            $res = $class::delete($array['ID']);
            if (!$res->isSuccess())
                $_this->warn['hlDelete'][$email] = $res->getErrorMessages();
        }
        $_this->writeLogs();
    }
    protected function init(){
        $hlResultList = $this->getHlResult();
        $HLClass = $this->hl;
        if (empty($hlResultList)) return;
        $userList = $this->getUsers(array_keys($hlResultList));
        foreach ($hlResultList as $email => $array){
            if ($this->checkEmail($email, $array['ID'])) continue;
            $ID = false;
            $update = $userList[$email];
            $arFields = $this->prepareFields($array, $update);
            $user = new \CUser();
            if (empty($update)){
                $ID = $user->Add($arFields);
            }else{
                if ($user->Update($update['ID'], $arFields)) $ID = $update['ID'];
            }
            if (!$ID) {
                $this->errors[$array['UF_NAME']] = $user->LAST_ERROR;
                continue;
            }
            if (empty($update)) $this->sendEmail($ID, $arFields);
            $this->writeSaleAccount($ID, $arFields);
            $res = $HLClass::delete($array['ID']);
            if (!$res->isSuccess())
                $this->warn['hlDelete'][$email] = $res->getErrorMessages();
        }
        $this->writeLogs();
    }
    protected function writeLogs(){
        $errors = $this->errors;
        $warn = $this->warn;
        if (!empty($errors)) Write::file('errorsUserExchange', $errors);
        if (!empty($warn)) Write::file('warnUserExchange', $warn);
    }
    protected function writeSaleAccount($userId,$arFields){
        if (($arFields['UF_BONUSES'] === '') || ($arFields['UF_BONUSES'] === null) || ($arFields['UF_BONUSES'] === false)) return;
        $res = false;
        $account = new \CSaleUserAccount();
        $accountFields = $account->GetByUserID($userId, "RUB");
        $array = array("USER_ID" => $userId, "CURRENCY" => "RUB", "CURRENT_BUDGET" => $arFields['UF_BONUSES']);
        if (empty($accountFields)){
            $res = $account->Add($array);
        }else{
            $res = $account->Update($accountFields['ID'], $array);
        }
        if (!$res) $this->warn['account'][] = $arFields['EMAIL'];
    }
    protected function sendEmail($ID, $arFields){
        $arEventFields= array(
            "LOGIN" => $arFields['EMAIL'],
            "PASSWORD" => $arFields['PASSWORD'],
            "EMAIL" => $arFields['EMAIL']
        );
        $CV = \CEvent::Send("USER_INFO_STRLOG", SITE_ID, $arEventFields, "N", 95, array(),LANGUAGE_ID);
        if (empty($CV)) $this->warn['mail'][] = $arFields['EMAIL'];
    }
    protected function prepareFields($newFields, $oldFields = array()){
        $result = array();
        $result['GROUP_ID'] = $this->getUserGroupId($newFields['UF_GRUPPANASAYTE'], $newFields['UF_OBSHCHIYOBOROT']);
        $result['PERSONAL_PROFESSION'] = $newFields['UF_GRUPPANASAYTE'];
        $result['UF_BONUSES'] = $newFields['UF_OSTATOKBONUSOV'];
        $result['UF_BONUS_CARD'] = $newFields['UF_KODBONUSNOYKARTY'];
        $result['UF_ACCUMULATION'] = $newFields['UF_OBSHCHIYOBOROT'];
        if (empty($oldFields['NAME'])) $result['NAME'] = $newFields['UF_NAME'];
        if (empty($oldFields['PERSONAL_PHONE'])) $result['PERSONAL_PHONE'] = $newFields['UF_TELEFONKONTRAGENT'];
        if (empty($oldFields['UF_INN'])) $result['UF_INN'] = $newFields['UF_INN'];
        if (empty($oldFields['UF_KPP'])) $result['UF_KPP'] = $newFields['UF_KPP'];
        if (!empty($oldFields)) return $result;
        $result['EMAIL'] = $newFields['UF_ELEKTRONNAYAPOCHT'];
        $result['LOGIN'] = $newFields['UF_ELEKTRONNAYAPOCHT'];
        $result['PERSONAL_PROFESSION'] = $newFields['UF_GRUPPANASAYTE'];
        $result['LID'] = 'ru';
        $result['ACTIVE'] = 'Y';
        $psw = ApplicationPasswordTable::generatePassword();
        $result['PASSWORD'] = $psw;
        $result['CONFIRM_PASSWORD'] = $psw;
        return $result;
    }
    protected function getUsers($email){
        if (empty($email)) return;
        $result = array();
        $params['filter'] = array('EMAIL' => $email);
        $params['select'] = array('ID', 'EMAIL', 'NAME', 'PERSONAL_PHONE','UF_INN','UF_KPP');
        $db = UserTable::getList($params);
        while ($ar = $db->fetch()){
            $result[strtolower($ar['EMAIL'])] = $ar;
        }
        return $result;
    }
    protected function getHlResult(){
        $result = array();
        $hl = $this->hl;
        $db = $hl::getList();
        while ($ar = $db->fetch()){
            $result[strtolower($ar['UF_ELEKTRONNAYAPOCHT'])] = $ar;
        }
        return $result;
    }
    protected function checkEmail($email, $id){
        $array = array('papa@strlog.ru', 'astrlog@strlog.ru');
        if (in_array($email,$array)){
            $this->hl::delete($id);
            return true;
        }
        return false;
    }
    protected function getUserGroupId($hlFieldGroup = '', $hlFieldAccum = null){
        $result = 9;
        $array = array(
            "КП(ФИЗ)" => 9, "КП(ЮР)" => 14, "СО(КМС)" => 10, "СО(ПГС)" => 12, "ТО" => 11
        );
        if (!empty($array[$hlFieldGroup])) $result = $array[$hlFieldGroup];
        $hlFieldAccum = (float)preg_replace('/\s/','',$hlFieldAccum);
        if (($result === 9) && ($hlFieldAccum >= 10000))
            $result = 15;
        return array_merge(array(2,3,4,5,6), array($result));
    }
}
