<?php

include dirname(__FILE__) . "/install/version.php";

$RBS_CONFIG = [
	'MODULE_ID' => 'rbs.credit',
	'BANK_NAME' => 'Sberbank',
	'RBS_PROD_URL' => 'https://securepayments.sberbank.ru/payment/rest/',
	'RBS_PROD_URL_С' => 'https://securepayments.sberbank.ru/sbercredit/',
	'RBS_TEST_URL' => 'https://3dsec.sberbank.ru/payment/rest/',
	'RBS_TEST_URL_С' => 'https://3dsec.sberbank.ru/sbercredit/',
	'ISO' => array(
	    'USD' => 840,
	    'EUR' => 978,
	    'RUB' => 643,
	    'RUR' => 643,
	    'BYN' => 933
	),
	'MODULE_VERSION' => $arModuleVersion['VERSION'],
];

