<?
#################################################
#        Company developer: ALTASIB
#        Developer: Evgeniy Pedan
#        Site: http://www.altasib.ru
#        E-mail: dev@altasib.ru
#        Copyright (c) 2006-2011 ALTASIB
#################################################
?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$templateAdd = '';
if($arParams['SHOW_POPUP']=='Y')
    $templateAdd = 'popup';
    
$APPLICATION->IncludeComponent("altasib:review.add", $templateAdd, $arParams,$component);?>

<?$APPLICATION->IncludeComponent("altasib:review.list", ".default", $arParams,$component);?>