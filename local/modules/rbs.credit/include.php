<?php
use Bitrix\Main\Loader;

require dirname(__FILE__) ."/config.php";

Loader::registerAutoLoadClasses(
	$RBS_CONFIG['MODULE_ID'],
	array(
        '\Rbs\Credit\Gateway' => 'lib/rbs/Gateway.php',
        '\Rbs\Credit\Orders' => 'lib/rbs/Orders.php',
	)
);
?>