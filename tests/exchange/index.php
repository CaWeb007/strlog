<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
?><?/*$APPLICATION->IncludeComponent(
	"bitrix:sale.export.1c",
	"",
	Array(
		"1C_SITE_NEW_ORDERS" => "",
		"CHANGE_STATUS_FROM_1C" => "Y",
		"EXPORT_ALLOW_DELIVERY_ORDERS" => "N",
		"EXPORT_FINAL_ORDERS" => "N",
		"EXPORT_PAYED_ORDERS" => "N",
		"FILE_SIZE_LIMIT" => "204800",
		"GROUP_PERMISSIONS" => array("1"),
		"IMPORT_NEW_ORDERS" => "N",
		"INTERVAL" => "0",
		"REPLACE_CURRENCY" => "руб.",
		"SITE_LIST" => "",
		"USE_ZIP" => "Y"
	)
);*/?>
<?
$_REQUEST['type'] = 'sale';
$ABS_FILE_NAME = $_SERVER['DOCUMENT_ROOT']."/tests/exchange/doc.xml";
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/local/components/bitrix/sale.export.1c/component.php');
if(!(CModule::IncludeModule('sale') && CModule::IncludeModule('catalog')))
{
    echo "failure\n".GetMessage("CC_BSC1_ERROR_MODULE");
    return;
}
if(file_exists($ABS_FILE_NAME) && filesize($ABS_FILE_NAME)>0)
{
    \Bitrix\Sale\Exchange\ManagerImport::deleteLoggingDate();


    $arParams = array(

    );
    $arParams["GROUP_PERMISSIONS"] = array(1);
    $arParams["SITE_LIST"] = "";
    $arParams["USE_ZIP"] = "N";
    $arParams["EXPORT_PAYED_ORDERS"] = false;
    $arParams["EXPORT_ALLOW_DELIVERY_ORDERS"] = false;
    $arParams["CHANGE_STATUS_FROM_1C"] = true;
    $arParams["EXPORT_FINAL_ORDERS"] = "N";
    $arParams["REPLACE_CURRENCY"] = 'RUB';
    $arParams["USE_TEMP_DIR"] = true;
    $arParams["INTERVAL"] = 30;
    $arParams["FILE_SIZE_LIMIT"] = 200*1024; //200KB
    @set_time_limit(0);
    $position = false;
    $startTime = time();
    $loader = new CSaleOrderLoader;
    $loader->arParams = $arParams;
    $loader->bNewVersion = true;
    $loader->crmCompatibleMode = false;
    $startTime = time();
    $o = new CXMLFileStream;
    $o->registerElementHandler("/".GetMessage("CC_BSC1_COM_INFO"), array($loader, "elementHandler"));


    $o->registerNodeHandler("/".GetMessage("CC_BSC1_COM_INFO")."/".GetMessage("CC_BSC1_CONTAINER"), function (CDataXML $xmlObject) use ($o, $loader)
    {
        \Caweb\Main\Sale\ImportOneCPackageCaweb::configuration();
        $loader->importer = \Caweb\Main\Sale\ImportOneCPackageCaweb::getInstance();
        $loader->nodeHandler($xmlObject, $o);
//        \Bitrix\Sale\Exchange\ImportOneCPackageSale::configuration();
//        $loader->importer = \Bitrix\Sale\Exchange\ImportOneCPackageSale::getInstance();
//        $loader->nodeHandler($xmlObject, $o);
    });
    $o->setPosition($_SESSION["BX_CML2_EXPORT"]["last_xml_entry"]);
    if ($o->openFile($ABS_FILE_NAME))
    {
        while($o->findNext())
        {
            if($arParams["INTERVAL"] > 0)
            {
                $_SESSION["BX_CML2_EXPORT"]["last_xml_entry"] = $o->getPosition();
                if(time()-$startTime > $arParams["INTERVAL"])
                {
                    break;
                }
            }
        }
    }

    if(!$o->endOfFile())
        echo "progress";
    else
    {
        $_SESSION["BX_CML2_EXPORT"]["last_xml_entry"] = "";
        echo "success";
    }
    if(strlen($loader->strError)>0)
        echo $loader->strError;
    echo "\n";
}
else
{
    echo "failure\n".GetMessage("CC_BSC1_EMPTY_CML");
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>