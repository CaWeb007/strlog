<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 29.05.2020
 * Time: 11:14
 */

namespace Caweb\Main\Secret;
use Bitrix\Main\Application;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
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
}