<?
#OnSaleBasketItemRefreshData
\Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OonSaleBasketBeforeSaved', 'OnSaleBasketItemRefreshDataHandler');
function OnSaleBasketItemRefreshDataHandler(\Bitrix\Main\Event $event) {
	$basketItem = $event->getParameter('ENTITY');
	//$basketValues = $event->getParameter('VALUES');

	//var_dump($basketItem->getBasketItems());
	//	var_dump("<pre>",get_class_methods($basketItem));//,get_class_methods($basketValues),$basketItem,$basketValues);
	//return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::ERROR);

}

$eventManager = Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('catalog', 'OnGetOptimalPrice', function(
    $productId,
    $quantity = 1,
    $arUserGroups = [],
    $renewal = "N",
    $arPrices = [],
    $siteID = false,
    $arDiscountCoupons = false){

    global $USER;

	$prices = \CCatalogProduct::GetByIDEx($productId);

	if($USER->IsAuthorized()){
		$isPriceC = false;
		
		$ALLOW_PRICES = changePriceID();
		
		if(1 < count($ALLOW_PRICES) && in_array("С",$ALLOW_PRICES)){
		
			$dbRes = CIBlockElement::GetProperty(16, $productId, array("sort" => "asc"), Array("CODE"=>"CML2_TRAITS"));
			
			while($dbRow = $dbRes->Fetch()){
				if($dbRow['DESCRIPTION'] == "Ценовая группа"){
					$isPriceC = isIssetPG($dbRow["VALUE"]);break;
				}
			}
			
			if($isPriceC){
				$basePriceId = 14;
			} else {
				$basePriceId = 9;
			}

			$price = $prices['PRICES'][$basePriceId]['PRICE'];
				
			return [
			  'PRICE' => [
				 "ID" => $productId,
				 'CATALOG_GROUP_ID' => $basePriceId,
				 'PRICE' => $price,
				 'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
				 'ELEMENT_IBLOCK_ID' => $productId,
				 'VAT_INCLUDED' => "Y",
			  ],
			  'DISCOUNT' => [
				  'VALUE' => '',
				  'CURRENCY' => "RUB",
			   ],
		   ];
		}
	
		/*if(0 < count($ALLOW_PRICES)) {
			
			$PRICE_ID = ['ТО'=>9,'СО'=>10,'КП'=>11,'С'=>14];
			$allow = current($ALLOW_PRICES);
			
			$basePriceId = $PRICE_ID[$allow];
			
			$price = $prices['PRICES'][$basePriceId]['PRICE'];
				
			
			return [
				  'PRICE' => [
					 "ID" => $productId,
					 'CATALOG_GROUP_ID' => $basePriceId,
					 'PRICE' => $price,
					 'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
					 'ELEMENT_IBLOCK_ID' => $productId,
					 'VAT_INCLUDED' => "Y",
				  ],
				  'DISCOUNT' => [
					  'VALUE' => '',
					  'CURRENCY' => "RUB",
				   ],
			   ];
		}*/
		
		return true;
	}

	$basePriceId = 11;

	$price = $prices['PRICES'][$basePriceId]['PRICE'];

	return [
	  'PRICE' => [
		 "ID" => $productId,
		 'CATALOG_GROUP_ID' => $basePriceId,
		 'PRICE' => $price,
		 'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
		 'ELEMENT_IBLOCK_ID' => $productId,
		 'VAT_INCLUDED' => "Y",
	  ],
	  'DISCOUNT' => [
		  'VALUE' => '',
		  'CURRENCY' => "RUB",
	   ],
	];

});

?>