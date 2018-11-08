<?$arDisplays = array("block", "list", "table");
if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]){
	if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))){
		$display = trim($_REQUEST["display"]);
		$_SESSION["display"]=trim($_REQUEST["display"]);
	}
	elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))){
		$display = $_SESSION["display"];
	}
	elseif($arSection["DISPLAY"]){
		$display = $arSection["DISPLAY"];
	}
	else{
		$display = $arParams["DEFAULT_LIST_TEMPLATE"];
	}
}
else{
	$display = "block";
}
$template = "catalog_".$display;
?>

<div class="sort_header view_<?=$display?>">
	<!--noindex-->
		<div class="sort_display">	
			<?foreach($arDisplays as $displayType):?>
				<?
				$current_url = '';
				$current_url = $APPLICATION->GetCurPageParam('display='.$displayType, 	array('display'));
				$url = str_replace('+', '%2B', $current_url);
				?>
				<a rel="nofollow" href="<?=$url;?>" class="sort_btn <?=$displayType?> <?=($display == $displayType ? 'current' : '')?>"><i title="<?=GetMessage("SECT_DISPLAY_".strtoupper($displayType))?>"></i></a>
			<?endforeach;?>
		</div>
	<!--/noindex-->
</div>