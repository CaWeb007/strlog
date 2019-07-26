<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$data = array(
	'NAME' => Loc::getMessage("RBS_CREDIT_MODULE_TITLE"),
	'SORT' => 100,
	// 'LOGOTIP' => '/bitrix/images/sale/sale_payments/yandexcheckout.png',
	'CODES' => array(
		"API_LOGIN" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_API_LOGIN_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_API_LOGIN_DESCR"),
			'SORT' => 100,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_GATE"),
		),
		"API_PASSWORD" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_API_PASSWORD_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_API_PASSWORD_DESCR"),
			'SORT' => 120,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_GATE"),
		),
		"API_TEST_MODE" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_API_TEST_MODE_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_API_TEST_MODE_DESCR"),
			'SORT' => 130,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_GATE"),
			"INPUT" => array(
				'TYPE' => 'Y/N'
			),
			'DEFAULT' => array(
				"PROVIDER_VALUE" => "N",
            	"PROVIDER_KEY" => "INPUT"
			)
		),
		"API_RETURN_URL" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_API_RETURN_URL_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_API_RETURN_URL_DESCR"),
			'SORT' => 670,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_GATE"),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'INPUT',
				'PROVIDER_VALUE' => ''
			)
		),
		"API_FAIL_URL" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_API_FAIL_URL_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_API_FAIL_URL_DESCR"),
			'SORT' => 680,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_GATE"),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'INPUT',
				'PROVIDER_VALUE' => ''
			)
		),
		"HANDLER_LOGGING" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_HANDLER_LOGGING_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_HANDLER_LOGGING_DESCR"),
			'SORT' => 250,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_HANDLER"),
			"INPUT" => array(
				'TYPE' => 'Y/N'
			),
			'DEFAULT' => array(
				"PROVIDER_VALUE" => "Y",
            	"PROVIDER_KEY" => "INPUT"
			)
		),

		"HANDLER_SHIPMENT" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_HANDLER_SHIPMENT_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_HANDLER_SHIPMENT_DESCR"),
			'SORT' => 320,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_HANDLER"),
			"INPUT" => array(
				'TYPE' => 'Y/N'
			),
			'DEFAULT' => array(
				"PROVIDER_VALUE" => "N",
            	"PROVIDER_KEY" => "INPUT"
			)
		),
		"CREDIT_TYPE" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_TYPE_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_TYPE_DESCR"),
			'SORT' => 210,
			'GROUP' => Loc::getMessage("RBS_GROUP_CREDIT"),
			'TYPE' => 'SELECT',
			'INPUT' => array(
				'TYPE' => 'ENUM',
				'OPTIONS' => array(
					"CREDIT" => Loc::getMessage("RBS_CREDIT_TYPE_VALUE_1"), // Кредит
	                "INSTALLMENT" => Loc::getMessage("RBS_CREDIT_TYPE_VALUE_2"), // Кредит без переплаты
	                
				)
			),
			'DEFAULT' => array(
				"PROVIDER_VALUE" => "1",
            	"PROVIDER_KEY" => "INPUT"
			)
		),
		"CREDIT_MAX_MONTH" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_MAX_MONTH_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_MAX_MONTH_DESCR"),
			'GROUP' => Loc::getMessage("RBS_GROUP_CREDIT"),
			'SORT' => 220,
			'DEFAULT' => array(
				"PROVIDER_VALUE" => "12",
			)
		),
		"ORDER_NUMBER" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_ORDER_NUMBER_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_ORDER_NUMBER_DESCR"),
			'SORT' => 650,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_ORDER"),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'ORDER',
				'PROVIDER_VALUE' => 'ACCOUNT_NUMBER'
			)
		),
		"ORDER_AMOUNT" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_ORDER_AMOUNT_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_ORDER_AMOUNT_DESCR"),
			'SORT' => 660,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_ORDER"),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'PAYMENT',
				'PROVIDER_VALUE' => 'SUM'
			)
		),
		"ORDER_DESCRIPTION" => array(
			"NAME" => Loc::getMessage("RBS_CREDIT_ORDER_DESCRIPTION_NAME"),
			"DESCRIPTION" => Loc::getMessage("RBS_CREDIT_ORDER_DESCRIPTION_DESCR"),
			'SORT' => 670,
			'GROUP' => Loc::getMessage("RBS_CREDIT_GROUP_ORDER"),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'ORDER',
				'PROVIDER_VALUE' => 'USER_DESCRIPTION'
			)
		),


	)
);
