<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
use Caweb\Main\Sale;
?>

<?
Pr(Sale\DiscountTable::getUserDiscountPrice('тестовое слово'));
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>