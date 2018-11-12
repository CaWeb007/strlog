<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a class="orange" href="<?=$arResult["URL_TO_LIST"]?>"><?=GetMessage("SALE_RECORDS_LIST")?></a>

<div class="bx_my_order_cancel">
	<?if(strlen($arResult["ERROR_MESSAGE"])<=0):?>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>">

			<input type="hidden" name="CANCEL" value="Y">
			<?=bitrix_sessid_post()?>
			<input type="hidden" name="ID" value="<?=$arResult["ID"]?>">

			<p class="cancel-order">

			


				<b><?= GetMessage("SALE_CANCEL_ORDER3") ?></b>

			</p>

			<p>

				<?= GetMessage("SALE_CANCEL_ORDER4") ?>:<br />

			</p>

			<textarea name="REASON_CANCELED"></textarea><br>
			<input type="submit" name="action" value="<?=GetMessage("SALE_CANCEL_ORDER_BTN") ?>">

		</form>
	<?else:?>
		<?=ShowError($arResult["ERROR_MESSAGE"]);?>
	<?endif;?>

</div>