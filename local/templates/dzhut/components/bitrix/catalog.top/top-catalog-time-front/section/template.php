<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION, $arrProductId;
?>
<div class="bx_catalog_top_home col<? echo $arParams['LINE_ELEMENT_COUNT']; ?> <? echo $templateData['TEMPLATE_CLASS']; ?>">
<?
//==== USER BASKET
//edebug($arResult['ITEMS']);
foreach ($arResult['ITEMS'] as $key => $arItem)
{


	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
	$strMainID = $this->GetEditAreaId($arItem['ID']);

	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => $strMainID.'_pict',
		'SECOND_PICT' => $strMainID.'_secondpict',
		'MAIN_PROPS' => $strMainID.'_main_props',

		'QUANTITY' => $strMainID.'_quantity',
		'QUANTITY_DOWN' => $strMainID.'_quant_down',
		'QUANTITY_UP' => $strMainID.'_quant_up',
		'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
		'BUY_LINK' => $strMainID.'_buy_link',
		'BASKET_ACTIONS' => $strMainID.'_basket_actions',
		'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
		'COMPARE_LINK' => $strMainID.'_compare_link',

		'PRICE' => $strMainID.'_price',
		'DSC_PERC' => $strMainID.'_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
		'BASKET_PROP_DIV' => $strMainID.'_basket_prop'
	);

	$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
	$productTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
		? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
		: $arItem['NAME']
	);
	$imgTitle = (
		isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
		? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
		: $arItem['NAME']
	);

	$minPrice = false;
	if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
		$minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
?>
	<div class="<? echo ($arItem['SECOND_PICT'] ? 'bx_catalog_item double css_popup' : 'bx_catalog_item'); ?>">
	<div class="bx_catalog_item_container" id="<? echo $strMainID; ?>">

	<a id="<? echo $arItemIDs['PICT']; ?>" href="" class="bx_catalog_item_images" style="background-image: url('<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>')" title="<? echo $imgTitle; ?>">
<?
	if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
	{
?>
			<div id="<? echo $arItemIDs['DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
<?
	}
	if ($arItem['LABEL'])
	{
?>
			<div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
<?
	}
?>
	</a>
	<?if('Y' == $arItem['PROPERTIES']['BEST_PRICE']['VALUE']){?>
		<div class="best_price">
			<?=GetMessage('BEST_PRICE_TEXT')?>
		</div>
	<?}?>

<?
$FormatTitle = explode(" ",$productTitle);
?>
		<div class="bx_catalog_item_title"><a href="" title="<? echo $productTitle; ?>"><? echo $FormatTitle[0] . "</br>" . "<span class='second-part'>" . $FormatTitle[1] . " " . $FormatTitle[2] . "</span>"; ?></a></div>

				<?$APPLICATION->IncludeComponent("altasib:review.rating", "raiting_stars", Array(
					"ALLOW_SET" => "N",	// Разрешать выставлять оценку
						"CACHE_TIME" => "86400",	// Время кеширования (сек.)
						"CACHE_TYPE" => "A",	// Тип кеширования
						"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
						"ELEMENT_CODE" => $arItem["CODE"],	// Код элемента
						"ELEMENT_ID" => $arItem["ID"],	// ID элемента
						"IBLOCK_ID" => "17",	// Инфо-блок
						"IBLOCK_TYPE" => "1c_catalog",	// Тип инфо-блока
						"IS_SEF" => "N",	// Включить режим совместимости с поддержкой ЧПУ
						"SECTION_PAGE_URL" => "#SECTION_CODE#/",
						"SEF_BASE_URL" => "#SITE_DIR#/catalog/",
						"SHOW_TITLE" => "N",	// Отображать текст перед рейтингом
						"TITLE_TEXT" => "",
						"COMPONENT_TEMPLATE" => ".default"
					),
					false
				);?>

		<div class="anons">
			<?=$arItem['PREVIEW_TEXT']?>
		</div>
		<div class="bx_catalog_item_price">
		<div id="<? echo $arItemIDs['PRICE']; ?>" class="bx_price">
<?
	if (!empty($minPrice))
	{
		if ('N' == $arParams['PRODUCT_DISPLAY_MODE'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
		{
			echo GetMessage(
				'CT_BCT_TPL_MESS_PRICE_SIMPLE_MODE',
				array(
					'#PRICE#' => $minPrice['PRINT_DISCOUNT_VALUE'],
					'#MEASURE#' => GetMessage(
						'CT_BCT_TPL_MESS_MEASURE_SIMPLE_MODE',
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
			echo "<span class='ot-price'>от </span>" . $minPrice['PRINT_DISCOUNT_VALUE'];
		}
		if ('Y' == $arParams['SHOW_OLD_PRICE'] && $minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE'])
		{
			?> <span><? echo $minPrice['PRINT_VALUE']; ?></span>
			<?
		}
	}
	unset($minPrice);
?>
		</div>
		</div>
<?
	$showSubscribeBtn = false;
	$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCT_TPL_MESS_BTN_COMPARE'));
	if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
	{
?>
		<div class="bx_catalog_item_controls">
<?
		if ($arItem['CAN_BUY'])
		{
			if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
			{
			?>
			<div class="bx_catalog_item_controls_blockone"><div style="display: inline-block;position: relative;">
				<a id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">-</a>
					<input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
				<a id="<? echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">+</a>
				<span id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo $arItem['CATALOG_MEASURE_NAME']; ?></span>
			</div></div>
<?
			}
?>
			<div id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" class="bx_catalog_item_controls_blocktwo">
			
			<a onclick="yaCounter36570340.reachGoal('click2'); return true;" class="bx_bt_button bx_medium css_popup 
			<?
				if($InBasketComplect == $arItem['ID'])	echo 'success-btn'
			?>" href="" rel="nofollow">
			<?
				
					echo GetMessage('ZAKAZ');
				
			?>
			</a>
				
				
			</div>

			
<?
			if ($arParams['DISPLAY_COMPARE'])
			{
?>
				<div class="bx_catalog_item_controls_blocktwo">
				<a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><? echo $compareBtnMessage; ?></a>
				</div>
<?
			}
		}
		else
		{
?>
			<div id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_catalog_item_controls_blockone"><span class="bx_notavailable">
<?
			echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCT_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
?>
			</span></div>
<?
			if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn)
			{
				?>
				<div class="bx_catalog_item_controls_blocktwo"><?
				if ($arParams['DISPLAY_COMPARE'])
				{
					?><a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><? echo $compareBtnMessage; ?></a><?
				}
				if ($showSubscribeBtn)
				{
					?>
					<a id="<? echo $arItemIDs['SUBSCRIBE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?
					echo ('' != $arParams['MESS_BTN_SUBSCRIBE'] ? $arParams['MESS_BTN_SUBSCRIBE'] : GetMessage('CT_BCT_TPL_MESS_BTN_SUBSCRIBE'));
					?></a><?
				}
				?>
				</div><?
			}
		}
?>
			<div style="clear: both;"></div></div>
			
			
			
<?
		if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
		{
?>
			<div class="bx_catalog_item_articul">
<?
			foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
			{
				?><br><strong><? echo $arOneProp['NAME']; ?></strong> <?
					echo (
						is_array($arOneProp['DISPLAY_VALUE'])
						? implode('<br>', $arOneProp['DISPLAY_VALUE'])
						: $arOneProp['DISPLAY_VALUE']
					);
			}
?>
			</div>
<?
		}
		$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
		if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
		{
?>
		<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
			<?
			if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
				{
					?>
					<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"><?
					if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
						unset($arItem['PRODUCT_PROPERTIES'][$propID]);
				}
			}
		
		
			?>
		</div>
	<?}?>

		
		<?
	}
	else
	{
		if ('Y' == $arParams['PRODUCT_DISPLAY_MODE'])
		{
			$canBuy = $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['CAN_BUY'];
			?>
		<div class="bx_catalog_item_controls no_touch">
			<?
			if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
			{
			?>
		<div class="bx_catalog_item_controls_blockone">
			<a id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">-</a>
			<input type="text" class="bx_col_input" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
			<a id="<? echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">+</a>
			<span id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"></span>
		</div>
			<?
			}
			?>
		<div id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_catalog_item_controls_blockone" style="display: <? echo ($canBuy ? 'none' : ''); ?>;"><span class="bx_notavailable">
<?
			echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCT_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
?>
		</span></div>
		<div id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" class="bx_catalog_item_controls_blocktwo" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
			<a id="<? echo $arItemIDs['BUY_LINK']; ?>" class="bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow"><?
			if ($arParams['ADD_TO_BASKET_ACTION'] == 'BUY')
			{
				echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCT_TPL_MESS_BTN_BUY'));
			}
			else
			{
				echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCT_TPL_MESS_BTN_ADD_TO_BASKET'));
			}
			?></a>
		</div>
<?
if ($arParams['DISPLAY_COMPARE'])
{
	?>
	<div class="bx_catalog_item_controls_blocktwo">
	<a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><? echo $compareBtnMessage; ?></a>
	</div><?
}
?>
				<div style="clear: both;"></div>
			</div>
			<?
			unset($canBuy);
		}
		else
		{
			?>
		<div class="bx_catalog_item_controls no_touch">
			<a class="bx_bt_button_type_2 bx_medium" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"><?
			echo ('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCT_TPL_MESS_BTN_DETAIL'));
			?></a>
		</div>
			<?
		}
		?>
		<div class="bx_catalog_item_controls touch">
			<a class="bx_bt_button_type_2 bx_medium" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"><?
			echo ('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCT_TPL_MESS_BTN_DETAIL'));
			?></a>
		</div>
		<?
		$boolShowOfferProps = ('Y' == $arParams['PRODUCT_DISPLAY_MODE'] && $arItem['OFFERS_PROPS_DISPLAY']);
		$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));
		if ($boolShowProductProps || $boolShowOfferProps)
		{
?>
			<div class="bx_catalog_item_articul">
<?
			if ($boolShowProductProps)
			{
				foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
				{
				?><br><strong><? echo $arOneProp['NAME']; ?></strong> <?
					echo (
						is_array($arOneProp['DISPLAY_VALUE'])
						? implode(' / ', $arOneProp['DISPLAY_VALUE'])
						: $arOneProp['DISPLAY_VALUE']
					);
				}
			}
			if ($boolShowOfferProps)
			{
?>
				<span id="<? echo $arItemIDs['DISPLAY_PROP_DIV']; ?>" style="display: none;"></span>
<?
			}
?>
			</div>
<?
		}
		if ('Y' == $arParams['PRODUCT_DISPLAY_MODE'])
		{
			if (!empty($arItem['OFFERS_PROP']))
			{
				$arSkuProps = array();
				?><div class="bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>"><?
				foreach ($arSkuTemplate as $code => $strTemplate)
				{
					if (!isset($arItem['OFFERS_PROP'][$code]))
						continue;
					echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate), '</div>';
				}
				foreach ($arResult['SKU_PROPS'] as $arOneProp)
				{
					if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
						continue;
					$arSkuProps[] = array(
						'ID' => $arOneProp['ID'],
						'SHOW_MODE' => $arOneProp['SHOW_MODE'],
						'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
					);
				}
				foreach ($arItem['JS_OFFERS'] as &$arOneJs)
				{
					if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
					{
						$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
						$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
					}
				}
				unset($arOneJs);
				?></div><?
				if ($arItem['OFFERS_PROPS_DISPLAY'])
				{
					foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer)
					{
						$strProps = '';
						if (!empty($arJSOffer['DISPLAY_PROPERTIES']))
						{
							foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp)
							{
								$strProps .= '<br>'.$arOneProp['NAME'].' <strong>'.(
									is_array($arOneProp['VALUE'])
									? implode(' / ', $arOneProp['VALUE'])
									: $arOneProp['VALUE']
								).'</strong>';
							}
						}
						$arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
					}
				}
				
		
				?>

				<?
			}
		}
		else
		{
			
?>

<?
		}
	}
?></div></div><?
}
?><div style="clear: both;"></div>
</div>
<div id="hideBlock" class="zagluzhka" style="display:none;">
 <p> Раздел находится в разработке.</br>
  Чтобы уточнить состав комплекта и сделать заказ</br>
позвоните, пожалуйста, нам по телефону <span style="color:#6daa48">(3952)</span> <span style="color:#f26541">260-900</span></p>
</div>




<script>
$('.bx_catalog_item_container a, .bx_catalog_item_title').on('click',function(e){
	e.preventDefault();
})
   window.BXDEBUG = true;

   BX.ready(function(){
   var oPopup = new BX.PopupWindow('call_feedback', window.body, {
      autoHide : true,
      offsetTop : 1,
      offsetLeft : 0,
      lightShadow : true,
      closeIcon : true,
      closeByEsc : true,
      overlay: {
         backgroundColor: 'gray', opacity: '80'
      }
   });
   oPopup.setContent(BX('hideBlock'));
   BX.bindDelegate(
      document.body, 'click', {className: 'css_popup' },
         BX.proxy(function(e){
            if(!e)
               e = window.event;
            oPopup.show();
            return BX.PreventDefault(e);
         }, oPopup)
   );
   
   
});
</script>









