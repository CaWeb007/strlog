<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?><div class="<? echo $arCurView['CONT']; ?>"><?
if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
{
	$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

	?>
<?
}
if (0 < $arResult["SECTIONS_COUNT"])
{
?>
<ul class="cat-list">
<?
foreach ($arResult['SECTIONS'] as &$arSection){
	
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?>
				<?if($arSection['ELEMENT_CNT']!=0):?>
				<li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_tile_img"
					style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>">
				</a>
					<?
					if ('Y' != $arParams['HIDE_SECTION_NAME'])
					{
					?>
						<h4 class="cat-list-title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a></h4>
					<?
					}
					?>
						<?
							if ($arParams["COUNT_ELEMENTS"])
							{
							?> 
							<div class="count-elem"><? echo $arSection['ELEMENT_CNT'] ." ". declension($arSection['ELEMENT_CNT'],GetMessage('CAT_COUNT_ONE'),GetMessage('CAT_COUNT_ODD'),GetMessage('CAT_COUNT_EVEN')); ?></div>
							<?
							}
						?>
					
				</li>
				<?else:
					continue;
				endif;?>
				
<?}unset($arSection);?>
	<?if($APPLICATION->GetCurDir() == '/catalog/utepliteli-zvukoizolyatsiya/'):?>
		<li style="display:none;">
			<a class="bx_catalog_tile_img" href="#" style="background-image: url(images/);"></a>
			<h4 class="cat-list-title"><a href="#">Утеплитель</a></h4>
			<div class="count-elem">20 товаров</div>
		</li>
	<?elseif($APPLICATION->GetCurDir() == '/catalog/izolyatsionnye-materialy/'):?>
		<li style="display:none;">
			<a class="bx_catalog_tile_img" href="#" style="background-image: url(images/);"></a>
			<h4 class="cat-list-title"><a href="#">Изоляционный материал</a></h4>
			<div class="count-elem">20 товаров</div>
		</li>
	<?endif;?>
</ul>
<?
	
}
?></div>