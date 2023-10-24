<?php

global $APPLICATION;
$MESS['MC_PAYMENT_MODULE_TITLE'] = 'MC-Credit';

$MESS['MC_CREDIT_API_HOST'] = 'HOST API moneycare';
$MESS['MC_CREDIT_LOGIN'] = 'Логин';
$MESS['MC_CREDIT_PASSWORD'] = 'Пароль';
$MESS['MC_CREDIT_POINT_ID'] = 'Код торговой точки';
$MESS['MC_CREDIT_INSTALLMENT'] = 'Разрешить покупку в рассрочку';
$MESS['MC_CREDIT_MAX_DISCOUNT'] = 'Максимальная скидка';
//$MESS['MC_CREDIT_SHOW_PAYMENT_MONTH'] = 'Отображать в товаре ежемесячный платеж';
$MESS['MC_CREDIT_COMPLETION_FORM'] = 'Форма анкеты';
$MESS['MC_CREDIT_DESCRIPTION'] = 'Покупка в кредит через';
$MESS['MC_CREDIT_IFRAME'] = 'На сайте (iFrame)';
$MESS['MC_CREDIT_REDIRECT'] = 'Редирект на moneycare.su';
$MESS['MC_CREDIT_PRODUCT_CATEGORY'] = 'Код товарной категории';

if (mb_strtoupper(SITE_CHARSET) !== 'UTF-8') {
    $MESS = $APPLICATION->ConvertCharsetArray($MESS, 'UTF-8', 'windows-1251');
}
