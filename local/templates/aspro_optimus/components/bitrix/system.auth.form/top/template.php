<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
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
<?$userTotal=0?>
	<div class="module-enter have-user">

			<?$APPLICATION->IncludeComponent(
				"bitrix:sale.personal.account",
				"personal.account",
				Array(
					"SET_TITLE" => "N"
				)
			);?>

		<?
		global $USER;
		$arPhoto=array();
		$arUser=COptimusCache::CUser_GetList(array("SORT"=>"ASC", "CACHE" => array("MULTI" => "N", "TAG"=>COptimusCache::GetUserCacheTag($USER->GetID()))), array("ID"=>$USER->GetID()), array("FIELDS"=>array("ID", "PERSONAL_PHOTO")));
		if($arUser["PERSONAL_PHOTO"]){
			$arPhoto=CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array("width"=>21, "height"=>21), BX_RESIZE_IMAGE_EXACT, true);
		}
		?>
		<!--noindex-->
<?$userData = CUser::GetByID($USER->GetID());
	$arUser = $userData->Fetch();
	$userGroups = \CUser::GetUserGroup($USER->GetID());
	$arGroups = [9,14];
	$result = array_intersect($arGroups, $userGroups);
	$userTotal = count($result) > 0 ? (float)$arUser["UF_ACCUMULATION"] : false;?>
		<?if($userTotal !== false && $userTotal<10000):?><div id="user-profile-link-wrapp" style="display:inline-block"><?endif?>
			<a href="<?=$arResult["PROFILE_URL"]?>" class="reg icon <?=($arPhoto ? "has-img" : "");?>" rel="nofollow" >
				<?if($arPhoto){?>
					<span class="bg_user" style='background:url("<?=$arPhoto['src'];?>") center center no-repeat;'></span>
				<?}?>
				<span><?=GetMessage("AUTH_LOGIN_BUTTON");?></span>
			</a>
			<?if($userTotal !== false && $userTotal<10000):?> <div class="user-bonus-popup-wrapper">
                <div class="user-bonus-popup">
                    <span class="bonuses-quantity-title">
                    В настоящий момент Вам доступны розничные цены<br>
                    <a href="/personal/" target="_blank">Подробнее</a>
                    </span>
                </div>
			</div>
				</div><?endif?>
			<a href="<?=SITE_DIR?>?logout=yes" class="exit_link" rel="nofollow"><span><?=GetMessage("AUTH_LOGOUT_BUTTON");?></span></a>
		<!--/noindex-->
	</div>	
<?endif;?>
<?$frame->end();?>
