<?
require __DIR__ . '/config.php';

$rbs_credit_default_option = array(
		'BANK_NAME' => $RBS_CONFIG['BANK_NAME'],
		'MODULE_ID' => $RBS_CONFIG['MODULE_ID'],
		'RBS_PROD_URL' => $RBS_CONFIG['RBS_PROD_URL'],
		'RBS_TEST_URL' => $RBS_CONFIG['RBS_TEST_URL'],
		'MODULE_VERSION' => $RBS_CONFIG['MODULE_VERSION'],
		'ISO' => serialize($RBS_CONFIG['ISO']),
		'RESULT_ORDER_STATUS' => 'P'
    );

?>