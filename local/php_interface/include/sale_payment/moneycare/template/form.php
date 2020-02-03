<?

use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
/**@var $params array*/
?>
<link href="/local/php_interface/include/sale_payment/moneycare/template/style.css" type="text/css" rel="stylesheet" />
<div class="money-care-container">
    <a href="<?=$params['url']?>" target="_self"><?=Loc::getMessage('MC_PAY')?></a>
</div>
