<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?if(($arParams["AJAX_REQUEST"]=="N") || !isset($arParams["AJAX_REQUEST"])){?>
		<div class="top_wrapper rows_block <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>">
			<div class="catalog_block items block_list">
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

		$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
		$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);


		switch ($arParams["LINE_ELEMENT_COUNT"]){
			case '2':
				$col=2;
				break;
			case '3':
				$col=3;
				break;
			default:
				$col=4;
				break;
		}
		$grouperTitleShow = "-0-";
	?>

		<?foreach($arResult["ITEMS"] as $arItem){?>
		<?if(isset($arItem['groupper']) && $arItem['groupper'] && ($grouperTitleShow === "-0-" || $grouperTitleShow != $arItem['groupper']['VALUE'])):?>
			<? $grouperTitleShow = $arItem['groupper']['VALUE']; ?>
				</div><div class="col-12">
				<div class="row-block-prod-title catalog_item item_wrap">
					<div class="prod-title"><?=$arItem['groupper']['TITLE']?>: <span><?if ($arItem['groupper']['SECTION'] !== 'Y') echo $grouperTitleShow?></span></div>
				</div>
					</div>
	<div class="catalog_block items block_list">
		<?endif?>
			<div class="item_block col-<?=$col;?>">
				<div class="catalog_item_wrapp item">
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
					<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

					$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
					$arItemIDs=COptimus::GetItemsIDs($arItem);

					$totalCount = COptimus::GetTotalCount($arItem);
                    $forOrder = in_array('Заказная позиция', $arItem['PROPERTIES']['CML2_TRAITS']['VALUE']);
                    $arQuantityData = COptimus::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], 'N', $forOrder, $arItem['STORES_COUNT'], $arItem['STORES']);

					$item_id = $arItem["ID"];
					$strMeasure = '';
					if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'){
						if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
							$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
							$strMeasure = $arMeasure["SYMBOL_RUS"];
						}
						$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
					}
					elseif($arItem["OFFERS"]){
						$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					}
					$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
                    $elementName = $arItem["NAME"];
					?>
                    <?/*Костыль для показа цены для разных групп пользователей start*/?>
                    <?/*global $USER;?>
                    <?$arGroups = $USER->GetUserGroupArray();?>
                    <?if(in_array("10", $arGroups) || in_array("11", $arGroups) || in_array("12", $arGroups) || in_array("13", $arGroups) || in_array("14", $arGroups)):?>
                        <?unset($arItem["PRICES"]["КП"]);?>
                    <?endif;*/?>
                    <?/*Костыль для показа цены для разных групп пользователей end*/?>

					<div class="catalog_item item_wrap <?=(($_GET['q'])) ? 's' : ''?>" id="<?=$arItemIDs["strMainID"];?> ааа">
						<div>
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
								<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
									<div class="like_icons">
										<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
											<?if(!$arItem["OFFERS"]):?>
												<div class="wish_item_button">
													<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
													<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
												</div>
											<?elseif($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP'])):?>
												<div class="wish_item_button" style="display: none;">
													<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to <?=$arParams["TYPE_SKU"];?>" data-item="" data-iblock="<?=$arItem["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
													<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="" data-iblock="<?=$arOffer["IBLOCK_ID"]?>"><i></i></span>
												</div>
											<?endif;?>
										<?endif;?>
										<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
											<?if(!$arItem["OFFERS"] || ($arParams["TYPE_SKU"] !== 'TYPE_1' || ($arParams["TYPE_SKU"] == 'TYPE_1' && !$arItem["OFFERS_PROP"]))):?>
												<div class="compare_item_button">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
												</div>
											<?elseif($arItem["OFFERS"]):?>
												<div class="compare_item_button">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item=""><i></i></span>
												</div>
											<?endif;?>
										<?endif;?>
									</div>
								<?endif;?>
								<div class="wrapper_fw">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>">
										<?
										$a_alt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] );
										$a_title=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] );
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
									
									<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arItem["IBLOCK_ID"]?>" data-param-id="<?=$arItem["ID"]?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"])?>" data-name="fast_view"><?=GetMessage('FAST_VIEW')?></div>
								</div>
							</div>
							<div class="item_info main_item_wrapper <?=$arParams["TYPE_SKU"]?>">
								<div class="item-title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$elementName//$st=strlen($elementName);$t=mb_substr($elementName,0,43,"UTF-8");echo $t.($st>43?"...":"");?></span></a>
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
                                <?if (!$forOrder):?>
								    <div class="cost prices clearfix">
									<?if( $arItem["OFFERS"]){?>
										<div class="with_matrix <?=($arParams["SHOW_OLD_PRICE"]=="Y" ? 'with_old' : '');?>" style="display:none;">
											<div class="price price_value_block"><span class="values_wrapper"></span></div>
											<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
												<div class="price discount"></div>
											<?endif;?>
											<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
												<div class="sale_block matrix" style="display:none;">
													<div class="sale_wrapper"><div class="value">-<span></span>%</div>
													<div class="text"><span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
													<span class="values_wrapper"></span></div>
													<div class="clearfix"></div></div>
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
											<?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
                                             <?=showProductBonus($arItem)?>
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
																<span class="value" <?=((count( $arItem["OFFERS"] ) > 0 && $arParams["TYPE_SKU"] == 'TYPE_1' && $arItem["OFFERS_PROP"]) ? 'style="opacity:0;"' : '')?>><?=$totalCount;?></span>
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
								<div class="hover_block1 footer_button">
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
									<?if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'):?>
										<div class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?>">
											<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arItem["CAN_BUY"]):?>
												<div class="counter_block" data-offers="<?=($arItem["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arItem["ID"];?>">
													<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
													<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
													<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
												</div>
											<?endif;?>
											<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/)  || !$arItem["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
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
												<?
												$arItem["OFFERS_MORE"] = "Y";
												$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams);?>
												<!--noindex-->
													<?=$arAddToBasketData["HTML"]?>
												<!--/noindex-->
											</div>
										<?}else{?>
											<div class="offer_buy_block buys_wrapp woffers" style="display:none;">
												<div class="counter_wrapp"></div>
											</div>
										<?}?>
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?}?>
	<?if(($arParams["AJAX_REQUEST"]=="N") || !isset($arParams["AJAX_REQUEST"])){?>
			</div>
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
	<div class="no_goods catalog_block_view">
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
	$(document).ready(function(){
		$('.catalog_block .catalog_item_wrapp .catalog_item .item-title').sliceHeight();
		$('.catalog_block .catalog_item_wrapp .catalog_item .cost').sliceHeight();
		$('.catalog_block .catalog_item_wrapp .item_info').sliceHeight({classNull: '.footer_button'});
		$('.catalog_block .catalog_item_wrapp').sliceHeight({classNull: '.footer_button'});
	});

	BX.message({
		QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
	})
</script>