<?
Class CAbricosAvitoautoload
{
    function CurFile()
    {
	  	if(CModule::IncludeModuleEx("abricos.avitoautoload")==2){
	  	return true;
	  	}else{return false;}
	}

	function get_text($templ,$name,$section,$offer=false)
	{
	   $templ=str_replace('#NAME#',$name,$templ);
       $templ=str_replace('#name#',mb_strtolower($name,'utf-8'),$templ);
       $templ=str_replace('#SECTION#',$section,$templ);
       $templ=str_replace('#section#',mb_strtolower($section,'utf-8'),$templ);
       if($offer)
       {
	       $templ=str_replace('#OFFER#',$offer,$templ);
	       $templ=str_replace('#offer#',mb_strtolower($offer,'utf-8'),$templ);
	    }
	    else
	    {
	       $templ=str_replace('#OFFER#','',$templ);
	       $templ=str_replace('#offer#','',$templ);

	    }
	   $templ=self::random_text($templ);
		return $templ;
	}

	function random_text($texttemplate)
	{
	  while(strpos($texttemplate, "{") !== false)
	  {
	    $texttemplate = preg_replace_callback(
	        '/{([^{}]+)}/',
	        create_function('$m',
	        '$r = $m[1];
	        if (strpos($r, "|") === false) return $r;
	        $v = explode("|", $r);
	        return $v[array_rand($v)];'
	      ),
	      $texttemplate
	    );
	  }
	  return $texttemplate;
	}

	function categoryBlock($avitoCategory)
	{
		$strSql='SELECT DISTINCT category FROM abr_avitoautoload;';
		global $DB;
		$res = $DB->Query($strSql, false);
		$word = $avitoCategory;
		echo '<select name="AVITO_CATEGORY" id="List1" onClick="Selected()">';

        while($arGroup=$res->GetNext())  {
	        $line = $arGroup["category"];
	        if ($line == $word) $status = " selected";
				else $status = "";
			   	echo '<option'.$status.' value="'.$line.'">'.$line.'</option>';
		 }

	    echo '</select>';
	}

	function categoryTypeBlock(){

		 $strSql='SELECT DISTINCT category FROM abr_avitoautoload;';

		global $DB;
		$res = $DB->Query($strSql, false);
		$word = $avitoCategory;
		$podCatTree='';
	    $podCatTree1='';

        while($arGroup=$res->GetNext())  {
	        $line = $arGroup["category"];
	        $podCatTree.="'".$line."':{
            	 ";
            $strSqlPodcat="SELECT DISTINCT category, goodstype FROM abr_avitoautoload where category='".$line."';";
            $resPod = $DB->Query($strSqlPodcat, false);
            while($arGroupPod=$resPod->GetNext())  {
            	$line2 = $arGroupPod["goodstype"];
            	if (strpos($line2, '*')) {
            		$first_token  = strtok($line2, '*');
            	$podCatTree.="'".$first_token."':'".strtok('*')."',
			            ";}
			            else
            	$podCatTree.="'".$line2."':'".$line2."',
			            ";
			    $strSqlApp="SELECT DISTINCT goodstype, apparel FROM abr_avitoautoload where goodstype='".$line2."';";
			    $resApp = $DB->Query($strSqlApp, false);
			    if ($resApp){
			    	$podCatTree1.="'".$line2."':{";
			    	while($arApp=$resApp->GetNext())  {
			    		$line3 = $arApp["apparel"];
			            $podCatTree1.="'".$line3."':'".$line3."',
			            ";
			    		}
			    		$podCatTree1.="},
				   	";
			    }

            	}
            	$podCatTree.="},
		   	     ";
		 }
		 return $podCatTree.$podCatTree1;

		 }
		 function yand_show_selector($group, $key, $IBLOCK, $value = "")
		{
			?><select name="FILTER_DATA[<? echo htmlspecialcharsbx($key)?>]">
			<option value=""<? echo ($value == "" ? ' selected' : ''); ?>><?=GetMessage('AVITO_SKIP_PROP')?></option>
			<?
			if (!empty($IBLOCK['OFFERS_PROPERTY']))
			{
				?><option value=""><? echo GetMessage('AVITO_PRODUCT_PROPS')?></option><?
			}
			foreach ($IBLOCK['PROPERTY'] as $key => $arProp)
			{
				?><option value="<?=$arProp['ID']?>"<? echo ($value == $arProp['ID'] ? ' selected' : ''); ?>>[<?=htmlspecialcharsbx($key)?>] <?=htmlspecialcharsbx($arProp['NAME'])?></option><?
			}
			if (!empty($IBLOCK['OFFERS_PROPERTY']))
			{
				?><option value=""><? echo GetMessage('AVITO_OFFERS_PROPS')?></option><?
				foreach ($IBLOCK['OFFERS_PROPERTY'] as $key => $arProp)
				{
					?><option value="<?=$arProp['ID']?>"<? echo ($value == $arProp['ID'] ? ' selected' : ''); ?>>[<?=htmlspecialcharsbx($key)?>] <?=htmlspecialcharsbx($arProp['NAME'])?></option><?
				}
			}
			?></select>

			<?
		}

		function addParamCode()
		{
			return '<small></small>';
		}

		function addParamName(&$IBLOCK, $intCount, $value)
		{
			ob_start();
			self::yand_show_selector('PARAMS',$intCount, $IBLOCK, $value);
			$strResult = ob_get_contents();
			ob_end_clean();
			return $strResult;
		}

		function addParamUnit(&$IBLOCK, $intCount, $value)
		{
			return '<input type="text" size="3" name="FILTER_DATA[UNIT_'.$intCount.']" value="'.htmlspecialcharsbx($value).'">';
		}

		function addParamRow(&$IBLOCK, $intCount, $strParam, $strUnit,$filterPref="=",$filterInput="")
		{
			return '<tr id="AVITO_FILTER_tbl_'.$intCount.'">

				<td>'.self::addParamName($IBLOCK, $intCount, $strParam).'</td>
				<td style="text-align: center;">
				<select name="FILTER_PREF['.$intCount.']">
			<option value="=" '.($filterPref=="="? 'selected':'').'>'.GetMessage('FILTER_PREF_RAVNO').'</option>
			<option value=">" '.($filterPref==">"? 'selected':'').'>'.GetMessage('FILTER_PREF_MAX').'</option>
			<option value="<" '.($filterPref=="<"? 'selected':'').'>'.GetMessage('FILTER_PREF_MIN').'</option>
			<option value="=!" '.($filterPref=="=!"? 'selected':'').'>'.GetMessage('FILTER_PREF_NERAVNO').'</option>
			<option value="z" '.($filterPref=="z"? 'selected':'').'>'.GetMessage('FILTER_PREF_ZERO').'</option>
			<option value="nz" '.($filterPref=="nz"? 'selected':'').'>'.GetMessage('FILTER_PREF_NOTZERO').'</option>
			</select></td>
				<td style="text-align: center;">
				<input name="FILTER_INPUT['.$intCount.']" type="text" value="'.$filterInput.'">
			</td>
				</tr>';
		}
		function addPrefRow($intCount)
		{
			$strResult='<select name=\"FILTER_PREF['.$intCount.']\">\n			<option value=\"=\" '.($filterPref=="="? 'selected':'').'>'.GetMessage('FILTER_PREF_RAVNO').'</option>\n			<option value=\">\" '.($filterPref==">"? 'selected':'').'>'.GetMessage('FILTER_PREF_MAX').'</option>\n			<option value=\"<\" '.($filterPref=="<"? 'selected':'').'>'.GetMessage('FILTER_PREF_MIN').'</option>\n			<option value=\"=!\" '.($filterPref=="=!"? 'selected':'').'>'.GetMessage('FILTER_PREF_NERAVNO').'</option>\n	<option value=\"z\" '.($filterPref=="z"? 'selected':'').'>'.GetMessage('FILTER_PREF_ZERO').'</option>\n	 <option value=\"nz\" '.($filterPref=="nz"? 'selected':'').'>'.GetMessage('FILTER_PREF_NOTZERO').'</option>\n			</select>';

			return $strResult;
		}
		function addInputRow($intCount)
		{
			$strResult='<input name=\"FILTER_INPUT['.$intCount.']\" type=\"text\" value=\"\">';

			return $strResult;
		}
		function addRowParam($arProp,$nameIn,$sel='')
		{
				$res="<select name='".$nameIn."'>\n";
				$res .="<option value=''".($sel== "" ? ' selected' : '').">".GetMessage('AVITO_SKIP_PROP')."</option>\n";
				foreach($arProp as $key=>$val)
					{
						$res .='<option value="'.$key.'"'.($sel==$key? 'selected':'').'>';
						$res .=$val['NAME'];
						$res .="</option>\n";
					}
					$res .="</select>\n";
					return $res;
		}
	function avito_text2xml(string $text, array $options)
	{   $text=strip_tags($text);
		$text = htmlspecialcharsbx($text, ENT_QUOTES|ENT_XML1);
		$text = preg_replace("/[\x1-\x8\xB-\xC\xE-\x1F]/", "", $text);
		$error = '';
		if (LANG_CHARSET!=='UTF-8')
		$text = $APPLICATION->ConvertCharset($text, LANG_CHARSET, 'UTF-8');
		return $text;
	}
	function encodingUTF(string $text)
	{
		if (LANG_CHARSET!=='UTF-8')
		$text = $APPLICATION->ConvertCharset($text, LANG_CHARSET, 'UTF-8');
		return $text;
	}
	function avito_replace_special($arg)
	{
		if (in_array($arg[0], array("&quot;", "&amp;", "&lt;", "&gt;")))
			return $arg[0];
		else
			return " ";
	}


}
function Agent_FileMerge()
	{
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/bitrix/catalog_export/avito_fid.php", "w");

		$allFid='<? header("Content-Type: text/xml; charset=UTF-8");
echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">"?><Ads formatVersion="3" target="Avito.ru">';
		$replTopString='<? $disableReferers = false;
if (!isset($_GET["referer1"]) || strlen($_GET["referer1"])<=0) $_GET["referer1"] = "yandext";
$strReferer1 = htmlspecialchars($_GET["referer1"]);
if (!isset($_GET["referer2"]) || strlen($_GET["referer2"]) <= 0) $_GET["referer2"] = "";
$strReferer2 = htmlspecialchars($_GET["referer2"]);
header("Content-Type: text/xml; charset=UTF-8");
echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">"?><Ads formatVersion="3" target="Avito.ru">';
		$replTopString2='<? header("Content-Type: text/xml; charset=UTF-8");
echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">"?>
<Ads formatVersion="3" target="Avito.ru">';
		$fidText='';
		$replBotString='</Ads>';
        $optStr = COption::GetOptionString("abricos.avitoautoload", "ABRICOS_AVITOAUTOLOAD_FILE");
        $arrProfileId = explode(",", $optStr);
        foreach ($arrProfileId as $id){
            $fileName = ExportProfile($id);
            if ($fileName === false) continue;
            $fidText .=file_get_contents($_SERVER['DOCUMENT_ROOT'].trim($fileName));
        }
		$fidText=str_replace($replTopString,'',$fidText);
		$fidText=str_replace($replTopString2,'',$fidText);
		$fidText=str_replace($replBotString,'',$fidText);
		$allFid=$allFid.$fidText;
        $allFid=$allFid.$replBotString;
	    $test = fwrite($fp, $allFid);
		fclose($fp);
		return "Agent_FileMerge();";
	};
    function ExportProfile($profile_id)
	{
		global $DB;

		$profile_id = (int)$profile_id;
		if ($profile_id <= 0)
			return false;
        \Bitrix\Main\Loader::includeModule('catalog');
		$ar_profile = CCatalogExport::GetByID($profile_id);
		if ((!$ar_profile) || ('Y' == $ar_profile['NEED_EDIT']))
			return false;

		$strFile = CATALOG_PATH2EXPORTS.$ar_profile["FILE_NAME"]."_run.php";
		if (!file_exists($_SERVER["DOCUMENT_ROOT"].$strFile))
		{
			$strFile = CATALOG_PATH2EXPORTS_DEF.$ar_profile["FILE_NAME"]."_run.php";
			if (!file_exists($_SERVER["DOCUMENT_ROOT"].$strFile))
				return false;
		}

		$arSetupVars = array();
		$intSetupVarsCount = 0;
		if ('Y' != $ar_profile["DEFAULT_PROFILE"])
		{
			parse_str($ar_profile["SETUP_VARS"], $arSetupVars);
			if (!empty($arSetupVars) && is_array($arSetupVars))
				$intSetupVarsCount = extract($arSetupVars, EXTR_SKIP);
		}

		if (!defined('CATALOG_EXPORT_NO_STEP'))
			define('CATALOG_EXPORT_NO_STEP', true);
		$firstStep = true;
		$finalExport = true;
		$CUR_ELEMENT_ID = 0;

		CCatalogDiscountSave::Disable();
		include($_SERVER["DOCUMENT_ROOT"].$strFile);
		CCatalogDiscountSave::Enable();

		CCatalogExport::Update($profile_id, array(
			"=LAST_USE" => $DB->GetNowFunction()
			)
		);

		return $arSetupVars['SETUP_FILE_NAME'];
}

?>
