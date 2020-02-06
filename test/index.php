<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\UserTable;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Uri;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\Order;
use Caweb\Main\Catalog\Helper;
use Caweb\Main\Catalog\Ratio;
use Caweb\Main\Log\Write;
use Caweb\Main\Sale\ImportOneCPackageCaweb;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
Loader::includeModule('caweb.main');
?>
<?
Caweb\Main\Agent\MoneyCare::checkStatus();
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>