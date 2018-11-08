<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $APPLICATION->SetPageProperty("isDetailPage", "yes");?>
<style>
.catalog_detail .w_icons.button.to-cart::before {
	display:none !important;
}
</style>
<div class="catalog_detail" itemscope itemtype="http://schema.org/Product">
<div class="basket_props_block" id="bx_basket_div_<?=$arResult["ID"];?>" style="display: none;">
	<?if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])){
		foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
			<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
			<?if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
				unset($arResult['PRODUCT_PROPERTIES'][$propID]);
		}
	}
	$arResult["EMPTY_PROPS_JS"]="Y";
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if (!$emptyProductProperties){
		$arResult["EMPTY_PROPS_JS"]="N";?>
		<div class="wrapper">
			<table>
				<?foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
					<tr>
						<td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
						<td>
							<?if('L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']){
								foreach($propInfo['VALUES'] as $valueID => $value){?>
									<label>
										<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
									</label>
								<?}
							}else{?>
								<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]">
									<?foreach($propInfo['VALUES'] as $valueID => $value){?>
										<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
									<?}?>
								</select>
							<?}?>
						</td>
					</tr>
				<?}?>
			</table>
		</div>
	<?}?>
</div>
<?
$this->setFrameMode(true);
$currencyList = '';
if (!empty($arResult['CURRENCIES'])){
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'STORES' => array(
		"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
		"SCHEDULE" => $arParams["SCHEDULE"],
		"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
		"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
		"ELEMENT_ID" => $arResult["ID"],
		"STORE_PATH"  =>  $arParams["STORE_PATH"],
		"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
		"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
		"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"USER_FIELDS" => $arParams['USER_FIELDS'],
		"FIELDS" => $arParams['FIELDS'],
		"STORES" => $arParams['STORES'] = array_diff($arParams['STORES'], array('')),
	)
);
unset($currencyList, $templateLibrary);

$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS'])){
	$arSkuTemplate=COptimus::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], "list", $arParams["OFFER_HIDE_NAME_PROPS"]);
}
$strMainID = $this->GetEditAreaId($arResult['ID']);

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$arResult["strMainID"] = $this->GetEditAreaId($arResult['ID']);
$arItemIDs=COptimus::GetItemsIDs($arResult, "Y");
$totalCount = COptimus::GetTotalCount($arResult);
$arQuantityData = COptimus::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "Y");

$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
$useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];
$showCustomOffer=(($arResult['OFFERS'] && $arParams["TYPE_SKU"] !="N") ? true : false);
if($showCustomOffer){
	$templateData['JS_OBJ'] = $strObName;
}
$strMeasure='';
if($arResult["OFFERS"]){
	$strMeasure=$arResult["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
	$templateData["STORES"]["OFFERS"]="Y";
	foreach($arResult["OFFERS"] as $arOffer){
		$templateData["STORES"]["OFFERS_ID"][]=$arOffer["ID"];
	}
}else{
	if (($arParams["SHOW_MEASURE"]=="Y")&&($arResult["CATALOG_MEASURE"])){
		$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
		$strMeasure=$arMeasure["SYMBOL_RUS"];
	}
	$arAddToBasketData = COptimus::GetAddToBasketArray($arResult, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn w_icons', $arParams);
}
$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);

// save item viewed
$arFirstPhoto = reset($arResult['MORE_PHOTO']);
$arItemPrices = $arResult['MIN_PRICE'];
if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX'])
{
	$rangSelected = $arResult['ITEM_QUANTITY_RANGE_SELECTED'];
	$priceSelected = $arResult['ITEM_PRICE_SELECTED'];
	if(isset($arResult['FIX_PRICE_MATRIX']) && $arResult['FIX_PRICE_MATRIX'])
	{
		$rangSelected = $arResult['FIX_PRICE_MATRIX']['RANGE_SELECT'];
		$priceSelected = $arResult['FIX_PRICE_MATRIX']['PRICE_SELECT'];
	}
	$arItemPrices = $arResult['ITEM_PRICES'][$priceSelected];
	$arItemPrices['VALUE'] = $arItemPrices['PRICE'];
	$arItemPrices['PRINT_VALUE'] = \Aspro\Functions\CAsproItem::getCurrentPrice('PRICE', $arItemPrices);
	$arItemPrices['DISCOUNT_VALUE'] = $arItemPrices['BASE_PRICE'];
	$arItemPrices['PRINT_DISCOUNT_VALUE'] = \Aspro\Functions\CAsproItem::getCurrentPrice('BASE_PRICE', $arItemPrices);
}
$arViewedData = array(
	'PRODUCT_ID' => $arResult['ID'],
	'IBLOCK_ID' => $arResult['IBLOCK_ID'],
	'NAME' => $arResult['NAME'],
	'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
	'PICTURE_ID' => $arResult['PREVIEW_PICTURE'] ? $arResult['PREVIEW_PICTURE']['ID'] : ($arFirstPhoto ? $arFirstPhoto['ID'] : false),
	'CATALOG_MEASURE_NAME' => $arResult['CATALOG_MEASURE_NAME'],
	'MIN_PRICE' => $arItemPrices,
	'CAN_BUY' => $arResult['CAN_BUY'] ? 'Y' : 'N',
	'IS_OFFER' => 'N',
	'WITH_OFFERS' => $arResult['OFFERS'] ? 'Y' : 'N',
);
?>
<script type="text/javascript">
setViewedProduct(<?=$arResult['ID']?>, <?=CUtil::PhpToJSObject($arViewedData, false)?>);
</script>
<div class="item_main_info <?=(!$showCustomOffer ? "noffer" : "");?> <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>" id="<?=$arItemIDs["strMainID"];?>">
	<div class="img_wrapper">
		<div class="item_slider">
			<div class="stickers">
				<?if (is_array($arResult["PROPERTIES"]["FLAG"]["VALUE"])):?>
					<?foreach($arResult["PROPERTIES"]["FLAG"]["VALUE"] as $key=>$class){?>
						<div><div class="sticker_<?=strtolower($class);?>"><?=GetMessage("ICON_TEXT_".$class)?></div></div>
					<?}?>
				<?endif;?>
				<?if($arParams["SALE_STIKER"] && $arResult["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
					<div><div class="sticker_sale_text"><?=$arResult["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
				<?}?>
			</div>
			

			<?reset($arResult['MORE_PHOTO']);
			$arFirstPhoto = current($arResult['MORE_PHOTO']);
			$viewImgType=$arParams["DETAIL_PICTURE_MODE"];?>
			<div class="slides">
				<?if($showCustomOffer && !empty($arResult['OFFERS_PROP'])){?>
					<div class="offers_img wof">
                        <?/*$alt=$arFirstPhoto["ALT"];
                        $title=$arFirstPhoto["TITLE"];*/?>
                        <?$alt=$arResult["NAME"];
                        $title=$arResult["NAME"];?>
						<?if($arFirstPhoto["BIG"]["src"]){?>
							<a href="<?=($viewImgType=="POPUP" ? $arFirstPhoto["BIG"]["src"] : "javascript:void(0)");?>" class="<?=($viewImgType=="POPUP" ? "popup_link" : "line_link");?>" title="<?=$title;?>">
								<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SMALL']['src']; ?>" <?=($viewImgType=="MAGNIFIER" ? 'data-large="" xpreview="" xoriginal=""': "");?> alt="<?=$alt;?>" title="<?=$title;?>" itemprop="image">
							</a>
						<?}else{?>
							<a href="javascript:void(0)" class="" title="<?=$title;?>">
								<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SRC']; ?>" alt="<?=$alt;?>" title="<?=$title;?>" itemprop="image">
							</a>
						<?}?>
					</div>
				<?}else{
					if($arResult["MORE_PHOTO"]){
						$bMagnifier = ($viewImgType=="MAGNIFIER");?>
						<ul>
							<?foreach($arResult["MORE_PHOTO"] as $i => $arImage){
								if($i && $bMagnifier):?>
									<?continue;?>
								<?endif;?>
								<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
								<?
								$alt=$arImage["ALT"];
								$title=$arResult["NAME"];
								?>
								<li id="photo-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
									<?if(!$isEmpty){?>
										<a href="<?=($viewImgType=="POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)");?>" <?=($bIsOneImage ? '' : 'data-fancybox-group="item_slider"')?> class="<?=($viewImgType=="POPUP" ? "popup_link fancy" : "line_link");?>" title="<?=$title;?>">
											<img  src="<?=$arImage["SMALL"]["src"]?>" <?=($viewImgType=="MAGNIFIER" ? "class='zoom_picture'" : "");?> <?=($viewImgType=="MAGNIFIER" ? 'xoriginal="'.$arImage["BIG"]["src"].'" xpreview="'.$arImage["THUMB"]["src"].'"' : "");?> alt="<?=$alt;?>" title="<?=$title;?>"<?=(!$i ? ' itemprop="image"' : '')?>/>
										</a>
									<?}else{?>
										<img  src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									<?}?>
								</li>
							<?}?>
						</ul>
					<?}
				}?>
			</div>
			<?/*thumbs*/?>
			<?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])){
				if(count($arResult["MORE_PHOTO"]) > 1):?>
					<div class="wrapp_thumbs xzoom-thumbs">
						<div class="thumbs flexslider" data-plugin-options='{"animation": "slide", "selector": ".slides_block > li", "directionNav": true, "itemMargin":10, "itemWidth": 54, "controlsContainer": ".thumbs_navigation", "controlNav" :false, "animationLoop": true, "slideshow": false}' style="max-width:<?=ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 64) - 10)?>px;">
							<ul class="slides_block" id="thumbs">
								<?foreach($arResult["MORE_PHOTO"]as $i => $arImage):?>
									<li <?=(!$i ? 'class="current"' : '')?> data-big_img="<?=$arImage["BIG"]["src"]?>" data-small_img="<?=$arImage["SMALL"]["src"]?>">
										<span><img class="xzoom-gallery" width="50" xpreview="<?=$arImage["THUMB"]["src"];?>" src="<?=$arImage["THUMB"]["src"]?>" alt="<?=$arImage["ALT"];?>" title="<?=$arImage["TITLE"];?>" /></span>
									</li>
								<?endforeach;?>
							</ul>
							<span class="thumbs_navigation custom_flex"></span>
						</div>
					</div>
					<script>
						$(document).ready(function(){
							$('.item_slider .thumbs li').first().addClass('current');
							$('.item_slider .thumbs').delegate('li:not(.current)', 'click', function(){
								var slider_wrapper = $(this).parents('.item_slider'),
									index = $(this).index();
								$(this).addClass('current').siblings().removeClass('current')
								if(arOptimusOptions['THEME']['DETAIL_PICTURE_MODE'] == 'MAGNIFIER')
								{
									var li = $(this).parents('.item_slider').find('.slides li');
									li.find('img').attr('src', $(this).data('small_img'));
									li.find('img').attr('xoriginal', $(this).data('big_img'));
								}
								else
								{
									slider_wrapper.find('.slides li').removeClass('current').hide();
									slider_wrapper.find('.slides li:eq('+index+')').addClass('current').show();
								}
							});
						})
					</script>
				<?endif;?>
			<?}else{?>
				<div class="wrapp_thumbs">
					<div class="sliders">
						<div class="thumbs" style="">
						</div>
					</div>
				</div>
			<?}?>
		</div>
		<?/*mobile*/?>
		<?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])){?>
			<div class="item_slider flex flexslider" data-plugin-options='{"animation": "slide", "directionNav": false, "controlNav": true, "animationLoop": false, "slideshow": true, "slideshowSpeed": 10000, "animationSpeed": 600}'>
				<ul class="slides">
					<?if($arResult["MORE_PHOTO"]){
						foreach($arResult["MORE_PHOTO"] as $i => $arImage){?>
							<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
							<li id="mphoto-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
								<?
								$alt=$arImage["ALT"];
								$title=$arImage["TITLE"];
								?>
								<?if(!$isEmpty){?>
									<a href="<?=$arImage["BIG"]["src"]?>" data-fancybox-group="item_slider_flex" class="fancy" title="<?=$title;?>" >
										<img src="<?=$arImage["SMALL"]["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									</a>
								<?}else{?>
									<img  src="<?=$arImage["SRC"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
							</li>
						<?}
					}?>
				</ul>
			</div>
		<?}else{?>
			<div class="item_slider flex"></div>
		<?}?>
	</div>
	<div class="prices_item_block scrollbar">
		<div class="middle_info main_item_wrapper">
			<div class="prices_block">
				<?$frame = $this->createFrame()->begin();?>
				<div class="cost prices clearfix">
					<?if( count( $arResult["OFFERS"] ) > 0 ){?>
						<div class="with_matrix" style="display:none;">
							<div class="price price_value_block"><span class="values_wrapper"></span></div>
							<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
								<div class="price discount"></div>
							<?endif;?>
							<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
								<div class="sale_block matrix" style="display:none;">
									<div class="sale_wrapper">
									<div class="value">-<span></span>%</div>
									<div class="text"><span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
									<span class="values_wrapper"></span></div>
									<div class="clearfix"></div></div>
								</div>
							<?}?>
						</div>
						<?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arResult, $item_id, $min_price_id, $arItemIDs);?>
					<?}else{?>
						<?
						$item_id = $arResult["ID"];
						if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX']) // USE_PRICE_COUNT
						{
							if($arResult['PRICE_MATRIX']['COLS'])
							{
								$arCurPriceType = current($arResult['PRICE_MATRIX']['COLS']);
								$arCurPrice = current($arResult['PRICE_MATRIX']['MATRIX'][$arCurPriceType['ID']]);
								$min_price_id = $arCurPriceType['ID'];?>
								<div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
									<meta itemprop="price" content="<?=($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'])?>" />
									<meta itemprop="priceCurrency" content="<?=$arResult['MIN_PRICE']['CURRENCY']?>" />
									<link itemprop="availability" href="http://schema.org/<?=($arResult['PRICE_MATRIX']['AVAILABLE'] == 'Y' ? 'InStock' : 'OutOfStock')?>" />
								</div>
							<?}?>
							<?if($arResult['ITEM_PRICE_MODE'] == 'Q' && count($arResult['PRICE_MATRIX']['ROWS']) > 1):?>
								<?=COptimus::showPriceRangeTop($arResult, $arParams, GetMessage("CATALOG_ECONOMY"));?>
							<?endif;?>
							<?=COptimus::showPriceMatrix($arResult, $arParams, $strMeasure, $arAddToBasketData);?>
						<?	
						}
						else
						{?>
							<?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arResult["PRICES"], $strMeasure, $min_price_id);?>
							<?php
							$userGroups = array(11, 12);
							$arGroups = CUser::GetUserGroup($USER->GetID());
							$result = array_intersect($userGroups, $arGroups);
							if(!empty($result)):?>
								<span class="bonuses-quantity-title"></span>
							<?else:?>
								<?foreach($arResult["PRICES"] as $priceType => $priceValue):?>
									<?$priceVal = $priceValue["VALUE"];?>
								<?endforeach;?>
								<?if($arResult["PROPERTIES"]["_POROGA_NACHISLENIYA_BONUSOV"]["VALUE"] != ''):?>
									<?$bonusPercent = $arResult["PROPERTIES"]["_POROGA_NACHISLENIYA_BONUSOV"]["VALUE"];?>
								<?endif;?>
								<?$totalBonus = round($priceVal * ($bonusPercent / 100));?>
								<div class="bonuses-wrapper bonuses-wrapper-list bonuses-wrapper-detail-page">
									<span class="bonuses-quantity-title">Бонус: </span>
									<span class="bonuses-quantity-desc"><?echo $totalBonus;?></span>
									<div class="bonuses-popup-wrapper bonuses-popup-wrapper-detail-page">
										<div class="bonuses-popup">
											<span class="bonuses-quantity-title">
												Копите бонусы и оплачивайте ими ваши покупки<br />
												1 бонус = 1 рубль<br />
												<a href="http://strlogclub.ru/about/" target="_blank">Узнать больше</a>
											</span>
										</div>
									</div>
								</div>
							<?endif;?>
						<?}?>
					<?}?>
				</div>
				<?if($arParams["SHOW_DISCOUNT_TIME"]=="Y"){?>
					<?$arUserGroups = $USER->GetUserGroupArray();?>
					<?if($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && !$arResult['OFFERS'])):?>
						<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", $min_price_id, SITE_ID);
						$arDiscount=array();
						if($arDiscounts)
							$arDiscount=current($arDiscounts);
						if($arDiscount["ACTIVE_TO"]){?>
							<div class="view_sale_block<?=($arQuantityData["HTML"] ? "": " wq");?>">
								<div class="count_d_block">
									<span class="active_to hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
									<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
									<span class="countdown values"></span>
								</div>
								<?if($arQuantityData["HTML"]):?>
									<div class="quantity_block">
										<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
										<div class="values">
											<span class="item">
												<span class="value" <?=((count( $arResult["OFFERS"] ) > 0 && $arParams["TYPE_SKU"] == 'TYPE_1' && $arResult["OFFERS_PROP"]) ? 'style="opacity:0;"' : '')?>><?=$totalCount;?></span>
												<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
											</span>
										</div>
									</div>
								<?endif;?>
							</div>
						<?}?>
					<?else:?>
						<?if($arResult['JS_OFFERS'])
						{

							foreach($arResult['JS_OFFERS'] as $keyOffer => $arTmpOffer2)
							{
								$active_to = '';
								$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arTmpOffer2['ID'], $arUserGroups, "N", array(), SITE_ID );
								if($arDiscounts)
								{
									foreach($arDiscounts as $arDiscountOffer)
									{
										if($arDiscountOffer['ACTIVE_TO'])
										{
											$active_to = $arDiscountOffer['ACTIVE_TO'];
											break;
										}
									}
								}
								$arResult['JS_OFFERS'][$keyOffer]['DISCOUNT_ACTIVE'] = $active_to;
							}
						}?>
						<div class="view_sale_block" style="display:none;">
							<div class="count_d_block">
									<span class="active_to_<?=$arResult["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
									<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
									<span class="countdown countdown_<?=$arResult["ID"]?> values"></span>
							</div>
							<?if($arQuantityData["HTML"]):?>
								<div class="quantity_block">
									<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
									<div class="values">
										<span class="item">
											<span class="value"><?=$totalCount;?></span>
											<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
										</span>
									</div>
								</div>
							<?endif;?>
						</div>
					<?endif;?>
				<?}?>
				<?$frame->end();?>
			</div>
			<div class="buy_block">
				<?if($arResult["OFFERS"] && $showCustomOffer){?>
					<div class="sku_props">
						<?if (!empty($arResult['OFFERS_PROP'])){?>
							<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
								<?foreach ($arSkuTemplate as $code => $strTemplate){
									if (!isset($arResult['OFFERS_PROP'][$code]))
										continue;
									echo str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate);
								}?>
							</div>
						<?}?>
						<?$arItemJSParams=COptimus::GetSKUJSParams($arResult, $arParams, $arResult, "Y");?>
						<script type="text/javascript">
							var <? echo $arItemIDs["strObName"]; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
						</script>
					</div>
				<?}?>
				<?if(!$arResult["OFFERS"]):?>
					<script>
						$(document).ready(function() {
							$('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
						});
					</script>
					<div class="counter_wrapp">
						<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arResult["CAN_BUY"]):?>
							<div class="counter_block big_basket" data-offers="<?=($arResult["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arResult["ID"];?>" <?=(($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N") ? "style='display: none;'" : "");?>>
								<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
								<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
								<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
							</div>
						<?endif;?>
						<div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arResult["CAN_BUY"]*/) || !$arResult["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>">
							<!--noindex-->
								<?=$arAddToBasketData["HTML"]?>
							<!--/noindex-->
						</div>
					</div>
					<?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
						<?if($arAddToBasketData["ACTION"] == "ADD" && $arResult["CAN_BUY"] && $arParams["SHOW_ONE_CLICK_BUY"]!="N"):?>
							<div class="wrapp_one_click">
								<span class="transparent big_btn type_block button transition_bg one_click" data-item="<?=$arResult["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
									<span><?=GetMessage('ONE_CLICK_BUY')?></span>
								</span>
							</div>
						<?endif;?>
					<?endif;?>

					<?if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX']) // USE_PRICE_COUNT
					{?>
						<?if($arResult['ITEM_PRICE_MODE'] == 'Q' && count($arResult['PRICE_MATRIX']['ROWS']) > 1):?>
							<?$arOnlyItemJSParams = array(
								"ITEM_PRICES" => $arResult["ITEM_PRICES"],
								"ITEM_PRICE_MODE" => $arResult["ITEM_PRICE_MODE"],
								"ITEM_QUANTITY_RANGES" => $arResult["ITEM_QUANTITY_RANGES"],
								"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
								"ID" => $arItemIDs["strMainID"],
							)?>
							<script type="text/javascript">
								var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
							</script>
						<?endif;?>
					<?}?>
				<?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] == 'TYPE_1'):?>
					<div class="offer_buy_block buys_wrapp" style="display:none;">
						<div class="counter_wrapp"></div>
					</div>
				<?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] != 'TYPE_1'):?>
					<span class="big_btn slide_offer button transition_bg type_block"><i></i><span><?=GetMessage("MORE_TEXT_BOTTOM");?></span></span>
				<?endif;?>
				<?if(($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y") || (strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer))):?>
				<div class="clearfix"></div>
				<div class="description_wrapp top_info">
					<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
						<div class="like_icons">
							<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
								<?if(!$arResult["OFFERS"]):?>
									<div class="wish_item_button">
										<span title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>" class="wish_item to" data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>"><i></i><div><?=GetMessage('CT_BCE_CATALOG_IZB')?></div></span>
										<span title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>" class="wish_item in added" style="display: none;" data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>"><i></i><div><?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?></div></span>
									</div>
								<?elseif($arResult["OFFERS"]):?>
									<?foreach($arResult["OFFERS"] as $arOffer):?>
										<?if($arOffer['CAN_BUY']):?>
											<div class="wish_item_button o_<?=$arOffer["ID"];?>" style="display: none;">
												<span title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>" class="wish_item to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arOffer["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i><div><?=GetMessage('CT_BCE_CATALOG_IZB')?></div></span>
												<span title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>" class="wish_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arOffer["ID"]?>" data-iblock="<?=$arOffer["IBLOCK_ID"]?>"><i></i><div><?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?></div></span>
											</div>
										<?endif;?>
									<?endforeach;?>
								<?endif;?>
							<?endif;?>
							<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
								<?if(!$arResult["OFFERS"] || $arParams["TYPE_SKU"] !== 'TYPE_1'):?>
									<div class="compare_item_button">
										<span title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arResult["ID"]?>" ><i></i><div><?=GetMessage('CT_BCE_CATALOG_COMPARE')?></div></span>
										<span title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arResult["ID"]?>"><i></i><div><?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?></div></span>
									</div>
								<?elseif($arResult["OFFERS"]):?>
									<?foreach($arResult["OFFERS"] as $arOffer):?>
										<div class="compare_item_button o_<?=$arOffer["ID"];?>" style="display: none;">
											<span title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>" class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arOffer["ID"]?>" ><i></i><div><?=GetMessage('CT_BCE_CATALOG_COMPARE')?></div></span>
											<span title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>" class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arOffer["ID"]?>"><i></i><div><?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?></div></span>
										</div>
									<?endforeach;?>
								<?endif;?>
							<?endif;?>
						</div>
					<?endif;?>
				</div>
			<?endif;?>
			</div>
		</div>
	</div>
	<div class="right_info">
		<div class="info_item scrollbar">
			<div class="title hidden"><?=$arResult["NAME"]?></div>
			<?$isArticle=(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer));?>
			<?if($isArticle || $arResult["BRAND_ITEM"] || $arParams["SHOW_RATING"] == "Y"){?>
				<div class="top_info">
					<?if($arParams["SHOW_RATING"] == "Y"):?>
							<?$frame = $this->createFrame('dv_'.$arResult["ID"])->begin('');?>
								<div class="rating">
									<?$APPLICATION->IncludeComponent(
									   "bitrix:iblock.vote",
									   "element_rating",
									   Array(
										  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
										  "IBLOCK_ID" => $arResult["IBLOCK_ID"],
										  "ELEMENT_ID" => $arResult["ID"],
										  "MAX_VOTE" => 5,
										  "VOTE_NAMES" => array(),
										  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
										  "CACHE_TIME" => $arParams["CACHE_TIME"],
										  "DISPLAY_AS_RATING" => 'vote_avg'
									   ),
									   $component, array("HIDE_ICONS" =>"Y")
									);?>
								</div>
							<?$frame->end();?>
					<?endif;?>
					<div class="rows_block">
						<?if($useStores){?>
							<div class="item_block">
						<?}?>
							<?=$arQuantityData["HTML"];?>
						<?if($useStores){?>
							</div>
						<?}?>
						<?if($isArticle):?>
							<div class="item_block">
								<div class="article iblock" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue" <?if($arResult['SHOW_OFFERS_PROPS']){?>id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_ARTICLE_DIV'] ?>" style="display: none;"<?}?>>
									<span class="block_title" itemprop="name"><?=GetMessage("ARTICLE");?>:</span>
									<span class="value" itemprop="value"><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span>
								</div>
							</div>
						<?endif;?>

					</div>
					<?if(strlen($arResult["PREVIEW_TEXT"])):?>
					<div class="preview_text dotdot"><p><?=mb_substr(strip_tags($arResult["DETAIL_TEXT"]),0,200);?></p></div>
					<?endif;?>
				</div>
			<?}?>
				<?if(count($arResult["DISPLAY_PROPERTIES"]) > 0):?>
					<div class="iblock char_block <?=(!$arResult["SERVICES"] ? 'wide' : '')?>">
						<h4><?=GetMessage("PROPERTIES_TAB");?></h4>
						<table class="props_list">
							<?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
								<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
									<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
										<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
											<td class="char_name">
												<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><span itemprop="name"><?=$arProp["NAME"]?></span></span>
											</td>
											<td class="char_value">
												<span itemprop="value">
													<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
														<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
													<?else:?>
														<?=$arProp["DISPLAY_VALUE"];?>
													<?endif;?>
												</span>
											</td>
										</tr>
									<?endif;?>
								<?endif;?>
							<?endforeach;?>
						</table>
						<table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
					</div>
				<?endif;?>
		</div>
	</div>
	<?if($arResult['OFFERS']):?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" style="display:none;">
			<?foreach($arResult['OFFERS'] as $arOffer):?>
				<?$currentOffersList = array();?>
				<?foreach($arOffer['TREE'] as $propName => $skuId):?>
					<?$propId = (int)substr($propName, 5);?>
					<?foreach($arResult['SKU_PROPS'] as $prop):?>
						<?if($prop['ID'] == $propId):?>
							<?foreach($prop['VALUES'] as $propId => $propValue):?>
								<?if($propId == $skuId):?>
									<?$currentOffersList[] = $propValue['NAME'];?>
									<?break;?>
								<?endif;?>
							<?endforeach;?>
						<?endif;?>
					<?endforeach;?>
				<?endforeach;?>
				<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<meta itemprop="sku" content="<?=implode('/', $currentOffersList)?>" />
					<a href="<?=$arOffer['DETAIL_PAGE_URL']?>" itemprop="url"></a>
					<meta itemprop="price" content="<?=($arOffer['MIN_PRICE']['DISCOUNT_VALUE']) ? $arOffer['MIN_PRICE']['DISCOUNT_VALUE'] : $arOffer['MIN_PRICE']['VALUE']?>" />
					<meta itemprop="priceCurrency" content="<?=$arOffer['MIN_PRICE']['CURRENCY']?>" />
					<link itemprop="availability" href="http://schema.org/<?=($arOffer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
				</span>
			<?endforeach;?>
		</span>
		<?unset($arOffer, $currentOffersList);?>
	<?else:?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?=($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'])?>" />
			<meta itemprop="priceCurrency" content="<?=$arResult['MIN_PRICE']['CURRENCY']?>" />
			<link itemprop="availability" href="http://schema.org/<?=($arResult['MIN_PRICE']['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
		</span>
	<?endif;?>
	<div class="clearleft"></div>
	
	<?if($arParams["SHOW_KIT_PARTS"] == "Y" && $arResult["SET_ITEMS"]):?>
		<div class="set_wrapp set_block">
			<div class="title"><?=GetMessage("GROUP_PARTS_TITLE")?></div>
			<ul>
				<?foreach($arResult["SET_ITEMS"] as $iii => $arSetItem):?>
					<li class="item">
						<div class="item_inner">
							<div class="image">
								<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>">
									<?if($arSetItem["PREVIEW_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["PREVIEW_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<img  src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?elseif($arSetItem["DETAIL_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["DETAIL_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<img  src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?else:?>
										<img  src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?endif;?>
								</a>
								<?if($arResult["SET_ITEMS_QUANTITY"]):?>
									<div class="quantity">x<?=$arSetItem["QUANTITY"];?></div>
								<?endif;?>
							</div>
							<div class="item_info">
								<div class="item-title">
									<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>"><span><?=$arSetItem["NAME"]?></span></a>
								</div>
								<?if($arParams["SHOW_KIT_PARTS_PRICES"] == "Y"):?>
									<div class="cost prices clearfix">
										<?
										$arCountPricesCanAccess = 0;
										foreach($arSetItem["PRICES"] as $key => $arPrice){
											if($arPrice["CAN_ACCESS"]){
												$arCountPricesCanAccess++;
											}
										}?>
										<?foreach($arSetItem["PRICES"] as $key => $arPrice):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?$price = CPrice::GetByID($arPrice["ID"]);?>
												<?if($arCountPricesCanAccess > 1):?>
													<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
												<?endif;?>
												<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
													<div class="price">
														<?=$arPrice["PRINT_DISCOUNT_VALUE"];?><?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?><small>/<?=$strMeasure?></small><?endif;?>
													</div>
													<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
														<div class="price discount">
															<span><?=$arPrice["PRINT_VALUE"]?></span>
														</div>
													<?endif;?>
												<?else:?>
													<div class="price">
														<?=$arPrice["PRINT_VALUE"];?><?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?><small>/<?=$strMeasure?></small><?endif;?>
													</div>
												<?endif;?>
											<?endif;?>
										<?endforeach;?>
									</div>
								<?endif;?>
							</div>
						</div>
					</li>
					<?if($arResult["SET_ITEMS"][$iii + 1]):?>
						<li class="separator"></li>
					<?endif;?>
				<?endforeach;?>
			</ul>
		</div>
	<?endif;?>
	<?if($arResult['OFFERS']):?>
		<?if($arResult['OFFER_GROUP']):?>
			<?foreach($arResult['OFFERS'] as $arOffer):?>
				<?if(!$arOffer['OFFER_GROUP']) continue;?>
				<span id="<?=$arItemIDs['ALL_ITEM_IDS']['OFFER_GROUP'].$arOffer['ID']?>" style="display: none;">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
						array(
							"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
							"ELEMENT_ID" => $arOffer['ID'],
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"BASKET_URL" => $arParams["BASKET_URL"],
							"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
							"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
							"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
							"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"]
						), $component, array("HIDE_ICONS" => "Y")
					);?>
				</span>
			<?endforeach;?>
		<?endif;?>
	<?else:?>
	
		<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
			array(
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_ID" => $arResult["ID"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
				"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
				"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
				"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"]
			), $component, array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>
</div>
</div>

<script type="text/javascript">
	BX.message({
		QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
		ONE_CLICK_BUY: '<? echo GetMessage("ONE_CLICK_BUY"); ?>',
		SITE_ID: '<? echo SITE_ID; ?>'
	})
</script>

<script type="text/javascript">
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES'];?>);
		</script>
	<script type="text/javascript">
	var viewedCounter = {
		path: '/bitrix/components/bitrix/catalog.element/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: "s1",
			PRODUCT_ID: "3103",
			PARENT_ID: "3103"
		}
	};
	BX.ready(
		BX.defer(function(){
			BX.ajax.post(
				viewedCounter.path,
				viewedCounter.params
			);
		})		
	);
	/*check mobile device*/
	if(jQuery.browser.mobile){
		$('.hint span').remove();

		$('*[data-event="jqm"]').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();
			var _this = $(this);
			var name = _this.data('name');
			if(window.matchMedia('(min-width:992px)').matches)
			{
				if(!$(this).hasClass('clicked'))
				{
					$(this).addClass('clicked');
					$(this).jqmEx();
					$(this).trigger('click');
				}
				return false;
			}
			else if(name.length){
				var script = arNextOptions['SITE_DIR'] + 'form/';
				var paramsStr = ''; var arTriggerAttrs = {};
				$.each(_this.get(0).attributes, function(index, attr){
					var attrName = attr.nodeName;
					var attrValue = _this.attr(attrName);
					arTriggerAttrs[attrName] = attrValue;
					if(/^data\-param\-(.+)$/.test(attrName)){
						var key = attrName.match(/^data\-param\-(.+)$/)[1];
						paramsStr += key + '=' + attrValue + '&';
					}
				});

				var triggerAttrs = JSON.stringify(arTriggerAttrs);
				var encTriggerAttrs = encodeURIComponent(triggerAttrs);
				script += '?name=' + name + '&' + paramsStr + 'data-trigger=' + encTriggerAttrs;
				location.href = script;
			}
		});
	}
</script>