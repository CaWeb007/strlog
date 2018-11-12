<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult['ERRORS']['FATAL'])):?>
	<?foreach($arResult['ERRORS']['FATAL'] as $error):?>
		<?=ShowError($error)?>
	<?endforeach?>
<?else:?>
	<?if(!empty($arResult['ERRORS']['NONFATAL'])):?>
		<?foreach($arResult['ERRORS']['NONFATAL'] as $error):?>
			<?=ShowError($error)?>
		<?endforeach?>
	<?endif?>

<?$this->SetViewTarget("personal_bar");?>
			<a href="/personal/order/" class="active" >Заказы</a>
			<a href="/personal/profile/">Профиль</a>
		<?$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);?>
		<?/*?>
		<?if($nothing || isset($_REQUEST["filter_history"])):?>
			<a class="bx_mo_link" href="<?=$arResult["CURRENT_PAGE"]?>?show_all=Y"><?=GetMessage('SPOL_ORDERS_ALL')?></a>
		<?endif?>
		<?*/?>
		<?if($_REQUEST["filter_history"] == 'N'):?>
			<a class="bx_mo_link active" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=N"><?=GetMessage('SPOL_CUR_ORDERS')?></a>
		<?else:?>
			<a class="bx_mo_link" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=N"><?=GetMessage('SPOL_CUR_ORDERS')?></a>
		<?endif?>

		<?if($nothing || $_REQUEST["filter_history"] == 'Y'):?>
			<a class="bx_mo_link active" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=Y"><?=GetMessage('SPOL_ORDERS_HISTORY')?></a>
		<?else:?>
			<a class="bx_mo_link" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=Y"><?=GetMessage('SPOL_ORDERS_HISTORY')?></a>
		<?endif?>
<?$this->EndViewTarget();?>

<div class="bx_my_order_content">

	<div class="bx_my_order_items">
	<?if(!empty($arResult['ORDERS'])):?>

		<?foreach($arResult["ORDER_BY_STATUS"] as $key => $group):?>

			<?foreach($group as $k => $order):?>
				<?/*?>
				<?if(!$k):?>

					<div class="bx_my_order_status_desc">

						<h2><?=GetMessage("SPOL_STATUS")?> "<?=$arResult["INFO"]["STATUS"][$key]["NAME"] ?>"</h2>
						<div class="bx_mos_desc"><?=$arResult["INFO"]["STATUS"][$key]["DESCRIPTION"] ?></div>

					</div>

				<?endif?>
				<?*/?>
				<div class="bx_my_order">
					<div class="order">
						<!--<div class="plus_minus"></div>-->
						<div class="order-head">
								<div class="order-info">
									<?=GetMessage('SPOL_ORDER')?> <?=GetMessage('SPOL_NUM_SIGN')?><?=$order["ORDER"]["ACCOUNT_NUMBER"]?> <?=GetMessage('SPOL_FROM')?> <?=$order["ORDER"]["DATE_INSERT_FORMATED"];?>
									<a href="" class="open-order">Состав заказа</a>
								</div>
								<div class="bx_my_order_status <?//=$arResult["INFO"]["STATUS"][$key]['COLOR']?><?/*yellow*/ /*red*/ /*green*/ /*gray*/?>">Состояние заказа: <span><?=$arResult["INFO"]["STATUS"][$key]["NAME"]?></span></div>
								<!-- <a href="<?=$order["ORDER"]["URL_TO_DETAIL"]?>"><?=GetMessage('SPOL_ORDER_DETAIL')?></a> -->
						</div>
						<div class="order-body">
								<div class="sum">
									<span class="label"><?=GetMessage('SPOL_PAY_SUM')?>:</span><span> <?=$order["ORDER"]["FORMATED_PRICE"]?></span>
								</div>

								<div class="pay">
									<span class="label"><?=GetMessage('SPOL_PAYED')?>:</span><span> <?=GetMessage('SPOL_'.($order["ORDER"]["PAYED"] == "Y" ? 'YES' : 'NO'))?> </span>
								</div>
									<? // PAY SYSTEM ?>
									<?if(intval($order["ORDER"]["PAY_SYSTEM_ID"])):?>
									<div class="pay-system">
										<span class="label"><?=GetMessage('SPOL_PAYSYSTEM')?>:</span><span> <?=$arResult["INFO"]["PAY_SYSTEM"][$order["ORDER"]["PAY_SYSTEM_ID"]]["NAME"]?> </span>
									</div>

									<?endif?>

									<? // DELIVERY SYSTEM ?>
									<?//if($order['HAS_DELIVERY']):?>
									<?if(false):?>
									<div class="delivery-system">

										<span class="label"><?=GetMessage('SPOL_DELIVERY')?>:</span>

										<?if(intval($order["ORDER"]["DELIVERY_ID"])):?>

											<?=$arResult["INFO"]["DELIVERY"][$order["ORDER"]["DELIVERY_ID"]]["NAME"]?> <br />

										<?elseif(strpos($order["ORDER"]["DELIVERY_ID"], ":") !== false):?>

											<?$arId = explode(":", $order["ORDER"]["DELIVERY_ID"])?>
											<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["NAME"]?> (<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["PROFILES"][$arId[1]]["TITLE"]?>) <br />

										<?endif?>
									</div>
									<?endif?>
						</div>
					</div>
					<div class="order_dropdown close">
						<div class="dropdown_container">
								<div class="name_label">
									Название товара
								</div>

								<div class="amount_label">
									Кол-во
								</div>

								<?foreach ($order["BASKET_ITEMS"] as $item):?>
									<div class="item_name">
										<?if(strlen($item["DETAIL_PAGE_URL"])):?>
											<a href="<?=$item["DETAIL_PAGE_URL"]?>" target="_blank">
										<?endif?>
											<?=html_entity_decode($item['NAME'])?>
										<?if(strlen($item["DETAIL_PAGE_URL"])):?>
											</a>
										<?endif?>
									</div>
									<div class="item_amound">
										<?=$item['QUANTITY']?> <?=(isset($item["MEASURE_NAME"]) ? $item["MEASURE_NAME"] : GetMessage('SPOL_SHT'))?>
									</div>
								<?endforeach?>
						</div>
						<div class="controls">
							<?if($order["ORDER"]["CANCELED"] != "Y"):?>
								<a href="<?=$order["ORDER"]["URL_TO_CANCEL"]?>" style="min-width:140px"class="bx_big bx_bt_button_type_2 bx_cart bx_order_action"><?=GetMessage('SPOL_CANCEL_ORDER')?></a>
							<?endif?>

							<a href="<?=$order["ORDER"]["URL_TO_COPY"]?>" style="min-width:140px"class="bx_big bx_bt_button_type_2 bx_cart bx_order_action"><?=GetMessage('SPOL_REPEAT_ORDER')?></a>
						</div>
					</div>
				</div>

			<?endforeach?>

		<?endforeach?>

		<?if(strlen($arResult['NAV_STRING'])):?>
			<?=$arResult['NAV_STRING']?>
		<?endif?>

	<?else:?>
		<?=GetMessage('SPOL_NO_ORDERS')?>
	<?endif?>
	</div>
</div>
<?endif?>
