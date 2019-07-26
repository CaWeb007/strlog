<?
if(!isset($_REQUEST['PAYMENT_ID']) && !isset($_REQUEST['ORDER_ID'])) {

	$combinateOrder = explode("_", $_REQUEST['orderNumber'] );
    array_pop($combinateOrder);

    $newRequest = [
	    'PAYMENT' => 'RBS_CREDIT',
	    'PAYMENT_ID' => array_pop($combinateOrder),
	    'ORDER_ID' => implode('_', $combinateOrder),
	    'mdOrder' => $_REQUEST['mdOrder'],
	    'orderNumber' => $_REQUEST['orderNumber'],
	    'operation' => $_REQUEST['operation'],
	    'status' => $_REQUEST['status'],
	];

	$redirect_query = '/bank/credit.php?' . http_build_query($newRequest);
	header('Location:' . $redirect_query, true, 301);
	die;
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\Order;
use \Bitrix\Sale\PaySystem;

global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);
define("DisableEventsCheck", true);

if (CModule::IncludeModule("sale"))
{
	$context = Application::getInstance()->getContext();
	$request = $context->getRequest();

	$item = PaySystem\Manager::searchByRequest($request);

	if ($item !== false)
	{
		
		$service = new PaySystem\Service($item);

		if ($service instanceof PaySystem\Service)
		{
			$result = $service->processRequest($request);
		}
	}
	else
	{
		$debugInfo = implode("\n", $request->toArray());
		PaySystem\Logger::addDebugInfo('Pay system not found. Request: '.$debugInfo);
	}
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>