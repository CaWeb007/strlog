<?php 
\Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved', 'OnSaleOrderBeforeSavedHandler');
function OnSaleOrderBeforeSavedHandler(\Bitrix\Main\Event $event) {
	
	/* Добавляем значения свойств заказа */
	$order = $event->getParameter('ENTITY');
	$oldValues = $event->getParameter("VALUES");
	$email = "";
				 
	$propertyCollection = $order->getpropertyCollection();
	foreach ($propertyCollection as $property) {
		if($property->getField('CODE') == 'COMPANY') {
			$COMPANY = $property->getValue('COMPANY');
		}
		if($property->getField('CODE') == 'LEGAL_FORM') {
			$LEGAL_FORM = $property->getValue('LEGAL_FORM');
		}
		if($property->getField('CODE') == 'EMAIL') {
			$email = $property->getValue('EMAIL');
		}
	}
	
	if(preg_match("#".$LEGAL_FORM."#siu",$COMPANY) !== 1){
		$exp = explode('"',$COMPANY);
		if(isset($exp[1])) $COMPANY = $exp[1];
		$COMPANY = $LEGAL_FORM . ' "'.$COMPANY.'"';
	}
	
	foreach ($propertyCollection as $property) {
		if($property->getField('CODE') == 'COMPANY') {
			$property->setValue($COMPANY);
		}
	}	
	
	global $USER;

    if(!$USER->IsAuthorized())
    {
		if($email){
			$filter = array('EMAIL' => $email);
			$dbRes = \Bitrix\Main\UserTable::getList(array('select' => array('EMAIL', 'ID'), 'filter' => $filter, 'order' => array('ID' => 'ASC')));
			
			if ($dbRes->getSelectedRowsCount() > 0) {
				return new \Bitrix\Main\EventResult(
					\Bitrix\Main\EventResult::ERROR,
					new \Bitrix\Sale\ResultError('Данный емайл уже зарегистрирован на сайте. <span class="btn btn-default" style="cursor:pointer;" onclick="showAuthFormPopup()">Войти</span>'),
					'sale'
				 );
			}
		}

	}
	
	return new \Bitrix\Main\EventResult(
		\Bitrix\Main\EventResult::SUCCESS, array(
			"ENTITY" => $order,
		)
	);
}

\Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderSaved', 'OnSaleOrderFinalActionHandler');//OnSaleOrderSaved//OnBeforeSaleOrderFinalAction
function OnSaleOrderFinalActionHandler(\Bitrix\Main\Event $event) {
	
	/* Добавляем значения свойств заказа */
	$order = $event->getParameter('ENTITY');
	$oldValues = $event->getParameter("VALUES");
	$isNew = $event->getParameter("IS_NEW");

	if ($isNew)
	{
		define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs/log-save-sale.txt");
		$userProfileId = getContragentID($order->getId());
		$propertyCollection = $order->getpropertyCollection();
		$personTypeId = $order->getPersonTypeId();
	
		global $USER; 
		$userID = $USER->GetID();

		$priceGroups = [9=>"КП(физ)",10=>"СО(КМС)",11=>"ТО",12=>"СО(ПГС)",14=>"КП(юр)",15=>"КП(СО)"];
		$userGroups = \CUser::GetUserGroup($userID);
		
		$intersect = array_intersect([10,11,12,15], $userGroups);
		
		if(!$intersect){
			$arGroups[] = $personTypeId == 2 ? 14 : 9;
			$userGroups = array_merge($userGroups, $arGroups);
			\CUser::SetUserGroup($userID, $userGroups);

			$userGroups = array_flip($userGroups);
			$intersect = array_intersect_key($priceGroups, $userGroups);
			if(!empty($intersect)){
				foreach($intersect as $key=>$empty){
					if($key == 1){
						unset($intersect[$key]);
					}
				}
				$prop_value = current($intersect);
			}
        }
		
			if($personTypeId == 2){
				if(!$prop_value) $prop_value = "КП(юр)";
			} else {
				if(!$prop_value) $prop_value = "КП(физ)";
			}
			
			$prop_id_PAY_BONUS = null;
			
			foreach ($propertyCollection as $property) {
				if($property->getField('CODE') == 'SUBUSER_ID') {
					$prop_id = $property->getField('ORDER_PROPS_ID');
				}
				if($property->getField('CODE') == 'KP_TYPE') {
					$prop_id2 = $property->getField('ORDER_PROPS_ID');
				}
				if($property->getField('CODE') == 'PAY_BONUS') {
					$prop_id_PAY_BONUS = $property->getField('ORDER_PROPS_ID');
				}

			}
			if($prop_id)
				AddOrderProperty($prop_id, $userProfileId, $order->getId());
			if($prop_id2 && $prop_value)
				AddOrderProperty($prop_id2, $prop_value, $order->getId());
			
			$bonusSum = 0;
			$paymentCollection = $order->getPaymentCollection();
			foreach($paymentCollection->getInnerPayment()->getCollection() as $payment){
			#	AddMessage2Log("getFields - " . serialize($payment->getFields()) . ": ", 'sale');
				if ($payment->isInner())
				   {
					   $bonusSum = (float)$payment->getSum();
				   }
			}
			#AddMessage2Log("bonusSum - " . $bonusSum . ": ", 'sale');
			#AddMessage2Log("prop_id_PAY_BONUS - " . $prop_id_PAY_BONUS . ": ", 'sale');
			
			if($bonusSum > 0 && $prop_id_PAY_BONUS)
				AddOrderProperty($prop_id_PAY_BONUS, $bonusSum, $order->getId());



	}

}


function AddOrderProperty($prop_id, $value, $order) {
  if (!strlen($prop_id)) {
    return false;
  }
  if (CModule::IncludeModule('sale')) {
    if ($arOrderProps = CSaleOrderProps::GetByID($prop_id)) {
      $db_vals = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $order, 'ORDER_PROPS_ID' => $arOrderProps['ID']));
      if ($arVals = $db_vals->Fetch()) {
        return CSaleOrderPropsValue::Update($arVals['ID'], array(
          'NAME' => $arVals['NAME'],
          'CODE' => $arVals['CODE'],
          'ORDER_PROPS_ID' => $arVals['ORDER_PROPS_ID'],
          'ORDER_ID' => $arVals['ORDER_ID'],
          'VALUE' => $value,
        ));
      } else {
        return CSaleOrderPropsValue::Add(array(
          'NAME' => $arOrderProps['NAME'],
          'CODE' => $arOrderProps['CODE'],
          'ORDER_PROPS_ID' => $arOrderProps['ID'],
          'ORDER_ID' => $order,
          'VALUE' => $value,
        ));
      }
    }
  }
}
 
function getContragentID($orderID) {
	    CModule::IncludeModule("sale");
	    if ($arOrder = CSaleOrder::GetByID($orderID)) {
		        $profiles = \Bitrix\Sale\Helpers\Admin\Blocks\OrderBuyer::getBuyerProfilesList($arOrder['USER_ID'],$arOrder['PERSON_TYPE_ID']);
		        unset($profiles[0]);
		        $profiles = array_flip($profiles);
		        $filterValue = array('PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID'], 'IS_PROFILE_NAME' => 'Y');
		        if ($arOrder['PERSON_TYPE_ID']==2) {
			            $filterValue = array('PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID'], 'CODE' => 'INN');
			        }
		        $rsOrderProps = CSaleOrderProps::GetList(array(), $filterValue);
		        if ($arOrderProp = $rsOrderProps->Fetch()) {
			            $rsProps = CSaleOrderPropsValue::GetList(array('SORT' => 'ASC'), array('ORDER_ID' => $orderID, 'ORDER_PROPS_ID' => $arOrderProp['ID']));
			            if ($arProp = $rsProps->Fetch()) {
				                $rsUP = CSaleOrderUserPropsValue::GetList(array(), array('ORDER_PROPS_ID' => $arOrderProp['ID'],
				                    'VALUE' => $arProp['VALUE'],
				                    'PROP_PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID']));
				                if ($arUP = $rsUP->Fetch()) {
					                    if (array_key_exists($arProp['VALUE'], $profiles) && $arOrder['PERSON_TYPE_ID']==1) {
						                        return $profiles[$arProp['VALUE']];
						                    }
					                    return $arUP['USER_PROPS_ID'];
					                } else {
					                    $db_sales = CSaleOrderUserProps::GetList(array(),array("USER_ID" => $arOrder['USER_ID'],"PERSON_TYPE_ID"=>$arOrder['PERSON_TYPE_ID'],"=NAME"=>$arProp['VALUE']));
					                    if ($ar_sales = $db_sales->Fetch()) {
						                       return $ar_sales["ID"];
						                    }
					                }
				            } 
			        }
		    }
	}

addEventHandler('sale', 'OnSaleComponentOrderUserResult', "change_type_user");
function change_type_user(&$arUserResult, $request, $arParams)
{
    global $USER;

    if($USER->IsAuthorized())
    {
		$UserID = $USER->GetID();
        $rsUser = $USER->GetByID($UserID);
        $arUser = $rsUser->Fetch();
        $entity = $arUser['PERSONAL_PROFESSION']; //поле принадлежности к юр. лицу

		if(!empty($entity)){
			$curPersonType = 2;
			if ($entity == 'КП(ФИЗ)') {
				$curPersonType = 1;
			}
	
			$arUserResult['PERSON_TYPE_ID'] = $curPersonType;
		}

    }

};

addEventHandler('sale', 'OnSaleComponentOrderProperties', "change_type_user2");
function change_type_user2(&$arUserResult, $request, $arParams)
{
    global $USER;

    if($USER->IsAuthorized())
    {
		$UserID = $USER->GetID();

		$dbRes = \CSaleOrderUserProps::GetList([],['USER_ID' => $USER->GetID()]);
		if ($PUSER = $dbRes->Fetch()) {
			$arUserResult["PROFILE_ID"] = $PUSER['ID'];
			$arUserResult["LAST_ORDER_DATA"] = ["PERSON_TYPE_ID"=>$PUSER['PERSON_TYPE_ID']];
			$arUserResult["PERSON_TYPE_ID"]=$PUSER['PERSON_TYPE_ID'];
			#$arUserResult["PROFILE_CHANGE"]="N";
		}

    }
};


addEventHandler('sale', 'OnSaleComponentOrderResultPrepared', "OnSaleComponentOrderResultPreparedHandler");
function OnSaleComponentOrderResultPreparedHandler($order, &$arUserResult, $request, &$arParams, &$arResult)
{
	$USE_BONUSES_SUMM = 0;
	
	foreach($arResult["JS_DATA"]["GRID"]["ROWS"] as $key => $value) {
		/* */
		$USE_BONUSES_SUMM += $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["data"]["SUM_NUM"];
	}

	$arResult["JS_DATA"]["USE_BONUSES_SUMM"] = $USE_BONUSES_SUMM;

}

# Ограничение для оплат
AddEventHandler("sale", "onSalePaySystemRestrictionsClassNamesBuildList", "bonusPayFunction");

function bonusPayFunction()
{
    return new \Bitrix\Main\EventResult(
        \Bitrix\Main\EventResult::SUCCESS,
        array(
            '\BonusPayRestriction' => '/local/php_interface/include/bonuspayrestriction.php',
            '\groupUserRestriction' => '/local/php_interface/include/groupuserrestriction.php',
        )
    );
}

# Ограничение для доставки
AddEventHandler("sale", "onSaleDeliveryRestrictionsClassNamesBuildList", "onSaleDeliveryRestrictionsClassNamesBuildListHandler");

function onSaleDeliveryRestrictionsClassNamesBuildListHandler()
{
    return new \Bitrix\Main\EventResult(
        \Bitrix\Main\EventResult::SUCCESS,
        array(
            '\groupUserRestriction' => '/local/php_interface/include/groupuserrestriction.php',
        )
    );
}



?>