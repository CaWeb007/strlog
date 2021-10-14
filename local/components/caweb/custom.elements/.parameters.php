<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Iblock\Component\ElementList;
/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * @global CUserTypeManager $USER_FIELD_MANAGER
 */
Loader::includeModule('iblock');

$CATALOG_SECTION_NAME = 'caweb:catalog.top';
\CBitrixComponent::includeComponentClass($CATALOG_SECTION_NAME);
$pathToSections = getLocalPath("components".\CComponentEngine::MakeComponentPath($CATALOG_SECTION_NAME));
$filterDataValues = array();
$filterDataValues['iblockId'] = 16;
$filterDataValues['iblockTypeId'] = '1c_catalog';
$arComponentParameters['PARAMETERS']['TITLE'] = array(
    'NAME' => GetMessage('CP_BCS_TPL_CUSTOM_NAME'),
    'TYPE' => 'STRING'
);
$arComponentParameters['PARAMETERS']['CUSTOM_FILTER'] = array(
    'NAME' => GetMessage('CP_BCS_TPL_CUSTOM_FILTER'),
    'TYPE' => 'CUSTOM',
    'JS_FILE' => CustomCatalogTopComponent::getSettingsScript($pathToSections, 'filter_conditions'),
    'JS_EVENT' => 'initFilterConditionsControl',
    'JS_MESSAGES' => Json::encode(array(
        'invalid' => GetMessage('CP_BCS_TPL_SETTINGS_INVALID_CONDITION')
    )),
    'JS_DATA' => Json::encode($filterDataValues),
    'DEFAULT' => ''
);
?>