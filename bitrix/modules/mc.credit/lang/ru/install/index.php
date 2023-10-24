<?php
global $APPLICATION;
$MESS['MC_PAYMENT_MODULE_NAME'] = 'MC-Credit';
$MESS['MC_PAYMENT_MODULE_DESCRIPTION'] = 'Покупка товаров в кредит';
$MESS['MC_PAYMENT_PARTNER_NAME'] = 'Moneycare';
$MESS['MC_PAYMENT_PARTNER_URI'] = 'http://moneycare.su/';

if (mb_strtoupper(SITE_CHARSET) !== 'UTF-8') {
    $MESS = $APPLICATION->ConvertCharsetArray($MESS, 'UTF-8', 'windows-1251');
}