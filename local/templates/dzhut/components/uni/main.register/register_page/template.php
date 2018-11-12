<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

<div class="bx-auth-reg" style="margin-top: 40px;">

<?if($USER->IsAuthorized()):?>

<p><? echo GetMessage("MAIN_REGISTER_AUTH")?></p>

<?else:?>
<?
if (count($arResult["ERRORS"]) > 0):
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0)
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

	ShowError(implode("<br />", $arResult["ERRORS"]));

elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
?>
<?endif?>

<div id="register">

<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
<?
if($arResult["BACKURL"] <> ''):
?>
<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
endif;
?>
<div class="reg-gruop-row">
<!--First-->
	<div class="reg-row"><input size="30" type="text" name="REGISTER[LAST_NAME]" value="" placeholder="Фамилия"></div>
	<div class="reg-row"><input size="30" type="text" name="REGISTER[NAME]" value="" placeholder="Имя"></div>
	<div class="reg-row"><input size="30" type="text" name="REGISTER[SECOND_NAME]" value="" placeholder="Отчество"></div>

<!--Second-->
<div class="reg-row"><input size="30" type="text" name="REGISTER[EMAIL]" value="" placeholder="Адрес e-mail">
	<input style="display:none;" size="30" type="text" name="REGISTER[LOGIN]" value=""></div>
<div class="reg-row tip">
<div id="phone-tip" class="topltip_error_top">
	<div class="close">X</div>
	<p>Пользователь с таким телефоном уже зарегистрирован. <a class="orange login" href="">Войти</a></p>
</div>
<input size="30" type="text" name="REGISTER[PERSONAL_PHONE]" value="" placeholder="Телефон">
<input id="CHECKPHONE" type="hidden" name="CHECKPHONE" value="N">
</div>

<div class="reg-row tl_code">
	<div id="error_confirm" class="error_confirm">
		<p>Подтвердите номер телефона</p>
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
	<button id="checkRegPhone" class="orange-btn top-reg">Подтвердить</button>

	<button id="sendCode" class="orange-btn top-reg">OK</button>

	<button id="re-confirm-top" class="orange-btn re-confirm">
		<span class="glyphicon glyphicon-refresh"></span>
	</button>

</div>

<div class="reg-row reg-row--date">
	<select name="REGISTER[PERSONAL_DAY]"></select>
	<select name="REGISTER[PERSONAL_MONTH]"></select>
	<select name="REGISTER[PERSONAL_YEAR]"></select>
	<input size="30" type="text" name="REGISTER[PERSONAL_BIRTHDAY]" value="" placeholder="Дата рождения" onclick="BX.calendar({node:this, field:'REGISTER[PERSONAL_BIRTHDAY]', form: 'regform', bTime: false, currentTime: '1457170319', bHideTime: false});" onmouseover="BX.addClass(this, 'calendar-icon-hover');" onmouseout="BX.removeClass(this, 'calendar-icon-hover');">

	<?$APPLICATION->IncludeComponent(
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
</div>

<div class="reg-row">
<div class="radio-gruop">
<span class="clsug-gender">Пол:</span>
<label>
	<input type="radio" name="REGISTER[PERSONAL_GENDER]" value="M">
		<span class="slug-radio">Мужской</span>
</label>
<label>
	<input type="radio" name="REGISTER[PERSONAL_GENDER]" value="F">
		<span class="slug-radio">Женский</span>
</label>
</div>
</div>
<div class="reg-row"></div>
<!--Third-->
<div class="reg-row"><input size="30" type="password" name="REGISTER[PASSWORD]" value="" autocomplete="off" class="bx-auth-input" placeholder="Пароль"></div>

<div class="reg-row"><input size="30" type="password" name="REGISTER[CONFIRM_PASSWORD]" value="" autocomplete="off" placeholder="Подтвердите пароль"></div>

<div class="reg-row"><input type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" /></div>


<!--<input size="30" type="text" name="REGISTER[LOGIN]" value="" placeholder="Логин">-->

<!--<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>-->
</form>

</div>
<?endif?>
</div>
</div>
