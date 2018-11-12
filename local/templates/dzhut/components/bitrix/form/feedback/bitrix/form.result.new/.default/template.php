<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

<?=$arResult["FORM_NOTE"]?>
<div class="feedback-form">
<?if ($arResult["isFormNote"] != "Y")
{
?>
<?=$arResult["FORM_HEADER"]?>


<br>
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


	<?global $USER;?>
	<?if ($USER->IsAuthorized()):?>
	<input type="hidden" name="web_form_apply" value="Y" />
	<input id="send-button" type="submit" name="web_form_apply" value="<?="Отправить"?>" onclick="yaCounter37983465.reachGoal('TN_FEEDBACK'); return true;"/>
	<div style="clear:both;"></div>
	<div class="checkup-wrapper">
		<label for="checkup" id="checkup-label">
			<div class="checkup-popup-outside">
				<div class="checkup-popup"></div>
			</div>
			<input id="checkup" type="checkbox" />
		</label>
		<span class="checkbox-title">Я согласен на <a href="/agreement/" target="_blank">обработку персональных данных</a></span>
	</div>
	<div style="clear:both;"></div>
	<?else:?>
	<div style="display: block;width: 100%;height: auto;margin: 0 auto;padding: 30px 0;text-align: center;color:#f04e23;">
		Если вы хотите задать вопрос, то авторизиуйтесь на сайте, либо позвоните нам по телефону +7 (3952) 280-900, либо напишите нашему консультанту(справа на экране).
	</div>
	<div style="clear:both;"></div>
	<?endif;?>


<?
} //endif (isFormNote)
?>
</div>

<script>
	$(document).ready(function(){
		$('#checkup-label').click(function(){
			if($('#checkup').is(':checked')){
				$('.checkup-popup-outside').css('width', '20px').css('transition', '0.3s');
				$('#send-button').css('user-select', 'auto').css('pointer-events', 'auto');
			}else{
				$('.checkup-popup-outside').css('width', '0px').css('transition', '0s');
				$('#send-button').css('user-select', 'none').css('pointer-events', 'none');
			}
		});
	});
</script>