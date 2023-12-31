<?
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Caweb\Main\Sale\Bonus;

Loader::includeModule('caweb.main');
Loader::includeModule('sale');
function Pr($z){echo '<pre>'; echo var_dump($z); echo '</pre><hr/>';}
foreach(glob(__DIR__."/events/*") as $file){
    if(is_file($file) && pathinfo($file,PATHINFO_EXTENSION) == "php") include($file);
}
AddEventHandler("main", "OnBeforeUserRegister", array('Caweb\Main\Events\Main', 'OnBeforeUserRegister'));
AddEventHandler("main", "OnBeforeUserLogin", array('Caweb\Main\Events\Main', 'OnBeforeUserLogin'));
EventManager::getInstance()->addEventHandler('sale', 'OnBeforeShipmentDeleted',array('Caweb\Main\Events\Sale', 'OnBeforeShipmentDeleted'));
EventManager::getInstance()->addEventHandler('sale', '\Bitrix\Sale\Internals\Shipment::OnBeforeDelete',array('Caweb\Main\Events\Sale', 'OnBeforeShipmentDelete'));
EventManager::getInstance()->addEventHandler('sale', '\Bitrix\Sale\Internals\Payment::OnBeforeDelete',array('Caweb\Main\Events\Sale', 'OnBeforePaymentDelete'));
EventManager::getInstance()->addEventHandlerCompatible('sale', 'OnBeforeUserAccountAdd',array('Caweb\Main\Events\Sale', 'CheckDoExchange'));
EventManager::getInstance()->addEventHandlerCompatible('sale', 'OnBeforeUserAccountUpdate',array('Caweb\Main\Events\Sale', 'CheckDoExchange'));
EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved',array('Caweb\Main\Events\Sale', 'priceFromPaySystemOrderEntity'));
EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved',array('Caweb\Main\Events\Sale', 'OnSaleOrderBeforeSaved'));
//EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderSaved', array('Caweb\Main\Sale\DiscountManager', 'OnSaleOrderSaved'));
//ventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved', array('Caweb\Main\Sale\DiscountManager', 'OnSaleOrderBeforeSaved'));
EventManager::getInstance()->addEventHandler('catalog', 'Bitrix\Catalog\Model\Product::OnBeforeUpdate',array('Caweb\Main\Events\Catalog', 'OnBeforeProductUpdate'));
EventManager::getInstance()->addEventHandler('sale', 'OnCondSaleActionsControlBuildList', array('Caweb\Main\Sale\SaleActionGiftCtrl', 'GetControlDescr'));
//EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'SortSku'));
//EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementUpdate', array('Caweb\Main\Events\Iblock', 'SortSku'));
/**start установка коэф для лино в рулонах*/
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'setLinoMeasure'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'setLinoMeasure'));
/**end*/
/**start не добавлять и не удалять элемент в экосистему*/
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'ecosystemFoolChecker'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementDelete', array('Caweb\Main\Events\Iblock', 'ecosystemFoolChecker2'));
/**end*/
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockSectionUpdate', array('Caweb\Main\Events\Iblock', 'doNotDeactivate'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockUpdate', array('\Caweb\Main\Events\Iblock', 'doNotUseFacet'));
EventManager::getInstance()->addEventHandlerCompatible('main', 'OnAfterSetUserGroup', array('Caweb\Main\Events\Main', 'OnAfterSetUserGroup'));
EventManager::getInstance()->addEventHandler('iblock', '\Bitrix\Iblock\Iblock::OnBeforeUpdate', array('\Caweb\Main\Events\Iblock', 'doNotUseFacetD7'));
EventManager::getInstance()->addEventHandlerCompatible('main', 'OnAfterUserAuthorize', array('Caweb\Main\Sale\Bonus', 'updateSessionsData'));
EventManager::getInstance()->addEventHandlerCompatible('main', 'OnAfterUserUpdate', array('Caweb\Main\Sale\Bonus', 'updateSessionsData'));
EventManager::getInstance()->addEventHandlerCompatible('main', 'OnBeforeEventAdd', array('Caweb\Main\Events\Main', 'OnBeforeEventAdd'));
/**контроль остатков линолеума*/
//region StoreActiveController
$callback = array('Caweb\Main\Events\Catalog', 'storeActiveController');
EventManager::getInstance()->addEventHandlerCompatible('catalog', 'OnBeforeCatalogStoreAdd', $callback);
EventManager::getInstance()->addEventHandlerCompatible('catalog', 'OnBeforeCatalogStoreUpdate', $callback);
//endregion
EventManager::getInstance()->addEventHandler('sale', 'OnSaleBasketBeforeSaved', array('Caweb\Main\Events\Sale', 'linoMinBalanceController'));

EventManager::getInstance()->addEventHandlerCompatible('forum', 'onBeforeMessageAdd', array('Caweb\Main\Events\Forum', 'checkSpam'));
EventManager::getInstance()->addEventHandlerCompatible('forum', 'onBeforeMessageUpdate', array('Caweb\Main\Events\Forum', 'checkSpam'));

EventManager::getInstance()->addEventHandlerCompatible('forum', 'onAfterMessageAdd', array('Caweb\Main\Events\Forum', 'sendMessage'));

EventManager::getInstance()->addEventHandlerCompatible('sale', 'OnCondSaleControlBuildList', array('\Caweb\Main\Sale\Action\CSaleActionCtrlFirstOrder', 'GetControlDescr'));
EventManager::getInstance()->addEventHandlerCompatible('sale', 'OnCondSaleControlBuildList', array('\Caweb\Main\Sale\Action\CSaleActionCtrlOneUse', 'GetControlDescr'));
EventManager::getInstance()->addEventHandlerCompatible('sale', 'OnCondSaleActionsControlBuildList', array('\Caweb\Main\Sale\Action\CSaleActionDiscountFromPrice', 'GetControlDescr'));

EventManager::getInstance()->addEventHandler('sale', '\Bitrix\Sale\Internals\Discount::OnBeforeUpdate',array('\Caweb\Main\Sale\Action\Tools', 'DiscountCondController'));

EventManager::getInstance()->addEventHandler('', 'KontragentyOnAfterAdd',array('Caweb\Main\Events\HL', 'OnAfterAddUpdate'));
EventManager::getInstance()->addEventHandler('', 'KontragentyOnAfterUpdate',array('Caweb\Main\Events\HL', 'OnAfterAddUpdate'));
EventManager::getInstance()->addEventHandler('', 'KontragentyOnBeforeAdd',array('Caweb\Main\Events\HL', 'OnBeforeAdd'));

/**добавим действие группового копирования ID в инфоблоке файлов start*/
EventManager::getInstance()->addEventHandlerCompatible("main", "OnAdminListDisplay", array('Caweb\Main\Events\Main',"addContextButtonInFiles"));
EventManager::getInstance()->addEventHandlerCompatible("main", "OnAfterEpilog", array('Caweb\Main\Events\Main',"contextButtonInFilesHandler"));
/**добавим действие в контекстное меню end*/
/**start работа с инфоблоком файлов*/
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'filesIblockAction'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementUpdate', array('Caweb\Main\Events\Iblock', 'filesIblockAction'));
/**end*/
/**start работа инфоблоков с ord*/
EventManager::getInstance()->addEventHandlerCompatible("main", "OnAdminListDisplay", array('Caweb\Main\Events\Main',"addContextButtonForOrd"));
EventManager::getInstance()->addEventHandlerCompatible("main", "OnBeforeProlog", array('Caweb\Main\Events\Main',"adminOrdActionHandler"));

EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'ordRelatedElements'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementUpdate', array('Caweb\Main\Events\Iblock', 'ordRelatedElements'));
/**end*/

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
?>