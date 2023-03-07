<?
namespace Caweb\Main\Events;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Authentication\ApplicationPasswordTable;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserFieldTable;
use Bitrix\Main\UserTable;

Loc::loadLanguageFile(__FILE__);
Loader::includeModule('highloadblock');
class HL{
    public static $doExchange = false;
    protected static $bHandlerStop = false;
    //KontragentyOnAfterAdd
    //запускает обновление вместо добавления
    public function OnBeforeAdd(\Bitrix\Main\Entity\Event $event){
        $result = new EventResult();
        $entity = $event->getEntity();
        $entityDataClass = $entity->GetDataClass();
        $fields = $event->getParameter('fields');
        $email = $fields['UF_ELEKTRONNAYAPOCHT'];
        try {
            $db = $entityDataClass::getList(array('filter' => array('UF_ELEKTRONNAYAPOCHT' => $email)))->fetch();
            if ($db){
                $entityDataClass::update((int)$db['ID'], $fields);
                throw new \Exception();
            }
        }catch (\Exception $exception){
            $result->setErrors(array());
            return $result;
        }
    }
    //обновление и добавление записи, если обновлено без ошибок, очищает поле UF_ERROR, таблица чистится агентом
    //userExchangeLogs
    public function OnAfterAddUpdate(\Bitrix\Main\Entity\Event $event){
        if (self::$bHandlerStop) return;
        self::$doExchange = true;
        $id = $event->getParameter('id');
        $entity = $event->getEntity();
        $entityDataClass = $entity->GetDataClass();

        $fields = $event->getParameter('fields');
        $email = $fields['UF_ELEKTRONNAYAPOCHT'];
        $instance = new self();
        try {
            if (empty($email))
                throw new \Exception('Пустой e-mail');
            if (in_array($email, array('papa@strlog.ru', 'astrlog@strlog.ru', 'reutov@caweb.ru')))
                throw new \Exception('Это админы');
            $userFields = $instance->getUser($email);
            $resultFields = $instance->prepareFields($fields, $userFields);

            $user = new \CUser();
            $ID = false;
            if (empty($userFields)){
                $ID = $user->Add($resultFields);
            }else{
                if ($user->Update((int)$userFields['ID'], $resultFields)) $ID = $userFields['ID'];
            }
            if (!$ID)
                throw new \Exception('Ошибка создания/обновления пользователя: '.$user->LAST_ERROR);

            if (empty($userFields)) $instance->sendEmail($ID, $resultFields);
            $instance->writeSaleAccount($ID, $resultFields);
            if (!empty($fields['UF_ERROR'])){
                self::$bHandlerStop = true;
                $entityDataClass::update($id, array('UF_ERROR' => ''));
                self::$bHandlerStop = false;
            }

        }catch (\Exception $exception){
            self::$bHandlerStop = true;
            $entityDataClass::update($id, array('UF_ERROR' => $exception->getMessage()));
            self::$bHandlerStop = false;
        }
        self::$doExchange = false;
    }
    protected function getUser($email){
        $result = array();
        $params['filter'] = array('EMAIL' => $email);
        $params['select'] = array('ID', 'EMAIL', 'NAME', 'PERSONAL_PHONE','UF_INN','UF_KPP');
        $result = UserTable::getRow($params);
        return $result;
    }
    protected function prepareFields($newFields, $oldFields = array()){
        $result = array();
        $result['GROUP_ID'] = $this->getUserGroupId($newFields['UF_GRUPPANASAYTE'], $newFields['UF_OBSHCHIYOBOROT']);
        $result['PERSONAL_PROFESSION'] = $newFields['UF_GRUPPANASAYTE'];
        $result['UF_BONUSES'] = $newFields['UF_OSTATOKBONUSOV'];
        $result['UF_BONUS_CARD'] = $newFields['UF_KODBONUSNOYKARTY'];
        $result['UF_ACCUMULATION'] = $newFields['UF_OBSHCHIYOBOROT'];

        if($newFields['UF_GRUPPANASAYTE'] === 'ТО')
            ((int)$newFields['UF_AKTIVENNASAYTE'] === 1)? $result['ACTIVE'] = 'Y': $result['ACTIVE'] = 'N';

        if (empty($oldFields['NAME'])) $result['NAME'] = $newFields['UF_NAME'];
        if (empty($oldFields['PERSONAL_PHONE'])) $result['PERSONAL_PHONE'] = $newFields['UF_TELEFONKONTRAGENT'];
        if (empty($oldFields['UF_INN'])) $result['UF_INN'] = $newFields['UF_INN'];
        if (empty($oldFields['UF_KPP'])) $result['UF_KPP'] = $newFields['UF_KPP'];
        if (!empty($oldFields)) return $result;
        $result['EMAIL'] = $newFields['UF_ELEKTRONNAYAPOCHT'];
        $result['LOGIN'] = $newFields['UF_ELEKTRONNAYAPOCHT'];
        $result['PERSONAL_PROFESSION'] = $newFields['UF_GRUPPANASAYTE'];
        $result['LID'] = 'ru';
        if($newFields['UF_GRUPPANASAYTE'] !== 'ТО')
            $result['ACTIVE'] = 'Y';
        $psw = ApplicationPasswordTable::generatePassword();
        $result['PASSWORD'] = $psw;
        $result['CONFIRM_PASSWORD'] = $psw;
        return $result;
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
    protected function sendEmail($ID, $arFields){
        $arEventFields= array(
            "LOGIN" => $arFields['EMAIL'],
            "PASSWORD" => $arFields['PASSWORD'],
            "EMAIL" => $arFields['EMAIL']
        );
        $CV = \CEvent::Send("USER_INFO_STRLOG", SITE_ID, $arEventFields, "N", 95, array(),LANGUAGE_ID);
        if (empty($CV))
            throw new \Exception('Ошибка добавления события отправки письма');
    }
    protected function writeSaleAccount($userId,$arFields){
        if (($arFields['UF_BONUSES'] === '') || ($arFields['UF_BONUSES'] === null) || ($arFields['UF_BONUSES'] === false)) return;
        $res = false;
        $account = new \CSaleUserAccount();
        $accountFields = $account->GetByUserID($userId, "RUB");
        $array = array("USER_ID" => $userId, "CURRENCY" => "RUB", "CURRENT_BUDGET" => $arFields['UF_BONUSES']);
        $resAdd = $resUpdate = true;
        if (empty($accountFields)){
            $resAdd = $account->Add($array);
        }else{
            $resUpdate = $account->Update((int)$accountFields['ID'], $array);
        }
        if (!$resAdd){
            $strError = 'хз';
            if($ex = $GLOBALS["APPLICATION"]->GetException())
                $strError = $ex->GetString();
            throw new \Exception('Не добавился аккаунт покупателя: '.$strError);
        }
        if (!$resUpdate)
            throw new \Exception('Не обновился аккаунт покупателя - '.$accountFields['ID']);
    }
    public static function userExchangeLogs(){
        $block = HighloadBlockTable::getById(4)->fetch();
        $object = HighloadBlockTable::compileEntity($block);
        $string = $object->getDataClass();
        $hlFieldsDb = \CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'HLBLOCK_4', 'LANG' => 'ru'));
        $hlFieldsNames = array();
        while ($iterator = $hlFieldsDb->Fetch()){
            if (!empty($iterator['EDIT_FORM_LABEL']))
                $hlFieldsNames[$iterator['FIELD_NAME']] = $iterator['EDIT_FORM_LABEL'];
            else
                $hlFieldsNames[$iterator['FIELD_NAME']] = $iterator['FIELD_NAME'];
        }
        $db = $string::getList(array(
            'group' => array('UF_GRUPPANASAYTE')
        ));
        $errors = '';
        while ($ar = $db->fetch()){
            if (!empty($ar['UF_ERROR'])){
                foreach ($ar as $key => $value){
                    if (!empty($value) && ($key !== 'ID')){
                        if (empty($errors)) $errors .= 'Ошибки обмена пользователями за 24 часа: #BR##BR#';
                        $errors .= $hlFieldsNames[$key].': '.$value.'#BR#';
                    }
                }
                $errors .= '#BR#';
            }else{
                $string::delete($ar['ID']);
            }

        }
        if (!empty($errors)){
            $queryUrl = 'https://crm.strlog.ru/rest/52/n64271cljpvl8cnj/im.message.add.json';
            $queryData = http_build_query(
                array(
                    "MESSAGE" => $errors,
                    "SYSTEM" => "N",
                    "DIALOG_ID" => "52",
                ));
            $curl = curl_init();
            curl_setopt_array($curl,
                array( CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $queryUrl, CURLOPT_POSTFIELDS => $queryData));
            $result = curl_exec($curl);
            curl_close($curl);
        }

        return '\Caweb\Main\Events\HL::userExchangeLogs();';

    }

}
