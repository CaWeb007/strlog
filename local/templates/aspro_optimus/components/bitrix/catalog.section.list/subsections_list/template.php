<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?
CModule::IncludeModule("iblock");
$sectionID = $arResult["SECTION"]["ID"];
foreach($arResult["SECTIONS"] as $sectionList => $section){
    $sectionCount = CIBlockSection::GetCount(array('IBLOCK_ID' => "16",'SECTION_ID' => $sectionID));
}
$elementCount=0;
$itemsCount = GetIBlockElementList(16, $sectionID, Array("SORT"=>"ASC"));
while($itemCount = $itemsCount->GetNext()){
    $elementCount++;
}?>
<?if($arResult["SECTIONS"]){?>
<div class="catalog_section_list rows_block items section">	
	<?foreach( $arResult["SECTIONS"] as $arItems ){
		$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
        <?if($sectionCount != '' && $elementCount != ''):?>
            <div class="item_block col-auto">
                <div class="sub_section_item item" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
					<a class="name" href="<?=$arItems["SECTION_PAGE_URL"]?>"><span><?=$arItems["NAME"]?> <i class="section_count_1"><?=$elementsQuantity?></i></span></a>
                </div>
            </div>
        <?else:?>
        <?php $elementsQuantity = CIBlockSection::GetSectionElementsCount($arItems["ID"], Array("CNT_ACTIVE"=>"Y"));?>
		<div class="item_block col-2">
			<div class="section_item item" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
				<table class="section_item_inner">	
					<tr>
						<?if ($arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
							<?$collspan = 2;?>
							<td class="image">
								<?if($arItems["PICTURE"]["SRC"]):?>
									<?$img = CFile::ResizeImageGet($arItems["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true );?>
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItems["PICTURE"]["ALT"] ? $arItems["PICTURE"]["ALT"] : $arItems["NAME"])?>" title="<?=($arItems["PICTURE"]["TITLE"] ? $arItems["PICTURE"]["TITLE"] : $arItems["NAME"])?>" /></a>
								<?elseif($arItems["~PICTURE"]):?>
									<?$img = CFile::ResizeImageGet($arItems["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true );?>
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItems["PICTURE"]["ALT"] ? $arItems["PICTURE"]["ALT"] : $arItems["NAME"])?>" title="<?=($arItems["PICTURE"]["TITLE"] ? $arItems["PICTURE"]["TITLE"] : $arItems["NAME"])?>" /></a>
								<?else:?>
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItems["NAME"]?>" title="<?=$arItems["NAME"]?>" height="90" /></a>
								<?endif;?>
							</td>
						<?endif;?>
						<td class="section_info">
							<ul>
								<li class="name">
									<a title="<?=$arItems["NAME"]?>" href="<?=$arItems["SECTION_PAGE_URL"]?>"><span><?=$arItems["NAME"]?></span></a> <i style="cursor: default;user-select: none;"><?=$elementsQuantity;?></i>
								</li>
							</ul>
							<?if($arParams["SECTIONS_LIST_PREVIEW_DESCRIPTION"] != 'N'):?>
								<?$arSection = $section=COptimusCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arItems["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]));?>
								<div class="desc" ><span class="desc_wrapp">
									<?if ($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]):?>
										<?=strip_tags(substr($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]], 0, 170));?>...
									<?else:?>
										<?=$arItems["DESCRIPTION"]?>
									<?endif;?>
								</span></div>
							<?endif;?>
						</td>
					</tr>
				</table>
			</div>
		</div>
        <?endif;?>
	<?}?>
</div>
<script>
	$(document).ready(function(){
		$('.catalog_section_list.rows_block .item .section_info').sliceHeight();
		$('.catalog_section_list.rows_block .item').sliceHeight();
		setTimeout(function(){
			$(window).resize();
		},100)
	});
</script>
<?}?>