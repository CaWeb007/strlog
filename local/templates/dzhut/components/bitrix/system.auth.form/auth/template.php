<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="bx-system-auth-form">
<?if($arResult["FORM_TYPE"] == "login"):?>

<a title="Войти" id="auth" href="#">Войти</a>
<a title="Регистрация" id="registr" href="#">Регистрация</a>

<div class="auth-form-wrap">
<h4 class="slug-call-back">Войти в личный кабинет</h4>
<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>
<form class="auth-form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
<input type="hidden" name="AUTH_FORM" value="Y" />
<input type="hidden" name="TYPE" value="AUTH" />

			<div class="auth-row">

			<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" placeholder="<?=GetMessage("AUTH_LOGIN")?>"/>
			</div>

		<div class="auth-row">
			<input type="password" name="USER_PASSWORD" maxlength="50" size="17" autocomplete="off" placeholder="<?=GetMessage("AUTH_PASSWORD")?>"/>

					<?if($arResult["SECURE_AUTH"]):?>
						<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
							<div class="bx-auth-secure-icon"></div>
						</span>
						<noscript>
						<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
							<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
						</span>
						</noscript>
						<script type="text/javascript">
						document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
						</script>
						<?endif?>


		</div>

		<div class="auth-row">
			<input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
		</div>
		<div class="auth-row">
				<noindex><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></noindex>
		</div>
	<button id="closeAH" class="close-call-back">Скрыть</button>
</form>

</div>

<?
else:
?>

<form action="<?=$arResult["AUTH_URL"]?>">
				<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
				<?endforeach?>
				<input type="hidden" name="logout" value="yes" />

		<!--BONUS DROP-->
		<div class="auth-el">
			<a href="/personal/bonusy/" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			<?=GetMessage("YOU_HAVE_BONUS")?> <?if(empty($arResult['USER_BONUS'])) $arResult['USER_BONUS'] = 0; echo  $arResult['USER_BONUS'];?> <?echo declension($arResult['USER_BONUS'],GetMessage("YOU_HAVE_BONUS_ONE"),GetMessage("YOU_HAVE_BONUS_ODD"),GetMessage("YOU_HAVE_BONUS_EVEN"))?></a>
			<div class="dropdown-menu-right">
				<p class="bonus-depoz-text">
					<?=GetMessage("YOU_HAVE_DEPOSIT")?>
					<span class="orange"><?=$arResult['USER_BONUS'] . " ". declension($arResult['USER_BONUS'],GetMessage("YOU_HAVE_BONUS_ONE"),GetMessage("YOU_HAVE_BONUS_ODD"),GetMessage("YOU_HAVE_BONUS_EVEN"))?></span>
				</p>
				<div class='orange-link'>
					<a target="_blank" href="http://strlogclub.ru/"><?=GetMessage("DEPOSIT_MORE")?></a>
				</div>
			</div>
		</div>
		<!--//BONUS DROP-->

		<!--PROFILE DROP-->
		<div class="auth-el">
			<a href="/personal/profile/" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$arResult["USER_EMAIL"]?></a>
			<div class="dropdown-menu-right lk">
				<ul class="dropdown-menu">
					<li><a href="/personal/profile/" title="<?=GetMessage("AUTH_PROFILE")?>">Личный кабинет</a></li>
					<li><input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" /></li>
				</ul>
			</div>
		</div>
		<!--//PROFILE DROP-->


</form>

<?endif?>

</div>
