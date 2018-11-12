<?
/*mmit - 22.08.2016 - Andrey Vlasov - start*/
/* сделаем переопределение количества товара в корзине и общей стоимости корзины, так как от component.php приходит неверное количество и стоимость при авторизации пользователя, если до авторизации у него в корзине были товары */
	function myCalculateOrder($arBasketItems)
	{
		$totalPrice = 0;
		$totalWeight = 0;

		foreach ($arBasketItems as $arItem)
		{
			$totalPrice += $arItem["PRICE"] * $arItem["QUANTITY"];
			$totalWeight += $arItem["WEIGHT"] * $arItem["QUANTITY"];
		}

		$arOrder = array(
			'SITE_ID' => SITE_ID,
			'ORDER_PRICE' => $totalPrice,
			'ORDER_WEIGHT' => $totalWeight,
		);

		if (is_object($GLOBALS["USER"]))
		{
			$arOrder['USER_ID'] = $GLOBALS["USER"]->GetID();
			$arErrors = array();
			CSaleDiscount::DoProcessOrder($arOrder, array(), $arErrors);
		}

		return $arOrder;
	}

	$dbBasketItems = CSaleBasket::GetList(
		array(),
		array(
			"FUSER_ID" => CSaleBasket::GetBasketUserID(),
			"LID" => SITE_ID,
			"ORDER_ID" => "NULL"
			),
		false,
		false,
		array("*")
	);

	$arResult["NUM_PRODUCTS"] = count($dbBasketItems->arResult);
	$arResult["TOTAL_PRICE"] = myCalculateOrder($dbBasketItems->arResult)["ORDER_PRICE"];
/*22.08.2016 - Andrey Vlasov - end*/
?>