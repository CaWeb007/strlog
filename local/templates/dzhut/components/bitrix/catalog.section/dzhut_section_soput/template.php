<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?
if (!empty($arResult['ITEMS']))
{
	$templateLibrary = array('popup');
	$currencyList = '';
	if (!empty($arResult['CURRENCIES']))
	{
		$templateLibrary[] = 'currency';
		$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
	}
	$templateData = array(
		'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
		'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
		'TEMPLATE_LIBRARY' => $templateLibrary,
		'CURRENCIES' => $currencyList
	);
	unset($currencyList, $templateLibrary);

	$skuTemplate = array();
	if (!empty($arResult['SKU_PROPS']))
	{
		foreach ($arResult['SKU_PROPS'] as $arProp)
		{
			$propId = $arProp['ID'];
			$skuTemplate[$propId] = array(
				'SCROLL' => array(
					'START' => '',
					'FINISH' => '',
				),
				'FULL' => array(
					'START' => '',
					'FINISH' => '',
				),
				'ITEMS' => array()
			);
			$templateRow = '';
			if ('TEXT' == $arProp['SHOW_MODE'])
			{
				$skuTemplate[$propId]['SCROLL']['START'] = '<div class="bx_item_detail_size full" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';;
				$skuTemplate[$propId]['SCROLL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style=""></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style=""></div>'.
					'</div></div>';

				$skuTemplate[$propId]['FULL']['START'] = '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';;
				$skuTemplate[$propId]['FULL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'</div></div>';
				foreach ($arProp['VALUES'] as $value)
				{
					$value['NAME'] = htmlspecialcharsbx($value['NAME']);
					$skuTemplate[$propId]['ITEMS'][$value['ID']] = '<li data-treevalue="'.$propId.'_'.$value['ID'].
						'" data-onevalue="'.$value['ID'].'" style="width: #WIDTH#;" title="'.$value['NAME'].'"><i></i><span class="cnt">'.$value['NAME'].'</span></li>';
				}
				unset($value);
			}
			elseif ('PICT' == $arProp['SHOW_MODE'])
			{
				$skuTemplate[$propId]['SCROLL']['START'] = '<div class="bx_item_detail_scu full" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';
				$skuTemplate[$propId]['SCROLL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style=""></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style=""></div>'.
					'</div></div>';

				$skuTemplate[$propId]['FULL']['START'] = '<div class="bx_item_detail_scu" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';
				$skuTemplate[$propId]['FULL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'</div></div>';
				foreach ($arProp['VALUES'] as $value)
				{
					$value['NAME'] = htmlspecialcharsbx($value['NAME']);
					$skuTemplate[$propId]['ITEMS'][$value['ID']] = '<li data-treevalue="'.$propId.'_'.$value['ID'].
						'" data-onevalue="'.$value['ID'].'" style="width: #WIDTH#; padding-top: #WIDTH#;"><i title="'.$value['NAME'].'"></i>'.
						'<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$value['PICT']['SRC'].'\');" title="'.$value['NAME'].'"></span></span></li>';
				}
				unset($value);
			}
		}
		unset($templateRow, $arProp);
	}

	if ($arParams["DISPLAY_TOP_PAGER"])
	{
		?><? echo $arResult["NAV_STRING"]; ?><?
	}

	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

	if($arParams['HIDE_SECTION_DESCRIPTION'] !== 'Y')
	{ ?>
<div class="bx-section-desc <? echo $templateData['TEMPLATE_CLASS']; ?>">
	<p class="bx-section-desc-post"><?=$arResult["DESCRIPTION"]?></p>
</div>
<? } ?>

<div class="clear"></div>

<div class="wrapper">
	<div class="soput-slider-wrapper">
		<div class="soput-wrapper">
			<div class="soput">
				<div class="soput-image">
					<a href="#land_3"><img src="images/soputka-6.png" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="#land_3">Шканты<br />из сухой березы</a></span>
				<div class="soput-rating" style="height: 20px;margin:-3px 0 18px 0;">
					<img style="margin:0 auto;" src="images/stars.png" alt="..." />
				</div>
				<div class="soput-price">299<span class="rus-rub">p</span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href="">0 <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="soput-buy-button"><a href="#land_3">Купить</a></div>
				<span class="soput-available">Нет в наличии</span>
			</div>
			<div class="soput">
				<div class="soput-image">
					<a href="#land_3"><img src="images/soputka-5.png" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="#land_3">Антисептик<br />«Сенеж»</a></span>
				<div class="soput-rating" style="height: 20px;margin:-3px 0 18px 0;">
					<img style="margin:0 auto;" src="images/stars.png" alt="..." />
				</div>
				<div class="soput-price">299<span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href="">0 <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="#land_3">Купить</a></div>
				<span class="soput-available">Нет в наличии</span>
			</div>
			<?foreach($arResult['ITEMS'] as $arItem): ?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
			$strMainID = $this->GetEditAreaId($arItem['ID']);
		
			$arItemIDs = array(
				'ID' => $strMainID,
				'PICT' => $strMainID.'_pict',
				'SECOND_PICT' => $strMainID.'_secondpict',
				'STICKER_ID' => $strMainID.'_sticker',
				'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
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
				'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
			);
			?>
			<?if($arItem['ID'] == 2841):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/kanat/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/kanat/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/kanat/" target="_blank">Купить</a></div>
				<?if($arItem['CUSTOM_AMOUNT'] == "Y"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2815):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/paklya/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/paklya/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/paklya/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 6278):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/krepezhnyy-instrument/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/krepezhnyy-instrument/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/krepezhnyy-instrument/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2923):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/skoby-stroitelnye_1/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/skoby-stroitelnye_1/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/skoby-stroitelnye_1/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?endforeach;?>
		</div><!--/soput-wrapper-->
		<div class="soput-wrapper">
			<?foreach($arResult['ITEMS'] as $arItem): ?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
			$strMainID = $this->GetEditAreaId($arItem['ID']);
		
			$arItemIDs = array(
				'ID' => $strMainID,
				'PICT' => $strMainID.'_pict',
				'SECOND_PICT' => $strMainID.'_secondpict',
				'STICKER_ID' => $strMainID.'_sticker',
				'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
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
				'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
			);
			?>
			<?if($arItem['ID'] == 3267):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/bazalt-minplita/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/bazalt-minplita/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/bazalt-minplita/" target="_blank">Купить</a></div>
				<?if($arItem['CUSTOM_AMOUNT'] == "Y"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2089):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/ekstruziya-xps/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/ekstruziya-xps/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/ekstruziya-xps/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2001):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/fanera/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/fanera/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/fanera/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2306):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/bazalt-minplita/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/bazalt-minplita/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/bazalt-minplita/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2042):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/steklovata/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/steklovata/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/steklovata/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?if($arItem['ID'] == 2271):?>
			<div class="soput">
				<div class="soput-image">
					<a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/osb/" target="_blank"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="image" /></a>
				</div>
				<span class="soput-title"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/osb/" target="_blank"><? echo html_entity_decode($arItem['PROPERTIES']['MAIN']['VALUE']);?></a></span>
				<div class="soput-rating">
					<?$APPLICATION->IncludeComponent("altasib:review.rating", "rating_front", Array(
							"ALLOW_SET" => "N",	// Разрешать выставлять оценку
							"CACHE_TIME" => "86400",	// Время кеширования (сек.) 
							"CACHE_TYPE" => "A",	// Тип кеширования
							"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
							"ELEMENT_CODE" => $arItem['CODE'],	// Код элемента
							"ELEMENT_ID" => $arItem['ID'],	// ID элемента
							"IBLOCK_ID" => "24",	// Инфо-блок
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
				</div>
				<div class="soput-price"><?=$arItem['PRICE_MATRIX']['MATRIX'][1][0]['DISCOUNT_PRICE']; ?><span class="rus-rub">p</span><span style="font-size:12px;color:#999999;">/ <?=$arItem['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span></div>
				<div class="clear"></div>
				<!--Bonus-->
				<div class="product-bonus">
					<a href=""><?=$arItem['PROPERTIES']['BONUS']['VALUE']?> <span class="orange">Бонусов</span></a>
				</div>
				<div class="desc-bonus">
					<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru">Узнать больше</a>
				</div>
				<!--/Bonus-->
				<div class="clear"></div>
				<div class="soput-buy-button"><a href="https://xn--80afpacjdwcqkhfi.xn--p1ai/catalog/osb/" target="_blank">Купить</a></div>
				<?if($arItem['CATALOG_QUANTITY'] != "0"):?>
				<span class="soput-available">Есть в наличии</span>
				<?else:?>
				<span class="soput-available">Нет в наличии</span>
				<?endif;?>
			</div>
			<?endif;?>
			<?endforeach;?>
		</div><!--/soput-wrapper-->
	</div><!--/soput-slider-wrapper-->
</div><!--/wrapper-->

<script type="text/javascript">
BX.message({
	BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
	BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
	ADD_TO_BASKET_OK: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS') ?>',
	TITLE_SUCCESSFUL: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE') ?>',
	BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
	COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK') ?>',
	COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
	COMPARE_TITLE: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE') ?>',
	BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
	SITE_ID: '<? echo SITE_ID; ?>'
});
</script>
<?
	if ($arParams["DISPLAY_BOTTOM_PAGER"])
	{
		?><?/* echo $arResult["NAV_STRING"]; */?><?
	}
}