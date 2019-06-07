<?

use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper;
use Caweb\Main\Catalog\Ratio;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

<?
//Ratio::getInstance()->measureRatioConfig(Ratio::LINO_SECTION);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>