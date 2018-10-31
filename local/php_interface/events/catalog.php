<?php

/* Выбор ценовой группы пользователя в каталоге */
function changePriceID(){
	global $USER,$GLOBAL;
	
	if(!isset($GLOBAL['PRICE_CODE']) || 0 == count($GLOBAL['PRICE_CODE'])) {
		// Выбор типа цены в зависимости от группы
		$PRICE_CODE = [9=>'ТО',10=>'СО',11=>'КП',14=>'С'];
		$PRICE_IDS = [9,10,11,14];
		if(0 < count($PRICE_IDS)){
			$db_res = \CCatalogGroup::GetGroupsList(["@CATALOG_GROUP_ID"=>$PRICE_IDS]);
			while ($ar_res = $db_res->Fetch())
			{
				$arGroups[$ar_res['GROUP_ID']] = $ar_res['GROUP_ID'];
				$priceGroups[$ar_res['GROUP_ID']][$ar_res['CATALOG_GROUP_ID']] = $ar_res['CATALOG_GROUP_ID'];
			}
		}
		if($USER->IsAuthorized()){
			
			if(0 < count($priceGroups)){
				
				$userGroups = \CUser::GetUserGroup($USER->GetID());
				
				$userGroups = array_diff($userGroups, [1,2]);
				
				$userGroups =array_flip($userGroups);
				
				$intersect = array_intersect_key($priceGroups, $userGroups);
				
				if(!empty($intersect) && 1 == count($intersect)){
						
					$prices = current($intersect);
					
					if(is_array($prices)){
						foreach($prices as $price){
							$GLOBAL['PRICE_CODE'][] = $PRICE_CODE[$price];
						}
					} else {
						$GLOBAL['PRICE_CODE'] = [$PRICE_CODE[$prices]];
					}

				}

			}
			
		} else {
			$GLOBAL['PRICE_CODE'] = [$PRICE_CODE[11]];
		}
	}
	
	return $GLOBAL['PRICE_CODE'];
}

/* Функция для определения существования ценовой группы пользователя */

function isIssetPG($value){
	
	global $USER,$GLOBAL;
	
	$userID = $USER->GetID();
	$userGroups = \CUser::GetUserGroup($userID);
	
	if(in_array("11",$userGroups) !== false){
		if(!isset($GLOBAL['prGroups'])) {
			
			$mass = [];
			$rsUser = CUser::GetByID($userID);
			if($arUSer = $rsUser->fetch()){
				if(isset($arUSer['UF_PRICE_GROUPS']) && $arUSer['UF_PRICE_GROUPS']){
					$mass = explode(";", $arUSer['UF_PRICE_GROUPS']);
				}
			}
			$GLOBAL['prGroups'] = $mass;
		}

		$mass = $GLOBAL['prGroups'];
		return in_array($value,$mass);
	}
	
	return false;
}

/* Функция показа бонусов */
function showProductBonus($arResult,$detail=false){
	global $USER;
	if($USER->IsAuthorized()){
		$userGroups = array(11, 12);
		$arGroups = CUser::GetUserGroup($USER->GetID());
		$result = array_intersect($userGroups, $arGroups);
	
		if(!empty($result)){
			return '<span class="bonuses-quantity-title"></span>';
		} else {
			if((float)$arResult["PROPERTIES"]["_POROGA_NACHISLENIYA_BONUSOV"]["VALUE"] == 0) {
				$totalBonus = 0;
			} else {
				$userGroups = array(9, 14);
				$result = array_intersect($userGroups, $arGroups);
				if(count($result) > 0){
					$totalBonus = $arResult["PROPERTIES"]["BONUS_KP"]["VALUE"];
				}
		
				$userGroups = array(10, 15);
				$result = array_intersect($userGroups, $arGroups);
				if(count($result) > 0){
					$totalBonus = $arResult["PROPERTIES"]["BONUS_SO"]["VALUE"];
				}
			}
	
			return '
				<div class="bonuses-wrapper bonuses-wrapper-list'.($detail?' bonuses-wrapper-detail-page':'').'">
					<span class="bonuses-quantity-title">Бонус: </span>
					<span class="bonuses-quantity-desc">'.$totalBonus.'</span>
					<div class="bonuses-popup-wrapper'.($detail?' bonuses-popup-wrapper-detail-page':'').'">
						<div class="bonuses-popup">
							<span class="bonuses-quantity-title">
								Копите бонусы и оплачивайте ими ваши покупки<br />
								1 бонус = 1 рубль<br />
								<a href="http://strlogclub.ru/about/" target="_blank">Узнать больше</a>
							</span>
						</div>
					</div>
				</div>';
		}
	} else {
		return '<div class="bonuses-wrapper bonuses-wrapper-list'.($detail?' bonuses-wrapper-detail-page':'').'">
					<span class="bonuses-quantity-title title-disc">Хочешь дешевле? </span>
					<div class="bonuses-popup-wrapper'.($detail?' bonuses-popup-wrapper-detail-page':'').'">
						<div class="bonuses-popup">
						<span class="bonuses-quantity-title">
							Для тех кто с нами - цены ниже!<br><a href="/auth/">Вход</a>
						</span>
						</div>
					</div>
				</div>';
	}

}

/* обновление фасетных индексов, работает как Агент */
function updateFacetIndex($IBLOCK_ID=16){
	
	$index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer($IBLOCK_ID);

	$index->startIndex();

	$index->continueIndex(0); // создание без ограничения по времени

	$index->endIndex();
	
	return "updateFacetIndex(16);";
}

?>