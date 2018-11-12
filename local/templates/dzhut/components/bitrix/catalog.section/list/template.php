<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $arrProductId;
$UserBasket = CheckBasket();
?>

<?if(!empty($arResult["ITEMS"][0]["IBLOCK_SECTION_ID"])):

	$nav = CIBlockSection::GetNavChain(false, $arResult["ITEMS"][0]["IBLOCK_SECTION_ID"]);
	while($resultNav = $nav->GetNext()):
		$APPLICATION->AddChainItem($resultNav['NAME'],$resultNav['SECTION_PAGE_URL']);
	endwhile;

?>
	
<?endif;?>


<?
	//==== START PARTY HARD
	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
?>

<div class="product-list">

	<?foreach($arResult["ITEMS"] as $arElement){
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
			// скидка
		$minPrice = false;
		if (isset($arElement['MIN_PRICE']) || isset($arElement['RATIO_PRICE']))
		$minPrice = (isset($arElement['RATIO_PRICE']) ? $arElement['RATIO_PRICE'] : $arElement['MIN_PRICE']);

		?>

		<div class="prod-item" id="<?=$strMainID?>">
		<?
			// ресайз картинок
			$IMG = CFile::ResizeImageGet($arElement['DETAIL_PICTURE'],array("width"=>110,"height"=>82),BX_RESIZE_IMAGE_PROPORTIONAL);

		?>
			<div class="wrap-prod-img">
			<div class="prod-img" style=<?if(!empty($IMG['src'])) echo '"background:url('.$IMG['src'].')"'?>></div>
				<?if('Y' == $arParams['SHOW_DISCOUNT_PERCENT']){ ?>
					<div  class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;"></div>
				<?}?>

				<?if(!empty($arElement['PROPERTIES']['BEST']['VALUE'])){?>
					<div class="best_price">
						<?=GetMessage('BEST_PRICE_TEXT')?>
					</div>
				<?}?>
				<?if(!empty($arElement['PROPERTIES']['HIT']['VALUE'])){?>
					<div class="best_price hit">
						<span><?=GetMessage('PRODUCT_HIT_FIRST')?></span></br>
							  <?=GetMessage('PRODUCT_HIT_SECOND')?>
					</div>
				<?}?>
				<?if(!empty($arElement['PROPERTIES']['NEW']['VALUE'])){?>
					<div class="best_price new">
						<?=GetMessage('PRODUCT_NEW')?>
					</div>
				<?}?>

			</div>
			<div class="prod-desc">
				<h4 class="orange"><a title="<?=$arElement["NAME"]?>" href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></h4>
				<p></p>
			</div>
			<div class="prod-price">
			<?

			if(!empty($arElement['PROPERTIES']['TSENA_ZA_M2']['VALUE'])){
				echo $arElement['PROPERTIES']['TSENA_ZA_M2']['VALUE'];
				echo '<ruble><span class="text"><meta itemprop="priceCurrency" content="RUB" /></span></ruble><span style="font-size:12px;color:#999999;margin:0;">/м<sup>2</sup>';
			}
			elseif(!empty($arElement['PROPERTIES']['TSENA_ZA_KG']['VALUE'])){
				echo $arElement['PROPERTIES']['TSENA_ZA_KG']['VALUE'];
				echo '<ruble><span class="text"><meta itemprop="priceCurrency" content="RUB" /></span></ruble><span style="font-size:12px;color:#999999;margin:0;">/кг';
			}else{

				if (!empty($minPrice))
				{
					if ('N' == $arParams['PRODUCT_DISPLAY_MODE'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
					{
						echo GetMessage(
							'CT_BCS_TPL_MESS_PRICE_SIMPLE_MODE',
							array(
								'#PRICE#' => $minPrice['PRINT_DISCOUNT_VALUE'],
								'#MEASURE#' => GetMessage(
									'CT_BCS_TPL_MESS_MEASURE_SIMPLE_MODE',
									array(
										'#VALUE#' => $minPrice['CATALOG_MEASURE_RATIO'],
										'#UNIT#' => $minPrice['CATALOG_MEASURE_NAME']
									)
								)
							)
						);
					}
					else
					{
						if ('Y' == $arParams['SHOW_OLD_PRICE'] && $minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE'])
							{
								?> <span class='discount-trh'><? echo $minPrice['PRINT_VALUE']; ?></span><?
							}
						echo  $minPrice['PRINT_DISCOUNT_VALUE'] ;

					}
				}
				unset($minPrice);
				?>
				<span style="font-size:12px;color:#999999;margin:0 0 0 -6px;">/ <?=$arElement['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span>
				<?if(!empty($arElement["OLD_PRICE"])):?>
				<span class="old-price" style="font-size:1.2rem;position:relative;display:inline-block;margin-top:0.5rem;opacity:0.5;font-family:'BlissPro-Regular';font-weight:normal;">
					<?=$arElement["OLD_PRICE"];?>
					<ruble>
						<span class="text"></span>
					</ruble>
				</span>
				<?endif;?>
				<?}?>

				<div class="product-bonus">
					<a title="количество бонусов" href="">Бонус <span class="orange"><?=($arElement['PROPERTIES']['BONUS']['VALUE']) ? $arElement['PROPERTIES']['BONUS']['VALUE'] : 0?></span></a>
				</div>
				<div class="desc-bonus">
						<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" href="/bonusnaya-karta/" title="Узнать больше">Узнать больше</a>
				</div>
			</div>
				
			<div class="prod-control">
			<!--Проверка корзины юзера-->
				<div class="wrap-buy ">
				<?if($arElement["CAN_BUY"]):?>
						<?if(in_array($arElement['ID'],$UserBasket)):?>
						
						<a rel="nofollow" class="bx_bt_button bx_medium success-btn" href="/personal/cart/" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_IN_CART")?></a>
						
						<?else:?>
						
						<a onclick="yaCounter36570340.reachGoal('click'); return true;" id="test" class="bx_bt_button bx_medium" href="<?echo $arElement["BUY_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_BUY")?></a>
					<?endif;?>
					
				<?endif;?>
				<div class="amount_store">
					<?if($arElement['CUSTOM_AMOUNT'] == "Y"):?>
						Есть в наличии
					<?else:?>
						Под заказ
					<?endif;?>
				</div>
				<?php
				/* mmit - 10.08.2016 - Andrey Vlasov - start */
				/* реализуем всплывающее окно со сроками доставки товара, если товара доступен только под заказ */
				if ($arElement['CUSTOM_AMOUNT'] != "Y") {
				?>
				<div style="display: none;" class="notice_delivery_time">
					<p>Уведомлен о том, что срок доставки данного товара под заказ 7-10 рабочих дней</p>
					<button>ok</button>
				</div>
				<?php
				}
				/* 10.08.2016 - Andrey Vlasov - end */
				?>
				</div>
			</div>
		</div>
	<?}?>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?if($arResult["NAV_STRING"] != "1"):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?endif?>

<?if(!empty($arResult["DESCRIPTION"])):?>
<div class="description-block">

	<div class="description-content">
		<?if(!empty($arResult["ITEMS"][0]['CATEGORY_DESCRIPTION'])){?>
			<p><?=$arResult["ITEMS"][0]['CATEGORY_DESCRIPTION']?></p>
		<?}else{?>
			<p><?=$arResult["DESCRIPTION"]?></p>
		<?}?>
	</div>

	<button class="description-toggle">
		<span class="text">Развернуть</span>
		<span class="icon"></span>
	</button>
</div>

<script>
	var $descCont = $('.description-content');
	var descContH = $descCont.outerHeight();
	console.log(descContH);
	$descCont.css({maxHeight: descContH});
	$descCont
		.velocity({'maxHeight': 100}, {duration: 700});

	$descCont.addClass('close');

	var $descButton = $('.description-toggle');

	$descButton.on('click', function () {
		$descCont
			.velocity("stop")
			.velocity("reverse");

		$descCont.toggleClass('close');

		if( $descCont.hasClass('close') ) {
			$(this).find('.text').text('Развернуть');
		} else {
			$(this).find('.text').text('Свернуть');
		}

	});
</script>
<?endif;?>

<script>
// (function(){
	// jQuery(document).ready(function(){
		// $(".wrap-buy a").removeAttr('onclick');
		// $(".wrap-buy a").not('.success-btn').on('click',function(e){
			// console.log("click")
			// e.preventDefault();
			// if(!$(this).hasClass('success-btn')){
				// var url = $(this).attr('href');
					// var SuccessBtn = $(this);
					// $.ajax({
						// type:'post',
						// beforeSend:function(){
							// BX.showWait();
						// },
						// url:url,
						// success:function(data){
							// BX.onCustomEvent('OnBasketChange');
							// SuccessBtn.addClass('success-btn');
							// SuccessBtn.text('В корзине');
							// SuccessBtn.attr({'href':'/personal/cart/'});
							// BX.closeWait();
						// },

					// })
			// }

		// })

	// })
// })();
</script>
