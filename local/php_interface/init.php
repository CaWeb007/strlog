<?
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
Loader::includeModule('caweb.main');
Loader::includeModule('sale');
function Pr($z){echo '<pre>'; echo var_dump($z); echo '</pre><hr/>';}
AddEventHandler("main", "OnBeforeUserRegister", array('Caweb\Main\Events\Main', 'OnBeforeUserRegister'));
//EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved',array('Caweb\Main\Events\Sale', 'OnSaleOrderBeforeSaved'));
EventManager::getInstance()->addEventHandler('sale', 'OnBeforeShipmentDeleted',array('Caweb\Main\Events\Sale', 'OnBeforeShipmentDeleted'));
EventManager::getInstance()->addEventHandler('sale', '\Bitrix\Sale\Internals\Shipment::OnBeforeDelete',array('Caweb\Main\Events\Sale', 'OnBeforeShipmentDelete'));
EventManager::getInstance()->addEventHandler('sale', '\Bitrix\Sale\Internals\Payment::OnBeforeDelete',array('Caweb\Main\Events\Sale', 'OnBeforePaymentDelete'));
EventManager::getInstance()->addEventHandler('catalog', 'Bitrix\Catalog\Model\Product::OnBeforeUpdate',array('Caweb\Main\Events\Catalog', 'OnBeforeProductUpdate'));
//EventManager::getInstance()->addEventHandler('sale', '\Bitrix\Sale\Internals\Payment::OnBeforeUpdate',array('Caweb\Main\Events\Sale', 'OnBeforePaymentUpdate'));
EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderSaved', array('Caweb\Main\Sale\DiscountManager', 'OnSaleOrderSaved'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'SortSku'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementUpdate', array('Caweb\Main\Events\Iblock', 'SortSku'));
EventManager::getInstance()->addEventHandlerCompatible('main', 'OnAfterSetUserGroup', array('Caweb\Main\Events\Main', 'OnAfterSetUserGroup'));
foreach(glob(__DIR__."/events/*") as $file){
	if(is_file($file) && pathinfo($file,PATHINFO_EXTENSION) == "php") include($file);
}
function CheckBasket(){
    if(CModule::IncludeModule("sale")){
        $dbBasketItems = CSaleBasket::GetList(
            array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
            false,
            false,
            array()
        );
        GLOBAL $arrProductId;
        $arrProductId = array();
        foreach($dbBasketItems->arResult as $productID){
            $arrProductId[] = $productID['PRODUCT_ID'];
        }
    }
    return $arrProductId;
}
function updateUsersFromHLBlockAgent(){
	$added = false;
    require '/var/www/u0505962/data/www/xn--80afpacjdwcqkhfi.xn--p1ai/user_update/user.php';
	if($added){
		define("LOG_FILENAME", "/var/www/u0505962/data/www/xn--80afpacjdwcqkhfi.xn--p1ai/user_update/log.txt");
		require("/var/www/u0505962/data/www/xn--80afpacjdwcqkhfi.xn--p1ai/bitrix/modules/main/include/prolog_before.php");
		AddMessage2Log("Пользователи обновлениы и добавлены из HighLoad блока");
	}
	return 'updateUsersFromHLBlockAgent();';
}
AddEventHandler("main", "OnEpilog", "error_page");
function error_page()
{
    $page_404 = "/404.php";
    GLOBAL $APPLICATION;
    if(strpos($APPLICATION->GetCurPage(), $page_404) === false && defined("ERROR_404") && ERROR_404 == "Y")
    {
        $APPLICATION->RestartBuffer();
        CHTTP::SetStatus("404 Not Found");
        include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php");
        include($_SERVER["DOCUMENT_ROOT"].$page_404);
        include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/footer.php");
        die();
    }
}