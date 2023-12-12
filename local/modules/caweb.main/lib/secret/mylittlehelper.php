<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 29.05.2020
 * Time: 11:14
 */

namespace Caweb\Main\Secret;
use Bitrix\Catalog\PriceTable;
use Bitrix\Forum\MessageTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\OrderTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\Internals\UserPropsValueTable;
use Bitrix\Sale\Order;
use Caweb\Main\Catalog\Helper as CatalogHelper;
use Caweb\Main\Sale\Bonus;

/**usage   \Bitrix\Main\Loader::includeModule('caweb.main');*/
class MyLittleHelper {
    public const CATALOG_IBLOCK = 16;
    public const NECONDITION_IBLOCK = 24;
    public const PROPERTY_ORDER_ITEM_ID = 710;
    public const ENUM_ORDER_ITEM_ID = 15984;
    public const NECOND_PROPERTY_ORDER_ITEM_ID = 793;
    //public const NECOND_PROPERTY_ORDER_ITEM_ID = 789;
    public const NECOND_ENUM_ORDER_ITEM_ID = 16721;
    //public const NECOND_ENUM_ORDER_ITEM_ID = 16701;
    /**usage   \Caweb\Main\Secret\MyLittleHelper::SortEnumOffer();*/
    public static function SortEnumOffer(){
        Loader::includeModule('iblock');
        $result = array();
        $db = \CIBlockPropertyEnum::GetList(array('value' => 'asc'), array('PROPERTY_ID' => 594));
        while ($ar = $db->Fetch()){
            if (empty((int)$ar['VALUE']))
                $sort = 100000;
            else
                $sort = floatval(str_replace(',', '.', $ar['VALUE'])) * 100;
            \CIBlockPropertyEnum::Update((int)$ar['ID'], array('SORT' => $sort));

        }
        return 'done';
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::writePersonalProfessionField();*/
    public static function writePersonalProfessionField(bool $are_you_ready = false){
        $domain = Application::getInstance()->getContext()->getServer()->getHttpHost();
        $arUser = array();
        $params = array();
        $params['filter'] = array('PERSONAL_PROFESSION' => false);
        $params['select'] = array('ID');
        $userIterator = UserTable::getList($params);
        while($ar = $userIterator->fetch()){
            $groups = UserTable::getUserGroupIds((int)$ar['ID']);
            $groupsFIZCheck = !empty(array_intersect($groups, array(9, 15)));
            $groupsURCheck = !empty(array_intersect($groups, array(10,11,12,14)));
            $props['filter'] = array('USER_ID' => $ar['ID']);
            $props['order'] = array('DATE_UPDATE' => 'desc');
            $props['limit'] = 1;
            $userTypeId = (int)UserPropsTable::getRow($props)['PERSON_TYPE_ID'];
            $fieldValue = false;
            $action = '';
            $userNewGroup = false;
            if ($groupsFIZCheck && $groupsURCheck){
                if ($userTypeId === 2){
                    $fieldValue = 'КП(ЮР)';
                    $userNewGroup = array_diff($groups, array(9,15));
                }else{
                    $fieldValue = 'КП(ФИЗ)';
                    $userNewGroup = array_diff($groups, array(10,11,12,14));
                }
                $type = 'double';
            }elseif($groupsFIZCheck){
                $type = 'fiz';
                $fieldValue = 'КП(ФИЗ)';
            }elseif ($groupsURCheck){
                $fieldValue = 'КП(ЮР)';
                $type = 'ur';
            }else{
                if ($userTypeId === 2){
                    $fieldValue = 'КП(ЮР)';
                    $userNewGroup = array_merge($groups, array(14));
                }else{
                    $fieldValue = 'КП(ФИЗ)';
                    $userNewGroup = array_merge($groups, array(9));

                }
                $type = '__HZ__';
            };
            if (!empty($fieldValue) && $are_you_ready){
                $class = new \CUser();
                $res = $class->Update((int)$ar['ID'], array('PERSONAL_PROFESSION' => $fieldValue));
                if (!$res) $action = $class->LAST_ERROR;
                else $action .= 'set_profes!!___';
            }
            if (!empty($userNewGroup) && $are_you_ready){
                \CUser::SetUserGroup((int)$ar['ID'], $userNewGroup);
                $action .= 'set_groups___';
            }
            Pr(array(
                'id' => $ar['ID'],
                'groups' => $groups,
                'check' => $groupsFIZCheck,
                'link' => '<a target="_blank" href="http://'.$domain.'/bitrix/admin/user_edit.php?lang=ru&ID='.$ar["ID"].'">user</a>',
                'type' => $type,
                'sale_type' => $userTypeId,
                'new_groups' => $userNewGroup,
                'field' => $fieldValue,
                'action' => $action
            ));
        }
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::prepareDataToSexSiteBaikalIntegration();*/
    public static function prepareDataToSexSiteBaikalIntegration(bool $are_you_ready = false){
        $arUser = array();
        $params = array();
        $userIterator = UserTable::getList($params);
        $k = 0;
        while($ar = $userIterator->fetch()){
            $id = (int)$ar['ID'];
            if ($id === 1) continue;
            $k++;
            if ($k <= 10){
                if (stripos($ar['EMAIL'], '@haha.com') !== false) continue;
                $update = array();
                $update['LOGIN'] = self::encode($ar['LOGIN']);
                $update['EMAIL'] = $update['LOGIN'].'@haha.com';
                $update['NAME'] = $update['LOGIN'];
                $update['SECOND_NAME'] = $update['LOGIN'];
                $update['LAST_NAME'] = $update['LOGIN'];
                $update['TITLE'] = $update['LOGIN'];
                $update['PERSONAL_PHONE'] = '123456789';
                $update['PERSONAL_WWW'] = $update['LOGIN'];
                if ($are_you_ready){
                    $user = new \CUser();
                    $user->Update($id, $update);
                }
                continue;
            }
            if ($are_you_ready)
                \CUser::Delete($id);
        }
        Loader::includeModule('iblock');
        Loader::includeModule('catalog');
        $iterator = PriceTable::getList();
        while ($ar = $iterator->fetch()){
            $id = (int)$ar['ID'];
            $price = 100 + (float)$ar['CATALOG_GROUP_ID'];
            if ($price === (float)$ar['PRICE']) continue;
            if ($are_you_ready)
                PriceTable::update($id, array(
                    'PRICE' => $price,
                    'PRICE_SCALE' => $price
                ));
        }
        $iterator = OrderTable::getList();
        while ($ar = $iterator->fetch()){
            if ($are_you_ready)
                OrderTable::delete((int)$ar['ID']);
        }
        $object = new \CSaleUserAccount();
        $iterator = $object->GetList();
        while ($ar = $iterator->Fetch()){
            if ($are_you_ready)
                $object->Delete((int)$ar['ID']);
        }
        $iterator = UserPropsValueTable::getList();
        while ($ar = $iterator->fetch()){
            UserPropsValueTable::delete((int)$ar['ID']);
        }
    }
    private static function encode ($unencoded, $key = '1111'){
        $string=base64_encode($unencoded);//Переводим в base64

        $arr=array();//Это массив
        $x=0;
        while ($x++< strlen($string)) {//Цикл
            $arr[$x-1] = md5(md5($key.$string[$x-1]).$key);//Почти чистый md5
            $newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//Склеиваем символы
        }
        return $newstr;//Вертаем строку
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::sectionHoreBitrixSystemFix();*/
    public static function sectionHoreBitrixSystemFix(){
        Loader::includeModule('iblock');
        $param['filter'] = array('IBLOCK_SECTION_ID' => 2040);
        $param['select'] = array('IBLOCK_SECTION_ID', 'IBLOCK_ELEMENT_ID');
        $db = SectionElementTable::getList($param);
        $el = new \CIBlockElement();
        while ($ar = $db->fetch()){
            $el->Update($ar['ID'], array('IBLOCK_SECTION_ID' => 2319));
            SectionElementTable::delete(array('IBLOCK_SECTION_ID' => 2040, 'IBLOCK_ELEMENT_ID' => $ar['IBLOCK_ELEMENT_ID']));
        }
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::checkUserSaleProfiles();*/
    public static function checkUserSaleProfiles(){
        $db = UserGroupTable::getList(array('filter' => array('GROUP_ID' => 11)));
        $resultUserId = array();
        while ($ar = $db->fetch()){
            $resultUserId[] = $ar['USER_ID'];
        }
        $db = UserPropsTable::getList(array('filter' => array('USER_ID' => $resultUserId, 'PERSON_TYPE_ID' => 1)));
        $resultStr = array();
        while ($ar = $db->fetch()){
             $resultStr[] = '<a href ="/bitrix/admin/user_edit.php?lang=ru&ID='.$ar['USER_ID'].'">'.$ar['USER_ID'].'</a></br>';
        }
         print_r($resultStr);
    }
    public static function deleteDoubleProfiles(){
        $db = UserGroupTable::getList(array('filter' => array('GROUP_ID' => Bonus::SITE_GROUP_MODEL)));
        $userIdBYuserProfileId = array();
        while ($ar = $db->fetch()){
            $profileId = 2;
            $check = ($ar['GROUP_ID'] == 9) || ($ar['GROUP_ID'] == 15);
            if ($check) $profileId = 1;
            $userIdBYuserProfileId[$ar['USER_ID']] = $profileId;
        }
        unset($db, $ar);
        $params['group'] = array('USER_ID');
        $params['order'] = array('DATE_UPDATE' => 'DESC');
        $db = UserPropsTable::getList($params);
        $userIdBYuserProfilesArray = array();
        while($ar = $db->fetch()){
            $check = ((int)$ar['PERSON_TYPE_ID'] !== $userIdBYuserProfileId[$ar['USER_ID']]) || !empty($userIdBYuserProfilesArray[$ar['USER_ID']]);
            if ($check){
                $res = UserPropsTable::delete($ar['ID']);
                if ($res->isSuccess()){
                    continue;
                }else{
                    echo 'error delete '.$ar['ID'];
                    return;
                }
            }
            $ar['CHECK'] = $check;
            $userIdBYuserProfilesArray[$ar['USER_ID']][] = $ar;
        }
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::adminsIsAMotherFuckers();*/
    public static function adminsIsAMotherFuckers($startOrderId = 0){
        if ($startOrderId === 0){
            Pr('write order');
            return;
        }
        $params['filter'] = array('>=ID'=> $startOrderId);
        $params['select'] = array('ID');
        $db = OrderTable::getList($params);
        $date = new DateTime();
        while ($ar = $db->fetch()){
            $res = OrderTable::update((int)$ar['ID'], array('DATE_UPDATE' => $date));
            if (!$res->isSuccess()) Pr($res->getErrorMessages());
        }
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::getList();*/
    public static function getList(){
        Loader::includeModule('iblock');
        $filter = array('ACTIVE' => 'Y', 'SECTION_ID' => 2401, 'IBLOCK_ID' => 16);
        $db = \CIBlockElement::GetList(array(), $filter);
        $res = array();
        while ($ar = $db->Fetch()){
            $res[] = $ar;
        }
        Pr(count($res));
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::cleanUpUpload();*/
    public static function cleanUpUpload(){
        global $DB;

        define("NO_KEEP_STATISTIC", true);
        define("NOT_CHECK_PERMISSIONS", true);
        $deleteFiles = 'yes'; //Удалять ли найденые файлы yes/no
        $saveBackup = 'no'; //Создаст бэкап файла yes/no
        //Папка для бэкапа
        $patchBackup = $_SERVER['DOCUMENT_ROOT'] . "/upload/iblock_Backup/";
        //Целевая папка для поиска файлов
        $rootDirPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/iblock";

        $time_start = microtime(true);

        //Создание папки для бэкапа
        if (!file_exists($patchBackup)) {
            CheckDirPath($patchBackup);
        }
        // Получаем записи из таблицы b_file
        $arFilesCache = array();
        $result = $DB->Query('SELECT FILE_NAME, SUBDIR FROM b_file WHERE MODULE_ID = "iblock"');
        while ($row = $result->Fetch()) {
            $arFilesCache[$row['FILE_NAME']] = $row['SUBDIR'];
        }
        $hRootDir = opendir($rootDirPath);
        $count = 0;
        $contDir = 0;
        $countFile = 0;
        $i = 1;
        $removeFile=0;
        while (false !== ($subDirName = readdir($hRootDir))) {
            if ($subDirName == '.' || $subDirName == '..') {
                continue;
            }
            //Счётчик пройденых файлов
            $filesCount = 0;
            $subDirPath = "$rootDirPath/$subDirName"; //Путь до подкатегорий с файлами
            $hSubDir = opendir($subDirPath);
            while (false !== ($fileName = readdir($hSubDir))) {
                if ($fileName == '.' || $fileName == '..') {
                    continue;
                }
                $countFile++;
                if (array_key_exists($fileName, $arFilesCache)) { //Файл с диска есть в списке файлов базы - пропуск
                    $filesCount++;
                    continue;
                }
                $fullPath = "$subDirPath/$fileName"; // полный путь до файла
                $backTrue = false; //для создание бэкапа
                if ($deleteFiles === 'yes') {
                    //Удаление файла
                    if (unlink($fullPath)) {
                        $removeFile++;
                    }
                } else {
                    $filesCount++;
                }
                $i++;
                $count++;
                unset($fileName, $backTrue);
            }
            closedir($hSubDir);
            //Удалить поддиректорию, если удаление активно и счётчик файлов пустой - т.е каталог пуст
            if ($deleteFiles && !$filesCount) {
                rmdir($subDirPath);
            }
            $contDir++;
        }
        closedir($hRootDir);
        echo $removeFile;
        return "CleanUpUpload();";
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::forumMessageCleaner();*/
    public static function forumMessageCleaner($clean = false){
        Loader::includeModule('forum');
        $db = MessageTable::getList();
        $cPattern = '(?:(?:(?:http[s]?):\/\/)|(?:www.))(?:[-_0-9a-z]+.)+[-_0-9a-z]{2,4}[:0-9]*[\/]*';
        mb_regex_encoding('UTF-8');
        $groupArray = array();
        $spamTopicId = array();
        $ipRule = new \CSecurityIPRule();
        $ipRuleSpamBlock = \CSecurityIPRule::GetRuleInclIPs(2);
        $spamIp = array();
        while($ar = $db->fetch()){
                $groupArray[$ar['TOPIC_ID']][] = $ar;
                if ($ar['PARAM1'] === 'IB') continue;
                $message = $ar['POST_MESSAGE'];
                $vRegs = array();
                mb_eregi($cPattern, $message, $vRegs);
                if (count($vRegs) > 0){
                    $spamTopicId[] = $ar['TOPIC_ID'];
                    $spamIp[] = $ar['AUTHOR_IP'];
                }
        }
        if (!$clean)
            $updateIpArray = array_unique(array_merge($ipRuleSpamBlock, $spamIp));
        else
            $updateIpArray = $spamIp;

        if (!empty($spamIp)){
            $ipRule->UpdateRuleIPs(2, $updateIpArray);
            $date = new DateTime();
            $date->add('1 month');
            $ipRule->Update(2, array('ACTIVE_TO' => $date));
        }
        foreach ($spamTopicId as $id){
            $spam = $groupArray[$id];
            foreach ($spam as $item){
                \CForumMessage::Delete((int)$item['ID']);
            }
        }
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::forumMessageCleaner();*/
    public static function setForOrderProperty(){
        Loader::includeModule('iblock');
        $traitsPropertyId = 90;
        $flagPropertyId = 663;
        $db = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => 16));
        while($ar = $db->GetNextElement()){
            $traits = $ar->GetProperty($traitsPropertyId);
            $elementId = (int)$ar->GetFields()['ID'];
            if (!in_array('Заказная позиция', $traits['VALUE'])) continue;
            \CIBlockElement::SetPropertyValuesEx($elementId, 16, array($flagPropertyId => 1));
        }
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::clearEmptyTopics();*/
    public static function clearEmptyTopics(){
        \Bitrix\Main\Loader::includeModule('forum');
        $db = \CForumTopic::GetListEx(array(), array("FORUM_ID" => 1));
        while ($ar = $db->GetNext())
        {
            if ((int)$ar['POSTS'] === 0) {
                \CForumTopic::Delete($ar['ID']);
                continue;
            }
            $messageCount = \CForumMessage::GetList(array(), array("TOPIC_ID" => $ar["ID"]), true);
            if ($messageCount === 0)
                \CForumTopic::Delete($ar['ID']);
        }
        return '\Caweb\Main\Secret\MyLittleHelper::clearEmptyTopics();';
    }
    /**usage   \Caweb\Main\Secret\MyLittleHelper::setElementRating();*/
    public static function setElementRating($elementUrl = false){
        if (!$elementUrl) return 'are you idiot???';
        Loader::includeModule('iblock');
        $vote_count_id = 208;
        $vote_sum_id = 209;
        $rating_id = 210;
        $arPath = explode('/', $elementUrl);
        $arPath = array_diff($arPath, array(""));
        $elementCode = array_pop($arPath);
        if (empty($elementCode)) return 'element not fount';
        $element = \CIBlockElement::GetList(array(), array('CODE' => $elementCode, 'IBLOCK_ID' => self::CATALOG_IBLOCK))->GetNextElement();
        $elementId = $element->GetFields()['ID'];
        $vote_count = (int)$element->GetProperty($vote_count_id)['VALUE'];
        $neededVote = $vote_count * 5;
        $updateArray = array();
        if ($vote_count)
            $updateArray[$vote_sum_id] = $neededVote;
        else
            $updateArray[$rating_id] = 5;
        \CIBlockElement::SetPropertyValuesEx($elementId, self::CATALOG_IBLOCK, $updateArray);
        echo '<a href="'.$elementUrl.'">check</a><br>';
    }
    /**usage
    //title: set order item property
    \Bitrix\Main\Loader::includeModule('caweb.main');
    \Caweb\Main\Secret\MyLittleHelper::setOrderItemProperty();
    */
    public static function setOrderItemProperty(){
        Loader::includeModule('iblock');
        $db = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::CATALOG_IBLOCK, 'PROPERTY_90' => 'Заказная позиция'), false, false, array('ID'));
        while ($iterator = $db->Fetch()){
            \CIBlockElement::SetPropertyValuesEx($iterator['ID'], self::CATALOG_IBLOCK, array(self::PROPERTY_ORDER_ITEM_ID => self::ENUM_ORDER_ITEM_ID));
        }
    }
    /**usage
    //title: catalog store deactivates
    \Bitrix\Main\Loader::includeModule('caweb.main');
    \Caweb\Main\Secret\MyLittleHelper::catalogStoreDeactivate();
     */
    public static function catalogStoreDeactivate(){
        Loader::includeModule('catalog');
        $db = \CCatalogStore::GetList();
        while ($ar = $db->Fetch()){
            if (!in_array((int)$ar['ID'], CatalogHelper::ACTIVE_STORE_IDS)){
                \CCatalogStore::Update((int)$ar['ID'], array('ACTIVE' => 'N'));
            }
        }
    }
    /**usage
    //title: delete necondition order items
    \Bitrix\Main\Loader::includeModule('caweb.main');
    \Caweb\Main\Secret\MyLittleHelper::deleteOrderItems();
     */
    public static function deleteOrderItems(){
        Loader::includeModule('iblock');
        $traitsPropertyId = 358;
        $db = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::NECONDITION_IBLOCK));
        while($ar = $db->GetNextElement()){
            $traits = $ar->GetProperty($traitsPropertyId);
            $elementId = (int)$ar->GetFields()['ID'];
            if (!in_array('Заказная позиция', $traits['VALUE'])) continue;
            \CIBlockElement::Delete($elementId);
        }
    }
    /**usage
    //title: deactivate TO users
    \Bitrix\Main\Loader::includeModule('caweb.main');
    \Caweb\Main\Secret\MyLittleHelper::deactivateTOUsers();
     */
    public static function deactivateTOUsers(){
        $order = array('sort' => 'asc');
        $tmp = 'sort'; //
        $db = \CUser::GetList($order, $tmp, array('GROUPS_ID' => array(11)));
/*        $db->NavStart();
        $c = $db->NavRecordCount;*/
        $user = new \CUser;
        while ($ar = $db->Fetch()){
            $user->Update((int)$ar['ID'], array('ACTIVE' => 'N'));
        }
    }
    /**usage
    //title: set order item property
    \Bitrix\Main\Loader::includeModule('caweb.main');
    \Caweb\Main\Secret\MyLittleHelper::setNecondOrderItemProperty();
     */
    public static function setNecondOrderItemProperty(){
        Loader::includeModule('iblock');
        $db = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::NECONDITION_IBLOCK, 'PROPERTY_358' => 'Заказная позиция'), false, false, array('ID'));
        while ($iterator = $db->Fetch()){
            \CIBlockElement::SetPropertyValuesEx($iterator['ID'], self::NECONDITION_IBLOCK, array(self::NECOND_PROPERTY_ORDER_ITEM_ID => self::NECOND_ENUM_ORDER_ITEM_ID));
        }
    }
}