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
/* echo "<pre>";
	print_r($arResult);
echo "</pre>"; */
?>

<div class="montazh">
<div class="uslugi-detail">
		<!--PREVIEW TEXT-->
	<?if(strlen($arResult["PREVIEW_TEXT"])>0):?>
		<?=$arResult["PREVIEW_TEXT"]?>
	<?endif;?>
	<!--//PREVIEW TEXT-->
	
	<?if(!empty($arResult["DISPLAY_PROPERTIES"]["ATT_POLZA_PDF"]["VALUE"])):?>
	<table class="top-table">
		<tbody>
	<!--PDF POLZA FILE-->
		<?foreach($arResult["DISPLAY_PROPERTIES"]["ATT_POLZA_PDF"]["VALUE"] as $FilePdf){
			$FilePdfInfo = CFile::GetFileArray($FilePdf);?>
			<tr>
				<td><?=$FilePdfInfo['ORIGINAL_NAME']?></td>
				<td><a href="<?=$FilePdfInfo['SRC']?>">Скачать</a></td>
			</tr>	
		<?}?>
	<!--//PDF POLZA FILE-->
		</tbody>
	</table>
	<?endif;?>
	<!--DATAIL TEXT-->
		<?if(strlen($arResult["DETAIL_TEXT"])>0):?>
			<?=$arResult["DETAIL_TEXT"]?>
		<?endif;?>
		<!--//DATAIL TEXT-->
	<!--PDF FILE INSTR-->
		<?foreach(array_reverse($arResult["DISPLAY_PROPERTIES"]["ATT_FILE_PDF"]["VALUE"]) as $FilePdf){
			$FilePdfInfo = CFile::GetFileArray($FilePdf);
			?>
			<div class="link"><a href="<?=$FilePdfInfo['SRC']?>"><?=str_replace("(.pdf).pdf","",$FilePdfInfo['ORIGINAL_NAME'])?></a></div>	
		<?}?>
	<!--//PDF FILE INSTR-->
	

	
	<!--DESC BOTTOM-->
		<div class="parent-text-bottom">
			<?=$arResult["DISPLAY_PROPERTIES"]["ATT_DESC_BOTTOM"]["DISPLAY_VALUE"]?>
		</div>
	<!--//DESC BOTTOM-->


	

</div>
</div>