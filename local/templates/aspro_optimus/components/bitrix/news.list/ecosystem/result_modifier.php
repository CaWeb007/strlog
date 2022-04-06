<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$folder = $this->GetFolder();
\Bitrix\Main\Page\Asset::getInstance()->addJs($folder.'/js/position.min.js');