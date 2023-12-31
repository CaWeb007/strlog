<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?if($arParams["AJAX_REQUEST"]=="N"){?>
		<div class="display_list <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>">
	<?}?>
		<?
		$currencyList = '';
		if (!empty($arResult['CURRENCIES'])){
			$templateLibrary[] = 'currency';
			$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
		}
		$templateData = array(
			'TEMPLATE_LIBRARY' => $templateLibrary,
			'CURRENCIES' => $currencyList
		);
		unset($currencyList, $templateLibrary);

		$arParams["BASKET_ITEMS"] = ($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());

		$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
		$grouperTitleShow = "-0-";
		?>
		<?foreach($arResult["ITEMS"] as $arItem){?>
			
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
			$arItemIDs=COptimus::GetItemsIDs($arItem);

			$totalCount = COptimus::GetTotalCount($arItem);
            $forOrder = in_array('Заказная позиция', $arItem['PROPERTIES']['CML2_TRAITS']['VALUE']);
            if (empty($arItem['OFFERS']))
                $arQuantityData = COptimus::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], 'N', $forOrder, $arItem['STORES_COUNT'], $arItem['STORES']);

			$item_id = $arItem["ID"];
			$strMeasure = '';
			if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'){
				if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
					$strMeasure = $arMeasure["SYMBOL_RUS"];
				}
			}
			elseif($arItem["OFFERS"]){
				$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
			}
			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
            $elementName = $arItem["NAME"];
			?>

			<?if(isset($arItem['groupper']) && $arItem['groupper'] && ($grouperTitleShow === "-0-" || $grouperTitleShow != $arItem['groupper']['VALUE'])):?>
                <? $grouperTitleShow = $arItem['groupper']['VALUE']; ?>
                <div class="row-block-prod-title list_item_wrapp item_wrap item">
                    <div class="prod-title"><?=$arItem['groupper']['TITLE']?>: <span><?if ($arItem['groupper']['SECTION'] !== 'Y') echo $grouperTitleShow?></span></div>
                </div>
		    <?endif?>

			<div class="list_item_wrapp item_wrap item">
				<table class="list_item" id="<?=$arItemIDs["strMainID"];?>" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr class="adaptive_name">
						<td colspan="2">
							<div class="desc_name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$elementName?></span></a></div>
						</td>
					</tr>
					<tr style="position: relative">
					<td style="position: relative; z-index: 1" class="image_block">
						<div class="image_wrapper_block">
                            <?if(($totalCount !== 0) && !$forOrder):?>
							    <div class="stickers">
								<?if (is_array($arItem["PROPERTIES"]["FLAG"]["VALUE"])):?>
									<?foreach($arItem["PROPERTIES"]["FLAG"]["VALUE"] as $key=>$class){?>
										<div><div class="sticker_<?=strtolower($class);?>"><?=GetMessage("ICON_TEXT_".$class)?></div></div>
									<?}?>
								<?endif;?>
								<?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
									<div><div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
								<?}?>
							</div>
                            <?endif?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>">
								<?
								$a_alt=($arItem["PREVIEW_PICTURE"]["DESCRIPTION"] ? $arItem["PREVIEW_PICTURE"]["DESCRIPTION"] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] ));
								$a_title=($arItem["PREVIEW_PICTURE"]["DESCRIPTION"] ? $arItem["PREVIEW_PICTURE"]["DESCRIPTION"] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] ));
								?>
								<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
									<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 170, "height" => 170 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
									<img src="<?=$img["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?else:?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?endif;?>
							</a>
							<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arItem["IBLOCK_ID"]?>" data-param-id="<?=$arItem["ID"]?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"])?>" data-name="fast_view">Быстрый просмотр</div>
						</div>
					</td>

					<td style="position: relative; z-index: 3" class="description_wrapp">
						<div class="description">
							<div class="item-title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$elementName;?></span></a>
							</div>
							<div class="wrapp_stockers" id="stock_wrap_<?=$arItem['ID']?>">
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
                                <?if(empty($arItem['OFFERS'])):?>
								    <?=$arQuantityData["HTML"];?>
                                <?else:?>
                                    <?foreach ($arItem['OFFERS'] as $key => $offer):?>
                                        <div id="offer_block_<?=$offer['ID']?>" <?=($key === 0)? "style='display: none'": "style='display: none'"?>>
                                            <?
                                                $totalCount = COptimus::GetTotalCount($offer);
                                                $arQuantityData = COptimus::GetQuantityArray($totalCount, array(), 'N', $forOrder, $offer['STORES_COUNT'], $offer['STORES'],$offer['ID']);
                                                echo $arQuantityData['HTML'];
                                            ?>
                                        </div>
                                    <?endforeach;?>
                                <?endif;?>
								<div class="article_block">
									<?if(isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']){?>
										<?=$arItem['ARTICLE']['NAME'];?>: <?=$arItem['ARTICLE']['VALUE'];?>
									<?}?>
								</div>
							</div>
							<?if ($arItem["PREVIEW_TEXT"]):?> <div class="preview_text"><?=$arItem["PREVIEW_TEXT"]?></div> <?endif;?>
							<?$boolShowOfferProps = ($arItem['OFFERS_PROPS_DISPLAY']);
							$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));?>
							<?if($boolShowProductProps || $boolShowOfferProps):?>
								<div class="props_list_wrapp">
									<table class="props_list prod">
										<?if ($boolShowProductProps){
											foreach( $arItem["DISPLAY_PROPERTIES"] as $arProp ){?>
												<?if( !empty( $arProp["VALUE"] ) ){?>
													<tr>
														<td><span><?=$arProp["NAME"]?></span></td>
														<td>
															<span>
															<?
															if(count($arProp["DISPLAY_VALUE"])>1) { foreach($arProp["DISPLAY_VALUE"] as $key => $value) { if ($arProp["DISPLAY_VALUE"][$key+1]) {echo $value.", ";} else {echo $value;} }}
															else { echo $arProp["DISPLAY_VALUE"]; }
															?>
															</span>
														</td>
													</tr>
												<?}?>
											<?}
										}?>
									</table>
									<?if ($boolShowOfferProps){?>
										<table class="props_list offers" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>" style="display: none;"></table>
									<?}?>
								</div>
								<div class="show_props dark_link">
									<span class="icons_fa char_title"><span><?=GetMessage('PROPERTIES')?></span></span>
								</div>
							<?endif;?>
						</div>
						<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
							<div class="like_icons">
								<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
									<?if(!$arItem["OFFERS"]):?>
										<div class="wish_item_button">
											<span class="wish_item to" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i><div><?=GetMessage('CATALOG_WISH')?></div></span>
											<span class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i><div><?=GetMessage('CATALOG_WISH_OUT')?></div></span>
										</div>
									<?elseif($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP'])):?>
										<div class="wish_item_button" style="display: none;">
											<span class="wish_item to <?=$arParams["TYPE_SKU"];?>" data-item="" data-iblock="<?=$arItem["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i><div><?=GetMessage('CATALOG_WISH')?></div></span>
											<span class="wish_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i><div><?=GetMessage('CATALOG_WISH_OUT')?></div></span>
										</div>
									<?endif;?>
								<?endif;?>
								<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
									<?if(!$arItem["OFFERS"] || ($arParams["TYPE_SKU"] !== 'TYPE_1' || ($arParams["TYPE_SKU"] == 'TYPE_1' && !$arItem["OFFERS_PROP"]))):?>
										<div class="compare_item_button">
											<span class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i><div><?=GetMessage('CATALOG_COMPARE')?></div></span>
											<span class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i><div><?=GetMessage('CATALOG_COMPARE_OUT')?></div></span>
										</div>
									<?elseif($arItem["OFFERS"]):?>
										<div class="compare_item_button">
											<span class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="" ><i></i><div><?=GetMessage('CATALOG_COMPARE')?></div></span>
											<span class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item=""><i></i><div><?=GetMessage('CATALOG_COMPARE_OUT')?></div></span>
										</div>
									<?endif;?>
								<?endif;?>
							</div>
						<?endif;?>
					</td>
					<?$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);?>
					<td style="position: relative; z-index: 2"  class="information_wrapp main_item_wrapper">
						<div class="information">
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
									$item_id = $arItem["ID"];
									if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
									{?>
										<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
											<?=COptimus::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
										<?endif;?>
										<?=COptimus::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?$arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
										$min_price_id=current($arMatrixKey);?>
									<?	
									}
									else
									{
										$arCountPricesCanAccess = 0;
										$min_price_id=0;?>
                                        <?$showPrice = null?>
                                        <?$showPrice = \Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
                                        <?if($showPrice !== false) echo showProductBonus($arItem);?>
									<?}?>
								<?}?>
							</div>
                            <?endif?>
							<?if($arParams["SHOW_DISCOUNT_TIME"]=="Y" && $arParams['SHOW_COUNTER_LIST'] != 'N'){?>
								<?$arUserGroups = $USER->GetUserGroupArray();?>
								<?if($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && !$arItem['OFFERS'])):?>
									<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", $min_price_id, SITE_ID);
									$arDiscount=array();
									if($arDiscounts)
										$arDiscount=current($arDiscounts);
									if($arDiscount["ACTIVE_TO"]){?>
										<div class="view_sale_block <?=($arQuantityData["HTML"] ? '' : 'wq');?>">
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
															<span class="value"><?=$totalCount;?></span>
															<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
														</span>
													</div>
												</div>
											<?endif;?>
										</div>
									<?}?>
								<?else:?>
									<?if($arItem['JS_OFFERS'])
									{
										foreach($arItem['JS_OFFERS'] as $keyOffer => $arTmpOffer2)
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
											$arItem['JS_OFFERS'][$keyOffer]['DISCOUNT_ACTIVE'] = $active_to;
										}
									}?>
									<div class="view_sale_block" style="display:none;">
										<div class="count_d_block">
												<span class="active_to_<?=$arItem["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
												<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
												<span class="countdown countdown_<?=$arItem["ID"]?> values"></span>
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
							<?if($arItem["OFFERS"]){?>
								<?if(!empty($arItem['OFFERS_PROP'])){?>
									<div class="sku_props">
										<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
											<?$arSkuTemplate = array();?>
											<?$arSkuTemplate=COptimus::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"]);?>
											<?foreach ($arSkuTemplate as $code => $strTemplate){
												if (!isset($arItem['OFFERS_PROP'][$code]))
													continue;
												echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
											}?>
										</div>
										<?$arItemJSParams=COptimus::GetSKUJSParams($arResult, $arParams, $arItem);?>
										<script type="text/javascript">
											var <? echo $arItemIDs["strObName"]; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
										</script>
									</div>
								<?}?>
							<?}?>

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

							<?if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'):?>								
								<div class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?>">
									<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arItem["CAN_BUY"]):?>
										<div class="counter_block" data-offers="<?=($arItem["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arItem["ID"];?>">
											<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
											<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
											<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
										</div>
									<?endif;?>
									<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/) || !$arItem["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
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
											"ID" => $arItemIDs["strMainID"],
										)?>
										<script type="text/javascript">
											var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
										</script>
									<?endif;?>
								<?}?>
							<?elseif($arItem["OFFERS"]):?>
								<?if(empty($arItem['OFFERS_PROP'])){?>
									<div class="offer_buy_block buys_wrapp woffers">
										<div class="counter_wrapp">
										<?
										$arItem["OFFERS_MORE"] = "Y";
										$arAddToBasketDataSku = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams);?>
										<!--noindex-->
											<?=$arAddToBasketDataSku["HTML"]?>
										<!--/noindex-->
										</div>
									</div>
								<?}else{?>
									<div class="offer_buy_block buys_wrapp woffers" style="display:none;">
										<div class="counter_wrapp"></div>
									</div>
								<?}?>
							<?endif;?>
						</div>
					</td></tr>
				</table>
			</div>
		<?}?>
	<?if($arParams["AJAX_REQUEST"]=="N"){?>
		</div>
	<?}?>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		<div class="wrap_nav">
	<?}?>
	<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
		<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
	</div>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		</div>
	<?}?>
<?}else{?>
	<script>
		// $(document).ready(function(){
			$('.sort_header').animate({'opacity':'1'}, 500);
		// })
	</script>
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
<?}?>
<script>
	function realWidth(obj){
		var clone = obj.clone();
		clone.css("visibility","hidden");
		$('body').append(clone);
		var width = clone.width()+0;
		clone.remove();
		return width;
	}
	function setPropWidth(){
		if($('table.props_list.offers').length){
			$('table.props_list.offers').each(function(){
				$(this).width(realWidth($(this).closest('.props_list_wrapp').find("table.props_list.prod")));
			});
		}
	}
	setPropWidth();
	BX.message({
		QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
	})
</script>
