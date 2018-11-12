<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(isset($_POST['author']) && isset($_POST['reviewText'])){
	
	$author = htmlspecialchars(trim($_POST['author']));
	$reviewText = htmlspecialchars(trim($_POST['reviewText']));
	$site_id = $_POST['site_id'];
	$dateRew = $_POST['dateRew'];
	
	if($site_id == "s1"){
		$iblock_id = 5;
	}
	if($site_id == "s2"){
		$iblock_id = 26;
	}
	if(CModule::IncludeModule("iblock")){
			
		$arFields = array(
			"ACTIVE" => "N",
			"NAME" => "Отзыв об интернет-магазине ".$author,
			"CODE" => "review_online_store".rand(),
			"IBLOCK_ID" => $iblock_id,
			  "PROPERTY_VALUES" => array(
				   "ATT_AUTHOR" =>$author, 
				   "ATT_TEXT" =>$reviewText, 
				   "ATT_AUTHOR_COMMENT" =>"Стройлогистика", 
				   "ATT_RANDOM_DATE" => $dateRew
			   )
		);
		$arEventFields = array(
			"AUTHOR" => $author,
			"DATE" => $dateRew,
			"TEXT" => $reviewText
		);
		$review = new CIBlockElement;
		if($review->Add($arFields,false,false,true) != false){
			CEvent::SendImmediate("NEW_REVIEW_ABOUT_SITE", SITE_ID, $arEventFields);
			echo "Автор".$author." Текст ".$reviewText;
		}else{
			 echo 'Error: '.$review->LAST_ERROR;
		}
	
}
	
}

?>