<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $arrProductId;
$UserBasket = CheckBasket();
?>

<style>
.old-price:after {
	content: "";
	display: block;
	position: absolute;
	top: 0;
	bottom: 0;
	left: -.5rem;
	right: -.5rem;
	width: 100%;
	height: 2px;
	margin: auto;
	background-color: rgba(0,0,0,0.5);
</style>

<?if(!empty($arResult["ITEMS"][0]["IBLOCK_SECTION_ID"])):

	// $nav = CIBlockSection::GetNavChain(false, $arResult["ITEMS"][0]["IBLOCK_SECTION_ID"]);
	// while($resultNav = $nav->GetNext()):
		// $APPLICATION->AddChainItem($resultNav['NAME'],$resultNav['SECTION_PAGE_URL']);
	// endwhile;

?>

<?if(!isset($_REQUEST["sorter"])):?>
<? $sorterTitle = 'возрастанию цены'; ?>
<?elseif(isset($_REQUEST["sorter"])):?>
<? $sorterTitle = $_REQUEST["sorter"][3]; ?>
<?endif;?>

<?if(!isset($_REQUEST["groupper"])):?>
<? $groupperTitle = 'без группировки'; ?>
<?elseif(isset($_REQUEST["groupper"])):?>
<? $groupperTitle = $_REQUEST["groupper"][3]; ?>
<?endif;?>

<?if(!isset($_REQUEST["availer"])):?>
<? $availerTitle = 'показать все'; ?>
<?elseif(isset($_REQUEST["availer"])):?>
<? $availerTitle = $_REQUEST["availer"][3]; ?>
<?endif;?>
<?if(isset($_REQUEST["availer"]) && $_REQUEST["availer"][3] != "показать все"):?>
<? $sorterTitle = 'без сортировки'; ?>
<?endif;?>

<div class="section-onload"></div>

	<div class="sorter">
		<div class="sorter-list-wrapper">
			<ul class="sorter-list">
				<li class="sorter-first-point">Сортировать по:<br /><span class="sorter-choice"><? echo $sorterTitle; ?></span><div class="sorter-list-arrow"></div></li>
				<li class="sorter-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("sorter[1]" => "NAME", "sorter[2]" => "asc", "sorter[3]" => "наименованию", "availer[1]" => "", "availer[2]" => "", "availer[3]" => "показать все")));?>" rel="nofollow">наименованию</a></noindex></li><!--/sorter=name-->
				<li class="sorter-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("sorter[1]" => "catalog_PRICE_1", "sorter[2]" => "asc", "sorter[3]" => "возрастанию цены", "availer[1]" => "", "availer[2]" => "", "availer[3]" => "показать все")));?>" rel="nofollow">возрастанию цены</a></noindex></li><!--/sorter=priceup-->
				<li class="sorter-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("sorter[1]" => "catalog_PRICE_1", "sorter[2]" => "desc", "sorter[3]" => "убыванию цены", "availer[1]" => "", "availer[2]" => "", "availer[3]" => "показать все")));?>" rel="nofollow">убыванию цены</a></noindex></li><!--/sorter=pricedown-->
			</ul>
			<ul class="groupper-list">
				<li class="groupper-first-point">Группировать по:<br /><span class="groupper-choice"><? echo $groupperTitle; ?></span><div class="sorter-list-arrow"></div></li>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["PROIZVODITEL_1"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "property_PROIZVODITEL_1", "groupper[2]" => "asc,nulls", "groupper[3]" => "производителю")));?>" rel="nofollow">производителю</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["TIP"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "property_TIP", "groupper[2]" => "asc,nulls", "groupper[3]" => "типу")));?>" rel="nofollow">типу</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["TOLSHCHINA_MM_1"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_TOLSHCHINA_MM_1", "groupper[2]" => "asc,nulls", "groupper[3]" => "толщине")));?>" rel="nofollow">толщине</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["DLINA_M"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_DLINA_M", "groupper[2]" => "asc,nulls", "groupper[3]" => "длине, м")));?>" rel="nofollow">длине, м</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["DLINA_MM_1"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_DLINA_MM_1", "groupper[2]" => "asc,nulls", "groupper[3]" => "длине, мм")));?>" rel="nofollow">длине, мм</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["VES_KG"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_VES_KG", "groupper[2]" => "asc,nulls", "groupper[3]" => "весу, кг")));?>" rel="nofollow">весу, кг</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["SHIRINA_MM_1"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_SHIRINA_MM_1", "groupper[2]" => "asc,nulls", "groupper[3]" => "ширине")));?>" rel="nofollow">ширине</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["PLOTNOST_KG_M2_1"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_PLOTNOST_KG_M2_1", "groupper[2]" => "asc,nulls", "groupper[3]" => "плотности")));?>" rel="nofollow">плотности</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["RAZMER_MM"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "property_RAZMER_MM", "groupper[2]" => "asc,nulls", "groupper[3]" => "размеру")));?>" rel="nofollow">размеру</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["DIAMETR_MM_1"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "property_DIAMETR_MM_1", "groupper[2]" => "asc,nulls", "groupper[3]" => "диаметру")));?>" rel="nofollow">диаметру</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K", "groupper[2]" => "asc,nulls", "groupper[3]" => "теплопроводности")));?>" rel="nofollow">теплопроводности</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["TSVET"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "property_TSVET", "groupper[2]" => "asc,nulls", "groupper[3]" => "цвету")));?>" rel="nofollow">цвету</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["GRUPPA_TOVAROV"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "property_GRUPPA_TOVAROV", "groupper[2]" => "asc,nulls", "groupper[3]" => "группе товаров")));?>" rel="nofollow">группе товаров</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["RAZMER"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_RAZMER", "groupper[2]" => "asc,nulls", "groupper[3]" => "размеру")));?>" rel="nofollow">размеру</a></noindex></li>
				<?endif;?>
				<?if($arResult["ITEMS"][0]["PROPERTIES"]["RAZMER_M"]["VALUE"] != ""):?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "propertysort_RAZMER_M", "groupper[2]" => "asc,nulls", "groupper[3]" => "размеру, м")));?>" rel="nofollow">размеру, м</a></noindex></li>
				<?endif;?>
				<li class="groupper-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("groupper[1]" => "", "groupper[2]" => "", "groupper[3]" => "без группировки")));?>" rel="nofollow">без группировки</a></noindex></li>
			</ul>
			<ul class="availer-list">
				<li class="availer-first-point">Наличие:<br /><span class="availer-choice"><? echo $availerTitle; ?></span><div class="sorter-list-arrow"></div></li>
				<li class="availer-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("availer[1]" => "CATALOG_QUANTITY", "availer[2]" => "DESC", "availer[3]" => "есть в наличии")));?>" rel="nofollow">есть в наличии</a></noindex></li>
				<li class="availer-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("availer[1]" => "CATALOG_QUANTITY", "availer[2]" => "ASC", "availer[3]" => "под заказ")));?>" rel="nofollow">под заказ</a></noindex></li>
				<li class="availer-point"><noindex><a href="?<?=http_build_query(array_merge($_GET, array("availer[1]" => "", "availer[2]" => "", "availer[3]" => "показать все", "sorter[1]" => "catalog_PRICE_1", "sorter[2]" => "ASC", "sorter[3]" => "возрастанию цены")));?>" rel="nofollow">показать все</a></noindex></li>
			</ul>
			<?if(isset($_REQUEST["sorter"]) || isset($_REQUEST["groupper"]) || isset($_REQUEST["availer"])):?>
			<div class="reset"><a href="/likvidaciya/">Сбросить<div class="sorter-list-clear">&times;</div></a></div>
			<?endif;?>
		</div><!--/sorter-list-wrapper-->


	<div class="label"><!--Сортировка:--></div>
<!--
	<ul>
	
	<li class="<? if ($_REQUEST['sort_order'] == 'desc'){echo "desc";}elseif($_REQUEST['sort_order'] == 'asc'){ echo "asc";}else{ echo "none";}?>">
		<noindex>
		<a title="сортировать по:" rel="nofollow" href="?<? echo $_SERVER['argv'][0]; ?>&sort=price<? if ($_REQUEST['sort_order'] == 'desc'){ echo '&sort_order=asc';}elseif($_REQUEST['sort_order'] == 'asc'){echo '&sort_order=desc';}else{ echo '&sort_order=desc'; } ?>">
		
			<? if ($_REQUEST['sort_order'] == 'desc') { echo "по убыванию цены"; }elseif($_REQUEST['sort_order'] == 'asc'){ echo "по возрастанию цены"; }else{ echo "по ценe";}?>

		</a>
		</noindex>
	</li>
	</ul>
-->
	</div>
<?endif;?>

<?
	//==== START PARTY HARD
	$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
	$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
	$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
?>
<?
//edebug($arResult["ITEMS"]);
?>
<div class="product-list">

	<?foreach($arResult["ITEMS"] as $arElement){
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
			// скидка
		$minPrice = false;
		if (isset($arElement['MIN_PRICE']) || isset($arElement['RATIO_PRICE']))
		$minPrice = (isset($arElement['RATIO_PRICE']) ? $arElement['RATIO_PRICE'] : $arElement['MIN_PRICE']);
		?>

		<?if($_REQUEST["groupper"][1] == "property_PROIZVODITEL_1"):?>
			<span class="prod-title"><?=$arElement["PROPERTIES"]["PROIZVODITEL_1"]["VALUE"];?><a id="<?=$arElement["PROPERTIES"]["PROIZVODITEL_1"]["VALUE"];?>" class="product-anchor"></a></span>
		<?elseif($_REQUEST["groupper"][1] == "property_TIP"):?>
			<span class="prod-title">Тип: <?=$arElement["PROPERTIES"]["TIP"]["VALUE"];?><a id="<?=$arElement["PROPERTIES"]["TIP"]["VALUE"];?>" class="product-anchor"></a></span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_DLINA_M"):?>
			<span class="prod-title">Длина: <?=$arElement["PROPERTIES"]["DLINA_M"]["VALUE"];?> м</span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_DLINA_MM_1"):?>
			<span class="prod-title">Длина: <?=$arElement["PROPERTIES"]["DLINA_MM_1"]["VALUE"];?> мм</span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_VES_KG"):?>
			<span class="prod-title">Вес: <?=$arElement["PROPERTIES"]["VES_KG"]["VALUE"];?> кг</span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_TOLSHCHINA_MM_1"):?>
			<span class="prod-title">Толщина: <?=$arElement["PROPERTIES"]["TOLSHCHINA_MM_1"]["VALUE"];?> мм</span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_SHIRINA_MM_1"):?>
			<span class="prod-title">Ширина: <?=$arElement["PROPERTIES"]["SHIRINA_MM_1"]["VALUE"];?> мм</span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_PLOTNOST_KG_M2_1"):?>
			<span class="prod-title">Плотность: <?=$arElement["PROPERTIES"]["PLOTNOST_KG_M2_1"]["VALUE"];?> кг/м<sup>2</sup></span>
		<?elseif($_REQUEST["groupper"][1] == "property_TSVET"):?>
			<span class="prod-title">Цвет: <?=$arElement["PROPERTIES"]["TSVET"]["VALUE"];?></span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K"):?>
			<span class="prod-title">Коэффициент теплопроводности: <?=$arElement["PROPERTIES"]["KOEFFITSIENT_TEPLOPROVODNOSTI_VT_M_K"]["VALUE"];?> Вт/(м*К)</span>
		<?elseif($_REQUEST["groupper"][1] == "property_RAZMER_MM"):?>
			<span class="prod-title">Размер: <?=$arElement["PROPERTIES"]["RAZMER_MM"]["VALUE"];?></span>
		<?elseif($_REQUEST["groupper"][1] == "property_DIAMETR_MM_1"):?>
			<span class="prod-title">Диаметр: <?=$arElement["PROPERTIES"]["DIAMETR_MM_1"]["VALUE"];?> мм</span>
		<?elseif($_REQUEST["groupper"][1] == "property_GRUPPA_TOVAROV"):?>
			<span class="prod-title"><?=$arElement["PROPERTIES"]["GRUPPA_TOVAROV"]["VALUE"];?><a id="<?=$arElement["PROPERTIES"]["GRUPPA_TOVAROV"]["VALUE"];?>" class="product-anchor"></a></span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_RAZMER_M"):?>
		<span class="prod-title">Размер: <?=$arElement["PROPERTIES"]["RAZMER_M"]["VALUE"];?><a id="<?=$arElement["PROPERTIES"]["RAZMER_M"]["VALUE"];?>" class="product-anchor"></a></span>
		<?elseif($_REQUEST["groupper"][1] == "propertysort_RAZMER"):?>
		<span class="prod-title">Размер: <?=$arElement["PROPERTIES"]["RAZMER"]["VALUE"];?><a id="<?=$arElement["PROPERTIES"]["RAZMER"]["VALUE"];?>" class="product-anchor"></a></span>
		<?endif;?>
		<div class="prod-item" id="<?=$strMainID?>">
		<?
			// ресайз картинок
			$IMG = CFile::ResizeImageGet($arElement['DETAIL_PICTURE'],array("width"=>110,"height"=>82),BX_RESIZE_IMAGE_PROPORTIONAL);
		
		?>
			<div class="wrap-prod-img">
			<div class="prod-img" style=<?if(!empty($IMG['src'])) echo '"background:url('.$IMG['src'].')"'?>></div>
				<?if('Y' == $arParams['SHOW_DISCOUNT_PERCENT']){ ?>
					<div  class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;"></div>
				<?}?>
				<?if(!empty($arElement['PROPERTIES']['BEST']['VALUE'])){?>
					<div class="best_price">
						<?=GetMessage('BEST_PRICE_TEXT')?>
					</div>
				<?}?>
				<?if(!empty($arElement['PROPERTIES']['HIT']['VALUE'])){?>
					<div class="best_price hit">
						<span><?=GetMessage('PRODUCT_HIT_FIRST')?></span></br>
							  <?=GetMessage('PRODUCT_HIT_SECOND')?>
					</div>
				<?}?>	
				<?if(!empty($arElement['PROPERTIES']['NEW']['VALUE'])){?>
					<div class="best_price new">
						<?=GetMessage('PRODUCT_NEW')?>
					</div>
				<?}?>
				<?/*if(!empty($arElement['PROPERTIES']['ACTION_1']['VALUE'])){?>
					<div class="best_price action">
						Акция
					</div>
				<?}*/?>
				<?if(!empty($arElement['PROPERTIES']['ACTION_1']['VALUE'])){?>
					<?if($arElement['PROPERTIES']['ACTION_1']['VALUE'] == 20):?>
					<div class="best_price percent">
						-20%
					</div>
					<?elseif($arElement['PROPERTIES']['ACTION_1']['VALUE'] == "распродажа"):?>
					<div class="best_price saler-section">
						-35%
					</div>
					<?else:?>
					<div class="best_price action">
						Акция
					</div>
					<?endif;?>
				<?}?>
			</div>
			<div class="prod-desc">
				<h4 class="orange"><a title="<?=$arElement["NAME"]?>" href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></h4>
				<?if(!empty($arElement['MINI_DESC'])):?>
				<p>
				<a href="<?=$arElement["DETAIL_PAGE_URL"]?>">[
				<span class="mini_desc"><?=$arElement['MINI_DESC']?></span>	
				]</a>
				</p>
				<div class="clear"></div>
				<ul style="display:none;">
					<?if($arElement['PROPERTIES']['ACTION_1']['VALUE'] == 20):?>
					<li class="line-action action-discount-35">-20%</li>
					<?endif;?>
					<?if($arElement['PROPERTIES']['ACTION_1']['VALUE'] == 'распродажа'):?>
					<li class="line-action action-discount-35">-35%</li>
					<?endif;?>
					<?if(!empty($arElement['PROPERTIES']['NEW']['VALUE'])):?>
					<li class="line-action action-new">Новинка</li>
					<?endif;?>
					<?if(!empty($arElement['PROPERTIES']['HIT']['VALUE'])):?>
					<li class="line-action action-hit">Хит продаж</li>
					<?endif;?>
					<?if(!empty($arElement['PROPERTIES']['BEST']['VALUE'])):?>
					<li class="line-action action-best">Лучшая цена</li>
					<?endif;?>
				</ul>
				<div class="clear"></div>
				<?endif;?>
			</div>
			<div class="prod-price">
			<?
			if(!empty($arElement['PROPERTIES']['TSENA_ZA_M2']['VALUE'])){
				echo $arElement['PROPERTIES']['TSENA_ZA_M2']['VALUE'];
				echo '<ruble><span class="text"><meta itemprop="priceCurrency" content="RUB" /></span></ruble><span style="font-size:12px;color:#999999;margin:0;">/м<sup>2</sup>';
			}
			elseif(!empty($arElement['PROPERTIES']['TSENA_ZA_KG']['VALUE'])){
				echo $arElement['PROPERTIES']['TSENA_ZA_KG']['VALUE'];
				echo '<ruble><span class="text"><meta itemprop="priceCurrency" content="RUB" /></span></ruble><span style="font-size:12px;color:#999999;margin:0;">/кг';
			}else{
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
						if ('Y' == $arParams['SHOW_OLD_PRICE'] && $minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE'])
							{
								?> <span class='discount-trh'><? echo $minPrice['PRINT_VALUE']; ?></span><?
							}
						echo  $minPrice['PRINT_DISCOUNT_VALUE'] ;

					}
				}
				unset($minPrice);
				?>
				<span style="font-size:12px;color:#999999;margin:0 0 0 -6px;">/ <?=$arElement['PROPERTIES']['CML2_BASE_UNIT']['VALUE'];?></span>
				<?if(!empty($arElement["OLD_PRICE"])):?>
				<span class="old-price" style="font-size:1.2rem;position:relative;display:inline-block;margin-top:0.5rem;opacity:0.5;font-family:'BlissPro-Regular';font-weight:normal;">
					<?=$arElement["OLD_PRICE"];?>
					<ruble>
						<span class="text"></span>
					</ruble>
				</span>
				<?endif;?>
			<?}?>

<!--
				<span class="old-price">
					<?=$arElement['OLD_PRICE']?>
					<ruble><span class="text"><meta itemprop="priceCurrency" content="RUB"></span></ruble>
				</span>
-->

				<?//=$arElement['PRICES']['BASE']['PRINT_VALUE']?>
				<div class="product-bonus">
					<a title="количество бонусов" href="">Бонус <span class="orange"><?=($arElement['PROPERTIES']['BONUS']['VALUE']) ? $arElement['PROPERTIES']['BONUS']['VALUE'] : 0?></span></a>
				</div>
				<div class="desc-bonus">
						<p class="orange">Копите бонусы и оплачивайте ими ваши покупки!</p>
					<p>1 бонус = 1 рубль</p>
					<a class="orange" target="_blank" href="http://strlogclub.ru" title="Узнать больше">Узнать больше</a>
				</div>
			</div>
			
			<div class="prod-control">
			<!--Проверка корзины юзера-->

				<div class="wrap-buy">
				<?if($arElement["CAN_BUY"]):?>
						<?$quantity = 1 * $arElement['CATALOG_QUANTITY'];?>
						<?if(in_array($arElement['ID'],$UserBasket)):?>
							<a rel="nofollow" class="bx_bt_button bx_medium success-btn" href="/personal/cart/" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_IN_CART")?></a>
						<?else:?>
							<!--<a onclick="yaCounter36570340.reachGoal('click'); return true;" id="test" class="bx_bt_button bx_medium accept-order" href="<?echo $arElement["BUY_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_BUY")?></a>-->
							<?if($quantity <= 0):?>
								<div class="prod-no-item-button-wrapper">
									<span class="prod-no-item-button"><?echo GetMessage("CATALOG_BUY")?></span>
									<div class="prod-no-item-popup">
										<p>Уведомлен о том, что срок доставки данного товара под заказ 7-10 рабочих дней</p>
										<a onclick="yaCounter36570340.reachGoal('click'); return true;" id="test" class="bx_bt_button bx_medium accept-order" href="<?echo $arElement["BUY_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_BUY")?></a>
										<div class="prod-no-item-popup-close">Отмена</div>
									</div>
								</div>
							<?else:?>
								<a onclick="yaCounter36570340.reachGoal('click'); return true;" id="test" class="bx_bt_button bx_medium" href="<?echo $arElement["BUY_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_BUY")?></a>
							<?endif;?>
						<?endif;?>

						<!--<?if(in_array($arElement['ID'],$UserBasket)):?>
							<?$arSets = CCatalogProductSet::getAllSetsByProduct($arResult['ID'], CCatalogProductSet::TYPE_SET);
							if(!empty($arSets)):?>
								<a class="bx_bt_button bx_medium success-btn" href="<?echo $arElement["DETAIL_PAGE_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_IN_CART")?></a>
							<?else:?>
								<a rel="nofollow" class="bx_bt_button bx_medium success-btn" href="<?echo $arElement["BUY_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_IN_CART")?></a>
							<?endif;?>
						<?else:?>

						<?$arSets = CCatalogProductSet::getAllSetsByProduct($arElement['ID'], CCatalogProductSet::TYPE_SET);
							if(!empty($arSets)):?>
								<a onclick="yaCounter36570340.reachGoal('cart'); return true;" class="bx_bt_button bx_medium no-ajax" href="<?echo $arElement["DETAIL_PAGE_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_MORE")?></a>
							<?else:?>
								<a rel="nofollow" onclick="yaCounter36570340.reachGoal('click'); return true;" id="test" class="bx_bt_button bx_medium" href="<?echo $arElement["BUY_URL"]?>" rel="nofollow" title="добавить в корзину"><?echo GetMessage("CATALOG_BUY")?></a>
							<?endif;?>
						<?endif;?>-->

				<?endif;?>

<?/*$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "section", array(
		"ELEMENT_ID" => $arElement["ID"],
		"STORE_PATH" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000",
		"MAIN_TITLE" => "Наличие",
		"USE_MIN_AMOUNT" => "N",
		"MIN_AMOUNT" => 0,
		"STORES" => array(0 => 34),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"USER_FIELDS" => array(),
		"FIELDS" => array()
	),
	false,
	array("HIDE_ICONS" => "Y")
);*/?>


					<div class="amount_store">
					<?$quant = 1 * $arElement['CATALOG_QUANTITY'];?>
					<?if($quant > 0):?>
						Есть в наличии
					<?else:?>
						Под заказ
					<?endif;?>
				</div>
				<?php
				/* mmit - 10.08.2016 - Andrey Vlasov - start */
				/* реализуем всплывающее окно со сроками доставки товара, если товара доступен только под заказ */
				if ($arElement['CUSTOM_AMOUNT'] != "Y") {
				?>

				<div style="display: none;" class="notice_delivery_time">
					<p>Уведомлен о том, что срок доставки данного товара под заказ 7-10 рабочих дней</p>
					<button class="button-ok">ok</button>
				</div>
				<?php
				}
				/* 10.08.2016 - Andrey Vlasov - end */
				?>
				</div>


				
			</div>
		</div>

	<?}?>

</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?if($arResult["NAV_STRING"] != "1"):?>
		<?=$arResult["NAV_STRING"];?>
	<?endif;?>
<?endif?>

<?if(!empty($arResult["DESCRIPTION"])):?>
<?if (is_object($arResult['NAV_RESULT']) && $arResult['NAV_RESULT']->PAGEN == 1):?>
<div class="description-block">

	<div class="description-content">
		<p><?=$arResult["DESCRIPTION"]?></p>
	</div>

<!--	<button class="description-toggle">
		<span class="text">Развернуть</span>
		<span class="icon"></span>
	</button>

	<script>
		var $descCont = $('.description-content');
		var descContH = $descCont.outerHeight();
			$descCont.css({maxHeight: descContH});
			$descCont
				.velocity({'maxHeight': 100}, {duration: 700});

			$descCont.addClass('close');

		var $descButton = $('.description-toggle');

		$descButton.on('click', function () {
			$descCont
				.velocity("stop")
				.velocity("reverse");

			$descCont.toggleClass('close');

			if( $descCont.hasClass('close') ) {
				$(this).find('.text').text('Развернуть');
			} else {
				$(this).find('.text').text('Свернуть');
			}

		});
	</script>
-->
</div>
<?endif;?>
<?endif;?>