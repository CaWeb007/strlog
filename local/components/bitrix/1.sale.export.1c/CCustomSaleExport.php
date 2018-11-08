<?

class CCustomSaleExport extends CSaleExport {

	static function ExportContragents($arOrder = array(), $arProp = array(), $agent = array(), $arOptions = array())
	{
		$bExportFromCrm = (isset($arOptions["EXPORT_FROM_CRM"]) && $arOptions["EXPORT_FROM_CRM"] === "Y");
		?>
		<<?=CSaleExport::getTagName("SALE_EXPORT_CONTRAGENTS")?>>
			<<?=CSaleExport::getTagName("SALE_EXPORT_CONTRAGENT")?>>
		<?

		$xmlIdTemp = htmlspecialcharsbx(substr($arProp["CRM"]["CLIENT_ID"]."0".$agent['REKV']['REKV_0']."#".$arProp["CRM"]["CLIENT"]["LOGIN"]."#".$arProp["CRM"]["CLIENT"]["LAST_NAME"]." ".$arProp["CRM"]["CLIENT"]["NAME"]." ".$arProp["CRM"]["CLIENT"]["SECOND_NAME"], 0, 40));

		if ($bExportFromCrm):
			$xmlId = htmlspecialcharsbx(substr($arProp["CRM"]["CLIENT_ID"].$agent['REKV']['REKV_0']."#".$arProp["CRM"]["CLIENT"]["LOGIN"]."#".$arProp["CRM"]["CLIENT"]["LAST_NAME"]." ".$arProp["CRM"]["CLIENT"]["NAME"]." ".$arProp["CRM"]["CLIENT"]["SECOND_NAME"], 0, 40));
		else:
				if(strlen($arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"])>0):
                    {	
						if(strlen($xmlIdTemp) != strlen($arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"])){
							$exp = explode("#",$arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"]);
							$exp[0] = $arProp["CRM"]["CLIENT_ID"].$agent['REKV']['REKV_0'];
							$arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"] = implode("#",$exp);
							$xmlId = htmlspecialcharsbx($arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"]);
						}
					}
                else:
				    $xmlId = htmlspecialcharsbx(substr($arOrder["USER_ID"]."_".$agent['REKV']['REKV_0']."#".$arProp["USER"]["LOGIN"]."#".$arProp["USER"]["LAST_NAME"]." ".$arProp["USER"]["NAME"]." ".$arProp["USER"]["SECOND_NAME"], 0, 40));
                    \Bitrix\Sale\Exchange\Entity\UserImportBase::updateEmptyXmlId($arOrder["USER_ID"], $xmlId);
				endif;
		endif; ?>
                <<?=CSaleExport::getTagName("SALE_EXPORT_ID")?>><?=$xmlId?></<?=CSaleExport::getTagName("SALE_EXPORT_ID")?>>

				<<?=CSaleExport::getTagName("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agent["AGENT_NAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_ITEM_NAME")?>>
				<?
				self::setDeliveryAddress($agent["ADDRESS_FULL"]);
				$address = "";
				if(strlen($agent["ADDRESS_FULL"])>0)
				{
				    $address .= "<".CSaleExport::getTagName("SALE_EXPORT_PRESENTATION").">".htmlspecialcharsbx($agent["ADDRESS_FULL"])."</".CSaleExport::getTagName("SALE_EXPORT_PRESENTATION").">";
				}
				else
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_PRESENTATION")."></".CSaleExport::getTagName("SALE_EXPORT_PRESENTATION").">";
				}
				if(strlen($agent["INDEX"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_POST_CODE")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["INDEX"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["COUNTRY"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
									<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_COUNTRY")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
									<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["COUNTRY"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
								</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["REGION"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_REGION")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["REGION"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["STATE"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_STATE")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["STATE"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["TOWN"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_SMALL_CITY")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["TOWN"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["CITY"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_CITY")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["CITY"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["STREET"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_STREET")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["STREET"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["HOUSE"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_HOUSE")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["HOUSE"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["BUILDING"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_BUILDING")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["BUILDING"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["FLAT"])>0)
				{
					$address .= "<".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">
								<".CSaleExport::getTagName("SALE_EXPORT_TYPE").">".CSaleExport::getTagName("SALE_EXPORT_FLAT")."</".CSaleExport::getTagName("SALE_EXPORT_TYPE").">
								<".CSaleExport::getTagName("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["FLAT"])."</".CSaleExport::getTagName("SALE_EXPORT_VALUE").">
							</".CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD").">";
				}

				if($agent["IS_FIZ"]=="Y")
				{
					self::$arResultStat["CONTACTS"]++;
					?>
					<<?=CSaleExport::getTagName("SALE_EXPORT_FULL_NAME")?>><?=htmlspecialcharsbx($agent["FULL_NAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_FULL_NAME")?>>
					<?
					if(strlen($agent["SURNAME"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_SURNAME")?>><?=htmlspecialcharsbx($agent["SURNAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_SURNAME")?>><?
					}
					if(strlen($agent["NAME"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_NAME")?>><?=htmlspecialcharsbx($agent["NAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_NAME")?>><?
					}
					if(strlen($agent["SECOND_NAME"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_MIDDLE_NAME")?>><?=htmlspecialcharsbx($agent["SECOND_NAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_MIDDLE_NAME")?>><?
					}
					if(strlen($agent["BIRTHDAY"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_BIRTHDAY")?>><?=htmlspecialcharsbx($agent["BIRTHDAY"])?></<?=CSaleExport::getTagName("SALE_EXPORT_BIRTHDAY")?>><?
					}
					if(strlen($agent["MALE"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_SEX")?>><?=htmlspecialcharsbx($agent["MALE"])?></<?=CSaleExport::getTagName("SALE_EXPORT_SEX")?>><?
					}
					if(strlen($agent["INN"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_INN")?>><?=htmlspecialcharsbx($agent["INN"])?></<?=CSaleExport::getTagName("SALE_EXPORT_INN")?>><?
					}
					if(strlen($agent["KPP"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_KPP")?>><?=htmlspecialcharsbx($agent["KPP"])?></<?=CSaleExport::getTagName("SALE_EXPORT_KPP")?>><?
					}
					if(strlen($address)>0)
                    {
						?><<?=CSaleExport::getTagName("SALE_EXPORT_REGISTRATION_ADDRESS")?>>
						<?=$address?>
                        </<?=CSaleExport::getTagName("SALE_EXPORT_REGISTRATION_ADDRESS")?>>
						<?
                    }
				}
				else
				{
					self::$arResultStat["COMPANIES"]++;
					?>
					<<?=CSaleExport::getTagName("SALE_EXPORT_OFICIAL_NAME")?>><?=htmlspecialcharsbx($agent["FULL_NAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OFICIAL_NAME")?>>
					<?
					if(strlen($address)>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_UR_ADDRESS")?>>
						<?=$address?>
						</<?=CSaleExport::getTagName("SALE_EXPORT_UR_ADDRESS")?>><?
					}
					if(strlen($agent["INN"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_INN")?>><?=htmlspecialcharsbx($agent["INN"])?></<?=CSaleExport::getTagName("SALE_EXPORT_INN")?>><?
					}
					if(strlen($agent["KPP"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_KPP")?>><?=htmlspecialcharsbx($agent["KPP"])?></<?=CSaleExport::getTagName("SALE_EXPORT_KPP")?>><?
					}
					if(strlen($agent["EGRPO"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_EGRPO")?>><?=htmlspecialcharsbx($agent["EGRPO"])?></<?=CSaleExport::getTagName("SALE_EXPORT_EGRPO")?>><?
					}
					if(strlen($agent["OKVED"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_OKVED")?>><?=htmlspecialcharsbx($agent["OKVED"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OKVED")?>><?
					}
					if(strlen($agent["OKDP"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_OKDP")?>><?=htmlspecialcharsbx($agent["OKDP"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OKDP")?>><?
					}
					if(strlen($agent["OKOPF"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_OKOPF")?>><?=htmlspecialcharsbx($agent["OKOPF"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OKOPF")?>><?
					}
					if(strlen($agent["OKFC"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_OKFC")?>><?=htmlspecialcharsbx($agent["OKFC"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OKFC")?>><?
					}
					if(strlen($agent["OKPO"])>0)
					{
						?><<?=CSaleExport::getTagName("SALE_EXPORT_OKPO")?>><?=htmlspecialcharsbx($agent["OKPO"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OKPO")?>><?
						?><<?=CSaleExport::getTagName("SALE_EXPORT_OKPO_CODE")?>><?=htmlspecialcharsbx($agent["OKPO"])?></<?=CSaleExport::getTagName("SALE_EXPORT_OKPO_CODE")?>><?
					}
					if(strlen($agent["ACCOUNT_NUMBER"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_MONEY_ACCOUNTS")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_MONEY_ACCOUNT")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ACCOUNT_NUMBER")?>><?=htmlspecialcharsbx($agent["ACCOUNT_NUMBER"])?></<?=CSaleExport::getTagName("SALE_EXPORT_ACCOUNT_NUMBER")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_BANK")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agent["B_NAME"])?></<?=CSaleExport::getTagName("SALE_EXPORT_ITEM_NAME")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_PRESENTATION")?>><?=htmlspecialcharsbx($agent["B_ADDRESS_FULL"])?></<?=CSaleExport::getTagName("SALE_EXPORT_PRESENTATION")?>>
						<?
						if(strlen($agent["B_INDEX"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_POST_CODE")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_INDEX"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_COUNTRY"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_COUNTRY")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_COUNTRY"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_REGION"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_REGION")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_REGION"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_STATE"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_STATE")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_STATE"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_TOWN"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_SMALL_CITY")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_TOWN"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_CITY"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_CITY")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_CITY"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_STREET"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_STREET")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_STREET"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_HOUSE"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_HOUSE")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_HOUSE"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_BUILDING"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_BUILDING")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_BUILDING"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_FLAT"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_FLAT")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
							<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_FLAT"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
							</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						?>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS")?>>
						<?
						if(strlen($agent["B_BIK"])>0)
						{
							?><<?=CSaleExport::getTagName("SALE_EXPORT_BIC")?>><?=htmlspecialcharsbx($agent["B_BIK"])?></<?=CSaleExport::getTagName("SALE_EXPORT_BIC")?>><?
						}
						?>
						</<?=CSaleExport::getTagName("SALE_EXPORT_BANK")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_MONEY_ACCOUNT")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_MONEY_ACCOUNTS")?>>
					<?
					}
				}
				if(strlen($agent["F_ADDRESS_FULL"])>0)
				{
					self::setDeliveryAddress($agent["F_ADDRESS_FULL"]);
					?>
					<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS")?>>
					<<?=CSaleExport::getTagName("SALE_EXPORT_PRESENTATION")?>><?=htmlspecialcharsbx($agent["F_ADDRESS_FULL"])?></<?=CSaleExport::getTagName("SALE_EXPORT_PRESENTATION")?>>
					<?
					if(strlen($agent["F_INDEX"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_POST_CODE")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_INDEX"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_COUNTRY"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_COUNTRY")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_COUNTRY"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_REGION"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_REGION")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_REGION"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_STATE"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_STATE")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_STATE"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_TOWN"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_SMALL_CITY")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_TOWN"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_CITY"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_CITY")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_CITY"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_STREET"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_STREET")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_STREET"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_HOUSE"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_HOUSE")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_HOUSE"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_BUILDING"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_BUILDING")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_BUILDING"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_FLAT"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=CSaleExport::getTagName("SALE_EXPORT_FLAT")?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_FLAT"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					?>
					</<?=CSaleExport::getTagName("SALE_EXPORT_ADDRESS")?>>
				<?
				}
				if(strlen($agent["PHONE"])>0 || strlen($agent["EMAIL"])>0)
				{
					?>
					<<?=CSaleExport::getTagName("SALE_EXPORT_CONTACTS")?>>
					<?
					if(strlen($agent["PHONE"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_CONTACT")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=(self::getVersionSchema() > self::DEFAULT_VERSION ? CSaleExport::getTagName("SALE_EXPORT_WORK_PHONE_NEW") : CSaleExport::getTagName("SALE_EXPORT_WORK_PHONE"))?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["PHONE"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_CONTACT")?>>
					<?
					}
					if(strlen($agent["EMAIL"])>0)
					{
						?>
						<<?=CSaleExport::getTagName("SALE_EXPORT_CONTACT")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>><?=(self::getVersionSchema() > self::DEFAULT_VERSION ? CSaleExport::getTagName("SALE_EXPORT_MAIL_NEW") : CSaleExport::getTagName("SALE_EXPORT_MAIL"))?></<?=CSaleExport::getTagName("SALE_EXPORT_TYPE")?>>
						<<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["EMAIL"])?></<?=CSaleExport::getTagName("SALE_EXPORT_VALUE")?>>
						</<?=CSaleExport::getTagName("SALE_EXPORT_CONTACT")?>>
					<?
					}
					?>
					</<?=CSaleExport::getTagName("SALE_EXPORT_CONTACTS")?>>
				<?
				}
				if(strlen($agent["CONTACT_PERSON"])>0)
				{
					?>
					<<?=CSaleExport::getTagName("SALE_EXPORT_REPRESENTATIVES")?>>
					<<?=CSaleExport::getTagName("SALE_EXPORT_REPRESENTATIVE")?>>
					<<?=CSaleExport::getTagName("SALE_EXPORT_CONTRAGENT")?>>
					<<?=CSaleExport::getTagName("SALE_EXPORT_RELATION")?>><?=CSaleExport::getTagName("SALE_EXPORT_CONTACT_PERSON")?></<?=CSaleExport::getTagName("SALE_EXPORT_RELATION")?>>
					<<?=CSaleExport::getTagName("SALE_EXPORT_ID")?>><?=md5($agent["CONTACT_PERSON"])?></<?=CSaleExport::getTagName("SALE_EXPORT_ID")?>>
					<<?=CSaleExport::getTagName("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agent["CONTACT_PERSON"])?></<?=CSaleExport::getTagName("SALE_EXPORT_ITEM_NAME")?>>
					</<?=CSaleExport::getTagName("SALE_EXPORT_CONTRAGENT")?>>
					</<?=CSaleExport::getTagName("SALE_EXPORT_REPRESENTATIVE")?>>
					</<?=CSaleExport::getTagName("SALE_EXPORT_REPRESENTATIVES")?>>
				<?
				}?>
				<<?=CSaleExport::getTagName("SALE_EXPORT_ROLE")?>><?=CSaleExport::getTagName("SALE_EXPORT_BUYER")?></<?=CSaleExport::getTagName("SALE_EXPORT_ROLE")?>>
			</<?=CSaleExport::getTagName("SALE_EXPORT_CONTRAGENT")?>>
		</<?=CSaleExport::getTagName("SALE_EXPORT_CONTRAGENTS")?>>
		<?

		$filedsTolog = array(
			'ENTITY_ID' => $arOrder["USER_ID"],
			'PARENT_ID' => $arOrder['ID'],
			'ENTITY_DATE_UPDATE' => new \Bitrix\Main\Type\DateTime(\CAllDatabase::FormatDate($arOrder["SALE_INTERNALS_ORDER_USER_TIMESTAMP_X"])),
			'XML_ID' => $xmlId
		);

		static::$documentsToLog[\Bitrix\Sale\Exchange\EntityType::USER_PROFILE][] = $filedsTolog;
	}
}

?>