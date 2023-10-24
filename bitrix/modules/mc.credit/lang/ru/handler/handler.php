<?php
global $APPLICATION;
$MESS['MC_PAYMENT_MESSAGE_SUCCESS'] = 'Анкета на кредит успешно заполнена!';
$MESS['MC_PAYMENT_MESSAGE_FAIL'] = 'Вы отказались от заполнения анкеты на кредит!';
$MESS['MC_PAYMENT_MESSAGE_ERROR'] = 'Произошла ошибка!';

if (mb_strtoupper(SITE_CHARSET) !== 'UTF-8') {
    $MESS = $APPLICATION->ConvertCharsetArray($MESS, 'UTF-8', 'windows-1251');
}
