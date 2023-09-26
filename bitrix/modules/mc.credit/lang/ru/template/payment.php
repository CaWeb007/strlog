<?php
global $APPLICATION;
$MESS['MC_TEXT_CREDIT_FORM'] = 'Теперь Вам необходимо приступить к оформлению анкеты на кредит.';
$MESS['MC_TEXT_ENTER_FORM'] = 'Для этого заполните форму размещенную ниже.';
$MESS['MC_TEXT_NOTE_FORM'] = 'Если вы заполнили анкету, то следует дождаться ответа банка';
$MESS['MC_TEXT_IFRAME_NOT_SUPPORT'] = 'Если вы заполнили анкету, то следует дождаться ответа банка';

if (mb_strtoupper(SITE_CHARSET) !== 'UTF-8') {
    $MESS = $APPLICATION->ConvertCharsetArray($MESS, 'UTF-8', 'windows-1251');
}
