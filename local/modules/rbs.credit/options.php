<?php
	use Bitrix\Main;
	use Bitrix\Main\Loader;
	use Bitrix\Main\Config\Option;
	use Bitrix\Main\Localization\Loc;
	use Bitrix\Sale;

	require __DIR__ . '/config.php';

	$moduleID = $RBS_CONFIG['MODULE_ID'];

	Loader::includeModule('sale');
	Loader::includeModule('currency');
	Loader::includeModule($moduleID);

	$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

	IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
	IncludeModuleLangFile(__FILE__);
?>

<?	
	if ($REQUEST_METHOD == 'POST' && strlen($Update . $Apply) > 0 && check_bitrix_sessid()) {
	    COption::SetOptionString($moduleID, "ISO", serialize($_POST['ISO']));
	    COption::SetOptionString($moduleID, "RESULT_ORDER_STATUS", $_POST['RESULT_ORDER_STATUS']);
	}


	$current_settings = [
		'BANK_NAME' => COption::GetOptionString($moduleID, 'BANK_NAME'),
		'MODULE_ID' => COption::GetOptionString($moduleID, 'MODULE_ID'),
		'RBS_PROD_URL' => COption::GetOptionString($moduleID, 'RBS_PROD_URL'),
		'RBS_TEST_URL' => COption::GetOptionString($moduleID, 'RBS_TEST_URL'),
		'MODULE_VERSION' => COption::GetOptionString($moduleID, 'MODULE_VERSION'),
		'ISO' => unserialize(COption::GetOptionString($moduleID, 'ISO')),
		'RESULT_ORDER_STATUS' => COption::GetOptionString($moduleID, 'RESULT_ORDER_STATUS')
	];

?>


<?
	$tabControl = new CAdminTabControl("tabControl",  array(
		array("DIV" => "edit1", "TAB" => Loc::getMessage('RBS_CREDIT_TAB_NAME'), "ICON" => "blog_settings", "TITLE" => Loc::getMessage('RBS_CREDIT_TAB_TITLE')),
	));
	$tabControl->Begin();
?>


<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&lang=<?=LANGUAGE_ID?>">
	<?= bitrix_sessid_post() ?>
	<? $tabControl->BeginNextTab(); ?>
	

		<!-- MODULE BASE INFO -->

		<tr class="heading">
	        <td colspan="2"><?= Loc::getMessage('RBS_CREDIT_STRING_MODULE_INFO')?>:</td>
	    </tr>
	    <tr>
	        <td width="50%"><?= Loc::getMessage('RBS_CREDIT_STRING_BANK')?></td>
	        <td width="50%"><span><?=$current_settings['BANK_NAME']?></span></td>
	    </tr>
	    <tr>
	        <td width="50%"><?= Loc::getMessage('RBS_CREDIT_STRING_MODULE_VERSION')?>:</td>
	        <td width="50%"><span><?=$current_settings['MODULE_VERSION']?></span></td>
	    </tr>


		<!-- ORDER STATUS -->

		<tr class="heading">
	        <td colspan="2"><?= Loc::getMessage('RBS_CREDIT_STRING_PAYMENT_STATUS')?>:</td>
	    </tr>
		<tr>

	        <td width="100%" colspan="2" style="text-align: center;">
	            <select name="RESULT_ORDER_STATUS">
	                <?
						$statuses = [];
						$dbStatus = CSaleStatus::GetList(Array("SORT" => "ASC"), Array("LID" => LANGUAGE_ID), false, false, Array("ID", "NAME", "SORT"));
						while ($arStatus = $dbStatus->GetNext()) {
						    $statuses[$arStatus["ID"]] = "[" . $arStatus["ID"] . "] " . $arStatus["NAME"];
						}
		                foreach ($statuses as $key => $name) {
		                    ?>
		                    <option value="<?= $key ?>"<?= $key == $current_settings['RESULT_ORDER_STATUS'] ? ' selected' : '' ?>><?= htmlspecialcharsex($name) ?></option><?
		                }
	                ?>
	            </select>
	        </td>
	    </tr>


		<!-- CODE CURRENCY -->

	    <tr class="heading">
	        <td colspan="2"><?= Loc::getMessage('RBS_CREDIT_STRING_CURRENCYES')?></td>
	    </tr>
		<tr>
	        <td width="100%" colspan="2">
	            <table style="margin: 0 auto;">
	                <thead>
		                <th><?= Loc::getMessage('RBS_CREDIT_STRING_CURRENCY')?></th>
		                <th style="padding: 0 15px;"><?= Loc::getMessage('RBS_CREDIT_STRING_CODE')?></th>
		                <th><?= Loc::getMessage('RBS_CREDIT_STRING_ISO')?></th>
	                </thead>
	                <tbody>
		                <? $dbRes = CCurrency::GetList(($by = 'id'), ($order = 'asc'));?>
		                <? while ($arItem = $dbRes->GetNext()) {?>
		                    <tr>
		                        <td><?= $arItem["FULL_NAME"] ?></td>
		                        <td style="padding: 0 15px;"><?= $arItem["CURRENCY"] ?></td>
		                        <td>
		                        	<input style="width: 60px; text-align: center;" name="ISO[<?= $arItem["~CURRENCY"] ?>]" type="text" value="<? echo $current_settings['ISO'][$arItem["~CURRENCY"]] ? $current_settings['ISO'][$arItem["~CURRENCY"]] : $arItem["NUMCODE"] ?>">
		                        </td>
		                    </tr>
		                <? } ?>
	                </tbody>
	            </table>
	        </td>
	    </tr>

	    <!-- TEST SERVER PHP,CURL,TLS -->

	    <? if ($_REQUEST['server_info'] == '1') { ?>
		    <tr class="heading">
		        <td colspan="2"><?= Loc::getMessage('RBS_CREDIT_STRING_SERVER_INFO')?>:</td>
		    </tr>
			<?
					$server_info = [];
					$server_info[] = ["PHP version:", phpversion() ];
				    if (function_exists('curl_version')) {
				        $curl = curl_version();
				        $server_info[] = ["cURL version:", $curl["version"] ];
				        $ch = curl_init('https://www.howsmyssl.com/a/check');
				        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				        $data = curl_exec($ch);
				        curl_close($ch);
				        $json = json_decode($data);
				        $server_info[] = ["TLS version: ", $json->tls_version ];
				    } else {
				    	$server_info[] = ["PHP version:", phpversion() ];
				    	$server_info[] = ["cURL", 'Not installed!!!' ];
				    }
				    $server_info[] = ["OpenSSL version text: ", OPENSSL_VERSION_TEXT ];
				    $server_info[] = ["OpenSSL version number: ", OPENSSL_VERSION_NUMBER ];
				
			?>
			<? foreach ($server_info as $key => $item) { ?>
			    <tr>
			        <td width="50%"><?=$item[0]?></td>
			        <td width="50%"><?=$item[1]?></td>
			    </tr>
			<? } ?>
		<? } ?>


	<? $tabControl->BeginNextTab(); ?>
    <? $tabControl->Buttons(); ?>
		<input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>" title="<?= GetMessage("MAIN_OPT_SAVE_TITLE") ?>" class="adm-btn-save">
		<input type="submit" name="Apply" value="<?= GetMessage("MAIN_OPT_APPLY") ?>" title="<?= GetMessage("MAIN_OPT_APPLY_TITLE") ?>">
		<? if (strlen($_REQUEST["back_url_settings"]) > 0): ?>
	        <input type="button" name="Cancel" value="<?= GetMessage("MAIN_OPT_CANCEL") ?>"
	               title="<?= GetMessage("MAIN_OPT_CANCEL_TITLE") ?>"
	               onclick="window.location='<? echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"])) ?>'">
	        <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($_REQUEST["back_url_settings"]) ?>">
	    <? endif ?>

	    <input type="button" id="check_server_info" value="Check server info">
	    <script>
	    	 BX.ready(function () {
	            var oButtonCheck = document.getElementById('check_server_info');
	            if (oButtonCheck) {
	                oButtonCheck.onclick = function () {
	                	window.location = '<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&lang=<?=LANGUAGE_ID?><?echo "&server_info=1"?>';
	                    return false;
	                }
	            }
	        });
	    </script>
    <? $tabControl->End(); ?>
</form>