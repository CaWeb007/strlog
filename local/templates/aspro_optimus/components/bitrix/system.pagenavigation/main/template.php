<?$this->setFrameMode(true);?>
<?if($arResult["NavPageCount"] > 1):?>
	<?
	$count_item = 2;
	$arResult["nStartPage"] = $arResult["NavPageNomer"] - $count_item;
	$arResult["nStartPage"] = $arResult["nStartPage"] <= 0 ? 1 : $arResult["nStartPage"];
	$arResult["nEndPage"] = $arResult["NavPageNomer"] + $count_item;
	$arResult["nEndPage"] = $arResult["nEndPage"] > $arResult["NavPageCount"] ? $arResult["NavPageCount"] : $arResult["nEndPage"];

	if($arResult["NavQueryString"] != "")
	{
		$arTmp = explode("&amp;", $arResult["NavQueryString"]);
		if($arTmp)
		{
			foreach($arTmp as $key => $value)
			{
				if(strpos($value, "ajax_get") !== false)
				{
					unset($arTmp[$key]);
				}
			}
			if(!$arTmp)
				$arResult["NavQueryString"] = "";
			else
				$arResult["NavQueryString"]  = implode("&amp;", $arTmp);
		}
	}

	$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
	$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
	if($arResult["NavPageNomer"] == 1){
		$bPrevDisabled = true;
	}
	elseif($arResult["NavPageNomer"] < $arResult["NavPageCount"]){
		$bPrevDisabled = false;
	}
	if($arResult["NavPageNomer"] == $arResult["NavPageCount"]){
		$bNextDisabled = true;
	}
	else{
		$bNextDisabled = false;
	}
	?>
	<?if(!$bNextDisabled){?>
		<div class="ajax_load_btn">
			<span class="more_text_ajax"><?=GetMessage('PAGER_SHOW_MORE')?></span>
		</div>
	<?}?>
	<div class="module-pagination">
		<div class="nums">
			<ul class="flex-direction-nav">
				<li class="flex-nav-prev <?if($bPrevDisabled){echo " disabled";}?>">
					<?if(!$bPrevDisabled):?>
						<?$prev = ($arResult["NavPageNomer"] <= 2 ? $strNavQueryStringFull : '?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1));?>
						<a href="<?=$arResult["sUrlPath"]?><?=$prev;?>" class="flex-prev"></a>
					<?endif;?>
				</li>
				<li class="flex-nav-next <?if($bNextDisabled){echo " disabled";}?>">
					<?if(!$bNextDisabled):?>
						<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="flex-next"></a>
					<?endif;?>
				</li>
			</ul>
			<?if($arResult["nStartPage"] > 1):?>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
				<span class='point_sep'></span>
			<?endif;?>
			<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
				<?if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
					<span class="cur"><?=$arResult["nStartPage"]?></span>
				<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
					<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
				<?else:?>
					<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
				<?endif;?>
				<?$arResult["nStartPage"]++;?>
			<?endwhile;?>
			<?if($arResult["nEndPage"] < $arResult["NavPageCount"]):?>
				<span class='point_sep'></span>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
			<?endif;?>
			<?if ($arResult["bShowAll"]):?>
			<noindex>
				<div class="all_block_nav">
					<?if ($arResult["NavShowAll"]):?>
						<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" class="link" rel="nofollow"><?=GetMessage("nav_paged")?></a>
					<?else:?>
						<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" class="link" rel="nofollow"><?=GetMessage("nav_all")?></a>
					<?endif?>
				</div>
			</noindex>
			<?endif?>
		</div>
	</div>
<?endif;?>