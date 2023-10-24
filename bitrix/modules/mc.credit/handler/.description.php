<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Mc\Credit\GatewayMoneyCare;

Loc::loadMessages(__FILE__);
Loader::includeModule('mc.credit');

$data = array(
    'NAME' => Loc::getMessage('MC_PAYMENT_MODULE_TITLE'),
    'SORT' => 100,
    'CODES' => array(
        'API_HOST' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_API_HOST'),
            'TYPE' => '',
            'SORT' => 1,
            'DEFAULT' => array(
                'PROVIDER_KEY' => 'VALUE',
                'PROVIDER_VALUE' => 'https://mc.moneycare.su'
            )
        ),
        'LOGIN' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_LOGIN'),
            'TYPE' => '',
            'SORT' => 2,
            'DEFAULT' => array(
                'PROVIDER_KEY' => 'VALUE',
                'PROVIDER_VALUE' => 'api_test'
            )
        ),
        'PASSWORD' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_PASSWORD'),
            'TYPE' => '',
            'SORT' => 3,
            'DEFAULT' => array(
                'PROVIDER_KEY' => 'VALUE',
                'PROVIDER_VALUE' => '1234567'
            )
        ),
        'MC_POINT_ID' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_POINT_ID'),
            'TYPE' => '',
            'SORT' => 4,
            'DEFAULT' => array(
                'PROVIDER_KEY' => 'VALUE',
                'PROVIDER_VALUE' => 'tt_test_1'
            )
        ),
        'INSTALLMENT' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_INSTALLMENT'),
            'VALUE' => 'ID',
            'SORT' => 5,
            "INPUT" => array(
                'TYPE' => 'Y/N'
            ),
            'DEFAULT' => array(
                'PROVIDER_VALUE' => 'N',
                'PROVIDER_KEY' => 'INPUT'
            )
        ),
        'MAX_DISCOUNT' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_MAX_DISCOUNT'),
            'VALUE' => 'ID',
            'TYPE' => '',
            'SORT' => 6,
        ),
        'PRODUCT_CATEGORY' => array(
            'NAME' => Loc::getMessage('MC_CREDIT_PRODUCT_CATEGORY'),
            'TYPE' => '',
            'SORT' => 9,
            'DEFAULT' => array(
                'PROVIDER_KEY' => 'VALUE',
                'PROVIDER_VALUE' => 'test'
            )
        )
    )
);


