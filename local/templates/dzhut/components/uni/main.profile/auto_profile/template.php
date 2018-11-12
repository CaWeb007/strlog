<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>

<div class="bx-auth-profile">

<?ShowError($arResult["strProfileError"]);?>
<?
echo "<pre style='display: none;'>";
print_r($arResult);
echo "</pre>";
if ($arResult['DATA_SAVED'] == 'Y')
	ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>
<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->
var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>
<?
//edebug($arResult)
?>
<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
<input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>">
<div class="profile-block-<?=strpos($arResult["opened"], "reg") === false ? "hidden" : "shown"?>" id="user_div_reg">

<h4 class="profile-header">ПРОФИЛЬ</h4>
<div class="prof-row">

	<div class="prof-col">

		<div class="profile_item">
			<div class="item">
			<input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" placeholder="Имя"/>
			</div>
		</div>
		<div class="profile_item">
			<div class="item">
			<input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" placeholder="Фамилия"/>
			</div>
		</div>
		<div class="profile_item">
			<div class="item">
			<input type="text" name="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"]?>" placeholder="Отчество"/>
			</div>
		</div>
		<div class="profile_item">
			<div class="item">
			<input type="text" name="EMAIL" maxlength="50" value="<?=$arResult["arUser"]["EMAIL"]?>" placeholder="Email"/>
			</div>
		</div>


	</div>

	<div class="prof-col">
		<div class="profile_item">
			<div class="item">
				<input data-tel="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" type="text" name="PERSONAL_PHONE" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" placeholder="Телефон"/>
				<input id="CHECKPHONE" type="hidden" name="CHECKPHONE" value="<?=(($arResult["arUser"]["UF_CONFIRM_PHONE"]) ? "Y" : "N")?>">
<?php
	if ($arResult["arUser"]["UF_CONFIRM_PHONE"]) {
?>
			<span class="confirm_tel">(подтвержден)</span>
			<div class="reg-row tl_code inactive">
				<div id="error_confirm" class="error_confirm">
					<p>Подтвердите номер телефона</p>
				</div>
				<div class="topltip_error_top">
					<div class="close">X</div>
					<p>Пользователь с таким телефоном уже зарегистрирован.</p>
				</div>
				<div class="re-desc-no">
					<p>Неверный код</p>
				</div>
				<input size="30" type="text" name="CODE" value="">
				<div class="desc_confirm_top">
					<p>В течении 1 минуты вам на телефон придет код с подтверждением</p>
				</div>
				<div class="re_desc_confirm_top">
					<p>Если вам не пришло сообщение, повторите отправку</p>
				</div>
				<div class="re_desc_confirm_top_error">
					<p>Неверный код</p>
				</div>
				<button id="checkTelForm1" class="orange-btn top-reg">Подтвердить</button>

				<button id="sendCodeForm1" class="orange-btn top-reg">OK</button>

				<button id="re-confirm-top-form1" class="orange-btn re-confirm">
					<span class="glyphicon glyphicon-refresh"></span>
				</button>

			</div>
<?php
	} else {
?>
			<span class="confirm_tel inactive">(подтвержден)</span>
			<div class="reg-row tl_code ">
				<div id="error_confirm" class="error_confirm">
					<p>Подтвердите номер телефона</p>
				</div>
				<div class="topltip_error_top">
					<div class="close">X</div>
					<p>Пользователь с таким телефоном уже зарегистрирован.</p>
				</div>
				<div class="re-desc-no">
					<p>Неверный код</p>
				</div>
				<input size="30" type="text" name="CODE" value="">
				<div class="desc_confirm_top">
					<p>В течении 1 минуты вам на телефон придет код с подтверждением</p>
				</div>
				<div class="re_desc_confirm_top">
					<p>Если вам не пришло сообщение, повторите отправку</p>
				</div>
				<div class="re_desc_confirm_top_error">
					<p>Неверный код</p>
				</div>
				<button id="checkTelForm1" class="orange-btn top-reg">Подтвердить</button>

				<button id="sendCodeForm1" class="orange-btn top-reg">OK</button>

				<button id="re-confirm-top-form1" class="orange-btn re-confirm">
					<span class="glyphicon glyphicon-refresh"></span>
				</button>

			</div>
<?php } ?>
			</div>
		</div>

		<div class="profile_item double gender-and-birthday">
				<select class="gender-selection" name="PERSONAL_GENDER">
					<option value="" <?if(empty($arResult["arUser"]["PERSONAL_GENDER"])) echo 'selected'?>>(неизвестно)</option>
					<option value="M" <?if($arResult["arUser"]["PERSONAL_GENDER"] == 'M') echo 'selected'?>>Мужской</option>
					<option value="F" <?if($arResult["arUser"]["PERSONAL_GENDER"] == 'F') echo 'selected'?>>Женский</option>
				</select>
<?php
// распарсим поле даты для подстановки значений по умолчанию
$date_str = $arResult["arUser"]["PERSONAL_BIRTHDAY"];
$arr_date = explode(".", $date_str);
?>			<div class="birthday-profile">
					<select name="REGISTER[PERSONAL_DAY]" data-default="<?=$arr_date[0]?>"></select>
					<select name="REGISTER[PERSONAL_MONTH]" data-default="<?=$arr_date[1]?>"></select>
					<select name="REGISTER[PERSONAL_YEAR]" data-default="<?=$arr_date[2]?>"></select>
					<input class="adm-input adm-input-calendar birthday-selection" name="PERSONAL_BIRTHDAY" size="13" value="<?=$arResult["arUser"]["PERSONAL_BIRTHDAY"]?>" type="text" onclick="BX.calendar({node: this, field: this, bTime: false});">
				</div>
		</div>
	<?
		$APPLICATION->IncludeComponent(
			'bitrix:main.calendar',
				'',
				array(
					'SHOW_INPUT' => 'N',
					'FORM_NAME' => 'regform',
					'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
					'SHOW_TIME' => 'N'
				),
				null,
				array("HIDE_ICONS"=>"Y")
			);
	?>

	<?if($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''):?>
		<div class="profile_item">
			<div class="item"><input type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" class="bx-auth-input" placeholder="Новый пароль"/>
		<?//if($arResult["SECURE_AUTH"]):?>
			</div>
		</div>
	<?//endif?>

		<div class="profile_item">
			<div class="item"><input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" placeholder="Подтвердите пароль"/></div>
		</div>
	</div>
	<?endif?>
</div>
<!--DIMAN-->
<!-- <div class="prof-col bonus-program">

	<h4 class="personal-header">Бонусная программа</h4>

	<div class="description">
		<div class="desc-img"></div>
		<div class="desc-text">Получайте бонусы при покупке в магазине или на сайте.</div>
	</div>

	<input name="bonus-check" id="bonus-check" type="checkbox">
	<label class="bonus-check-button" for="bonus-check">Хочу стать участником <a target="_blank" href="http://strlogclub.ru" target="_blank" href="">бонусной программы</a> и накапливать бонусы.</label>

	<p>Соглашаясь на участие в бонусной программе, вы подтверждаете своё согласие на обработку Ваших персональных данных и согласие с <a target="_blank" href="http://strlogclub.ru" target="_blank">условиями участия в Программе</a></p>

</div> -->

	<!--<div class="profile_item">
		<span class="starrequired">*</span>
		<div class="item"><input type="text" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" placeholder="Логин"/></div>
	</div>-->




</div>

	<div class="action">
	<input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>"><!-- <input type="reset" value="<?=GetMessage('MAIN_RESET');?>"> -->
	</div>

</form>

</div>
