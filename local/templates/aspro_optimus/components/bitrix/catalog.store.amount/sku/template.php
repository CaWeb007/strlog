<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<?
if(strlen($arResult["ERROR_MESSAGE"]) > 0){
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
<?if(count($arResult["STORES"]) > 0):?>
	<?
	// get shops
	$arShops = array();
	CModule::IncludeModule('iblock');
	$dbRes = CIBlock::GetList(array(), array('CODE' => 'aspro_optimus_shops', 'ACTIVE' => 'Y', 'SITE_ID' => SITE_ID));
	if ($arShospIblock = $dbRes->Fetch()){
		$dbRes = CIBlockElement::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arShospIblock['ID']), false, false, array('ID', 'DETAIL_PAGE_URL', 'PROPERTY_STORE_ID'));
		while($arShop = $dbRes->GetNext()){
			$arShops[$arShop['PROPERTY_STORE_ID_VALUE']] = $arShop;
		}
	}
	?>
		<?$empty_count=0;
		$count_stores=count($arResult["STORES"]);?>
		<?foreach($arResult["STORES"] as $pid => $arProperty):?>

            <?$totalCount = COptimus::CheckTypeCount($arProperty["NUM_AMOUNT"]);?>
            <div class="store-item">
                <div class="store-title">
                    <?=$arProperty["ADDRESS"]?>
                </div>
                <div class="store-amount">
                    <?=\COptimus::GetQuantityArray($totalCount)['HTML']?>
                </div>
            </div>
		<?endforeach;?>

<?endif;?>