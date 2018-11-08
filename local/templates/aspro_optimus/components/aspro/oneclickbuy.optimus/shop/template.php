<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<div class="popup-intro">
		<div class="pop-up-title"><?=GetMessage('FORM_HEADER_CAPTION')?></div>
	</div>
	<a class="jqmClose close"><i></i></a>
	<div class="form-wr">
		<form method="post" id="one_click_buy_form" enctype="multipart/form-data" action="<?=$arResult['SCRIPT_PATH']?>/script.php">
			<?foreach($arParams['PROPERTIES'] as $field):
				$class.="inputtext";?>
				<div class="form-control bg">
					<label class="description">
						<?if($field == "COMMENT"):?>
							<?=GetMessage('CAPTION_'.$field)?>
						<?else:?>
							<?=$arResult["PROPS"][$field]["TITLE"];?>
						<?endif;?>
						<?if (in_array($field, $arParams['REQUIRED'])):?><span class="star">*</span><?endif;?>
					</label>
					<?if($field=="PHONE"){
						$class.=" phone";
					}?>
					<?if($field=="COMMENT"):?>
						<textarea name="ONE_CLICK_BUY[<?=$field?>]" id="one_click_buy_id_<?=$field?>" class="<?=$class;?>"></textarea>
					<?else:?>
						<?if($arResult["PROPS"][$field]["TYPE"] == "FILE"):?>
							<div class="files" data-code="<?=$field?>" data-required="<?=(in_array($field, $arParams['REQUIRED']) ? 'Y' : 'N');?>">
								<input type="file" <?if (in_array($field, $arParams['REQUIRED'])):?>required<?endif;?> name="ONE_CLICK_BUY[<?=$field?>][]">
							</div>
							<?if($arResult["PROPS"][$field]["MULTIPLE"] == "Y"):?>
								<div class="btn_block_file"><span class="button small"><?=GetMessage("ADD_BTN");?></span></div>
							<?endif;?>
						<?else:?>
							<input type="<?=($field=="EMAIL" ? "email" : ($field=="PHONE" ? "tel" : "text"));?>" name="ONE_CLICK_BUY[<?=$field?>]" value="<?=$value?>" class="<?=$class;?>" id="one_click_buy_id_<?=$field?>" />
						<?endif;?>
					<?endif;?>
				</div>
			<?endforeach;?>
			<?if(\Bitrix\Main\Config\Option::get('aspro.optimus', 'ONE_CLICK_BUY_CAPTCHA', 'N') == 'Y'):?>
				<div class="form-control captcha-row clearfix">
					<label><span><?=GetMessage("CAPTCHA_LABEL");?><span class="star">*</span></span></label>
					<div class="captcha_image">
						<?$code = htmlspecialcharsbx($APPLICATION->CaptchaGetCode())?>
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$code;?>" border="0">
						<input type="hidden" name="captcha_sid" value="<?=$code;?>">
						<div class="captcha_reload"></div>
					</div>
					<div class="captcha_input">
						<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required="" aria-required="true">
					</div>
				</div>
			<?endif;?>
			<?if(COption::GetOptionString("aspro.optimus", "SHOW_LICENCE", "N") == "Y"):?>
				<div class="form">
					<div class="licence_block filter label_block">
						<input type="checkbox" id="licenses_popup_OCB" <?=(COption::GetOptionString("aspro.optimus", "LICENCE_CHECKED", "N") == "Y" ? "checked" : "");?> name="licenses_popup_OCB" required value="Y">
						<label for="licenses_popup_OCB" class="license">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
						</label>
					</div>
				</div>
			<?endif;?>
			<div class="but-r clearfix">
				<!--noindex-->
					<?if($arParams['SHOW_DELIVERY_NOTE'] === 'Y'):?>
						<div class="delivery_note">
							<a  href="javascript:;" class="title"><?=GetMessage('DELIVERY_NOTE_TITLE')?></a>
							<div class="text hidden"><?$APPLICATION->IncludeFile(SITE_DIR."include/oneclick_delivery_text.php", Array(), Array("MODE" => "html"));?></div>
						</div>
					<?endif;?>
					<button class="button short" type="submit" id="one_click_buy_form_button" name="one_click_buy_form_button" value="<?=GetMessage('ORDER_BUTTON_CAPTION')?>"><span><?=GetMessage("ORDER_BUTTON_CAPTION")?></span></button>
				<!--/noindex-->
			</div>
			<?if(strlen($arParams['OFFER_PROPERTIES'])):?>
				<input type="hidden" name="OFFER_PROPERTIES" value="<?=$arParams['OFFER_PROPERTIES']?>" />
			<?endif;?>
			<?if(intVal($arParams['IBLOCK_ID'])):?>
				<input type="hidden" name="IBLOCK_ID" value="<?=intVal($arParams['IBLOCK_ID']);?>" />
			<?endif;?>
			<?if(intVal($arParams['ELEMENT_ID'])):?>
				<input type="hidden" name="ELEMENT_ID" value="<?=intVal($arParams['ELEMENT_ID']);?>" />
			<?endif;?>
			<?if((float)($arParams['ELEMENT_QUANTITY'])):?>
				<input type="hidden" name="ELEMENT_QUANTITY" value="<?=(float)($arParams['ELEMENT_QUANTITY']);?>" />
			<?endif;?>
			<?if($arParams['BUY_ALL_BASKET']=="Y"):?>
				<input type="hidden" name="BUY_TYPE" value="ALL" />
			<?endif;?>
			<input type="hidden" name="CURRENCY" value="<?=$arParams['DEFAULT_CURRENCY']?>" />
			<input type="hidden" name="SITE_ID" value="<?=SITE_ID;?>" />
			<input type="hidden" name="PROPERTIES" value='<?=serialize($arParams['PROPERTIES'])?>' />
			<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arParams['DEFAULT_PAYMENT']?>" />
			<input type="hidden" name="DELIVERY_ID" value="<?=$arParams['DEFAULT_DELIVERY']?>" />
			<input type="hidden" name="PERSON_TYPE_ID" value="<?=$arParams['DEFAULT_PERSON_TYPE']?>" />
			<?=bitrix_sessid_post()?>
		</form>
		<div class="one_click_buy_result" id="one_click_buy_result">
			<div class="one_click_buy_result_success"><?=GetMessage('ORDER_SUCCESS')?></div>
			<div class="one_click_buy_result_fail"><?=GetMessage('ORDER_ERROR')?></div>
			<div class="one_click_buy_result_text"><?=GetMessage('ORDER_SUCCESS_TEXT')?></div>
		</div>
	</div>
<script type="text/javascript">
$('#one_click_buy_form').validate({
	rules: {
	"ONE_CLICK_BUY['EMAIL']": {email : true},
		<?
		foreach($arParams['REQUIRED'] as $key => $value){
			echo '"ONE_CLICK_BUY['.$value.']": {required : true}';
			if($arParams['REQUIRED'][$key + 1]){
				echo ',';
			}
		}
		?>
	},
	highlight: function( element ){
		$(element).removeClass('error');
	},
	errorPlacement: function( error, element ){
		error.insertBefore(element);
	},
	messages:{
      licenses_popup_OCB: {
        required : BX.message('JS_REQUIRED_LICENSES')
      }
	}
});

var ocb_files = [], ocb_files_index = 0, ocbTmpFiles = [];
$(document).ready(function(){
	$(document).off('change', '#one_click_buy_form input[type=file]')
	$(document).on('change', '#one_click_buy_form input[type=file]', function(){
		var index = 0;

		if(ocbTmpFiles['i_'+$(this).offset().top] >=0)
			index = ocbTmpFiles['i_'+$(this).offset().top];
		else
		{
			ocbTmpFiles['i_'+$(this).offset().top] = ocb_files_index;
			index = ocbTmpFiles['i_'+$(this).offset().top];
			ocb_files_index++;
		}
		if(this.files.length)
		{
			this.files[0].code = $(this).closest('.files').data('code');
			ocb_files[index] = this.files;
			ocb_files[index].code = $(this).closest('.files').data('code');
		}
		else
			delete ocb_files[index];
	});

	$('.btn_block_file .button').click(function(){
		var block = $(this).closest('.form-control').find('.files');
		$('<input type="file" '+(block.data('required') == 'Y' ? 'required' : '')+' name="ONE_CLICK_BUY['+block.data('code')+'][]">').appendTo(block)
	});

	$('#one_click_buy_form .delivery_note .title').on('click', function(e){
		e.preventDefault();
		$(this).addClass('hidden');
		$(this).closest('.delivery_note').find('.text').removeClass('hidden');
	});

	$('#one_click_buy_form .delivery_note .text a').on('click', function(e){
		e.preventDefault();
		var href = $(this).attr('href');
		if(typeof href !== 'undefined' && href.length){
			window.open(href, '_blank');
		}
	});

<?if($arParams['BUY_ALL_BASKET'] == "Y"):?>
	if(arOptimusOptions['COUNTERS']['USE_FASTORDER_GOALS'] !== 'N'){
		var eventdata = {goal: 'goal_fastorder_begin'};
		BX.onCustomEvent('onCounterGoals', [eventdata])
	}
<?else:?>
	if(arOptimusOptions['COUNTERS']['USE_1CLICK_GOALS'] !== 'N'){
		var eventdata = {goal: 'goal_1click_begin'};
		BX.onCustomEvent('onCounterGoals', [eventdata])
	}
<?endif;?>
});

$('.popup').jqmAddClose('.jqmClose');

if(arOptimusOptions['THEME']['PHONE_MASK']){
	$('#one_click_buy_id_PHONE').inputmask( "mask", { "mask": arOptimusOptions['THEME']['PHONE_MASK'], "showMaskOnFocus": true } );
}
</script>