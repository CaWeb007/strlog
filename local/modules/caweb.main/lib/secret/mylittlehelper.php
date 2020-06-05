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
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
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
}