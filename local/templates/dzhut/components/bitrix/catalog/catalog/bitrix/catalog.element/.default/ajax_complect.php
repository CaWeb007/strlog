<?
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
	if(CModule::IncludeModule("sale")){
		$arFields = array(
             "PRODUCT_ID" => $_POST['ID'],
             "PRICE" => $_POST['Price'],
             "CURRENCY" => "RUB",
             "DETAIL_PAGE_URL" => $_POST['Page'],
             "LID" => LANG,
             "QUANTITY" => $_POST['CountProd'],
              "NAME" => $_POST['Name']
            );
			CSaleBasket::Add($arFields);
		}
?>