<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<h4 class="slug-call-back">Заказать обратный звонок</h4>
<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
?>

<?=$arResult["FORM_HEADER"]?>



<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
	?>
		<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
		<?endif;?>
		<?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?>
		<?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
		<?=$arQuestion["HTML_CODE"]?>
		
	<?
	} //endwhile
	?>
	<?
	 $arResult["arForm"]["BUTTON"] = "Отправить";
	?>

	<input id="agreePersonalSend" onclick="yaCounter37983465.reachGoal('goal_webform_success_6');_gaq.push(['_trackEvent', 'zvonok', 'ok']); return true;" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />

	<?if ($arResult["F_RIGHT"] >= 15):?>
		<input type="hidden" name="web_form_apply" value="Y" />
		
	<?endif;?>

<!--agreePersonalButton-->
<label id="labelAgreePersonalButton" for="agreePersonalCheckbox">
	<input id="agreePersonalCheckbox" type="checkbox" value="" checked />
	<span id="agreePersonalText">Я согласен на <a href="/agreement/" target="_blank">обработку персональных данных</a></span>
</label>
<!--/agreePersonalButton-->

<!--<p>
<?//=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?>
</p>-->
<div class="mess-test">
<?=$arResult["FORM_FOOTER"]?>
</div>
<button id="closeCB" class="close-call-back">Скрыть</button>
<?
} //endif (isFormNote)
?>