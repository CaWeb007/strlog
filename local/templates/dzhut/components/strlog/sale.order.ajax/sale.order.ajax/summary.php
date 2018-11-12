<div class="summary-block">
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
$totalBonus = 0;
foreach($arResult["GRID"]["ROWS"] as $BonusID){
	$totalBonus += $BonusID['data']['PROPERTY_BONUS_VALUE'];

}

?>
<?
//echo count($arResult["GRID"]["ROWS"]);
//edebug($arResult["GRID"]["ROWS"]);
?> 
<div class="bx_ordercart">
	
	<div class="summary-total">
	<!--LEFT-->
	<div class="left-summary">
		<div class="icon" style="background:url(/bitrix/templates/universal/image/sec.png)"></div>
		<div class="desc">
			<h4>Безопасность</h4>
			<p>Безопасность платежей гарантируется использованием
			SSL протокола. Данные вашем банковской карты надежно
			защищены при оплате онлайн.</p>
		</div>
	</div>
	<!--//LEFT-->
	
	<!--RIGHT-->
		<?php
/* mmit - 11.08.16 - Andrey Vlasov - start */
/* Сделаем проверку на присутствие товара, который доступен только под заказ */
			if ($arResult["CUSTOM_AMOUNT"] == "N") {
		?>
			<div class="center_summary">
				<div class="notice_not_amount">
					<h3>Внимание!</h3>
					<p>В вашем заказе имеются заказные позиции. 
	Срок доставки 7-10 рабочих дней.</p>
				</div>
			</div>
		<? } ?>
		<div class="right-summary <?=($arResult["CUSTOM_AMOUNT"] != "N") ? "right" : ""?>">
			<div class="total-cart-product">
				<div  class="green">
					<?=count($arResult["GRID"]["ROWS"]) . " ". declension(count($arResult["GRID"]["ROWS"]),GetMessage("PRODUCT_ONE"),GetMessage("PRODUCT_ODD"),GetMessage("PRODUCT_EVENT"));?> <?=GetMessage("TO_THE_AMOUNT_OF"). ":"?>
				</div>
				<div class="orange-sum"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></div>
			</div>
				
			<div class="total-cart-product">
					<div class="green"><?=GetMessage("SOA_TEMPL_SUM_IT")?></div>
					<div class="orange-sum"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></div>
			</div>
			
			
						<div class="incart-bonus check-out">
							<div class="product-bonus">
								<a href="">Бонусов <span class="orange">
									<?=$totalBonus?>
									</span>
								</a>
							</div>
							<div class="desc-bonus">
								<p class="orange">Копите бонусы и оплачивайте
								ими ваши покупки!</p>
								<p>1 бонус = 1 рубль</p>
								<a class="orange" href="/bonusnaya-karta/">Узнать больше</a>
							</div>
						</div>	
					
					<div class="bx_ordercart_order_sum">
					<a href="javascript:void(0);"  id="ORDER_CONFIRM_BUTTON" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a>
					<!--agreePersonalButton-->
					<label id="basketLabelAgreePersonalButton" for="basketAgreePersonalCheckbox">
						<input id="basketAgreePersonalCheckbox" type="checkbox" value="" checked />
						<span id="basketAgreePersonalText">Я согласен на <a href="/agreement/" target="_blank">обработку персональных данных</a></span>
					</label>
					<!--/agreePersonalButton-->
						<!--<a href="javascript:void();" onclick="submitForm('Y'); return false;" id="ORDER_CONFIRM_BUTTON" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a>-->
					</div>
			
		</div>
	<!--//RIGHT-->

	</div>


	
</div>
</div>