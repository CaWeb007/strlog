<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
//$_GET['mode'] = 'checkauth';

$_GET['type'] = 'catalog';
$_GET['mode'] = 'import';
$_GET['filename'] = 'import___7bad8a38-830b-4d04-853d-97000d3631ff.xml';
$_SESSION["BX_CML2_IMPORT"]["zip"] = null;
$_SESSION["BX_CML2_IMPORT"]["TEMP_DIR"] = '/home/bitrix/www/tests/import/1/';
//if($_SESSION["BX_CML2_IMPORT"]["NS"] == 6) $_SESSION["BX_CML2_IMPORT"]["NS"] = 7;
//$_GET['mode'] = 'init';

$APPLICATION->IncludeComponent("bitrix:catalog.import.1c", "", Array(
        "IBLOCK_TYPE" => COption::GetOptionString("catalog", "1C_IBLOCK_TYPE", "-"),
        "SITE_LIST" => array(COption::GetOptionString("catalog", "1C_SITE_LIST", "-")),
        "INTERVAL" => COption::GetOptionString("catalog", "1C_INTERVAL", "-"),
        "GROUP_PERMISSIONS" => explode(",", COption::GetOptionString("catalog", "1C_GROUP_PERMISSIONS", "1")),
        "GENERATE_PREVIEW" => COption::GetOptionString("catalog", "1C_GENERATE_PREVIEW", "Y"),
        "PREVIEW_WIDTH" => COption::GetOptionString("catalog", "1C_PREVIEW_WIDTH", "100"),
        "PREVIEW_HEIGHT" => COption::GetOptionString("catalog", "1C_PREVIEW_HEIGHT", "100"),
        "DETAIL_RESIZE" => COption::GetOptionString("catalog", "1C_DETAIL_RESIZE", "Y"),
        "DETAIL_WIDTH" => COption::GetOptionString("catalog", "1C_DETAIL_WIDTH", "300"),
        "DETAIL_HEIGHT" => COption::GetOptionString("catalog", "1C_DETAIL_HEIGHT", "300"),
        "ELEMENT_ACTION" => COption::GetOptionString("catalog", "1C_ELEMENT_ACTION", "D"),
        "SECTION_ACTION" => COption::GetOptionString("catalog", "1C_SECTION_ACTION", "D"),
        "FILE_SIZE_LIMIT" => COption::GetOptionString("catalog", "1C_FILE_SIZE_LIMIT", 200*1024),
        "USE_CRC" => COption::GetOptionString("catalog", "1C_USE_CRC", "Y"),
        "USE_ZIP" => 'N',
        "USE_OFFERS" => COption::GetOptionString("catalog", "1C_USE_OFFERS", "N"),
        "FORCE_OFFERS" => COption::GetOptionString("catalog", "1C_FORCE_OFFERS", "N"),
        "USE_IBLOCK_TYPE_ID" => COption::GetOptionString("catalog", "1C_USE_IBLOCK_TYPE_ID", "N"),
        "USE_IBLOCK_PICTURE_SETTINGS" => COption::GetOptionString("catalog", "1C_USE_IBLOCK_PICTURE_SETTINGS", "N"),
        "TRANSLIT_ON_ADD" => COption::GetOptionString("catalog", "1C_TRANSLIT_ON_ADD", "Y"),
        "TRANSLIT_ON_UPDATE" => COption::GetOptionString("catalog", "1C_TRANSLIT_ON_UPDATE", "Y"),
        "TRANSLIT_REPLACE_CHAR" => COption::GetOptionString("catalog", "1C_TRANSLIT_REPLACE_CHAR", "_"),
        "SKIP_ROOT_SECTION" => COption::GetOptionString("catalog", "1C_SKIP_ROOT_SECTION", "N"),
        "DISABLE_CHANGE_PRICE_NAME" => COption::GetOptionString("catalog", "1C_DISABLE_CHANGE_PRICE_NAME"),
        'SKIP_SOURCE_CHECK' => 'Y',
        'USE_TEMP_DIR' => 'Y'
    )
);
 require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>