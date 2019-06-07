<?
use Caweb\Main\Catalog\Ratio;
use Bitrix\Main\Loader;
Loader::includeModule('caweb.main');
Ratio::getInstance()->measureRatioConfig(Ratio::LINO_SECTION);
$strImportErrorMessage = Ratio::getInstance()->getLog();