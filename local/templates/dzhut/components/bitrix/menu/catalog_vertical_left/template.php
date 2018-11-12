<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


/*require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/main/include/prolog_before.php");*/

if (empty($arResult["ALL_ITEMS"]))
return;


// количесво разделов
/*
if(!function_exists('getCountsSection')){
function getCountsSection($NameCat){
 
$arFilter = Array('IBLOCK_TYPE'=>'%catalog%', 'GLOBAL_ACTIVE'=>'Y','ACTIVE'=>'Y','CNT_ACTIVE'=>'Y', 'PROPERTY'=>Array('SRC'=>'https://%'));
  $db_list = CIBlockSection::GetList(Array(), $arFilter, true);
  while($ar_result = $db_list->GetNext())
  {
  if($NameCat == $ar_result['NAME']){
  echo $ar_result['ELEMENT_CNT'];
  }
  //echo $ar_result['ID'].' '.$ar_result['NAME'].': '.$ar_result['ELEMENT_CNT'].'<br>';
  }
}
}*/
?>

<div class="bx_vertical_menu_advanced bx_<?=$arParams["MENU_THEME"]?> catalog-top trigger-cat" id="<?=$menuBlockId?> ">
<ul>
<li class="bx_hma_one_lvl catalog-top" onmouseover="BX.CatalogVertMenu.itemOver(this);" onmouseout="BX.CatalogVertMenu.itemOut(this)"><a title="Каталог товаров" href="/catalog/">Каталог товаров</a></li> 
<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?> 
<li onmouseover="BX.CatalogVertMenu.itemOver(this);" onmouseout="BX.CatalogVertMenu.itemOut(this)" class="bx_hma_one_lvl <?if($arResult["ALL_ITEMS"][$itemID]["SELECTED"]):?>current<?endif?><?if (is_array($arColumns) && count($arColumns) > 0):?> dropdown<?endif?>">
<a class="first-level <?if (is_array($arColumns) && count($arColumns) > 0){echo 'have-sub';} ?>" href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>" title="<?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?>"><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?></a>
<?if (is_array($arColumns) && count($arColumns) > 0):?>
<div class="bx_children_container b<?=($existPictureDescColomn) ? count($arColumns)+1 : count($arColumns)?>">
<?foreach($arColumns as $key=>$arRow):?>
<div class="bx_children_block">
<ul>
<?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>
<li class="parent"><a title="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?>" href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>"><?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?></a>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/bazalt-minplita/'):?>
<ul>
<li><a href="/catalog/bazalt-minplita/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#ТехноНИКОЛЬ">ТехноНИКОЛЬ</a></li>
<li><a href="/catalog/bazalt-minplita/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#TEPlit">TEPlit</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/steklovata/'):?>
<ul>
<li><a href="/catalog/steklovata/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#ISOVER">ISOVER</a></li>
<li><a href="/catalog/steklovata/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#Knauf">KNAUF</a></li>
<li><a href="/catalog/steklovata/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#Тепломастер">Тепломастер</a></li>
</ul></li>
<?endif;?>

<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/ekstruziya-xps/'):?>
<ul>
<li><a href="/catalog/ekstruziya-xps/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#Пеноплэкс">Пеноплэкс</a></li>
<li><a href="/catalog/ekstruziya-xps/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#ТехноНИКОЛЬ">ТехноНИКОЛЬ</a></li>
<li><a href="/catalog/ekstruziya-xps/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#THERMIT">THERMIT</a></li>
<li><a href="/catalog/ekstruziya-xps/?groupper%5B1%5D=property_PROIZVODITEL_1&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=производителю#Термоплекс">Термоплекс</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/vetro-gidro-paroizolyatsionnye-membrany_1/'):?>
<ul>
<li><a href="/catalog/vetro-gidro-paroizolyatsionnye-membrany_1/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#пароизоляция">Пароизоляция</a></li>
<li><a href="/catalog/vetro-gidro-paroizolyatsionnye-membrany_1/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#ветро-гидроизоляция">Ветро-гидроизоляция</a></li>
<li><a href="/catalog/vetro-gidro-paroizolyatsionnye-membrany_1/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#гидро-пароизоляция">Гидро-пароизоляция</a></li>
<li><a href="/catalog/vetro-gidro-paroizolyatsionnye-membrany_1/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#отражающая изоляция">Отражающая изоляция</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/naplavlyaemye-materialy/'):?>
<ul>
<li><a href="/catalog/naplavlyaemye-materialy/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Бикрост">Бикрост</a></li>
<li><a href="/catalog/naplavlyaemye-materialy/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Биполь">Биполь</a></li>
<li><a href="/catalog/naplavlyaemye-materialy/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Линокром">Линокром</a></li>
<li><a href="/catalog/naplavlyaemye-materialy/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Стеклоизол">Стеклоизол</a></li>
<li><a href="/catalog/naplavlyaemye-materialy/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Техноэласт">Техноэласт</a></li>
<li><a href="/catalog/naplavlyaemye-materialy/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Унифлекс">Унифлекс</a></li>
</ul>
<ul>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/sayding/'):?>
<ul>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Сайдинг">Панели</a></li>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Профиль">Профиль</a></li>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Угол">Угол</a></li>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Молдинг">Молдинг</a></li>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Фаска">Фаска</a></li>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Отлив">Отлив</a></li>
<li><a href="/catalog/sayding/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Соффит">Соффит</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/fasadnaya-plitka/'):?>
<ul>
<li><a href="/catalog/fasadnaya-plitka/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Фасадная плитка">Фасадная плитка</a></li>
<li><a href="/catalog/fasadnaya-plitka/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Уголки">Уголки</a></li>
<li><a href="/catalog/fasadnaya-plitka/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Клей">Клей</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/docke/'):?>
<ul>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Желоба">Желоба</a></li>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Воронки">Воронки</a></li>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Трубы">Трубы</a></li>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Заглушки">Заглушки</a></li>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Решетки">Решетки</a></li>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Кронштейны">Кронштейны</a></li>
<li><a href="/catalog/docke/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Хомуты">Хомуты</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/verat/'):?>
<ul>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Желоба">Желоба</a></li>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Воронки">Воронки</a></li>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Трубы">Трубы</a></li>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Заглушки">Заглушки</a></li>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Решетки">Решетки</a></li>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Кронштейны">Кронштейны</a></li>
<li><a href="/catalog/verat/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Хомуты">Хомуты</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/gipsokarton-gipsovolokno/'):?>
<ul>
<li><a href="/catalog/gipsokarton_1/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#стандартный">стандартный</a></li>
<li><a href="/catalog/gipsokarton_1/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#влагостойкий">влагостойкий</a></li>
</ul>
<?endif;?>
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/profili-napravlyayushchie/'):?>
<ul>
<li><a href="/catalog/profili/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#направляющий">Направляющий профиль</a></li>
<li><a href="/catalog/profili/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#потолочный">Потолочный профиль</a></li>
<li><a href="/catalog/profili/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#маячковый">Стоечный профиль</a></li>
<li><a href="/catalog/profili/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#углозащитный">Углозащитный профиль</a></li>
<li><a href="/catalog/profili/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#маячковый">Маячковый профиль</a></li>
<li><a href="/catalog/profili/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#подвесы">Подвесы</a></li>
</ul>
<?endif;?>


<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/paklya/'):?>
<ul>
<li><a href="/catalog/paklya/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу№тюковая">Пакля тюковая</a></li>
<li><a href="/catalog/paklya/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#ленточная">Пакля ленточная</a></li>
<li><a href="/catalog/paklya/?groupper%5B1%5D=property_TIP&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=типу#фасованная">Пакля фасованная</a></li>
</ul>
<?endif;?>
<!--
<?if($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] == '/catalog/pechnoe-lite/'):?>
<ul>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Дверцы поддувальные">Дверцы поддувальные</a></li>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Дверцы прочистные">Дверцы прочистные</a></li>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Дверцы топочные">Дверцы топочные</a></li>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Задвижки">Задвижки</a></li>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Конфорки">Конфорки</a></li>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров#Плиты">Плиты</a></li>
<li><a href="/catalog/pechnoe-lite/?groupper%5B1%5D=property_GRUPPA_TOVAROV&groupper%5B2%5D=asc%2Cnulls&groupper%5B3%5D=группе+товаров&PAGEN_2=2#Решетки">Решетки</a></li>
</ul>
<?endif;?>-->


<?if (is_array($arLevel_3) && count($arLevel_3) > 0):?>
<ul>
<?foreach($arLevel_3 as $itemIdLevel_3):?>
<li><a title="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?>" href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"]?>"><?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?></a></li>


<?endforeach;?></ul><?endif?></li><?endforeach;?></ul></div><?endforeach;?><div style="clear: both;"></div></div><?endif?></li><?endforeach;?></ul>
<div style="clear: both;"></div>
</div>