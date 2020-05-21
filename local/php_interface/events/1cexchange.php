<?

use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Loader;
use Caweb\Main\Log\Write;

AddEventHandler('catalog', 'OnSuccessCatalogImport1C', 'customCatalogImportStep');

function customCatalogImportStep()
{
	define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs/log-change1c.txt");
    $stepInterval = (int) COption::GetOptionString("catalog", "1C_INTERVAL", "-");
    $startTime = time();
    // Флаг импорта файла остатков
    $isRests = strpos($_REQUEST['filename'], 'rests') !== false;
    // Флаг импорта файла каталога
    $isCatalog = strpos($_REQUEST['filename'], 'import') !== false;
    // Флаг импорта файла цен
    $isPrices = strpos($_REQUEST['filename'], 'prices') !== false;
    // Флаг импорта файла предложений
    $isOffers = strpos($_REQUEST['filename'], 'offers') !== false;
	
    $NS = &$_SESSION["BX_CML2_IMPORT"]["NS"];
 
    if (!isset($NS['custom']['lastId'])) {
        // Последний отработанный элемент для пошаговости.
        $NS['custom']['lastId'] = 0;
        $NS['custom']['counter'] = 0;
    }
		
	global $DB;
	// Условия выборки элементов для обработки
	$arFilter = array(
		'IBLOCK_ID' => 16,
		'ACTIVE' => 'Y'
	);

	$arItem = [];
	
	if($isCatalog){
		
		$el = new CIBlockElement;
	#	$DB->Query("DROP TABLE IF EXISTS b_xml_tree_c;");
	#	$DB->Query("CREATE TABLE IF NOT EXISTS b_xml_tree_c AS SELECT * FROM b_xml_tree;");

		$PRODUCT_SET_Q = [];
		$query = "SELECT ID, VALUE FROM b_xml_tree WHERE ID > '".$NS['custom']['lastId']."' && DEPTH_LEVEL = '4' && NAME = 'Ид' ORDER BY ID ASC";
		$res = $DB->Query($query);
		//$res = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('>ID' => $NS['custom']['lastId'])), false, false, ['ID','XML_ID','PROPERTY_OBEM_M3']);
		$errorMessage = null;
        if (method_exists($res, 'Fetch')){
            while ($arXMLItem = $res->Fetch()) {

                $XML_ID = $arXMLItem["VALUE"];

                #AddMessage2Log("Продукт XML_ID - " . $XML_ID . ": " . serialize($arXMLItem));

                $ires = $el::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('XML_ID' => $XML_ID,'ACTIVE' => ['LOGIC'=>'OR','Y','N'])), false, false, ['ID','PROPERTY_NOT_WORK']);

                if($arItem = $ires->Fetch()){
                    $PRODUCT_ID = $arItem["ID"];
                    $query = "  SELECT item.VALUE as item,  type1.VALUE as NameRekv, type2.VALUE as ValRekv
								FROM b_xml_tree as item
								INNER JOIN b_xml_tree as pr ON (item.PARENT_ID = pr.ID && pr.DEPTH_LEVEL = 3)
								INNER JOIN b_xml_tree as rekv ON (rekv.LEFT_MARGIN BETWEEN pr.LEFT_MARGIN AND pr.RIGHT_MARGIN AND rekv.DEPTH_LEVEL = 5 AND rekv.NAME = 'ЗначениеРеквизита')
								INNER JOIN b_xml_tree as type1 ON (type1.PARENT_ID = rekv.ID AND type1.DEPTH_LEVEL = 6 AND type1.NAME = 'Наименование')
								INNER JOIN b_xml_tree as type2 ON (type2.PARENT_ID = rekv.ID AND type2.DEPTH_LEVEL = 6 AND type2.NAME = 'Значение')
								WHERE item.VALUE = '$XML_ID' AND item.NAME = 'Ид'";

                    $results = $DB->Query($query);

                    while($row = $results->Fetch()) {
                        //	Товар комплект, установка остатков
                        if($row['NameRekv'] == "ВидНоменклатуры" && $row['ValRekv'] == "Товар-комплект"){
                            $PRODUCT_SET_Q[$PRODUCT_ID] = 10;
                        }
                    }

                    $disableItem = false;

                    $query = "  SELECT item.VALUE as item, del.VALUE as del
								FROM b_xml_tree as item
								INNER JOIN b_xml_tree as pr ON (item.PARENT_ID = pr.ID && pr.DEPTH_LEVEL = 3)
								INNER JOIN b_xml_tree as del ON (del.LEFT_MARGIN BETWEEN pr.LEFT_MARGIN AND pr.RIGHT_MARGIN AND del.DEPTH_LEVEL = 4 AND del.NAME = 'ПометкаУдаления')
								WHERE item.VALUE = '$XML_ID' AND item.NAME = 'Ид'";

                    $results = $DB->Query($query);

                    while($row = $results->Fetch()) {
                        // Есть ли пометка на удаление
                        if(trim($row['item']) == $XML_ID && trim($row['del']) === "true") {
                            $disableItem = true;
                            AddMessage2Log("Продукт ID-" . $PRODUCT_ID . " = " . serialize($row));
                        }
                    }

                    AddMessage2Log("Продукт $XML_ID ID-" . $PRODUCT_ID . " = " . ($disableItem?"DA":"NET"));
                    // Отключаем\Включаем товар, если пометка на удаление true/false
                    if($disableItem===true){
                        $PROP = ['533' => 6036];
                        $el::SetPropertyValuesEx($PRODUCT_ID, 16, $PROP);
                        $el->update($PRODUCT_ID,['ACTIVE'=>'N'], $WF=="Y", true, true);

                        CPrice::DeleteByProduct($PRODUCT_ID);
                        $DB->query("UPDATE b_catalog_product SET `QUANTITY` = '0' WHERE `ID` = '".$PRODUCT_ID."'");
                    } elseif($arItem["PROPERTY_NOT_WORK_VALUE"] == "ДА"){
                        $PROP = ['533' => 6035];
                        $el::SetPropertyValuesEx($PRODUCT_ID, 16, $PROP);
                        $el->update($PRODUCT_ID,['ACTIVE'=>'Y'], $WF=="Y", true, true);
                    }

                    if ($error === true) {
                        $errorMessage = 'Что-то случилось.';
                        break;
                    }

                    $NS['custom']['lastId'] = $arXMLItem['ID'];
                    $NS['custom']['counter']++;

                    // Прерывание по времени шага
                    if ($stepInterval > 0 && (time() - $startTime) > $stepInterval) {
                        break;
                    }
                }
            }
        }

		if(0 < count($PRODUCT_SET_Q)){
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/update_quantity_set","w");
			fwrite($fp,serialize($PRODUCT_SET_Q));
			fclose($fp);
		}
		//	AddMessage2Log("Продукт 123 " . serialize($PRODUCT_SET_Q));
	}
	
	if($isPrices){
		
		/* BONUS_KP BONUS_SO Считаем бонусы */
		$dbPriceType = CCatalogGroup::GetList(
			['NAME'=>"ASC"],
			['NAME'=>['LOGIC'=>'OR','ТО','СО','КП']],
			false,
			false,
			['NAME','XML_ID']
		);
		
		while ($arPriceType = $dbPriceType->Fetch())
		{
			$PRICE_ID[$arPriceType['XML_ID']] = $arPriceType['NAME'];
		}
		
		$query = "SELECT ID, VALUE FROM b_xml_tree WHERE ID > '".$NS['custom']['lastId']."' && DEPTH_LEVEL = '4' && NAME = 'Ид' ORDER BY ID ASC";
		$res = $DB->Query($query);
		
		//$res = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('>ID' => $NS['custom']['lastId'])), false, false, ['ID','XML_ID','PROPERTY_OBEM_M3']);
		$errorMessage = null;
	 
		while ($arXMLItem = $res->Fetch()) {			
		
			$XML_ID = $arXMLItem["VALUE"];
			
			$ires = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('XML_ID' => $XML_ID,'ACTIVE' => ['LOGIC'=>'OR','Y','N'])), false, false, ['ID','PROPERTY_NOT_WORK']);
			
			if($arItem = $ires->Fetch()){
				
				$ELEMENT_ID = $arItem["ID"];
								
				if($arItem["PROPERTY_NOT_WORK_VALUE"] == "ДА"){	
					CPrice::DeleteByProduct($ELEMENT_ID);
				}
				
				$query = "  SELECT pricev.VALUE as priceValue, priceid.VALUE as priceID
								FROM b_xml_tree as item
								INNER JOIN b_xml_tree as predl ON (item.PARENT_ID = predl.ID && predl.DEPTH_LEVEL = 3)
								INNER JOIN b_xml_tree as price ON (price.LEFT_MARGIN BETWEEN predl.LEFT_MARGIN AND predl.RIGHT_MARGIN AND price.DEPTH_LEVEL = 5 AND price.NAME = 'Цена')
								INNER JOIN b_xml_tree as pricev ON (pricev.PARENT_ID = price.ID AND pricev.DEPTH_LEVEL = 6 AND pricev.NAME = 'ЦенаЗаЕдиницу')
								INNER JOIN b_xml_tree as priceid ON (priceid.PARENT_ID = price.ID AND priceid.DEPTH_LEVEL = 6 AND priceid.NAME = 'ИдТипаЦены')
								WHERE item.VALUE = '$XML_ID' AND item.NAME = 'Ид'";	
								
				$results = $DB->Query($query);
				
				$PRICES = [];
				
				while($row = $results->Fetch()) {
					if(isset($PRICE_ID[$row['priceID']]) && (float)$row['priceValue'] > 0){
						$PRICES[$PRICE_ID[$row['priceID']]] = (float)$row['priceValue'];
					}
				}
				
				if(0 < count($PRICES)){
					$PROPERTIES = [
						"BONUS_KP" => roundEx((($PRICES['КП'] - $PRICES['ТО']) * 0.1), 2),
						"BONUS_KP15" => roundEx((($PRICES['КП'] - $PRICES['ТО']) * 0.15), 2),
						"BONUS_SO" => roundEx((($PRICES['СО'] - $PRICES['ТО']) * 0.1), 2),
						"BONUS_SO15" => roundEx((($PRICES['СО'] - $PRICES['ТО']) * 0.15), 2),
						"BONUS_SO20" => roundEx((($PRICES['СО'] - $PRICES['ТО']) * 0.20), 2)
					];
					
				
					CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, $PROPERTIES);
				
				}
				/* END BONUS_KP BONUS_SO Считаем бонусы */
			}
			
			if ($error === true) {
				$errorMessage = 'Что-то случилось.';
				break;
			}
	 
			$NS['custom']['lastId'] = $arXMLItem['ID'];
			$NS['custom']['counter']++;
	 
			// Прерывание по времени шага
			if ($stepInterval > 0 && (time() - $startTime) > $stepInterval) {
				break;
			}
		}
		
	}
	if ($isRests) {
		
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/update_quantity_set")){
			
			$lines = file($_SERVER['DOCUMENT_ROOT']."/update_quantity_set");
			
			$STORE_ID = 49;
			
			foreach($lines as $line){

				if(trim($line)){
				#	AddMessage2Log("Продукт line " . $line);
					$PRODUCT_SET_Q = unserialize($line);
					
					if(0 < count($PRODUCT_SET_Q)){
						foreach($PRODUCT_SET_Q as $IID=>$IQ){
							$DB->query("UPDATE `b_catalog_store_product` SET `AMOUNT` = '".$IQ."' WHERE `PRODUCT_ID` = '".$IID."' AND `STORE_ID` = '".$STORE_ID."'");
							$DB->query("UPDATE b_catalog_product SET `QUANTITY` = '".$IQ."' WHERE `ID` = '".$IID."'");
							$DB->query("UPDATE b_catalog_product SET `AVAILABLE` = 'Y' WHERE `ID` = '".$IID."'");
						}
					}
				}
				
			}
			
			unlink($_SERVER['DOCUMENT_ROOT']."/update_quantity_set");
		}

		$query = "SELECT ID, VALUE FROM b_xml_tree WHERE ID > '".$NS['custom']['lastId']."' && DEPTH_LEVEL = '4' && NAME = 'Ид' ORDER BY ID ASC";
		$res = $DB->Query($query);
		//$res = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('>ID' => $NS['custom']['lastId'])), false, false, ['ID','XML_ID','PROPERTY_OBEM_M3']);
		$errorMessage = null;
		
		$el = new CIBlockElement();
        Loader::includeModule('caweb.main');
        $log = new Write();
		while ($arXMLItem = $res->Fetch()) {
			
			$XML_ID = $arXMLItem["VALUE"];
			
			#AddMessage2Log("Продукт XML_ID REST - " . $XML_ID . ": " . serialize($arXMLItem));
			
			$ires = $el::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('XML_ID' => $XML_ID,'ACTIVE' => ['LOGIC'=>'OR','Y','N'])), false, false, ['ID','ACTIVE','PROPERTY_OBEM_M3','PROPERTY_NOT_WORK']);
			
			if($arItem = $ires->Fetch()){
				/*
				// Что-нибудь делаем
				if (updateElement($arItem['ID']) === false) {
					$error = true;
				}
				*/

                $totalQuantity = 0;
				$itemStore = [];
				$PRODUCT_ID = $arItem["ID"];
				$V = (float)$arItem["PROPERTY_OBEM_M3_VALUE"];//0216
				$isV = false;
				
				#AddMessage2Log("Продукт XML_ID - " . $XML_ID . ": " . serialize($arItem));
				
				if($arItem["PROPERTY_NOT_WORK_VALUE"] == "ДА"){
					$DB->query("UPDATE b_catalog_product SET `QUANTITY` = '0' WHERE `ID` = '".$PRODUCT_ID."'");
					$el->update($PRODUCT_ID,['ACTIVE'=>'N'], $WF=="Y", true, true);
				} else {
					if($arItem["ACTIVE"] != "Y")
						$el->update($PRODUCT_ID,['ACTIVE'=>'Y'], $WF=="Y", true, true);
				}		
				
				$pRes = $el::GetProperty(16, $PRODUCT_ID, "sort", "asc", array("CODE" => "CML2_TRAITS"));
				while ($ob = $pRes->GetNext())
				{
					if($ob["DESCRIPTION"] === "Единица хранения" && $ob['VALUE'] === "м3"){
						$isV = true;
						break;
					}
				}
				$log->setLogArray('PRODUCT_ID', $PRODUCT_ID);
				$log->setLogArray('isV', $isV);
				$log->setLogArray('V', $V);
				if($isV && $V>0 && $V<1 && $PRODUCT_ID>0){
					
					$query = "  SELECT item.VALUE as item,  rest.VALUE as amount, stock.VALUE as stockID
								FROM b_xml_tree as item
								INNER JOIN b_xml_tree as offer ON (item.PARENT_ID = offer.ID && offer.DEPTH_LEVEL = 3)
								INNER JOIN b_xml_tree as stock ON (stock.LEFT_MARGIN BETWEEN offer.LEFT_MARGIN AND offer.RIGHT_MARGIN AND stock.DEPTH_LEVEL = 7 AND stock.NAME = 'Ид')
								INNER JOIN b_xml_tree as rest ON (rest.LEFT_MARGIN BETWEEN offer.LEFT_MARGIN AND offer.RIGHT_MARGIN AND rest.DEPTH_LEVEL = 7 AND rest.NAME = 'Количество' AND stock.PARENT_ID = rest.PARENT_ID)
								WHERE item.VALUE = '$XML_ID' AND item.NAME = 'Ид'";
								
					$results = $DB->Query($query);
                    while($stock = $results->Fetch()) {
                        $log->setLogArray('stock', $stock);

                        if(isset($stock['amount']) && (float)$stock['amount'] > 0){
						
						#	AddMessage2Log("Продукт ID-" . $PRODUCT_ID . " склад XML_ID = " . $stock['stockID'] . ", остаток из 1c = " . $stock['amount']);
							//$rsStore = \CCatalogStoreProduct::GetList(array(), array('PRODUCT_ID' =>$arItem['ID']), false, false, array('ID','STORE_ID','AMOUNT')); 
							$result = $DB->Query($q="SELECT ID FROM `b_catalog_store` WHERE `XML_ID` = '".$stock['stockID']."'");
							$store = $result->Fetch();
                            $log->setLogArray('store', $store);

                            #AddMessage2Log("Продукт ID-" . $PRODUCT_ID . " склад ID из БД = " . serialize($store) . ", QUERY = " . $q);
							
							if(isset($store['ID']) && $store['ID']){
								$storeID = $store['ID'];
								$amountStore = (float)$stock['amount'];
								
							#	AddMessage2Log("Продукт ID-" . $PRODUCT_ID . " склад БД ид = " . $storeID . ", остаток из 1c = " . $amountStore);
								
								if($amountStore > 0){
									if(fmod($amountStore, 1)!==0){
										$itemStore[$storeID] = [
											'AMOUNT' => $amountStore/$V,
											'STORE_ID' => $storeID
										];
									} else {
										$totalQuantity += $amountStore;
									}
								}
							}
						}
					}
					$log->setLogArray('itemStore', $itemStore);
					if(count($itemStore)>0){
						$ratio = false;
						
						foreach($itemStore as $sID=>$ST){
						//	AddMessage2Log("Продукт ID-" . $PRODUCT_ID . "||" .$sID. " НОВЫЙ остаток = " . $ST['AMOUNT'] . ", объем = " . $V);
							if($ST['AMOUNT'] > 0) {
								$totalQuantity += $ST['AMOUNT'];
								$DB->query("UPDATE `b_catalog_store_product` SET `AMOUNT` = '".$ST['AMOUNT']."' WHERE `PRODUCT_ID` = '".$PRODUCT_ID."' AND `STORE_ID` = '".$ST['STORE_ID']."'");
								$ratio = true;
							}
						}
						
						//AddMessage2Log("Продукт ID-" . $PRODUCT_ID . " Общий остаток новый = " . $totalQuantity);
                        $log->setLogArray('totalQuantity', $totalQuantity);
                        $log->setLogArray('$ratio', $ratio);

						if($totalQuantity>0){
							$DB->query("UPDATE b_catalog_product SET `QUANTITY` = '".$totalQuantity."' WHERE `ID` = '".$PRODUCT_ID."'");
							if($ratio){
								$DB->query("UPDATE `b_catalog_measure_ratio` SET `RATIO` = '1' WHERE `PRODUCT_ID` = '".$PRODUCT_ID."' && `IS_DEFAULT` = 'Y'");
							}
						}
						
					}
					
				}
                $log->dumpLog('amount', false);
            }
			$exp = explode("#",$XML_ID);
				#AddMessage2Log("Продукт XML_ID - " . $XML_ID . ": " . serialize($arXMLItem));
				
			if(isset($exp[1])) {
				
				$arFilter2 = array(
					'IBLOCK_ID' => 23
				);
				
				$PARENT_XML_ID = $exp[0];
				#AddMessage2Log("Продукт PARENT_XML_ID - " . $PARENT_XML_ID . ": " . serialize($arXMLItem));
			
				$ires = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('XML_ID' => $PARENT_XML_ID)), false, false, ['ID']);
				
				if($arOItem = $ires->Fetch()){
					
					$productId = $arOItem['ID'];
					
					#AddMessage2Log("Продукт productId - " . $productId);
					
					$prices = [];
					
					$dbProductPrice = CPrice::GetListEx(
						array(),
						array("PRODUCT_ID" => $productId),
						false,
						false,
						array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY")
					);
					
					while($arPrice = $dbProductPrice->Fetch()){
						$prices[$arPrice['CATALOG_GROUP_ID']] = [
							'PRICE' =>$arPrice['PRICE'],
							'ID' => $arPrice['ID'],
							'CURRENCY' => $arPrice['CURRENCY']
						 ];
					}
					#AddMessage2Log("Продукт prices - " . serialize($prices));
			
					if(0 < count($prices)) {
						$ores = $el::GetList(array('ID' => 'ASC'), array_merge($arFilter2, array('XML_ID' => $XML_ID,'ACTIVE' => ['LOGIC'=>'OR','Y','N'])), false, false, ['ID','PROPERTY_M2']);
					
						if($arOffer = $ores->Fetch()){
							$pricesOffer = [];
							$OFFER_ID = $arOffer["ID"];
						#	AddMessage2Log("Продукт OFFER_ID - " . $OFFER_ID);
							
							$dbProductPrice = CPrice::GetListEx(
								array(),
								array("PRODUCT_ID" => $OFFER_ID),
								false,
								false,
								array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY")
							);
							
							while($arPrice = $dbProductPrice->Fetch()){
								
								$pricesOffer[$arPrice['CATALOG_GROUP_ID']] = [
									'PRICE' =>$arPrice['PRICE'],
									'ID' => $arPrice['ID'],
									'CURRENCY' => $arPrice['CURRENCY']
								];
								
							}
						#	AddMessage2Log("Продукт pricesOffer - " . serialize($pricesOffer));
							
							foreach($prices as $PrID=>$value){
								$arFields = Array(
									"PRODUCT_ID" => $OFFER_ID,
									"CATALOG_GROUP_ID" => $PrID,
									"PRICE" => $value['PRICE'],
									"CURRENCY" => $value['CURRENCY']
								);
								if(isset($pricesOffer[$PrID])){
									if((float)$pricesOffer[$PrID]["PRICE"] == 0)
										\CPrice::Update($pricesOffer[$PrID]["ID"], $arFields);
								}
								else
								{
									\CPrice::Add($arFields);
								}									
							}
							
					
							/* RATIO */
							
							$ratio = (float)$arOffer["PROPERTY_M2_VALUE"];
							
							if($ratio>0){
								$arFields = Array(
									"PRODUCT_ID" => $OFFER_ID,
									"RATIO" => $ratio
								);
								
								$existRatio = \Bitrix\Catalog\MeasureRatioTable::getList(array(
											'select' => ['ID'],
											'filter' => ['=PRODUCT_ID' => $OFFER_ID] 
										))->Fetch();

								if($existRatio){
									\Bitrix\Catalog\MeasureRatioTable::Update($existRatio['ID'],$arFields);
								} else {
									\Bitrix\Catalog\MeasureRatioTable::Add($arFields);
								}
							}
							
						}
					}
				}
			}
		 
			if ($error === true) {
				$errorMessage = 'Что-то случилось.';
				break;
			}
	 
			$NS['custom']['lastId'] = $arXMLItem['ID'];
			$NS['custom']['counter']++;
	 
			// Прерывание по времени шага
			if ($stepInterval > 0 && (time() - $startTime) > $stepInterval) {
				break;
			}
		}
	
	}
	
	if ($arItem != false) {
		if ($errorMessage === null) {
			print "progress\n";
			print "Обработано " . $NS['custom']['counter'] . ' элементов, осталось ' . $res->SelectedRowsCount();
		} else {
			print "failure\n" . $errorMessage;
		}
 
		$contents = ob_get_contents();
		ob_end_clean();
 
		if (toUpper(LANG_CHARSET) != "WINDOWS-1251") {
			$contents = $GLOBALS['APPLICATION']->ConvertCharset($contents, LANG_CHARSET, "windows-1251");
		}

		header("Content-Type: text/html; charset=windows-1251");
		print $contents;
		exit;
	}

 
}
?>