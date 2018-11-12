<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

<div class="bx-auth-reg" >

<?if($USER->IsAuthorized()):?>

<?//echo GetMessage("MAIN_REGISTER_AUTH")?></p>

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
<div class="reg-row"><input size="30" type="text" name="REGISTER[NAME]" value="" placeholder="Имя"></div>
<div class="reg-row"><input size="30" type="text" name="REGISTER[LAST_NAME]" value="" placeholder="Фамилия"></div>
<div class="reg-row"><input size="30" type="text" name="REGISTER[SECOND_NAME]" value="" placeholder="Отчество"></div>
<div class="reg-row"><input size="30" type="text" name="REGISTER[EMAIL]" value="" placeholder="Адрес e-mail">
<input style="display:none;" size="30" type="text" name="REGISTER[LOGIN]" value="">
</div>

<!--Second-->
<div class="reg-row"><input size="30" type="text" name="REGISTER[PERSONAL_PHONE]" value="" placeholder="Телефон"></div>
<div class="reg-row reg-row--date">
<select name="REGISTER[PERSONAL_DAY]"></select>
<select name="REGISTER[PERSONAL_MONTH]"></select>
<select name="REGISTER[PERSONAL_YEAR]"></select>
<input size="30" type="text" name="REGISTER[PERSONAL_BIRTHDAY]" value="" placeholder="Дата рождения" onclick="BX.calendar({node:this, field:'REGISTER[PERSONAL_BIRTHDAY]', form: 'regform', bTime: false, currentTime: '1457170319', bHideTime: false});" onmouseover="BX.addClass(this, 'calendar-icon-hover');" onmouseout="BX.removeClass(this, 'calendar-icon-hover');">

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
