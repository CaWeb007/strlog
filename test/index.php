<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper;
use Caweb\Main\Catalog\Ratio;
use Caweb\Main\Log\Write;
use Caweb\Main\Sale\ImportOneCPackageCaweb;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

<?
/*CCatalogProduct::Update(20211, array(
    "QUANTITY" => 3,
    'WEIGHT' => 30
));*/


/*$ABS_FILE_NAME = $_SERVER['DOCUMENT_ROOT']."/upload/1c_exchange/documents___aca7da11-869c-4406-a107-582ef24c255c.xml";
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
    $loader->crmCompatibleMode = $bExportFromCrm;
    $startTime = time();
    $o = new CXMLFileStream;
    $o->registerElementHandler("/".GetMessage("CC_BSC1_COM_INFO"), array($loader, "elementHandler"));

    $o->registerNodeHandler("/".GetMessage("CC_BSC1_COM_INFO")."/".GetMessage("CC_BSC1_DOCUMENT"), function (CDataXML $xmlObject) use ($o, $loader)
    {
        $loader->nodeHandler($xmlObject, $o);
    });

    $o->registerNodeHandler("/".GetMessage("CC_BSC1_COM_INFO")."/".GetMessage("CC_BSC1_AGENTS")."/".GetMessage("CC_BSC1_AGENT"), function (CDataXML $xmlObject) use ($o, $loader)
    {
        \Bitrix\Sale\Exchange\ImportOneCContragent::configuration();
        $loader->importerContragent = new \Bitrix\Sale\Exchange\ImportOneCContragent();
        $loader->nodeHandler($xmlObject, $o);

    });
    $o->registerNodeHandler(
        "/".GetMessage("CC_BSC1_COM_INFO")."/".GetMessage("CC_BSC1_CONTAINER"),
        function (CDataXML $xmlObject) use ($o, $loader){
            ImportOneCPackageCaweb::configuration();
            $loader->importer = ImportOneCPackageCaweb::getInstance();
            $loader->nodeHandler($xmlObject, $o);
        }
    );
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
}*/
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>