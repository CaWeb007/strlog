<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerCaweb');
function OnBuildGlobalMenuHandlerCaweb(&$arGlobalMenu, &$arModuleMenu){
    if(!defined('CAWEB_MENU_INCLUDED')){
        define('CAWEB_MENU_INCLUDED', true);

        IncludeModuleLangFile(__FILE__);
        $moduleID = 'caweb.main';

        //$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/menu.css");

        if($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R'){
            $arMenu = array(
                'menu_id' => 'global_menu_caweb',
                'text' => Loc::getMessage('CAWEB_DISCOUNT'),
                'title' => Loc::getMessage('CAWEB_DISCOUNT'),
                'sort' => 1000,
                'items_id' => 'global_menu_caweb_items',
                'icon' => '',
                'url' => '/local/admin/caweb_discount_list.php'
            );

            if(!isset($arGlobalMenu['global_menu_caweb'])){
                $arGlobalMenu['global_menu_caweb'] = array(
                    'menu_id' => 'global_menu_caweb',
                    'text' => Loc::getMessage('CAWEB_ADMIN'),
                    'title' => Loc::getMessage('CAWEB_ADMIN'),
                    'sort' => 1000,
                    'items_id' => 'global_menu_caweb_items',
                    'items' => array($arMenu)
                );
            }

            //$arGlobalMenu['global_menu_caweb']['items'][] = $arMenu;
        }
    }
}