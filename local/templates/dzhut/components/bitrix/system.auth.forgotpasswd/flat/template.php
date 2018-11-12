<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

//one css for all system.auth.* forms
// $APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>

<div class="bx-authform">

<?
if(!empty($arParams["~AUTH_RESULT"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

	<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">

		<div class="columns">

			<div class="column">

				<div class="bx-authform-formgroup-container">
					<div class="bx-authform-input-container">
						<input class="input-field" type="text" name="USER_LOGIN" placeholder="<?echo GetMessage("AUTH_LOGIN_EMAIL")?>" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
						<input type="hidden" name="USER_EMAIL" />
					</div>
				</div>
			</div>

			<div class="column">
				<div class="bx-authform-formgroup-container">
					<input class="reg-submit" type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
				</div>
			</div>

			<div class="column">
				<div class="bx-authform-link-container">
					<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=GetMessage("AUTH_AUTH")?></a>
				</div>
			</div>

		</div>

	</form>

</div>

<script type="text/javascript">
document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
document.bform.USER_LOGIN.focus();
</script>
