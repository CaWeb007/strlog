<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="banners_column">
	<div class="small_banners_block">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$img = (($arItem["PREVIEW_PICTURE"] || $arItem["DETAIL_PICTURE"]) ? CFile::ResizeImageGet(($arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"] : $arItem["DETAIL_PICTURE"]), array("width" => 220, "height" => 270), BX_RESIZE_IMAGE_EXACT , true) : false);
            $link = $arItem["PROPERTIES"]["URL_STRING"]["VALUE"];
            if ($link && $arItem['DISPLAY_PROPERTIES']['MARKER_ORD']['VALUE'])
                $link = \Caweb\Main\Tools::getInstance()->getMarkerOrdUri($arItem['DISPLAY_PROPERTIES']['MARKER_ORD']['VALUE'] , $link);
			?>
			<?if($img):?>
				<div class="advt_banner" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($link):?>
						<a href="<?=$link?>" <?=($arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"] ? "target='".$arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"]."'" : "");?>>
					<?endif;?>
						<img src="<?=$img["src"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
					<?if(strlen($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])):?>
						</a>
					<?endif;?>
                    <?if ($link && $arItem['DISPLAY_PROPERTIES']['MARKER_ORD']['VALUE']):?>
                        <div class="ord-link">
                            Реклама
                            <i class="fa fa-angle-right"></i>
                            <input type="text" class="ord-link-href" value="<?=$link?>">
                        </div>
                    <?endif?>
				</div>
			<?endif;?>
		<?endforeach;?>
	</div>
</div>