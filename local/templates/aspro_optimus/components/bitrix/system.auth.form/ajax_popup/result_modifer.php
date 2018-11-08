<?php
/**
 * Created by olegpro.ru.
 * User: Oleg Maksimenko <oleg.39style@gmail.com>
 * Date: 10.12.2015 22:32
 */
 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
 
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */
 
global $APPLICATION;
 
if (isset($_POST['AJAX-ACTION']) && $_POST['AJAX-ACTION'] == 'AUTH') {
    $APPLICATION->RestartBuffer();
 var_dump($_POST);exit;
    header('Content-type: application/json');
 
    if (
        (
            isset($arResult['ERROR'])
            && $arResult['ERROR'] === true
        )
        ||
        (
            !empty($arResult['ERROR_MESSAGE'])
            && isset($arResult['ERROR_MESSAGE']['TYPE'])
            && $arResult['ERROR_MESSAGE']['TYPE'] == 'ERROR'
        )
    ) {
        $response = array(
            'STATUS' => 'ERROR',
            'MESSAGES' => array(
                strip_tags($arResult['ERROR_MESSAGE']['MESSAGE'])
            ),
        );
    } else {
        $response = array(
            'STATUS' => 'OK',
        );
    }
 
    echo \Bitrix\Main\Web\Json::encode($response);
 
    die();
}