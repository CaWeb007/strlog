<?

use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Caweb\Main\Catalog\Helper;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

<?
\Caweb\Main\Events\Main::getInstance()->updateUserGroups();
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>