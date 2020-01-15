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
			<?if(empty($arItem['CATALOG_PRICE_11'])) continue;?>
            <?
                $my = array();
                $my['Детальная катинка'] = (!empty($arItem["~DETAIL_PICTURE"]))? 'да': 'нет';
                $my['Детальное описание'] = (!empty($arItem["DETAIL_TEXT"]))? 'да': 'нет';
                if ($arItem["DETAIL_TEXT"] === $arItem ["NAME"]) $my['Детальное описание'] = 'Название';
            ?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
			$arItemIDs=COptimus::GetItemsIDs($arItem);

			$totalCount = COptimus::GetTotalCount($arItem);
			$arQuantityData = COptimus::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"]);

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
				<div class="prod-title"><?=$arItem['groupper']['TITLE']?>: <span><?=$grouperTitleShow?></span></div>
			</div>
		<?endif?>

			<div class="list_item_wrapp item_wrap item">
				<table class="list_item" id="<?=$arItemIDs["strMainID"];?>" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr class="adaptive_name">
						<td colspan="2">
							<div class="desc_name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$elementName?></span></a></div>
						</td>
					</tr>
					<tr>
					<td class="image_block">
						<div class="image_wrapper_block">

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
						</div>
					</td>

					<td class="description_wrapp">
						<div class="description">

							<?$boolShowOfferProps = ($arItem['OFFERS_PROPS_DISPLAY']);
							$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));?>
								<div class="props_list_wrapp">
									<table class="props_list prod">
										<?
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
										?>
                                        <?foreach ($my as $name => $val):?>
                                            <tr>
                                                <td><span><?=$name?></span></td>
                                                <td><span><?=$val?></span></td>
                                            </tr>
                                        <?endforeach?>
									</table>
									<?if ($boolShowOfferProps){?>
										<table class="props_list offers" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>" style="display: none;"></table>
									<?}?>
								</div>

						</div>
					</td>
					<?$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);?>
				</table>
			</div>
        <hr>
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
					<?=GetMessage('NO_PRODUCTS');?>
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
