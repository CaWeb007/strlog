<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arParam['SHOW_ACCOUNT_PAGE'] === 'N')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_ACCOUNT"));
// $APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_ACCOUNT"));
?>
<div class="personal_wrapper">
	<div class="inner_border">
		<?if ($arParam['SHOW_ACCOUNT_COMPONENT'] !== 'N')
		{
			$APPLICATION->IncludeComponent(
				"bitrix:sale.personal.account",
				"",
				Array(
					"SET_TITLE" => "N"
				),
				$component
			);
		}
		?>
		<? // '<h3 class="sale-personal-section-account-sub-header">
//Loc::getMessage("SPS_BUY_MONEY")
				//</h3>'
				
		global $USER;
		$res = $USER->GetByID($USER->GetID());
		$userData = $res->fetch();
        $userListPhone = $userData["PERSONAL_PHONE"];
        $userListPhone = preg_replace('![^0-9]+!', '', $userListPhone);
        if(strlen($userListPhone) == 11) $userListPhone = mb_substr($userListPhone, 1);
		
		$bonuses = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/bitrix/px_files/bonus.xml");
		
		foreach ($bonuses->user as $bonus) {
            $userPhone = (string)$bonus->user_phone;
            $userBonus = $bonus->user_bonus;

            if ($userPhone == $userListPhone) {
				?>
				<div class="bonus-history">
					<div><h2>История начислений</h2></div>
					<div class="table">
						<div class="row-history bheader">
							<div class="date">Дата</div>
							<div class="type">Тип начисления</div>
							<div class="operation">Операция</div>
							<div class="amount">Сумма</div>
						</div>
				<?
				foreach($bonus->bonus_history->amount as $bhistory):?>
						<div class="row-history">
							<div class="date"><?=(string)$bhistory['date']?></div>
							<div class="type"><?=(string)$bhistory['type']?></div>
							<div class="operation"><?=(string)$bhistory['operation']?></div>
							<div class="amount"><?=(float)$bhistory?></div>
						</div>
				<?
				endforeach;
				?>
					</div>
				</div>
				<?
            }

        }
    /*
	$userFilter = array(
        'PERSONAL_PHONE' => '',
        'ACTIVE' => 'Y'
    );
    $userParams = array(
        'SELECT' => array('UF_BONUSES'),
        'FIELDS' => array(
            'ID',
            'PERSONAL_PHONE',
            'UF_BONUSES',
        ),
    );
    $rsUser = CUser::GetList(
        $userFilter,
        $userParams
    );
    while ($userList = $rsUser->Fetch()) {
        $userListID = $userList["ID"];
        $userListPhone = $userList["PERSONAL_PHONE"];
        $phone = preg_replace('![^0-9]+!', '', $userListPhone);
        if(strpos($userListPhone) == 11) $userListPhone = mb_substr($phone, 1);
        foreach ($bonuses->user as $bonus) {
            $userPhone = (string)$bonus->user_phone;
            $userBonus = $bonus->user_bonus;

            if ($userPhone == $userListPhone) {
                if ($userBonus == '') {
                    $userBonus = 0;
                }
                $thisUser = new CUser;
				$thisUser->Update($userListID, array("UF_BONUSES" => $userBonus));

				$account = CSaleUserAccount::GetByUserID($userListID, "RUB");
				$arFields = Array("USER_ID" => $userListID, "CURRENCY" => "RUB", "CURRENT_BUDGET" => $userBonus);
				if(!$account) {
					$accountID = CSaleUserAccount::Add($arFields);
				} else {
					if((int)$account['ID'] > 0)
						$accountID = CSaleUserAccount::Update($account['ID'],$arFields);
				}
            }

        }
    }*/?>

		<?
		if ($arParam['SHOW_ACCOUNT_PAY_COMPONENT'] === 'Y')
		{
			$APPLICATION->IncludeComponent(
				"bitrix:sale.account.pay",
				"",
				Array(
					"COMPONENT_TEMPLATE" => ".default",
					"REFRESHED_COMPONENT_MODE" => "Y",
					"ELIMINATED_PAY_SYSTEMS" => $arParams['ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS'],
					"PATH_TO_BASKET" => $arParams['PATH_TO_BASKET'],
					"PATH_TO_PAYMENT" => $arParams['PATH_TO_PAYMENT'],
					"PERSON_TYPE" => $arParams['ACCOUNT_PAYMENT_PERSON_TYPE'],
					"REDIRECT_TO_CURRENT_PAGE" => "N",
					"SELL_AMOUNT" => $arParams['ACCOUNT_PAYMENT_SELL_TOTAL'],
					"SELL_CURRENCY" => $arParams['ACCOUNT_PAYMENT_SELL_CURRENCY'],
					"SELL_SHOW_FIXED_VALUES" => $arParams['ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES'],
					"SELL_SHOW_RESULT_SUM" =>  $arParams['ACCOUNT_PAYMENT_SELL_SHOW_RESULT_SUM'],
					"SELL_TOTAL" => $arParams['ACCOUNT_PAYMENT_SELL_TOTAL'],
					"SELL_USER_INPUT" => $arParams['ACCOUNT_PAYMENT_SELL_USER_INPUT'],
					"SELL_VALUES_FROM_VAR" => "N",
					"SELL_VAR_PRICE_VALUE" => "",
					"SET_TITLE" => "N",
				),
				$component
			);
		}
		?>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.btn').addClass('button')
		})
	</script>
</div>
<div class="clearfix"></div>
<div class="personal_menu">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
			"AREA_FILE_SHOW" => "file",
			"AREA_FILE_SUFFIX" => "",
			"AREA_FILE_RECURSIVE" => "Y",
			"EDIT_TEMPLATE" => "standard.php"
		),
		false
	);?>
</div>
