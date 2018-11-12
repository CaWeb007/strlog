<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $arrProductId;
//edebug($arResult["ITEMS"]);
?>
<?
	//==== START PARTY HARD
	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");	
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
	//edebug($arParams);	
?>

<div class="product-list">
	
	<?foreach($arResult["ITEMS"] as $key => $arElement){
		
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], $strElementEdit);
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
		$strMainID = $this->GetEditAreaId($arElement['ID']);
	// скидка
	$minPrice = false;
	if (isset($arElement['MIN_PRICE']) || isset($arElement['RATIO_PRICE']))
		$minPrice = (isset($arElement['RATIO_PRICE']) ? $arElement['RATIO_PRICE'] : $arElement['MIN_PRICE']);
	
	?>


		<div class="prod-item" id=<?=$strMainID?>>
		<?
			// ресайз картинок
			$IMG = CFile::ResizeImageGet($arElement['DETAIL_PICTURE'],array("width"=>110,"height"=>82),BX_RESIZE_IMAGE_PROPORTIONAL);
			
		?>
			<!--IMAGE-->
			<div class="wrap-prod-img">
				<div class="prod-img"  style="background:url(<?=$IMG['src']?>)"></div>
					
				<?if('Y' == $arParams['SHOW_DISCOUNT_PERCENT']){ ?>
					<div  class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;"></div>
				<?}?>
			</div>
			<!--//IMAGE-->
			
			<!--TITLE / DESC-->
			<div class="prod-desc">
				<h4 class="orange"><a  title="<?=$arElement["NAME"]?>" href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></h4>
				<p><?=$arElement['PREVIEW_TEXT']?></p>
			</div>
			<!--//TITLE / DESC-->
			
			<!--PRICE-->
			<div class="prod-price">
				<?
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
				<?//=$arElement['PRICES']['BASE']['PRINT_VALUE']?>
			</div>
			<!--//PRICE-->
			
			<div class="prod-control">
			<!--Проверка корзины юзера-->
			
			<!--CONTROLS ACTION-->
				<div class="wrap-buy" id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>">
				<?if($arElement["CAN_BUY"]):?>
					
						<?if(in_array($arElement['ID'],$arrProductId)):?>
							<?$arSets = CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET);
							if(!empty($arSets)):?>
								<a  class="bx_bt_button bx_medium success-btn" href="<?echo $arElement["ADD_URL"]?>" rel="nofollow"><?echo GetMessage("CATALOG_IN_CART")?></a>
							<?else:?>	
								<a  class="bx_bt_button bx_medium success-btn" href="<?echo $arElement["ADD_URL"]?>" rel="nofollow"><?echo GetMessage("CATALOG_IN_CART")?></a>
							<?endif;?>
						<?else:?>
						
						<?$arSets = CCatalogProductSet::getAllSetsByProduct($arElement['ID'], CCatalogProductSet::TYPE_SET);
							if(!empty($arSets)):?>
								<a  class="bx_bt_button bx_medium no-ajax" href="<?echo $arElement["ADD_URL"]?>" rel="nofollow"><?echo GetMessage("CATALOG_BUY")?></a>
							<?else:?>
								<a class="bx_bt_button bx_medium " href="<?echo $arElement["ADD_URL"]?>" rel="nofollow"><?echo GetMessage("CATALOG_BUY")?></a>
							<?endif;?>
						<?endif;?>
				
				<?endif;?>	
				</div>
			<!--//CONTROLS ACTION-->	
			
				<div  class="count" style="display:none">
					<input type="text" name="quantity" value="1">
				</div>
				
				<div class="product-bonus">
					<a href="">Бонус <span class="orange"><?=$arElement['PROPERTIES']['BONUS']['VALUE']?></span></a>
				</div>
				<div class="desc-bonus">
						<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" href="/bonusnaya-karta/">Узнать больше</a>
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

<script>




 (function(){
	jQuery(document).ready(function(){
		$(".wrap-buy a").removeAttr('onclick');
		$(".wrap-buy a").not('.success-btn').on('click',function(e){
			e.preventDefault();
			
			 if(!$(this).hasClass('success-btn')){
				var url = $(this).attr('href');
					var SuccessBtn = $(this);
					$.ajax({
						type:'post',
						url:url,
						success:function(data){
							BX.onCustomEvent('OnBasketChange');	
							SuccessBtn.addClass('success-btn');
							SuccessBtn.text('В корзине');
						},
						
					})	
			} 
			
		})
		
	})
})(); 
</script>