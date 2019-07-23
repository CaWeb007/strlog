<?
namespace Caweb\Main\User;
use Bitrix\Highloadblock\HighloadBlockTable as HL;
use Bitrix\Main\Authentication\ApplicationPasswordTable;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Caweb\Main\Log\Write;

class Exchange{
    protected $hl = array();
    protected $user = array();
    protected $test = 10;
    protected $errors = array();
    protected $warn = array();
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
        $_this = new self();
        $_this->init();
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
                $this->errors[$email] = $user->LAST_ERROR;
                //if ($user->LAST_ERROR !== "Неверный E-Mail.<br>")
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
            "NAME" => $arFields['NAME'],
            "PASSWORD" => "Пароль: " . $arFields['PASSWORD'],
            "EMAIL" => $arFields['EMAIL'],
            "SERVER_NAME" => "stroylogistika.ru",
            "USER_ID" => $ID,
        );
        $CV = \CEvent::Send("USER_INFO_STRLOG", SITE_ID, $arEventFields, "N", 96,[],LANGUAGE_ID);
        if (empty($CV)) $this->warn['mail'][] = $arFields['EMAIL'];
    }
    protected function prepareFields($newFields, $oldFields = array()){
        $result = array();
        $result['GROUP_ID'] = $this->getUserGroupId($newFields['UF_GRUPPANASAYTE'], $newFields['UF_OBSHCHIYOBOROT']);
        $result['PERSONAL_PROFESSION'] = $newFields['UF_GRUPPANASAYTE'];
        $result['UF_BONUSES'] = $newFields['UF_OSTATOKBONUSOV'];
        if ((int)$result['GROUP_ID'] === 14) $result['UF_BONUSES'] = '0.0000';
        if ((int)$result['GROUP_ID'] === 10) $result['UF_BONUSES'] = '0.0000';
        if ((int)$result['GROUP_ID'] === 11) $result['UF_BONUSES'] = '0.0000';
        if ((int)$result['GROUP_ID'] === 12) $result['UF_BONUSES'] = '0.0000';
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
        if ($result['EMAIL'] === "Xxx_ues@mail.ru")
            Write::file('Xxx_ues', $result, true);
        return $result;
    }
    protected function getUsers($email){
        if (empty($email)) return;
        $result = array();
        $params['filter'] = array('EMAIL' => $email);
        $params['select'] = array('ID', 'EMAIL', 'NAME', 'PERSONAL_PHONE','UF_INN','UF_KPP');
        $db = UserTable::getList($params);
        while ($ar = $db->fetch()){
            $result[$ar['EMAIL']] = $ar;
        }
        return $result;
    }
    protected function getHlResult(){
        $result = array();
        $hl = $this->hl;
        $db = $hl::getList();
        while ($ar = $db->fetch()){
            $result[$ar['UF_ELEKTRONNAYAPOCHT']] = $ar;
        }
        return $result;
    }
    protected function checkEmail($email){
        $array = array('dima19681@ya.ru', 'lenysion@gmail.com', 'sgn_sse@mail.ru', 'yarik780@yandex.ru', 'lightworksa@gmail.com', 'podluzhnaya.a@mail.ru', 'viprobinzon@irk.ru', 'mir-krepega@bk.ru', 'krasnik_andrey@mail.ru', 'sergradionov@yandex.ru', 'krpm@mail.ru', 'avs367@mail.ru', 'boronov_baitog@rambler.ru', 'novichkov_vn@demetra.ru', 'potap.irk@yandex.ru', 'asmakssn@gmail.com', 'gutehexee@gmail.com', 'komarinochka@mail.ru', 'm.krilov2015@yandex.ru', 'sg.dm@mail.ru', 'pushkarev93@yandex.ru', 'bars.irk@mail.ru', '17dis04@list.ru', 'irk.sklad@khabtt.ru', '424460@mail.ru', 'kapai46v@gmail.com', 'taigarden@yandex.ru', 'missmo.store.info@gmail.com', 'che_lex7@mail.ru', 'mkredentser@yandex.ru', 'gnovik009@gmail.com', 'yeollihyeon@gmail.com', 'ya.kapai@ya.ru', 'paw1964@mail.ru', 'kernel_asv@yahoo.com', 'ma-prezental@mail.ru', 'kr-dennis@mail.ru', 'benedyuk23@gmail.com', 'asgard@mail.ru', 'ys.tsvetkov@gmail.com', 'subochev_vladim@mail.ru', 'ne1drug@gmail.com', 'aviamexanik@icloud.com', 'palchikov.58@mail.ru', 'ddenisoff@mail.ru', 'py9aa@inbox.ru', 'fetisov-60@list.ru', 'bookkeeper2003@mail.ru', 'vlad38ru@gmail.com', 'vashal@mail.ru', 'biryuk_stanislav@mail.ru', 'profi1204@mail.ru', '1274423@gmail.com', 'berkut210777@yandex.ru', 'konovlyoha@mail.ru', 'ovchinnikov.im@yandex.ru', 'basheff.oleg@yandex.ru', 'decor@mirgos.ru', 'kuzminskaya@baikalsea.com', 'vaswet1003@gmail.com', 'apmbox@gmail.com', 'mymoding@mail.ru', 'yriy_konchakov@mail.ru', 'teplyakov89@gmail.com', '907-900@mail.ru', 'wit-vent@mail.ru', 'aviamexanik77@gmail.com', 'dukirill@mail.ru', 'ale13264@yandex.ru', 'bur4nov@yandex.ru', 'arteomz@yandex.ru', 'dimakirk@gmail.com', 'tvv.85@ya.ru', 'iznu@irk.ru', 'angarsk@inbox.ru', 'fartusova.katya@mail.ru', 'alex_pin@mail.ru', 'vafint@bk.ru', 'st-expo@mail.ru', 'kobreus@gmail.com', 'adigo777@list.ru', 'v.gilev@groupstp.ru', 'zav1964@yandex.ru', 'belovkirill@yandex.ru', 'strlogist@yandex.ru', 'sarma201@mail.ru', 'yasksergej@yandex.ru', 'ya.tna77@yandex.ru', 'griha888@gmail.com', 'a663903@yandex.ru', 'miks.st@mail.ru', 'vlastin-vm@yandex.ru', 'anik_60@mail.ru', 'kuliasov.v@yandex.ru', 'comradnikitin@yandex.ru', 'nalofree@gmail.com', '149@irk.ru', 'iltadi@mail.ru', 'papa@strlog.ru');
        return in_array($email,$array);
    }
    protected function getUserGroupId($hlFieldGroup = '', $hlFieldAccum = null){
        $result = 9;
        $array = array(
            "КП(ФИЗ)" => 9, "КП(ЮР)" => 14, "СО(КМС)" => 10, "СО(ПГС)" => 12, "ТО" => 11
        );
        if (!empty($array[$hlFieldGroup])) $result = $array[$hlFieldGroup];
        $hlFieldAccum = (float)preg_replace('/\s/','',$hlFieldAccum);
        if ((($result === 9) || ($result === 14)) && ($hlFieldAccum > 10000))
            $result = 15;
        return array_merge(array(2,3,4,5,6), array($result));
    }
}
