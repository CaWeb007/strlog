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
        'WITHOUT_OVERPAY' => array(
            'NAME' => Loc::getMessage('MONEYCARE_WITHOUT_OVERPAY'),
            'SORT' => 100,
            "INPUT" => array(
                'TYPE' => 'Y/N'
            ),
            'DEFAULT' => array(
                "PROVIDER_VALUE" => "N",
                "PROVIDER_KEY" => "INPUT"
            )
        ),
        'TEST_MODE' => array(
            'NAME' => Loc::getMessage('MONEYCARE_TEST_MODE'),
            'SORT' => 100,
            "INPUT" => array(
                'TYPE' => 'Y/N'
            ),
            'DEFAULT' => array(
                "PROVIDER_VALUE" => "N",
                "PROVIDER_KEY" => "INPUT"
            )
        )
    )
);