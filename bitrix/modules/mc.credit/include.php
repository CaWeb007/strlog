<?php

use Bitrix\Main\Loader;

require dirname(__FILE__) . '/config.php';

Loader::registerAutoLoadClasses(
    $MC_CONFIG['MODULE_ID'],
    array(
        '\Mc\Credit\API\MoneyCareAPI' => 'lib/api/MoneyCareAPI.php',
        '\Mc\Credit\Client\CurlHttpClient' => 'lib/client/CurlHttpClient.php',
        '\Mc\Credit\GatewayMoneyCare' => 'lib/GatewayMoneyCare.php',
    )
);
