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
        ),
        'PASSWORD' => array(
            'NAME' => Loc::getMessage('MONEYCARE_PASSWORD'),
            'SORT' => 100
        ),
        'POINT_ID' => array(
            'NAME' => Loc::getMessage('MONEYCARE_POINT_ID'),
            'SORT' => 100
        ),
    )
);