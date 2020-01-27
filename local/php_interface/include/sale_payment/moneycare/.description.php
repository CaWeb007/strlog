<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$data = array(
    'NAME' => Loc::getMessage('MONEYCARE_NAME'),
    'SORT' => '500',
    'CODES' => array(
        'LOGIN' => array(
            'NAME' => Loc::getMessage('MONEYCARE_LOGIN'),
            'SORT' => 100
        )
    )
);