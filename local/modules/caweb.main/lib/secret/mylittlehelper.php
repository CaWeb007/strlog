<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 29.05.2020
 * Time: 11:14
 */

namespace Caweb\Main\Secret;
use Bitrix\Catalog\PriceTable;
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
use Caweb\Main\Sale\Bonus;

/**usage   \Bitrix\Main\Loader::includeModule('caweb.main');*/
class MyLittleHelper {
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
}