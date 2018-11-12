<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/components/bitrix/catalog/catalog/bitrix/catalog.element/.default/jquery.elevatezoom.js");

global $arrProductId;
global $allPrice,$InBasketComplect, $complectBonus;


$UserBasket = CheckBasket();
$templateLibrary = array('popup');
$currencyList = '';



$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);

unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?>
<!--Start Content-->
<div itemscope itemtype="http://schema.org/Product" class="bx_item_detail" id="<? echo $arItemIDs['ID']; ?>">
<?
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
<!--Title-->
<div class="bx_item_title">
	<h1 itemprop="name">
<?
		echo (
			isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
			? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			: $arResult["NAME"]
		); ?>
	</h1>
</div>
<?
}
reset($arResult['MORE_PHOTO']);
$arFirstPhoto = current($arResult['MORE_PHOTO']);

?>



<div class="bx_item_container">

<div class="wrap-lt-rt">
	<!--Photo-->
	<div class="bx_lt">
	<div class="bx_item_slider" id="<? echo $arItemIDs['BIG_SLIDER_ID']; ?>">
	<!---->
	<div id="db-items">


		<div class="bx_bigimages" id="<? echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">

			<div  class="bx_bigimages_imgcontainer">
				<span class="bx_bigimages_aligner">
				<img itemprop="image" id="zoom_03" src='<?=$arFirstPhoto['SRC']?>'/>
						<!--<img
							id="<? echo $arItemIDs['PICT']; ?>"
							src="<?=$arFirstPhoto['SRC']?>"
							data-zoom-image="<?=$arFirstPhoto['SRC']?>"
							alt="<? echo $strAlt; ?>"
							title="<? echo $strTitle; ?>"
							class="zoom-first" />-->
				</span>

				<?if(!empty($arResult['PROPERTIES']['BEST']['VALUE'])){?>
					<div class="best_price">
						<?=GetMessage('BEST_PRICE_TEXT')?>
					</div>
				<?}?>

				<?if(!empty($arResult['PROPERTIES']['HIT']['VALUE'])){?>
					<div class="best_price hit">
						<span><?=GetMessage('PRODUCT_HIT_FIRST')?></span></br>
							  <?=GetMessage('PRODUCT_HIT_SECOND')?>
					</div>
				<?}?>
				<?if(!empty($arResult['PROPERTIES']['NEW']['VALUE'])){?>
					<div class="best_price new">
						<?=GetMessage('PRODUCT_NEW')?>
					</div>
				<?}?>
					<?
					if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
					{
						if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS']))
						{
							if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'])
							{
					?>
						<div class="bx_stick_disc right bottom" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"></div>
					<?
							}
						}
						else
						{
					?>
						<div class="bx_stick_disc right bottom" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>" style="display: none;"></div>
					<?
						}
					}
					if ($arResult['LABEL'])
					{
					?>
						<div class="bx_stick average left top" id="<? echo $arItemIDs['STICKER_ID'] ?>" title="<? echo $arResult['LABEL_VALUE']; ?>"><? echo $arResult['LABEL_VALUE']; ?></div>
					<?
					}
					?>
			</div>

	</div>

<?
if ($arResult['SHOW_SLIDER'])
{
	if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS']))
	{
		$iter = 0;
		if (5 < $arResult['MORE_PHOTO_COUNT'])
		{
			$strClass = 'bx_slider_conteiner full';
			$strOneWidth = (100/$arResult['MORE_PHOTO_COUNT']).'%';
			$strWidth = (20*$arResult['MORE_PHOTO_COUNT']).'%';
			$strSlideStyle = '';
		}
		else
		{
			$strClass = 'bx_slider_conteiner';
			$strOneWidth = '20%';
			$strWidth = '100%';
			$strSlideStyle = 'display: none;';
		}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>">
	<div class="bx_slider_scroller_container">
	<div id="gal1" class="bx_slide">

	<div style="width: <? echo $strWidth; ?>;" id="<? echo $arItemIDs['SLIDER_LIST']; ?>">
<?
		foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
		{
		$sliderImg = CFile::ResizeImageGet($arOnePhoto['ID'],array("width"=>88,"height"=>88),BX_RESIZE_IMAGE_PROPORTIONAL);
		 if($iter<4){
?>

		<div class="wrap-img-slide">
			<a  href="#" data-image="<?=$arOnePhoto['SRC']?>" >
				<img
				src="<? echo $sliderImg['src']; ?>"
				alt="<? echo $strAlt; ?>"
				title="<? echo $strTitle; ?>"
				class="thumb" />
			</a>
		</div>

<?
		$iter++;
		}
		}
unset($arOnePhoto);
?>

<script>
$("#zoom_03").elevateZoom({
 gallery:'gal1',
 cursor: 'pointer',
 galleryActiveClass: 'active',
 borderSize : 0
 });
 $("#zoom_03, .thumb").on("click", function(e) {
	var ez = $('#zoom_03').data('elevateZoom');
	$.fancybox(ez.getGalleryList()); return false;
	});
</script>
</div>

   </div>
 </div>
</div>
<?
	}
	else
	{	$iter = 0;
		foreach ($arResult['OFFERS'] as $key => $arOneOffer)
		{



			if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
				continue;
			$strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
			if (5 < $arOneOffer['MORE_PHOTO_COUNT'])
			{
				$strClass = 'bx_slider_conteiner full';
				$strOneWidth = (100/$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strWidth = (20*$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_slider_conteiner';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}

?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'].$arOneOffer['ID']; ?>" style="display: <? echo $strVisible; ?>;">
	<div class="bx_slider_scroller_container">
	<div class="bx_slide">
	<ul style="width: <? echo $strWidth; ?>;" id="<? echo $arItemIDs['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
<?
			foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto)
			{
			$imgSlider = CFile::ResizeImageGet($arOnePhoto,array("width"=>88,"height"=>88),BX_RESIZE_IMAGE_PROPORTIONAL);
			 if($iter<4){
?>
	<!--<li data-value="<? echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>"><span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $imgSlider['src']; ?>');"></span></span></li>-->
<?
		$iter++;
			}
		}
	unset($arOnePhoto);
?>
	</ul>
	</div>
<div class="bx_slide_left" id="<? echo $arItemIDs['SLIDER_LEFT_OF_ID'].$arOneOffer['ID'] ?>" style="<? echo $strSlideStyle; ?>" data-value="<? echo $arOneOffer['ID']; ?>"></div>
	<div class="bx_slide_right" id="<? echo $arItemIDs['SLIDER_RIGHT_OF_ID'].$arOneOffer['ID'] ?>" style="<? echo $strSlideStyle; ?>" data-value="<? echo $arOneOffer['ID']; ?>"></div>
	</div>
	</div>

<?


		}
	}
}
?>

</div>
<!----->
</div>
</div>

<!--control-->
<div class="bx_middle">

<div class="item_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
<?
$minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
$boolDiscountShow = (0 < $minPrice['DISCOUNT_DIFF']);
if ($arParams['SHOW_OLD_PRICE'] == 'Y')
{
?>
	<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo($boolDiscountShow ? '' : 'none'); ?>"><? echo($boolDiscountShow ? $minPrice['PRINT_VALUE'] : ''); ?></div>
<?
}
?>
	<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>" style="display:none"><? echo $minPrice['PRINT_DISCOUNT_VALUE']; ?></div>
	<? // NEW PRICE
		$arSets = CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET);
		if(!empty($arSets)):
	?>
	<div class="item_current_price"><? echo $allPrice; ?> <span class="rub" itemprop="priceCurrency">i</span></div>
	<?else:?>
	<div class="item_current_price">
		<span class="new-price">
			<span itemprop="price"><? echo $minPrice['PRINT_DISCOUNT_VALUE']; ?></span>
		</span>
		<span class="old-price">
			<?=ceil($arResult['PR_OLD_PRICE'])?>
			<ruble><span class="text"><meta itemprop="priceCurrency" content="RUB"></span></ruble>
		</span>
	</div>
	<?endif;?>

	<div class="product-bonus">
		<a href="">Бонус <span class="orange"><?=($arResult['PROPERTIES']['BONUS']['VALUE']) ? $arResult['PROPERTIES']['BONUS']['VALUE'] : 0?></span></a>
	</div>

	<div class="desc-bonus">
	<p class="orange">Копите бонусы и оплачивайте
		ими ваши покупки!</p>
	<p>1 бонус = 1 рубль</p>
	<a class="orange" href="">Узнать больше</a>
	</div>

	 <div itemprop="reviews" itemscope itemtype="http://schema.org/AggregateRating">
	 <meta itemprop="ratingValue" content="5" /> <meta itemprop="bestRating" content="5" />
	 <span style="display:none" itemprop="ratingCount">9000</span>
	<?// RAITING,
	if('Y' == $arParams['USE_VOTE_RATING']):?>
				<?$APPLICATION->IncludeComponent(
						"altasib:review.rating",
						"raiting_stars",
						array(
							"ALLOW_SET" => "N",
							"CACHE_TIME" => "86400",
							"CACHE_TYPE" => "A",
							"DETAIL_PAGE_URL" => "/utsenennye-tovary/detail/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arResult["CODE"],
							"ELEMENT_ID" => $arResult["ID"],
							"IBLOCK_ID" => "45",
							"IBLOCK_TYPE" => "1c_catalog",
							"IS_SEF" => "N",
							"SECTION_PAGE_URL" => "#SECTION_CODE#/",
							"SEF_BASE_URL" => "#SITE_DIR#/utsenennye-tovary/",
							"SHOW_TITLE" => "N",
							"TITLE_TEXT" => "",
							"COMPONENT_TEMPLATE" => "raiting_stars"
						),
						false
					);?>
	<?endif;?>

	</div>


<?
if ($arParams['SHOW_OLD_PRICE'] == 'Y')
{
	?>
	<!--<div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <? echo($boolDiscountShow ? '' : 'none'); ?>"><? echo($boolDiscountShow ? GetMessage('CT_BCE_CATALOG_ECONOMY_INFO', array('#ECONOMY#' => $minPrice['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>-->
<?
}
?>
</div>

<?
unset($minPrice);
if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
{
?>
<div class="item_info_section">
<?
	if (!empty($arResult['DISPLAY_PROPERTIES']))
	{
?>
	<dl>
<?
		foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
		{
?>
		<dt></dt><dd><?
			echo (
				is_array($arOneProp['DISPLAY_VALUE'])
				? implode(' / ', $arOneProp['DISPLAY_VALUE'])
				: $arOneProp['DISPLAY_VALUE']
			); ?></dd><?
		}
		unset($arOneProp);
?>
	</dl>
<?
	}
	if ($arResult['SHOW_OFFERS_PROPS'])
	{
?>
	<dl id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
<?
	}
?>
</div>
<?
}
if ('' != $arResult['PREVIEW_TEXT'])
{
	if (
		'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
	)
	{
?>
<div class="item_info_section">
<?
	//echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
?>
</div>
<?
	}
}
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	$arSkuProps = array();
?>
<div class="item_info_section" style="padding-right:150px;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">

		<div class="bx_size_scroller_container"><div class="bx_size">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
				$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
?>
<li data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strOneWidth; ?>; display: none;">
<i title="<? echo $arOneValue['NAME']; ?>"></i><span class="cnt" title="<? echo $arOneValue['NAME']; ?>"><? echo $arOneValue['NAME']; ?></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">

		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
				$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
?>
<li data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;" >
<i title="<? echo $arOneValue['NAME']; ?>"></i>
<span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');" title="<? echo $arOneValue['NAME']; ?>"></span></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
	unset($arProp);
?>
</div>
<?
}
?>
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
}
else
{
	$canBuy = $arResult['CAN_BUY'];
}
$buyBtnMessage = ($arParams['MESS_BTN_BUY'] != '' ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
$addToBasketBtnMessage = ($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCE_CATALOG_ADD'));
$notAvailableMessage = ($arParams['MESS_NOT_AVAILABLE'] != '' ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);

$showSubscribeBtn = false;
$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCE_CATALOG_COMPARE'));

if ($arParams['USE_PRODUCT_QUANTITY'] == 'Y')
{
	if ($arParams['SHOW_BASIS_PRICE'] == 'Y')
	{
		$basisPriceInfo = array(
			'#PRICE#' => $arResult['MIN_BASIS_PRICE']['PRINT_DISCOUNT_VALUE'],
			'#MEASURE#' => (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : '')
		);
?>

<?
	}
?>

<div class="item_buttons vam first-block">

<?//COUNT?>
<span class="item_buttons_counter_block">
    <a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb down" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
		<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				); ?>">
	<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb up" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
	<span class="bx_cnt_desc" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
</span>
<?//BUY?>
<span class="item_buttons_counter_block" id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <? echo ($canBuy ? '' : 'block'); ?>;">
<?if ($showBuyBtn){?>
			<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart hello" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?}if ($showAddBtn){?>

<?if(in_array($arResult['ID'],$UserBasket)):?>
	<a href="/personal/cart/" class="bx_big bx_bt_button bx_cart success-btn-complect" style="background-color: #6daa48;" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><? echo GetMessage('CT_BCE_CATALOG_IN_BASKET'); ?></a>
<?else:?>
	<a href="<?=$arResult['ADD_URL']?>" class="bx_big bx_bt_button bx_cart" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?endif;?>

<?}?>
</span>

</div>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
			"ELEMENT_ID" => $arResult['ID'],
			"STORE_PATH" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000",
			"MAIN_TITLE" => "",
			"USE_MIN_AMOUNT" => "N",
			"STORES" => array(34),
			"SHOW_EMPTY_STORE" => "Y",
			"SHOW_GENERAL_STORE_INFORMATION" => "N",
			"USER_FIELDS" => array(),
			"FIELDS" => array()
		),
		$component,
		array("HIDE_ICONS" => "Y")
	);?>

<?
	if ('Y' == $arParams['SHOW_MAX_QUANTITY'])
	{
		if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
		{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span></p>
<?
		}
		else
		{
			if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO'])
			{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>: <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
<?
			}
		}
	}
}
else
{
?>
	<div class="item_buttons vam second-block">
		<span class="item_buttons_counter_block" id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
<?
	if ($showBuyBtn)
	{
?>
			<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?
	}
	if ($showAddBtn)
	{
?>

<? // COMPLEKT

if(!empty($arSets)):
?>
	<?if($InBasketComplect):?>
		<a href="#" class="bx_big bx_bt_button bx_cart success-btn-complect" style="background-color: #6daa48;"><span></span><? echo GetMessage('CT_BCE_CATALOG_IN_BASKET'); ?></a>
	<?else:?>
		<a href="#" class="bx_big bx_bt_button bx_cart" ><span></span><? echo $buyBtnMessage; ?></a>
	<?endif;?>
<?else:?>
	<?if(in_array($arResult['ID'],$arrProductId)):?>
		<a href="#" class="bx_big bx_bt_button bx_cart success-btn-complect" style="background-color: #6daa48;" id="<? echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><? echo GetMessage('CT_BCE_CATALOG_IN_BASKET'); ?></a>
	<?else:?>
		<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
	<?endif;?>
<?endif;?>


<?
	}
?>
		</span>
		<span id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable" style="display: <? echo (!$canBuy ? '' : 'none'); ?>;"><? echo $notAvailableMessage; ?></span>
<?
	if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn)
	{
		?>
		<span class="item_buttons_counter_block">
	<?
	if ($arParams['DISPLAY_COMPARE'])
	{
		?>
		<a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"><? echo $compareBtnMessage; ?></a>
	<?
	}
	if ($showSubscribeBtn)
	{

	}
?>
		</span>
<?
	}
?>
	</div>
<?
}
unset($showAddBtn, $showBuyBtn);
?>
</div>

</div>
<!--desc-->
<div class="bx_rt">
	<div class="row-desc">
		<?
			CModule::IncludeModule('highloadblock');
			use Bitrix\Highloadblock as HL;
				$hlblock = HL\HighloadBlockTable::getById('2')->fetch();
				$entity = HL\HighloadBlockTable::compileEntity($hlblock);
				$entity_data_class = $entity->getDataClass();
				$entity_table_name = $hlblock['TABLE_NAME'];

				$arFilter = array("UF_XML_ID"=>$arResult['PROPERTIES']['BREND_LOGO']['VALUE']); //задаете фильтр по вашим полям

				$sTableID = 'tbl_'.$entity_table_name;
				$rsData = $entity_data_class::getList(array(
				"select" => array('*'), //выбираем все поля
				"filter" => $arFilter,
				"order" => array("UF_SORT"=>"ASC") // сортировка по полю UF_SORT, будет работать только, если вы завели такое поле в hl'блоке
				));
				$rsData = new CDBResult($rsData, $sTableID);
				$arRes = $rsData->Fetch();
				$FileBrend = CFile::GetFileArray($arRes['UF_FILE']);
		?>
		<div class="wrap-brend"><img src="<?=$FileBrend['SRC']?>"></div>


				<div class="row-desc-text">
					<?if(!empty($arResult['PROPERTIES']['GARANTIYA']['VALUE']) || !empty($arResult['PROPERTIES']['PROIZVODITEL']['VALUE'])){?>
						<h3>Дополнительно:</h3>
					<?}else{?>
						<h3>Поделиться:</h3>
					<?}?>
						<?if(!empty($arResult['PROPERTIES']['GARANTIYA']['VALUE'])){?>
							<p>Гарантия: <?=$arResult['PROPERTIES']['GARANTIYA']['VALUE']?></p>
						<?}?>
						<?if(!empty($arResult['PROPERTIES']['PROIZVODITEL']['VALUE'])){?>
							<p>Производитель: <?=$arResult['PROPERTIES']['PROIZVODITEL']['VALUE']?></p>
						<?}?>
		<br><script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
		<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter" data-size="s"></div>
				</div>

	</div>
</div>
<!--end lt-rt-->
</div>


<div class="bx_md">
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFER_GROUP_VALUES'] as $offerID)
		{
?>
	<span id="<? echo $arItemIDs['OFFER_GROUP'].$offerID; ?>" style="display: none;">
<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
		"ELEMENT_ID" => $offerID,
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
?>
	</span>
<?
		}
	}
}
else
{
	if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
	{
?><?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["ID"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
	}
}
?>
</div>
</div>
<?
//edebug($arResult);
?>
<div class="rb-lb-wrap">
<!--bottom-->
<div class="bx_lb">
<div class="bx-ctabs">
		<ul>
			<li id="description" class="active">Описание</li>
			<?if(!empty($arResult['PROPERTIES']['ATT_INSTRUCTIONS']['VALUE'])){?><li id="guid">Инструкция</li><?}?>
			<?if(!empty($arResult['PROPERTIES']['ATT_YOUTUBE']['~VALUE']['TEXT'])){?><li id="video">Видео</li><?}?>
			<li id="revew">Отзывы</li>

		</ul>
	</div>
<div class="bx-tabs">


		<div class="bx-tab description active">
			<?if(!empty($arResult['DETAIL_TEXT'])){?>
				<h3><!--Описание --><?=$arResult["NAME"]?></h3>
				<span itemprop="description"><?=$arResult['DETAIL_TEXT']?></span>
			<?}?>
			<?if(!empty($arResult['PROPERTIES']['NO_EMPTY_HR'])){?>
				<h3>Характеристики<!--  <?=$arResult["NAME"]?>--></h3>
				
					<table class="property-table">
					<?
						$NoView = array('ATT_YOUTUBE','BEST_PRICE','RAZRESHENNYY_OPLATY_BONUSAMI','_POROGA_NACHISLENIYA_BONUSOV','NAIMENOVANIE_DLYA_SAYTA','CML2_BAR_CODE','CML2_ARTICLE','CML2_TRAITS','CML2_BASE_UNIT','CML2_TAXES','CML2_MANUFACTURER','BLOG_POST_ID','BLOG_COMMENTS_CNT','BONUS','ON_DESCOUNT','AMOUNT_OF_DISCOUNT','ATT_INSTRUCTIONS','BREND_LOGO','BREND','MORE_PHOTO','FILES','POD_ZAKAZ_CHEREZ','NALICHIE','_1','RATING');

						foreach($arResult['PROPERTIES'] as $key => $value) {

							if(!in_array($key,$NoView)) {

								if(!empty($value['VALUE'])){

									// если выводится поле с кодом CML2_ATTRIBUTES
									if ($key == "CML2_ATTRIBUTES") {

										$val_name = $value["VALUE"];
										$desc = $value["DESCRIPTION"];
										for ($i = 0; $i < count($val_name); $i++) {
										
											echo '<tr><td>' . $val_name[$i] . '</td><td>' . $desc[$i] .'</td></tr>';
											
										}

										continue;
									}

									echo '<tr><td>' . $value['NAME'] . '</td><td>' . $value['VALUE'] .'</td></tr>';
								}

							}

						}
					?>
					</table>
			<?}?>
		</div>
		<div class="bx-tab guid">
			<?if(!empty($arResult['PROPERTIES']['ATT_INSTRUCTIONS']['VALUE'])){
				$FilePdf =  CFile::GetFileArray($arResult['PROPERTIES']['ATT_INSTRUCTIONS']['VALUE']);
				$Name = explode(".",$FilePdf['ORIGINAL_NAME']);
				$RealName = $Name[0];
				?>
				<div class="instr-parent">
				<a class=" instr-item" href="<?=$FilePdf['SRC']?>" download>
					<div class="icon"></div>
					<div class="item-name"><?=$RealName?></div>
					<div class="item-button">
						<button>Скачать</button>
					</div>
				</a>
				</div>
			<?}?>
		</div>

		<div class="bx-tab video">
			<?=$arResult['PROPERTIES']['ATT_YOUTUBE']['~VALUE']['TEXT'];?>
		</div>

		<div class="bx-tab revew">
				<?//REVIEW?>
				 <?$APPLICATION->IncludeComponent(
	"altasib:review", 
	".default", 
	array(
		"ADD_TITLE" => "",
		"ALLOW_TITLE" => "N",
		"ALLOW_UPLOAD_FILE" => "Y",
		"ALLOW_VOTE" => "Y",
		"AVATAR_HEIGHT" => "80",
		"AVATAR_WIDTH" => "80",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMMENTS_MODE" => "N",
		"DETAIL_PAGE_URL" => "/utsenennye-tovary/detail/#ELEMENT_CODE#/",
		"ELEMENT_CODE" => $_REQUEST["ELEMENT_CODE"],
		"ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
		"EMPTY_MESSAGE" => "",
		"IBLOCK_ID" => "45",
		"IBLOCK_TYPE" => "1c_catalog",
		"IS_SEF" => "Y",
		"LIST_TITLE" => "",
		"MAX_LENGTH" => "",
		"MESSAGE_OK" => "Отзыв добавлен",
		"MINUS_TEXT_MAX_LENGTH" => "",
		"MINUS_TEXT_MIN_LENGTH" => "",
		"MIN_LENGTH" => "5",
		"MODERATE" => "Y",
		"MODERATE_LINK" => "N",
		"MOD_GOUPS" => array(
			0 => "1",
		),
		"NAME_SHOW_TYPE" => "NAME",
		"NOT_HIDE_FORM" => "N",
		"ONLY_AUTH_COMPLAINT" => "Y",
		"ONLY_AUTH_SEND" => "N",
		"PLUS_TEXT_MAX_LENGTH" => "",
		"PLUS_TEXT_MIN_LENGTH" => "5",
		"POST_DATE_FORMAT" => "j M Y",
		"REG_URL" => "/auth/?register=yes",
		"REQUIRED_RATING" => "N",
		"REVIEWS_ON_PAGE" => "8",
		"SAVE_COUNT" => "N",
		"SAVE_RATING" => "Y",
		"SAVE_RATING_IB_PROPERTY" => "RATING",
		"SECTION_PAGE_URL" => "",
		"SEF_BASE_URL" => "/utsenennye-tovary/",
		"SEND_NOTIFY" => "N",
		"SHOW_AVATAR_TYPE" => "ns",
		"SHOW_CNT" => "N",
		"SHOW_MAIN_RATING" => "Y",
		"SHOW_POPUP" => "N",
		"SHOW_UPLOAD_FILE" => "N",
		"TITLE_MIN_LENGTH" => "5",
		"UF" => array(
		),
		"UF_VOTE" => array(
		),
		"UPLOAD_FILE_SIZE" => "150",
		"UPLOAD_FILE_TYPE" => "jpg,jpeg,gif,png,ppt,doc,docx,xls,xlsx,odt,odp,ods,odb,rtf,txt",
		"USER_PATH" => "",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>

		</div>
	</div>
</div>
<script>

</script>

</div>

</div>

</div>







<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	foreach ($arResult['JS_OFFERS'] as &$arOneJS)
	{
		if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
		{
			$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
			$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
		}
		$strProps = '';
		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($arOneJS['DISPLAY_PROPERTIES']))
			{
				foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
						is_array($arOneProp['VALUE'])
						? implode(' / ', $arOneProp['VALUE'])
						: $arOneProp['VALUE']
					).'</dd>';
				}
			}
		}
		$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
	}
	if (isset($arOneJS))
		unset($arOneJS);
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
			'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
	if ($arParams['DISPLAY_COMPARE'])
	{
		$arJSParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
?>
<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?
		if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
?>
	<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
<?
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
			}
		}
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if (!$emptyProductProperties)
		{
?>
	<table>
<?
			foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo)
			{
?>
	<tr><td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
	<td>
<?
				if(
					'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><br><?
					}
				}
				else
				{
					?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
					}
					?></select><?
				}
?>
	</td></tr>
<?
			}
?>
	</table>
<?
		}
?>

</div>

<?
	}
	if ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] != $arResult['MIN_PRICE']['VALUE'])
	{
		$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
		$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
	}
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
			'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
			'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'BASIS_PRICE' => $arResult['MIN_BASIS_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		)
	);
	if ($arParams['DISPLAY_COMPARE'])
	{
		$arJSParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}
	unset($emptyProductProperties);
}
?>
<script>
	//==== WORKROUND
	Bonus = Number($('.product-bonus .orange').text());

		$('.item_buttons_counter_block a.down').on('click',function(){

			ProdQuantity = Number($('.item_buttons_counter_block input').val())
			if(ProdQuantity!= 1){
				ProdQuantity -=1;
				$('.item_buttons_counter_block input').val(ProdQuantity)
			}


			// count = Number($('.tac').val())
			// if(count != 1){
				// count -=1;
				// res = count*Bonus;
				// $('.product-bonus .orange').text(res);
			// }

			})

			$('.item_buttons_counter_block a.up').on('click',function(){
				 ProdQuantity = Number($('.item_buttons_counter_block input').val())
				 ProdQuantity +=1;
				 $('.item_buttons_counter_block input').val(ProdQuantity);
				 // count = Number($('.tac').val()) +1;
				 // res = count*Bonus;
				// $('.product-bonus .orange').text(res);
			})

			$('.bx_big.bx_bt_button.bx_cart').on('click',function(e){
						/*mmit - 11.08.16 - Andrey Vlasov - start*/
					if(!$(this).hasClass('success-btn') && !$(this).hasClass('success-btn-complect')){
						var quantity = "&quantity=" + $('.item_buttons_counter_block input').val();
						var url = $(this).attr('href') + quantity;
						var SuccessBtn = $(this);

						// если товар доступен только под заказ
					 	if ($(this).closest(".item_buttons").siblings(".notice_delivery_time").length && $(this).closest(".item_buttons").siblings(".notice_delivery_time").css("display") == "none") {

						 	e.preventDefault();
					 		$(this).closest(".item_buttons").siblings(".notice_delivery_time").css("display", "block");
					 		var btn = $(this).closest(".item_buttons").siblings(".notice_delivery_time").children("button");

					 		$(btn).on("click", {SuccessBtn: SuccessBtn, url: url}, function(event) {

					 			$(this).parent(".notice_delivery_time").css("display", "none").addClass("ok");
					 			SuccessBtn.addClass('success-btn');
								SuccessBtn.text('В корзине');

					 			$.ajax({
									type:'post',
									beforeSend:function(){
										BX.showWait();
									},
									url:url,
									success:function(data){
										BX.onCustomEvent('OnBasketChange');
										$('.bx_big.bx_bt_button.bx_cart').attr({'href':'/personal/cart/'});

										BX.closeWait();
									},
								})

					 		});

					 		return;
					 	}

					 	// если открыта всплывашка и вы нажимаем кнопку Добавить в корзину
					 	if ($(this).closest(".item_buttons").siblings(".notice_delivery_time").css("display") == "block") {
						 	 e.preventDefault();
							return;
						}

						e.preventDefault();
						SuccessBtn.addClass('success-btn');
						SuccessBtn.text('В корзине');
						$.ajax({
							type:'post',
							beforeSend:function(){
								BX.showWait();
							},
							url:url,
							success:function(data){
								BX.onCustomEvent('OnBasketChange');
								$('.bx_big.bx_bt_button.bx_cart').attr({'href':'/personal/cart/'});

								BX.closeWait();
							},

						})
				}

			/*Andrey Vlasov - end*/

			})
		$(".bx-ctabs ul li").on('click',function(){
			$(".bx-ctabs ul li").removeClass('active');
		$(this).addClass('active');
		IdTab = $(this).attr('id');
		$(".bx-tabs .bx-tab").removeClass('active');

		$(".bx-tabs .bx-tab" + "." + IdTab).addClass('active');
		})
</script>
<script>
$(document).ready(function(){
	// DIMAN
	function validateEmail( _input ) {
	var reg_email = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

	var result = reg_email.test( jQuery( _input ).val() );

	if ( result ) jQuery( _input ).removeClass( 'error' )
	else jQuery( _input )
			// .attr('placeholder', 'Введите ваш E-mail')
			.focus(function() {
				jQuery(this)
					// .attr('placeholder', 'E-mail')
					.off('focus')
						.removeClass('error');
			})
				.addClass( 'error' );

		return result;
	}


})
</script>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
BX.message({
	ECONOMY_INFO_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO'); ?>',
	BASIS_PRICE_MESSAGE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_BASIS_PRICE') ?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT') ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE'); ?>',
	BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
	TITLE_SUCCESSFUL: '<? echo GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK'); ?>',
	COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK') ?>',
	COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
	COMPARE_TITLE: '<? echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE') ?>',
	BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
	SITE_ID: '<? echo SITE_ID; ?>'
});
</script>
