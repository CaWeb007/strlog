<?
/**@var $arCurrentValues array*/
/**@var $arComponentParameters array*/

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
Loader::includeModule('iblock');

$CATALOG_SECTION_NAME = 'caweb:catalog.top';
\CBitrixComponent::includeComponentClass($CATALOG_SECTION_NAME);
$filterDataValues = array();
$pathToSections = getLocalPath("components".\CComponentEngine::MakeComponentPath($CATALOG_SECTION_NAME));
$filterDataValues['iblockId'] = 16;
$filterDataValues['iblockTypeId'] = '1c_catalog';


/*$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>16, "ACTIVE"=>"Y"));
while ($arr=$rsProp->Fetch()){
    if($arr["PROPERTY_TYPE"] != "F")
        $arProperty[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
        $arPropertyName[$arr["ID"]] = $arr["NAME"];
}*/
$arComponentParameters = array(
    "GROUPS" => array(
        "TITLE" => array(
            'NAME' => GetMessage('TABS_TITLE'),
            'SORT' => 1
        )
    ),
	"PARAMETERS" => array(
		"TABS_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage('TABS_COUNT'),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9),
            "SIZE" => 1,
            'REFRESH' => 'Y',
            "DEFAULT" => 1
		),
        "LINE_1" => array(
            "NAME" => GetMessage('TABS_TITLE_LINE_1'),
            "TYPE" => 'STRING',
            "PARENT" => "TITLE"
        ),
        "LINE_2" => array(
            "NAME" => GetMessage('TABS_TITLE_LINE_2'),
            "TYPE" => 'STRING',
            "PARENT" => "TITLE"
        )
	),
);
if (empty($arCurrentValues['TABS_COUNT'])) $arCurrentValues['TABS_COUNT'] = 1;
else $arCurrentValues['TABS_COUNT'] = (int)$arCurrentValues['TABS_COUNT'];
for ($i = 1; $i <= $arCurrentValues['TABS_COUNT']; $i++) {
    $arComponentParameters['GROUPS']['TAB_'.$i] = array(
        'NAME' => GetMessage("TAB").$i
    );
    $arComponentParameters['PARAMETERS']['TAB_TITLE'.$i] = array(
        "PARENT" => 'TAB_'.$i,
        'NAME' => GetMessage("TITLE"),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
        'COLS' => 10
    );
    $arComponentParameters['PARAMETERS']['TAB_FILTER'.$i] = array(
        'PARENT' => 'TAB_'.$i,
        'NAME' => GetMessage("FILTER"),
        'TYPE' => 'CUSTOM',
        'JS_FILE' => CustomCatalogTopComponent::getSettingsScript($pathToSections, 'filter_conditions'),
        'JS_EVENT' => 'initFilterConditionsControl',
        'JS_MESSAGES' => Json::encode(array(
            'invalid' => 'Условие задано не верно'
        )),
        'JS_DATA' => Json::encode($filterDataValues),
        'DEFAULT' => ''
    );
}