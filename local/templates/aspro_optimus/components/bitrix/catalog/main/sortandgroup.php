<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?php
$curDir = $APPLICATION->GetCurDir();
$curPage = $APPLICATION->GetCurPage();

if(!isset($_REQUEST['groupper']) && !isset($_REQUEST['cleargroupper'])) {
	$IBLOCK_ID = 16;
	$SCODE = $arResult['VARIABLES']['SECTION_CODE'];
	$arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'GLOBAL_ACTIVE'=>'Y','CODE'=>$SCODE);
  	$db_list = CIBlockSection::GetList(Array(), $arFilter, false,array("ID","UF_GROUP_PROP"));
	if($ar_result = $db_list->GetNext()){
		if($ar_result['UF_GROUP_PROP']){
			$properties = CIBlockProperty::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID,'CODE'=>$ar_result['UF_GROUP_PROP']));
			if ($prop_fields = $properties->GetNext())
			{
				global $_REQUEST;
			 	$_REQUEST['groupperID'] =  $prop_fields["ID"];
				$_REQUEST['groupperTitle'] = $prop_fields["NAME"];
				$_REQUEST['groupper'] = $prop_fields['CODE'];
			}

		}
	}
}

/*if($_REQUEST['groupper'] && !empty($_REQUEST['groupperID'])) {
	$property_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 16, "PROPERTY_ID" => $_GET['groupperID']));
		while ($enum_fields = $property_enums->GetNext(true, false)) {
			//$enum_fields['VALUE'] = str_replace(array(',', '-'), ['.',''], $enum_fields['VALUE']);
			//$enum_fields['VALUE'] = str_replace(array(',', '-'), '', $enum_fields['VALUE']);
			if($enum_fields['PROPERTY_ID'] == $_REQUEST['groupperID']){
				//var_dump("<pre>",$enum_fields,is_numeric($enum_fields['VALUE']));
				if (is_numeric($enum_fields['VALUE'])) {
					$typeProp = 'PROPERTYSORT_';
				} else {
					$typeProp = 'PROPERTY_';
				}
			}
}
}*/
$typeProp = 'PROPERTYSORT_';
$arParams["ELEMENT_SORT_FIELD2"] = "catalog_QUANTITY";
$arParams["ELEMENT_SORT_ORDER2"] = "ASC, nulls";

if(!isset($_REQUEST['groupper'])) {
    $groupper = 'catalog_PRICE_11';
    $groupperTitle = 'Без группировки';
} else {
    $groupperID = $_REQUEST['groupperID'];
	$groupper = $typeProp.$_REQUEST['groupper'];
    $groupperTitle = $_REQUEST['groupperTitle'];

	$arParams["ELEMENT_SORT_FIELD"] =  $groupper;
	$arParams["ELEMENT_SORT_ORDER"] = "ASC, nulls";
}

if(count($arParams["PRICE_CODE"])>0){
	$priceNames = [];
	foreach($arParams["PRICE_CODE"] as $PCODE){
		$res = \CCatalogGroup::GetList(array(), ["=NAME"=>$PCODE], false, false, array('ID','NAME')); 	
		if ($group = $res->Fetch())  
		{
			$PRICE_IDS[] = $group['ID'];
			/*if(in_array($group['NAME'],$arParams["PRICE_CODE"]) == true){
				$priceNames[$group['NAME']] = $group['ID'];
			};*/
		}
	}
	if(0 < count($PRICE_IDS)){
		$PRICE_IDS = array_unique($PRICE_IDS);
		$db_res = CCatalogGroup::GetGroupsList(["@CATALOG_GROUP_ID"=>$PRICE_IDS]);
		while ($ar_res = $db_res->Fetch())
		{
			$arGroups[$ar_res['GROUP_ID']] = $ar_res['GROUP_ID'];
			$priceGroups[$ar_res['GROUP_ID']] = $ar_res['CATALOG_GROUP_ID'];
		}
	}
	if(0 < count($priceGroups)){

		$userGroups = CUser::GetUserGroup($USER->GetID());
		$userGroups =array_flip($userGroups);
		$intersect = array_intersect_key($priceGroups, $userGroups);
		if(!empty($intersect)){
			foreach($intersect as $key=>$empty){
				if($key == 1){
					unset($intersect[$key]);
				}
			}
			$PRICE_ID = current($intersect);
		}

	}
	//if(isset($_GET['sorter']))
	$arParams["ELEMENT_SORT_FIELD2"] = "CATALOG_PRICE_". ($PRICE_ID?$PRICE_ID:"9");
}


if(!isset($_GET['sorter'])) {
	$sorter = 'ASC';
	$sorterTitle = 'Без сортировки';
} else {
    $sorter = $_GET['sorter'];
	if($sorter == "ASC")
		$sorterTitle = 'По возрастанию';
	if($sorter == "DESC")
		$sorterTitle = 'По убыванию';
}

$arParams["ELEMENT_SORT_ORDER2"] = $sorter;

if(isset($_GET['cleargroupper'])) unset($_GET['cleargroupper']);
//printArr('d');

//$arParams["ELEMENT_SORT_FIELD2"] = "";
//$arParams["ELEMENT_SORT_ORDER2"] = "";


?>
<? $basePage = preg_replace("#filter(.*)#siu","",$APPLICATION->GetCurPage());?>
<div class="group-groups-avail-lists-wrapper">
    <div class="group-list-wrapper">
        <ul class="group-list">
            <li class="first-group-item">Группировать по:<span class="first-group-item-title"><?=$groupperTitle;?></span></li>
            <?php
foreach(CIBlockSectionPropertyLink::GetArray($arParams["IBLOCK_ID"], $arSection["ID"]) as $PID => $arLink) {//printArr($arLink);
                if($arLink["SMART_FILTER"] !== "Y"){
                    continue;
                } else {
                    $sectionPropsArray = CIBlockProperty::GetByID($PID, $arParams['IBLOCK_ID'], $arSection['ID']);
                    if ($sectionPropArray = $sectionPropsArray->GetNext()) {?>
                    <li class="group-item">
                        <a class="group-item-link"
                            name="groupper"
							href="<?=$basePage?>?<?=http_build_query(
                                array_merge(
                                    $_GET, array(
                                        "groupper" => $sectionPropArray["CODE"],
                                        "groupperTitle" => $sectionPropArray["NAME"],
                                        "groupperID" => $sectionPropArray["ID"],
                                    )
                                )
                            );?>">
                            <?=$sectionPropArray["NAME"];?>
                        </a>
                    </li>
                    <?php }
                }
            }
            ?>
			<?$get = $_GET;
			if(isset($get["groupper"])) unset($get["groupper"]);
			if(isset($get["groupperTitle"])) unset($get["groupperTitle"]);
			if(isset($get["groupperID"])) unset($get["groupperID"]);
			?>
			<li class="group-item">
				<a class="group-item-link"
					name="groupper"
					href="<?=$basePage?>?cleargroupper&<?=http_build_query($get);?>">
					Без группировки
				</a>
			</li>
        </ul>
    </div>
    <div class="sort-list-wrapper">
        <ul class="sort-list">
            <li class="first-sort-item">Сортировать по:<span class="first-sort-item-title"><?=$sorterTitle?></span></li>
            <li class="sort-item">
                <a class="sort-item-link"
                    name="sorter"
                    href="<?=$basePage?>?<?=http_build_query(array_merge($_GET, array("sorter" => "ASC")));?>">
                    Возрастанию цены
                </a>
            </li>
            <li class="sort-item">
                <a class="sort-item-link"
                    name="sorter"
                   href="<?=$basePage?>?<?=http_build_query(array_merge($_GET, array("sorter" => "DESC")));?>">
                    Убыванию цены
                </a>
            </li>
			<?$get = $_GET; if(isset($get["sorter"])) unset($get["sorter"]);?>
            <li class="sort-item">
                <a class="sort-item-link"
                    name="sorter"
                   href="<?=$basePage?>?<?=http_build_query($get);?>">
                    Без сортировки
                </a>
            </li>
        </ul>
    </div>
	<?if(isset($_GET['sorter']) || isset($_GET['groupper'])):?>
	<?// $basePage = preg_replace("#filter(.*)#siu","",$APPLICATION->GetCurPage());?>
	<div class="reset"><a href="<?=$basePage;?>filter/clear/apply/?cleargroupper">Сбросить<div class="sorter-list-clear">&times;</div></a></div>
	<?endif?>
</div>













