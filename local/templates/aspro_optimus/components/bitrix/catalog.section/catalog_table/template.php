<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<style>
    .catalogItemsCount{
        display: none;
    }
</style>
<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());?>
	<?if($arParams["AJAX_REQUEST"]=="N"){?>
	<table class="module_products_list">
		<tbody>
	<?}?>
		<?$currencyList = '';
		if (!empty($arResult['CURRENCIES'])){
			$templateLibrary[] = 'currency';
			$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
		}
		$templateData = array(
			'TEMPLATE_LIBRARY' => $templateLibrary,
			'CURRENCIES' => $currencyList
		);
		unset($currencyList, $templateLibrary);
		$grouperTitleShow = "-0-";
		?>
			<?$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);?>
			<?foreach($arResult["ITEMS"]  as $arItem){				
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				$totalCount = COptimus::GetTotalCount($arItem);
                $forOrder = in_array('Заказная позиция', $arItem['PROPERTIES']['CML2_TRAITS']['VALUE']);
                $arQuantityData = COptimus::GetQuantityArray($totalCount, array(), 'N', $forOrder, $arItem['STORES_COUNT'], $arItem['STORES']);
				$strMeasure = '';
				if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] === 'TYPE_2'){
					if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
						$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure = $arMeasure["SYMBOL_RUS"];
					}
					$arItem["OFFERS_MORE"]="Y";
				}
				elseif($arItem["OFFERS"]){
					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					$arItem["OFFERS_MORE"]="Y";
				}
				//$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
				$elementName = $arItem["NAME"];
                ?>
			<?if(isset($arItem['groupper']) && $arItem['groupper'] && ($grouperTitleShow === "-0-" || $grouperTitleShow != $arItem['groupper']['VALUE'])):?>
                <? $grouperTitleShow = $arItem['groupper']['VALUE']; ?>
                <tr class="row-block-prod-title">
                    <td colspan="5"><div class="prod-title"><?=$arItem['groupper']['TITLE']?>: <h2 style="margin:0px;font-size:20px;display: inline;"><?if ($arItem['groupper']['SECTION'] !== 'Y') echo $grouperTitleShow?></h2></div></td>
                </tr>
		    <?endif?>
				<tr class="item main_item_wrapper" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<td class="foto-cell" style="position: relative;">
                        <style>
                            .sticker_hit::before,
                            .sticker_main::before,
                            .sticker_recommend::before{
                                background: none !important;
                            }
                        </style>
						<div class="image_wrapper_block">
							<?
							$a_alt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] );
							$a_title=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] );
							?>
							<?if( !empty($arItem["DETAIL_PICTURE"]) || !empty($arItem["PREVIEW_PICTURE"]) ){?>
								<?
								$picture=($arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"] : $arItem["DETAIL_PICTURE"]);
								$img_preview = CFile::ResizeImageGet( $picture, array( "width" => 50, "height" => 50 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);?>
								<?if ($arParams["LIST_DISPLAY_POPUP_IMAGE"]=="Y"){?>
									<a class="popup_image fancy" href="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" title="<?=$a_title;?>">
								<?}?>
									<img src="<?=$img_preview["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?if ($arParams["LIST_DISPLAY_POPUP_IMAGE"]=="Y"){?>
									</a>
								<?}?>
							<?}else{?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
							<?}?>
							<div class="fast_view_block icons" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arItem["IBLOCK_ID"]?>" data-param-id="<?=$arItem["ID"]?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"])?>" data-name="fast_view">Быстрый просмотр</div>
						</div>
					</td>
                    <td class="rating-cell">
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
                        <div class="wrapp_stockers">
                            <div class="like_icons">
                                <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
                                    <?if(!$arItem["OFFERS"]):?>
                                        <div class="wish_item_button">
                                            <span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
                                            <span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
                                        </div>
                                    <?elseif($arItem["OFFERS"]):?>
                                        <?foreach($arItem["OFFERS"] as $arOffer):?>
                                            <?if($arOffer['CAN_BUY']):?>
                                                <div class="wish_item_button o_<?=$arOffer["ID"];?>" style="display: none;">
                                                    <span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arOffer["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
                                                    <span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arOffer["ID"]?>" data-iblock="<?=$arOffer["IBLOCK_ID"]?>"><i></i></span>
                                                </div>
                                            <?endif;?>
                                        <?endforeach;?>
                                    <?endif;?>
                                <?endif;?>
                                <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                    <?if(!$arItem["OFFERS"] || $arParams["TYPE_SKU"] !== 'TYPE_1'):?>
                                        <div class="compare_item_button">
                                            <span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
                                            <span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
                                        </div>
                                    <?elseif($arItem["OFFERS"]):?>
                                        <?foreach($arItem["OFFERS"] as $arOffer):?>
                                            <div class="compare_item_button o_<?=$arOffer["ID"];?>" style="display: none;">
                                                <span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arOffer["ID"]?>" ><i></i></span>
                                                <span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arOffer["ID"]?>"><i></i></span>
                                            </div>
                                        <?endforeach;?>
                                    <?endif;?>
                                <?endif;?>
                            </div>
                        </div>
                    </td>
					<td class="item-name-cell">
						<div class="title"><a title="<?=$elementName?>" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$elementName?></a></div>
                        <?if(($totalCount !== 0) && !$forOrder):?>
                            <div class="stickers-wrapper">
                                <?if (is_array($arItem["PROPERTIES"]["FLAG"]["VALUE"])):?>
                                    <?foreach($arItem["PROPERTIES"]["FLAG"]["VALUE"] as $key=>$class){?>
                                        <div class="table-stickers sticker_<?=strtolower($class);?>"><?=GetMessage('ICON_TEXT_'.$class)?></div>
                                    <?}?>
                                <?endif;?>
                                <?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
                                    <div class="table-stickers sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div>
                                <?}?>
                            </div>
                        <?endif?>
                        <div class="preview_text">
							<?if(0 < count($arItem["DISPLAY_PROPERTIES"])):
								$rEcho="";?>
								<?foreach($arItem["DISPLAY_PROPERTIES"] as $dprop):?>
									<? $rEcho .= ($rEcho?", ":"").$dprop['NAME'].': '.$dprop['DISPLAY_VALUE'];?>
								<?endforeach?>
								<?if($rEcho):?>[<?=$rEcho?>]<?endif;?>
                            <?endif;?>
                        </div>
					</td>
					<?$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'small', $arParams);?>
					<td class="price-cell">
                        <?if(!$forOrder):?>
						    <div class="cost prices clearfix">
							<?if( count( $arItem["OFFERS"] ) > 0 ){?>
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
												<div class="clearfix"></div>
											</div>
										</div>
									<?}?>
								</div>
								<?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, $arItemIDs);?>
								<?=showProductBonus($arItem)?>
							<?}else{?>
								<?
								if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
								{?>
									<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
										<?=COptimus::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
									<?endif;?>
									<?=COptimus::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
								<?
								}
								else
								{?>
                                    <?$showPrice = null?>
									<?$showPrice = \Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
                                    <?if($showPrice !== false) echo showProductBonus($arItem);?>
								<?}?>
							<?}?>
							<div class="counter_wrapp"></div>
						</div>
                        <?endif;?>
						<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>" style="display: none;">
							<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])){
								foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
									<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
									<?if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
										unset($arItem['PRODUCT_PROPERTIES'][$propID]);
								}
							}
							$arItem["EMPTY_PROPS_JS"]="Y";
							$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
							if (!$emptyProductProperties){
								$arItem["EMPTY_PROPS_JS"]="N";?>
								<div class="wrapper">
									<table>
										<?foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
											<tr>
												<td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
												<td>
													<?if('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']	&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']){
														foreach($propInfo['VALUES'] as $valueID => $value){?>
															<label>
																<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
															</label>
														<?}
													}else{?>
														<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
															foreach($propInfo['VALUES'] as $valueID => $value){?>
																<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
															<?}?>
														</select>
													<?}?>
												</td>
											</tr>
										<?}?>
									</table>
								</div>
								<?
							}?>
						</div>						
						<div class="adaptive_button_buy">
							<!--noindex-->
								<?=$arAddToBasketData["HTML"]?>
							<!--/noindex-->
						</div>
					</td>
					<td class="but-cell item_<?=$arItem["ID"]?>">
						<div class="counter_wrapp">
							<?if($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && !count($arItem["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD" && $arItem["CAN_BUY"]):?>
								<div class="counter_block" data-item="<?=$arItem["ID"];?>" <?=(in_array($arItem["ID"], $arParams["BASKET_ITEMS"]) ? "style='display: none;'" : "");?>>
									<span class="minus">-</span>
									<input type="text" class="text" name="quantity" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
									<span class="plus" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
								</div>
							<?endif;?>
							<div class="button_block <?=(in_array($arItem["ID"], $arParams["BASKET_ITEMS"])  || $arAddToBasketData["ACTION"] == "ORDER" || !$arItem["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] ? "wide" : "");?>">
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
								<!--/noindex-->
							</div>
						</div>
						<?
						if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
						{?>
							<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
								<?$arOnlyItemJSParams = array(
									"ITEM_PRICES" => $arItem["ITEM_PRICES"],
									"ITEM_PRICE_MODE" => $arItem["ITEM_PRICE_MODE"],
									"ITEM_QUANTITY_RANGES" => $arItem["ITEM_QUANTITY_RANGES"],
									"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
									"ID" => $this->GetEditAreaId($arItem["ID"]),
								)?>
								<script type="text/javascript">
									var ob<? echo $this->GetEditAreaId($arItem["ID"]); ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
								</script>
							<?endif;?>
						<?}?>
<?=$arQuantityData["HTML"];?>
					</td>
				</tr>
			<?}?>
	<?if($arParams["AJAX_REQUEST"]=="N"){?>
		</tbody>
	</table>
		<script>
			$(document).ready(function(){
				$('.sort_header').fadeIn();
			})
		</script>
	<?}?>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		<div class="wrap_nav">
		<tr <?=($arResult["NavPageCount"]>1 ? "" : "style='display: none;'");?>><td>
	<?}?>

		<div>
		<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($arParams["AJAX_REQUEST"]=="Y"  && $arResult["NavPageCount"]<=1 ? "style='display: none; '" : "");?>>
			<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
		</div>
		</div>

	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		</td></tr>
		</div>
	<?}?>
	<script type="text/javascript">
		$('.module_products_list').removeClass('errors');
	</script>
<?}else{?>
	<?if($arParams["AJAX_REQUEST"]!="Y"){?>
		<table class="module_products_list errors">
		<tbody>
		<tr><td>
	<?}?>
		<script type="text/javascript">
			$('.module_products_list').addClass('errors');
		</script>
		<div class="module_products_list_b">
			<div class="no_goods">
				<div class="no_products">
					<div class="wrap_text_empty">
						<?if($_REQUEST["set_filter"]){?>
							<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products_filter.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
						<?}else{?>
							<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
						<?}?>
					</div>
				</div>
				<?if($_REQUEST["set_filter"]){?>
					<span class="button wide"><?=GetMessage('RESET_FILTERS');?></span>
				<?}?>
			</div>
		</div>
		<?if($arParams["AJAX_REQUEST"]!="Y"){?>
		</td></tr>
		</tbody>
		</table>
	<?}?>
<?}?>