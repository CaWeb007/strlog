<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?php
$curDir = $APPLICATION->GetCurDir();
$curPage = $APPLICATION->GetCurPage();

if(!isset($_GET['groupper'])) {
    $sort = 'catalog_PRICE_11';
    $sortTitle = 'Без группировки';
} else {
    $sort = 'PROPERTY_'.$_GET['groupper'];
    $sortTitle = $_GET['groupperTitle'];
}
if(!isset($_GET['sorter'])) {
	$sort_order = 'ASC';
	$sort_order_title = 'По возрастанию';
} else {
	$sort_order = $_GET['sorter'];
}

?>
<div class="group-groups-avail-lists-wrapper">
    <div class="group-list-wrapper">
        <ul class="group-list">
            <li class="first-group-item">Группировать по:<span class="first-group-item-title"><?=$sortTitle;?></span></li>
            <?php
            foreach(CIBlockSectionPropertyLink::GetArray($arParams["IBLOCK_ID"], $arSection["ID"]) as $PID => $arLink){
                if($arLink["SMART_FILTER"] !== "Y"){
                    continue;
                }else{
                    $sectionPropsArray = CIBlockProperty::GetByID($PID, $arParams['IBLOCK_ID'], $arSection['ID']);
                    if ($sectionPropArray = $sectionPropsArray->GetNext()) {?>
                    <li class="group-item">
                        <a class="group-item-link"
                            name="groupper"
                            href="?<?=http_build_query(array_merge($_GET, array("groupper" => $sectionPropArray["CODE"], "groupperTitle" => $sectionPropArray["NAME"])));?>">
                            <?=$sectionPropArray["NAME"];?>
                        </a>
                    </li>
                    <?php }
                }
            }
            ?>
        </ul>
    </div>
    <div class="sort-list-wrapper">
        <ul class="sort-list">
            <li class="first-sort-item">Сортировать по:<span class="first-sort-item-title">Без сортировки</span></li>
            <li class="sort-item">
                <a class="sort-item-link"
                    name="sorter"
                    href="?<?=http_build_query(array_merge($_GET, array("sorter" => "ASC")));?>">
                    Возрастанию цены
                </a>
            </li>
            <li class="sort-item">
                <a class="sort-item-link"
                    name="sorter"
                   href="?<?=http_build_query(array_merge($_GET, array("sorter" => "DESC")));?>">
                    Убыванию цены
                </a>
            </li>
        </ul>
    </div>
</div>




















