<?
use Bitrix\Main\Loader;
Loader::includeModule('caweb.main');
function Pr($z){echo '<pre>'; echo var_dump($z); echo '</pre></hr>';}
AddEventHandler("main", "OnBeforeUserRegister", array('Caweb\Main\Events\Main', 'OnBeforeUserRegister'));
foreach(glob(__DIR__."/events/*") as $file){
	if(is_file($file) && pathinfo($file,PATHINFO_EXTENSION) == "php") include($file);
}

//function bonusAgent() {
	//   require_once $_SERVER['DOCUMENT_ROOT'].'/xml/bonus.php';
	//   bonus();
	//   return 'bonusAgent()';
//}
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
//    addToLog();
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

//updateUsersFromHLBlockAgent();
//function addYandexNewsAgent(){
	/* require_once($_SERVER['DOCUMENT_ROOT'].'/tests/index.php');//Путь до функции добавления
addYandexNews();
    return 'addYandexNewsAgent()';*/
//}