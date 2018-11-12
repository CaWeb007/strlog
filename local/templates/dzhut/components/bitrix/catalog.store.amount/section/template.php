<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$TotalAmount = 0;
$ELEMENT_ID = $arParams['ELEMENT_ID'];

if(CModule::IncludeModule("iblock")){
	$PodZakaz = CIBlockElement::GetList(array(),array("ID"=>$ELEMENT_ID),false,false,array("PROPERTY_POD_ZAKAZ_CHEREZ"));
	$PodZakazResult = $PodZakaz->GetNext();
	$MutchDay  = $PodZakazResult['PROPERTY_POD_ZAKAZ_CHEREZ_VALUE'];

}


?>
<div class="amount_store">
<?php 
	/*echo "<pre>";
	print_r($arParams);
	echo "</pre>";*/
?>
	<?if(!empty($arResult["STORES"])):?>
		<?foreach($arResult["STORES"] as $key => $amountStore){$TotalAmount += $amountStore['AMOUNT'];}?>

		<?if($TotalAmount != 0){?>
			<span>Есть в наличии</span>
		<?}else{?>
			<span>Под заказ  <?=$MutchDay?></span>
		<?}?>
	<?endif;?>
</div>
<?php
/* mmit - 10.08.2016 - Andrey Vlasov - start */
/* реализуем всплывающее окно со сроками доставки товара, если товара доступен только под заказ */
if ($TotalAmount == 0) {
?>
<div style="display: none;" class="notice_delivery_time">
	<p>Уведомлен о том, что срок доставки данного товара под заказ 7-10 рабочих дней</p>
	<button>OK</button>
</div>
<?php
}

/* 10.08.2016 - Andrey Vlasov - end */
?>
<?if (isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1):?>
	<script type="text/javascript">
		var obStoreAmount = new JCCatalogStoreSKU(<? echo CUtil::PhpToJSObject($arResult['JS'], false, true, true); ?>);
	</script>
	<?
endif;?>