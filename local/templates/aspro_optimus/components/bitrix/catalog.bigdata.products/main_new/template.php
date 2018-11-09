<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
$templateData = array(
	//'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);
$injectId = $arParams['UNIQ_COMPONENT_ID'];

if (isset($arResult['REQUEST_ITEMS']))
{
	// code to receive recommendations from the cloud
	CJSCore::Init(array('ajax'));

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');

	?>

	<span id="<?=$injectId?>"></span>

	<script type="text/javascript">
		BX.ready(function(){
			bx_rcm_get_from_cloud(
				'<?=CUtil::JSEscape($injectId)?>',
				<?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
				{
					'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
					'template': '<?=CUtil::JSEscape($signedTemplate)?>',
					'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
					'rcm': 'yes'
				}
			);
		});
	</script>
	<?
	$frame->end();
	return;

	// \ end of the code to receive recommendations from the cloud
}
if($arResult['ITEMS']){?>
	<?$arResult['RID'] = ($arResult['RID'] ? $arResult['RID'] : (\Bitrix\Main\Context::getCurrent()->getRequest()->get('RID') != 'undefined' ? \Bitrix\Main\Context::getCurrent()->getRequest()->get('RID') : '' ));?>
	<input type="hidden" name="bigdata_recommendation_id" value="<?=htmlspecialcharsbx($arResult['RID'])?>">
	<span id="<?=$injectId?>_items" class="bigdata_recommended_products_items">
		<ul class="tabs_slider RECOMENDATION_slides wr">
			<?foreach ($arResult['ITEMS'] as $key => $arItem){?>
				<?$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);?>
				<li class="catalog_item" id="<?=$strMainID;?>">
					<?$strTitle = (
						isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
						? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
						: $arItem['NAME']
					);
					$totalCount = COptimus::GetTotalCount($arItem);
					$arQuantityData = COptimus::GetQuantityArray($totalCount);
					$arItem["FRONT_CATALOG"]="Y";
					$arItem["RID"]=$arResult["RID"];
					$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);
					
					
					$strMeasure='';
					if($arItem["OFFERS"]){
						$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					}else{
						if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
							$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
							$strMeasure=$arMeasure["SYMBOL_RUS"];
						}
					}
					?>
				
					<div class="image_wrapper_block">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?><?=($arResult["RID"] ? '?RID='.$arResult["RID"] : '')?>" class="thumb">
							<?if($arItem["PROPERTIES"]["FLAG"]["VALUE"]){?>
								<div class="stickers">
									<?if (is_array($arItem["PROPERTIES"]["FLAG"]["VALUE"])):?>
										<?foreach($arItem["PROPERTIES"]["FLAG"]["VALUE"] as $key=>$class){?>
											<div><div class="sticker_<?=strtolower($class);?>"><?=GetMessage('ICON_TEXT_'.$class)?></div></div>
										<?}?>
									<?endif;?>
									<?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
										<div><div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
									<?}?>
								</div>
							<?}?>
							<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
								<div class="like_icons">
									<?if($arItem["CAN_BUY"] && empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
										<div class="wish_item_button">
											<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>"><i></i></span>
											<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>"><i></i></span>
										</div>
									<?endif;?>
									<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
										<div class="compare_item_button">
											<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
											<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
							<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
								<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
							<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
								<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true );?>
								<img border="0" src="<?=$img["src"]?>" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
							<?else:?>
								<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
							<?endif;?>
						</a>
									<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arItem["IBLOCK_ID"]?>" data-param-id="<?=$arItem["ID"]?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"])?>" data-name="fast_view"><?=GetMessage('FAST_VIEW')?></div>
					</div>
					<div class="item_info">
						<div class="item-title">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?><?=($arResult["RID"] ? '?RID='.$arResult["RID"] : '')?>"><span><?=$arItem["NAME"]?></span></a>
						</div>
						<?if($arParams["SHOW_RATING"] == "Y"):?>
							<div class="rating">
								<?$APPLICATION->IncludeComponent(
								   "bitrix:iblock.vote",
								   "element_rating_front",
								   Array(
									  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									  "IBLOCK_ID" => $arItem["IBLOCK_ID"],
									  "ELEMENT_ID" =>$arItem["ID"],
									  "MAX_VOTE" => 5,
									  "VOTE_NAMES" => array(),
									  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
									  "CACHE_TIME" => $arParams["CACHE_TIME"],
									  "DISPLAY_AS_RATING" => 'vote_avg'
								   ),
								   $component, array("HIDE_ICONS" =>"Y")
								);?>
							</div>
						<?endif;?>
						<?=$arQuantityData["HTML"];?>
						<div class="cost prices clearfix">
							<?if($arItem["OFFERS"]):?>
								<?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id);?>
							<?elseif($arItem["PRICES"]):?>
								<?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
								<?=showProductBonus($arItem)?>
							<?endif;?>
						</div>
						<div class="buttons_block clearfix">
							<?=$arAddToBasketData["HTML"]?>
						</div>
					</div>
				</li>
			<?}?>
		</ul>
	</span>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tabs li[data-code="RECOMENDATION"]').show();
			
			var flexsliderItemWidth = 220;
			var flexsliderItemMargin = 12;
			
			//var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
			$('.tabs_content > li.RECOMENDATION_wrapp ').flexslider({
				animation: 'slide',
				selector: '.tabs_slider .catalog_item',
				slideshow: false,
				animationSpeed: 600,
				directionNav: true,
				controlNav: false,
				pauseOnHover: true,
				animationLoop: true, 
				itemWidth: flexsliderItemWidth,
				itemMargin: flexsliderItemMargin, 
				//minItems: flexsliderMinItems,
				controlsContainer: '.tabs_slider_navigation.RECOMENDATION_nav',
				start: function(slider){
					slider.find('li').css('opacity', 1);
				}
			});

			setHeightBlockSlider();
		})
	</script>
<?}else{?>
	<script type="text/javascript">
		$('.tabs li[data-code="RECOMENDATION"]').remove();
	</script>
<?}
$frame->end();?>