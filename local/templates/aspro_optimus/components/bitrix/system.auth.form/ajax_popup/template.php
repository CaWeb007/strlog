<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->SetTitle(GetMessage("AUTH_AUTH"));?>
<?
if(!empty( $_REQUEST["change_password"])){
	LocalRedirect(SITE_DIR.'auth/change-password/?change_password='.$_REQUEST["change_password"].'&lang='.$_REQUEST["lang"].'&USER_CHECKWORD='.$_REQUEST["USER_CHECKWORD"].'&USER_LOGIN='.$_REQUEST["USER_LOGIN"].'');
}
?>
<?if(!$USER->isAuthorized()):?>
	<div class="popup-intro">
		<div class="pop-up-title"><?=GetMessage('AUTH_TITLE')?></div>
	</div>
	<a class="jqmClose close"><i></i></a>

		
		<?$sLoginEqual = COption::GetOptionString('aspro.optimus', 'LOGIN_EQUAL_EMAIL', 'Y');?>

					<div class="form-wr form-block">
                        <?if($arResult['SHOW_ERRORS'] == 'Y'):?>
                            <?ShowMessage($arResult['ERROR_MESSAGE']);?>
                        <?endif;?>
						<form id="avtorization-form-page" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=SITE_DIR?>auth/<?=!empty( $_REQUEST["backurl"] ) ? '?backurl='.$_REQUEST["backurl"] : ''?>">
							<?if($arResult["BACKURL"] <> ''):?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?endif?>
							<?foreach($arResult["POST"] as $key => $value):?>
								<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
							<?endforeach;?>
							<input type="hidden" name="AUTH_FORM" value="Y" />
							<input type="hidden" name="AJAX-ACTION" value="AUTH"/>
							<input type="hidden" name="TYPE" value="AUTH" />
							<div class="form-control bg">
								<label class="description"><?=($sLoginEqual == 'Y' ? GetMessage("EMAIL") : GetMessage("AUTH_LOGIN"));?> <span class="star">*</span></label>
								<input type="text" class="inputtext" name="USER_LOGIN" required maxlength="50" value="<?=$_POST["USER_LOGIN"]?>" size="17" tabindex="7" />
								<?if($_POST["USER_LOGIN"]=='' && isset($_POST["USER_LOGIN"])){?><label class="error"><?=GetMessage("FIELD_REQUIRED")?></label><?}?>
							</div>
							<div class="form-control bg">
								<label class="description"><?=GetMessage("AUTH_PASSWORD")?> <span class="star">*</span></label>
								<input type="password" class="inputtext password" name="USER_PASSWORD" required maxlength="50" size="17" tabindex="8" value="<?=$_POST["USER_PASSWORD"]?>"  />
								<?if($_POST["USER_PASSWORD"]=='' && isset($_POST["USER_PASSWORD"])){?><label class="error"><?=GetMessage("FIELD_REQUIRED")?></label><?}?>
							</div>
							<?if ($arResult["CAPTCHA_CODE"]):?>
								<div class="form-control captcha-row clearfix">
									<label class="description"><span><?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:</span></label>
									<div class="captcha_image">
										<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
										<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
										<div class="captcha_reload"></div>
									</div>
									<div class="captcha_input">
										<input type="text" name="captcha_word" maxlength="50" value="" />
									</div>
								</div>
							<?endif?>
							<div class="but-r">
								<div class="filter block">
									<a class="forgot" href="<?=SITE_DIR?>auth/forgot-password/<?=!empty( $_REQUEST["backurl"] ) ? '?backurl='.$_REQUEST["backurl"] : ''?>" tabindex="9"><?=GetMessage("FORGOT_PASSWORD")?></a>
									<div class="remember">
										<input id="remuser" type="checkbox" tabindex="11" />
										<label for="remuser" tabindex="11"><?=GetMessage("AUTH_REMEMBER_ME")?></label>
									</div>
									<div class="clearboth"></div>
								</div>
								<div class="buttons">
									<button type="submit" class="button short" name="Login" tabindex="10"><span><?=GetMessage("AUTH_LOGIN_BUTTON")?></span></button>
								</div>
							</div>							
						</form>
						<div class="one_click_buy_result show" id="one_click_buy_result">
							<div class="one_click_buy_result_fail">
							<br>
							</div>
						</div>
					</div>
					<div class="clearboth"></div>

	<script type="text/javascript">
	if($(window).width() >= 600){
		$('.authorization-cols').equalize({children: '.col .auth-title', reset: true});
		$('.authorization-cols').equalize({children: '.col .form-block', reset: true}); 
	}
	
	$(document).ready(function(){
		$(window).resize();
		
		$(".authorization-cols .col.authorization .soc-avt .row a").click(function(){
			$(window).resize();
		});
				
		$formResult = $(".show_auth_form_popup_frame ");

		$("#avtorization-form-page").validate({
			rules: {
				USER_LOGIN: {
					<?if($sLoginEqual == 'Y'):?>
					email: true,
					<?endif;?>
					required:true
				}
			},
			submitHandler: function( form ){
			 
				$.post('/ajax/show_auth_form_popup.php', $(form).serialize(), function (response) {
					if(response.trim() == "success"){
						window.location = window.location;
					} else {
						$formResult.html(response);
					}
				});
				
				return false;
			}
		});
		
		$("form[name=bx_auth_servicesform]").validate(); 
		
	});
	</script>
<?else:?>
success
<?endif;?>

