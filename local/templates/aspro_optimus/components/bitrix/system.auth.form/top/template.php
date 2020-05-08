<? use Bitrix\Main\Loader;
use Caweb\Main\Sale\Helper;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form id="auth_params" action="<?=SITE_DIR?>ajax/show_personal_block.php">
	<input type="hidden" name="REGISTER_URL" value="<?=$arParams["REGISTER_URL"]?>" />
	<input type="hidden" name="FORGOT_PASSWORD_URL" value="<?=$arParams["FORGOT_PASSWORD_URL"]?>" />
	<input type="hidden" name="PROFILE_URL" value="<?=$arParams["PROFILE_URL"]?>" />
	<input type="hidden" name="SHOW_ERRORS" value="<?=$arParams["SHOW_ERRORS"]?>" />
</form>
<?
$frame = $this->createFrame()->begin('');
$frame->setBrowserStorage(true);
?>
<?if(!$USER->IsAuthorized()):?>
	<div class="module-enter no-have-user">
		<!--noindex-->
			<a class="avtorization-call icon" rel="nofollow" href="<?=SITE_DIR;?>auth/"><span><?=GetMessage("AUTH_LOGIN_ENTER");?></span></a>
			<a class="register" rel="nofollow" href="<?=$arParams["REGISTER_URL"];?>"><span><?=GetMessage("AUTH_LOGIN_REGISTER");?></span></a>
		<!--/noindex-->
	</div>
<?else:?>
	<div class="module-enter have-user">
			<?$APPLICATION->IncludeComponent(
				"bitrix:sale.personal.account",
				"personal.account",
				Array(
					"SET_TITLE" => "N"
				)
			);?>
            <!--noindex-->
                <?if(Loader::includeModule('caweb.main') && Helper::getInstance()->isKpUser()):?>
                    <div id="user-profile-link-wrapp" style="display:inline-block">
                        <div class="user-bonus-popup-wrapper">
                            <div class="user-bonus-popup">
                                <span class="bonuses-quantity-title">
                                    В настоящий момент Вам доступны розничные цены<br>
                                    <a href="/personal/" target="_blank">Подробнее</a>
                                </span>
                            </div>
                        </div>
                    </div>
                <?endif?>
                <a href="<?=$arResult["PROFILE_URL"]?>" class="reg icon" rel="nofollow">
                    <span><?=GetMessage("AUTH_LOGIN_BUTTON");?></span>
                </a>
			    <a href="<?=SITE_DIR?>?logout=yes" class="exit_link" rel="nofollow"><span><?=GetMessage("AUTH_LOGOUT_BUTTON");?></span></a>
		    <!--/noindex-->
	</div>	
<?endif;?>
<?$frame->end();?>
