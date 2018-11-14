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

<!--
<div class="wrapper">
	<span class="good-main-title">Тонкий, толщина 5 мм, плотность 350 г/м<sup>2</sup></span>
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

	<?if($arItem['PROPERTIES']['TOLSHCHINA_MM']['VALUE'] == 5):?>
	<div class="good-wrapper">
		<div class="good-anons-wrapper">
			<span class="good-anons"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE'] / $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE'], 2));?></span><br />руб/м.п.
		</div>
		<div class="good-image">
			<a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" target="_blank"><img src="images/dzhut-1-1.png<?/* echo $arItem['PREVIEW_PICTURE']['SRC'] */?>" alt="Межвенцовый утеплитель" /></a>
		</div>
		<span class="good-title">
			<span><? echo $arItem['PROPERTIES']['TOLSHCHINA_MM']['VALUE'] .'<span class="middle-star">*</span>'. $arItem['PROPERTIES']['SHIRINA_MM']['VALUE'] ?>мм
				<div class="good-desc">
					<span class="good-desc-text">Состав: 100% джут</span>
					<span class="good-desc-text">Плотность: 350 г/м<sup>2</sup></span>
					<span class="good-desc-text">Метров в рулоне: <? echo $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE']?> м</span>
					<span class="good-desc-text">Рулонов в упаковке: <? echo $arItem['PROPERTIES']['RULONOV_V_UPAKOVKE']['VALUE']?> м</span>
					<span class="good-desc-text">Метров в упаковке: <? echo $arItem['PROPERTIES']['METROV_V_UPAKOVKE']['VALUE']?> м</span>
				</div>
			</span>
		</span>
		<div class="good-price-wrapper">
			<span class="good-price-title">Цена рулона: </span>
			<span class="good-price-desc"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE']));?> руб</span>
		</div>
		<div class="good-price-wrapper">
			<span class="good-price-title">Цена упаковки: </span>
			<span class="good-price-desc"><? echo $arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE']?> руб</span>
		</div>
		<div class="clear"></div>
		<button class="order-send" data-name="<? echo $arItem['PROPERTIES']['RAZMER_MM']['VALUE'] ?>">Заказать</button>
	</div>
	<?endif;?>
	<?endforeach;?>
	<div class="good-wrapper">
		<div class="good-anons-wrapper" style="padding: 25px 0 0 0;">
			<span class="good-anons" style="font-size: 30px;">Под заказ</span>
		</div>
		<div class="good-image">
			<img src="images/dzhut-1-1.png" alt="Межвенцовый утеплитель" />
		</div>
		<span class="good-title">5<span class="middle-star">*</span> ... X</span>
		<div class="good-price-wrapper">
			<span class="good-own">Произведем джут по<br />Вашим размерам</span>
		</div>
		<div class="clear"></div>
		<button class="order-send" data-name="под заказ 5">Заказать</button>
	</div>
</div><!--/wrapper-->

<div class="clear"></div>

<div class="wrapper">
	<div style="box-sizing: border-box; width:160px;height:auto;margin: 0;padding: 25px 0;float: left; border: none;font-size:17px;text-align: center;"></div>
	<div style="box-sizing: border-box; width:124px;height:auto;margin: 0;padding: 34px 0 35px 0;float: left; border: 1px solid #fff;border-right: none;font-size:17px;text-align: center;background:#dddddd;">Ширина, мм</div>
	<div style="box-sizing: border-box; width:125px;height:auto;margin: 38px 0 0 0;padding:  6px 0;float: left; border: 1px solid #fff;border-right: none;font-size:17px;text-align: center;background:#dddddd;">рулонов<br />в упаковке</div>
	<div style="box-sizing: border-box; width:125px;height:auto;margin: 38px 0 0 0;padding:  6px 0;float: left; border: 1px solid #fff;border-right: none;font-size:17px;text-align: center;background:#dddddd;">метров<br />в рулоне</div>
	<div style="box-sizing: border-box; width:125px;height:auto;margin: 38px 0 0 0;padding:  6px 0;float: left; border: 1px solid #fff;border-right: none;font-size:17px;text-align: center;position:relative;background:#dddddd;">метров<br />в упаковке
		<div style="position:absolute;right: -1px;top:-39px;width:376px;padding: 7px 0 8px 0;border:1px solid #fff;background: #fff;box-sizing: border-box;font-size:19px;background:#dddddd;">Вложимость</div>
	</div>
	<div style="box-sizing: border-box; width:125px;height:auto;margin: 38px 0 0 0;padding: 15px 0 16px 0;float: left; border: 1px solid #fff;border-right:none;font-size:17px;text-align: center;background:#dddddd;">упаковка</div>
	<div style="box-sizing: border-box; width:125px;height:auto;margin: 38px 0 0 0;padding: 15px 0 16px 0;float: left; border: 1px solid #fff;border-right:none;font-size:17px;text-align: center;background:#dddddd;">рулон</div>
	<div style="box-sizing: border-box; width:125px;height:auto;margin: 38px 0 0 0;padding: 15px 0 16px 0;float: left; border: 1px solid #fff;font-size:17px;text-align: center;position:relative;background:#dddddd;">погонный метр
		<div style="position:absolute;right: -1px;top:-39px;width:375px;padding: 7px 0 8px 0;border:1px solid #fff;background: #fff;box-sizing: border-box;font-size:19px;background:#dddddd;">Цена, руб</div>
	</div>
</div><!--/wrapper-->

<div class="wrapper">
	<div class="table-good-title">Толщина 5 мм, плотность 350 г/м<sup>2</sup></div>
	<div class="table-good-image-1"> 
		<a class="fancybox-good-image" href="images/fancy-dzhut-1-1.png" rel="fancy-dzhut-1">
			<div class="lupa"></div>
			<img id="zoom_dzhut_1" src="images/dzhut-1-1.png" data-zoom-image="images/dzhut-1-1.png" alt="Межвенцовый утеплитель" />
		</a>
	</div>
	<div class="table-good-data-wrapper-1">
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
			<?if($arItem['PROPERTIES']['TOLSHCHINA_MM_1']['VALUE'] == 5):?>
			<div class="table-good-data-1">
				<div class="table-good-desc-1"><? echo $arItem['PROPERTIES']['SHIRINA_MM_1']['VALUE']?></div>
				<div class="table-good-desc-1"><? echo $arItem['PROPERTIES']['RULONOV_V_UPAKOVKE']['VALUE']?></div>
				<div class="table-good-desc-1"><? echo $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE']?></div>
				<div class="table-good-desc-1"><? echo $arItem['PROPERTIES']['METROV_V_UPAKOVKE']['VALUE']?></div>
				<div class="table-good-desc-1"><? echo $arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE']?></div>
				<div class="table-good-desc-1"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE']));?></div>
				<div class="table-good-desc-1"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE'] / $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE'], 2));?></div>
				<div class="table-good-desc-1" style="padding:4px 0 8px 0;background:#fff;"><button class="order-send" data-name="<? echo $arItem['PROPERTIES']['RAZMER_MM']['VALUE'] ?>">Заказать</button></div>
			</div>
			<?endif;?>
			<?endforeach;?>
	</div>
</div><!--/wrapper-->

<div class="wrapper">
	<div class="table-good-title">Толщина 8 мм, плотность 450 г/м<sup>2</sup></div>
	<div class="table-good-image-2">
		<a class="fancybox-good-image" href="images/fancy-dzhut-1-2.png" rel="fancy-dzhut-2">
			<div class="lupa"></div>
			<img id="zoom_dzhut_2" src="images/dzhut-1-2.png" data-zoom-image="images/dzhut-1-2.png" alt="Межвенцовый утеплитель" />
		</a>
	</div>
	<div class="table-good-data-wrapper-2">
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
			<?if($arItem['PROPERTIES']['TOLSHCHINA_MM_1']['VALUE'] == 8):?>
			<div class="table-good-data-2">
				<div class="table-good-desc-2"><? echo $arItem['PROPERTIES']['SHIRINA_MM_1']['VALUE']?></div>
				<div class="table-good-desc-2"><? echo $arItem['PROPERTIES']['RULONOV_V_UPAKOVKE']['VALUE']?></div>
				<div class="table-good-desc-2"><? echo $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE']?></div>
				<div class="table-good-desc-2"><? echo $arItem['PROPERTIES']['METROV_V_UPAKOVKE']['VALUE']?></div>
				<div class="table-good-desc-2"><? echo $arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE']?></div>
				<div class="table-good-desc-2"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE']));?></div>
				<div class="table-good-desc-2"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE'] / $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE'], 2));?></div>
				<div class="table-good-desc-1" style="padding:4px 0 8px 0;background:#fff;"><button class="order-send" data-name="<? echo $arItem['PROPERTIES']['RAZMER_MM']['VALUE'] ?>">Заказать</button></div>
			</div>
			<?endif;?>
			<?endforeach;?>
	</div>
</div><!--/wrapper-->

<div class="wrapper">
	<div class="table-good-title">Толщина 10 мм, плотность 650 г/м<sup>2</sup></div>
	<div class="table-good-image-3">
		<a class="fancybox-good-image" href="images/fancy-dzhut-1-3.png" rel="fancy-dzhut-3">
			<div class="lupa"></div>
			<img id="zoom_dzhut_3" src="images/dzhut-1-3.png" data-zoom-image="images/dzhut-1-3.png" alt="Межвенцовый утеплитель" />
		</a>
	</div>
	<div class="table-good-data-wrapper-3">
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
			<?if($arItem['PROPERTIES']['TOLSHCHINA_MM_1']['VALUE'] == 10):?>
			<div class="table-good-data-3">
				<div class="table-good-desc-3"><? echo $arItem['PROPERTIES']['SHIRINA_MM_1']['VALUE']?></div>
				<div class="table-good-desc-3"><? echo $arItem['PROPERTIES']['RULONOV_V_UPAKOVKE']['VALUE']?></div>
				<div class="table-good-desc-3"><? echo $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE']?></div>
				<div class="table-good-desc-3"><? echo $arItem['PROPERTIES']['METROV_V_UPAKOVKE']['VALUE']?></div>
				<div class="table-good-desc-3"><? echo $arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE']?></div>
				<div class="table-good-desc-3"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE']));?></div>
				<div class="table-good-desc-3"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE'] / $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE'], 2));?></div>
				<div class="table-good-desc-3" style="padding:4px 0 8px 0;background:#fff;"><button class="order-send" data-name="<? echo $arItem['PROPERTIES']['RAZMER_MM']['VALUE'] ?>">Заказать</button></div>
			</div>
			<?endif;?>
			<?endforeach;?>
	</div>
</div><!--/wrapper-->

<div class="bx_catalog_list_home col<? echo $arParams['LINE_ELEMENT_COUNT']; ?> <? echo $templateData['TEMPLATE_CLASS']; ?>">
	<?/*
foreach ($arResult['ITEMS'] as $key => $arItem)
{
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

	?><div class="<? echo ($arItem['SECOND_PICT'] ? 'bx_catalog_item double' : 'bx_catalog_item'); ?>"><div class="bx_catalog_item_container" id="<? echo $strMainID; ?>">
		<!--<a id="<? echo $arItemIDs['PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="bx_catalog_item_images" style="background-image: url('<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>')" title="<? echo $imgTitle; ?>"><?
	if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
	{
	?>
			<div id="<? echo $arItemIDs['DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
	<?
	}
	if ($arItem['LABEL'])
	{
	?>
			<div id="<? echo $arItemIDs['STICKER_ID']; ?>" class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
	<?
	}
	?>
		</a>--><?
	if ($arItem['SECOND_PICT'])
	{
		?><a id="<? echo $arItemIDs['SECOND_PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="bx_catalog_item_images_double" style="background-image: url('<? echo (
				!empty($arItem['PREVIEW_PICTURE_SECOND'])
				? $arItem['PREVIEW_PICTURE_SECOND']['SRC']
				: $arItem['PREVIEW_PICTURE']['SRC']
			); ?>');" title="<? echo $imgTitle; ?>"><?
		if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
		{
		?>
			<div id="<? echo $arItemIDs['SECOND_DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
		<?
		}
		if ($arItem['LABEL'])
		{
		?>
			<div id="<? echo $arItemIDs['SECOND_STICKER_ID']; ?>" class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
		<?
		}
		?>
		</a><?
	}
	?>

		<div class="bx_image" style="background:url('<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>') no-repeat center center;"></div>
		<!--<div class="bx_catalog_item_title"><a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $productTitle; ?>"><? echo $productTitle; ?></a></div>-->
		<div class="title_desc_wrap"><span class="bx_title_1"><? echo $arItem['PROPERTIES']['RAZMER_MM']['VALUE'] ?> мм</span></div>
		<div class="title_desc_wrap"><span class="bx_title">Цена рулона: </span><span class="bx_desc"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE']));?> руб</span></div>
		<div class="title_desc_wrap"><span class="bx_title">Цена упаковки: </span><span class="bx_desc"><? echo $arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE']?> руб</span></div>
		<div class="title_desc_wrap"><span class="bx_title">Цена пог/метра: </span><span class="bx_desc"><? echo (round($arItem['PRICE_MATRIX']['MATRIX']['11']['ZERO-INF']['DISCOUNT_PRICE'] / $arItem['PROPERTIES']['KOLICHESTVO_V_UPAKOVKE_SHT']['VALUE'] / $arItem['PROPERTIES']['METROV_V_RULONE']['VALUE'], 2));?> руб</span></div>
		<div class="bx_catalog_item_price"><div id="<? echo $arItemIDs['PRICE']; ?>" class="bx_price"><?
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
			echo $minPrice['PRINT_DISCOUNT_VALUE'];
		}
		if ('Y' == $arParams['SHOW_OLD_PRICE'] && $minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE'])
		{
			?> <span><? echo $minPrice['PRINT_VALUE']; ?></span><?
		}
	}
	unset($minPrice);
	?></div></div><?
	if($arItem['CATALOG_SUBSCRIBE'] == 'Y')
		$showSubscribeBtn = true;
	else
		$showSubscribeBtn = false;
	$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCS_TPL_MESS_BTN_COMPARE'));
	if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
	{
		?>

<!--
<div class="bx_catalog_item_controls"><?
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
			<a id="<? echo $arItemIDs['BUY_LINK']; ?>" class="bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow"><?
			if ($arParams['ADD_TO_BASKET_ACTION'] == 'BUY')
			{
				echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCS_TPL_MESS_BTN_BUY'));
			}
			else
			{
				echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET'));
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
		}
		else
		{
			if($showSubscribeBtn):
				$APPLICATION->includeComponent('bitrix:catalog.product.subscribe','',
					array(
						'PRODUCT_ID' => $arItem['ID'],
						'BUTTON_ID' => $arItemIDs['SUBSCRIBE_LINK'],
						'BUTTON_CLASS' => 'bx_bt_button bx_medium',
						'DEFAULT_DISPLAY' => true,
					),
					$component, array('HIDE_ICONS' => 'Y')
				);
			endif;
			?><div id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_catalog_item_controls_blockone"><span class="bx_notavailable"><?
			echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
			?></span></div><?
			if ($arParams['DISPLAY_COMPARE'])
			{
			?>
				<div class="bx_catalog_item_controls_blocktwo"><?
				if ($arParams['DISPLAY_COMPARE'])
				{
					?><a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><? echo $compareBtnMessage; ?></a><?
				}?>
			</div><?
			}
		}
		?><div style="clear: both;"></div></div>
-->

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
					<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
<?
					if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
						unset($arItem['PRODUCT_PROPERTIES'][$propID]);
				}
			}
			$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
			if (!$emptyProductProperties)
			{
?>
				<table>
<?
					foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
					{
?>
						<tr><td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
							<td>
<?
								if(
									'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
									&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
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
										?><option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? 'selected' : ''); ?>><? echo $value; ?></option><?
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
		$arJSParams = array(
			'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
			'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
			'SHOW_ADD_BASKET_BTN' => false,
			'SHOW_BUY_BTN' => true,
			'SHOW_ABSENT' => true,
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'PRODUCT' => array(
				'ID' => $arItem['ID'],
				'NAME' => $productTitle,
				'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
				'CAN_BUY' => $arItem["CAN_BUY"],
				'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
				'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
				'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
				'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
				'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
				'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
				'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
			),
			'BASKET' => array(
				'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
				'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
				'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
				'EMPTY_PROPS' => $emptyProductProperties,
				'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
				'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
			),
			'VISUAL' => array(
				'ID' => $arItemIDs['ID'],
				'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
				'QUANTITY_ID' => $arItemIDs['QUANTITY'],
				'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
				'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
				'PRICE_ID' => $arItemIDs['PRICE'],
				'BUY_ID' => $arItemIDs['BUY_LINK'],
				'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
				'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
				'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
				'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
				'SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_LINK'],
			),
			'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
		);
		if ($arParams['DISPLAY_COMPARE'])
		{
			$arJSParams['COMPARE'] = array(
				'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
				'COMPARE_PATH' => $arParams['COMPARE_PATH']
			);
		}
		unset($emptyProductProperties);
?><script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script><?
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
			if($showSubscribeBtn):
				$APPLICATION->includeComponent('bitrix:catalog.product.subscribe','',
					array(
						'PRODUCT_ID' => $arItem['ID'],
						'BUTTON_ID' => $arItemIDs['SUBSCRIBE_LINK'],
						'BUTTON_CLASS' => 'bx_bt_button bx_medium',
						'DEFAULT_DISPLAY' => !$canBuy,
					),
					$component, array('HIDE_ICONS' => 'Y')
				);
			endif;
			?>
		<div id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_catalog_item_controls_blockone" style="display: <? echo ($canBuy ? 'none' : ''); ?>;"><span class="bx_notavailable"><?
			echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
		?></span></div>
		<div id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>" class="bx_catalog_item_controls_blocktwo" style="display: <? echo ($canBuy ? '' : 'none'); ?>;">
			<a id="<? echo $arItemIDs['BUY_LINK']; ?>" class="bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow"><?
			if ($arParams['ADD_TO_BASKET_ACTION'] == 'BUY')
			{
				echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCS_TPL_MESS_BTN_BUY'));
			}
			else
			{
				echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET'));
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
			echo ('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCS_TPL_MESS_BTN_DETAIL'));
			?></a>
		</div>
			<?
		}
		?>
		<div class="bx_catalog_item_controls touch">
			<a class="bx_bt_button_type_2 bx_medium" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"><?
			echo ('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCS_TPL_MESS_BTN_DETAIL'));
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
				foreach ($skuTemplate as $propId => $propTemplate)
				{
					if (!isset($arItem['SKU_TREE_VALUES'][$propId]))
						continue;
					$valueCount = count($arItem['SKU_TREE_VALUES'][$propId]);
					if ($valueCount > 5)
					{
						$fullWidth = ($valueCount*20).'%';
						$itemWidth = (100/$valueCount).'%';
						$rowTemplate = $propTemplate['SCROLL'];
					}
					else
					{
						$fullWidth = '100%';
						$itemWidth = '20%';
						$rowTemplate = $propTemplate['FULL'];
					}
					unset($valueCount);
					echo '<div>', str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $fullWidth), $rowTemplate['START']);
					foreach ($propTemplate['ITEMS'] as $value => $valueItem)
					{
						if (!isset($arItem['SKU_TREE_VALUES'][$propId][$value]))
							continue;
						echo str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $itemWidth), $valueItem);
					}
					unset($value, $valueItem);
					echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $rowTemplate['FINISH']), '</div>';
				}
				unset($propId, $propTemplate);
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
				$arJSParams = array(
					'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
					'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
					'SHOW_ADD_BASKET_BTN' => false,
					'SHOW_BUY_BTN' => true,
					'SHOW_ABSENT' => true,
					'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
					'SECOND_PICT' => $arItem['SECOND_PICT'],
					'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
					'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
					'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
					'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
					'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
					'DEFAULT_PICTURE' => array(
						'PICTURE' => $arItem['PRODUCT_PREVIEW'],
						'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
					),
					'VISUAL' => array(
						'ID' => $arItemIDs['ID'],
						'PICT_ID' => $arItemIDs['PICT'],
						'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
						'QUANTITY_ID' => $arItemIDs['QUANTITY'],
						'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
						'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
						'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
						'PRICE_ID' => $arItemIDs['PRICE'],
						'TREE_ID' => $arItemIDs['PROP_DIV'],
						'TREE_ITEM_ID' => $arItemIDs['PROP'],
						'BUY_ID' => $arItemIDs['BUY_LINK'],
						'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
						'DSC_PERC' => $arItemIDs['DSC_PERC'],
						'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
						'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
						'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
						'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
						'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
						'SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_LINK'],
					),
					'BASKET' => array(
						'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
						'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
						'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
						'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
						'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
					),
					'PRODUCT' => array(
						'ID' => $arItem['ID'],
						'NAME' => $productTitle
					),
					'OFFERS' => $arItem['JS_OFFERS'],
					'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
					'TREE_PROPS' => $arSkuProps,
					'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
				);
				if ($arParams['DISPLAY_COMPARE'])
				{
					$arJSParams['COMPARE'] = array(
						'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
						'COMPARE_PATH' => $arParams['COMPARE_PATH']
					);
				}
				?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>
				<?
			}
		}
		else
		{
			$arJSParams = array(
				'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
				'SHOW_QUANTITY' => false,
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => false,
				'SHOW_ABSENT' => false,
				'SHOW_SKU_PROPS' => false,
				'SECOND_PICT' => $arItem['SECOND_PICT'],
				'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
				'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
				'DEFAULT_PICTURE' => array(
					'PICTURE' => $arItem['PRODUCT_PREVIEW'],
					'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
				),
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
					'PICT_ID' => $arItemIDs['PICT'],
					'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
					'QUANTITY_ID' => $arItemIDs['QUANTITY'],
					'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
					'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
					'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
					'PRICE_ID' => $arItemIDs['PRICE'],
					'TREE_ID' => $arItemIDs['PROP_DIV'],
					'TREE_ITEM_ID' => $arItemIDs['PROP'],
					'BUY_ID' => $arItemIDs['BUY_LINK'],
					'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
					'DSC_PERC' => $arItemIDs['DSC_PERC'],
					'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
					'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
					'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
					'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
					'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
					'SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_LINK'],
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				),
				'PRODUCT' => array(
					'ID' => $arItem['ID'],
					'NAME' => $productTitle
				),
				'OFFERS' => array(),
				'OFFER_SELECTED' => 0,
				'TREE_PROPS' => array(),
				'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
			);
			if ($arParams['DISPLAY_COMPARE'])
			{
				$arJSParams['COMPARE'] = array(
					'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
					'COMPARE_PATH' => $arParams['COMPARE_PATH']
				);
			}
?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>
<?
		}
	}
?></div></div>
<?
}
*/?>
<div style="clear: both;"></div>
</div>
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