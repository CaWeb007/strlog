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

<br><br>
<div class="faq-list">
<?if($APPLICATION->GetCurPage() == "/kak-oplatit/"): ?> 
Оформите заказ, определив способ оплаты. Оплатить заказ можно следующими способами:<br><br>
<?endif; //getcurpage?>
<?if($APPLICATION->GetCurPage() == "/kak-zakazat/"): ?> 
Вы можете заказать товары любым удобным для вас способом:<br><br>
<?endif; //getcurpage?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
		<div class="faq-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="faq-title">
			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
					<h4 class="orange"><?echo $arItem["NAME"]?></h4>
			<?endif;?>
			</div>
			<div class="faq-text">
			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
				<p><?echo $arItem["PREVIEW_TEXT"];?></p>
			<?endif;?>
			</div>
		</div>

<?endforeach;?>
<?if($APPLICATION->GetCurPage() == "/kak-oplatit/"): ?> 
<div class="safety">
	<div class="safety-ico"></div>
	<div class="safety-text">
		<h5>Безопасность</h5>
		<p style="text-indent:0px;">Безопасность платежей гарантируется использованием SSL протокола.<br> 
При оплате онлайн - Ваши данные будут надежно защищены .</p>
	</div>
</div>
	<?endif; //getcurpage?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>