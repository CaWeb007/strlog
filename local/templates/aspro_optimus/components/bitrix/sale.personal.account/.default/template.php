 <?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="sale-personal-account-wallet-container">
	<div class="card-bonus">
		<div class="card-bonus-img"><img src="<?=$this->GetFolder()?>/images/card-1.jpg" alt="card"/></div>
		<div class="card-bonus-title">
			<? 
				global $USER;
				$userData = CUser::GetByID($USER->GetID());
				$arUser = $userData->Fetch();
				$userGroups = \CUser::GetUserGroup($USER->GetID());
				$userTotal = (float)$arUser["UF_ACCUMULATION"];
				$bonusName = "";
				$ostalos = false;
				
				/* КП */
				$arGroups = [9,14,15];
				$result = array_intersect($arGroups, $userGroups);
				if($result){
					if($userTotal >= 0 && $userTotal < 10000) {
						$bonusName = "КП Бронза";
						$ostalos = 10000 - $userTotal;
					}
					if($userTotal >= 10000 && $userTotal < 200000){
						$bonusName = "КП Серебро";
						$ostalos = 200000 - $userTotal;
					}
					if($userTotal >= 200000)
						$bonusName = "КП Золото";		
				}
				
				/* СО */
				$arGroups = [10,12];
				$result = array_intersect($arGroups, $userGroups);
				if($result){
					if($userTotal >= 0 && $userTotal < 500000){
						$bonusName = "СО Бронза";
						$ostalos = 500000 - $userTotal;
					}
					if($userTotal >= 500000 && $userTotal < 1500000){
						$bonusName = "СО Серебро";
						$ostalos = 1500000 - $userTotal;
					}
					if($userTotal >= 1500000){
						$bonusName = "СО Золото";
					}
				}
				
			?>
			<?=$bonusName?>
		</div>
	</div>
	<div class="r-block">
		<div class="sale-personal-account-wallet-title">
			Бонусов на счёте:
		</div>
		<div class="sale-personal-account-wallet-list-container">
			<div class="sale-personal-account-wallet-list">
				<?
				foreach($arResult["ACCOUNT_LIST"] as $accountValue)
				{ 
						if($accountValue['CURRENCY'] == 'RUB'){
						$bonus = (float)$accountValue['ACCOUNT_LIST']['CURRENT_BUDGET'];
						?>
						<div class="sale-personal-account-wallet-list-item">
							<div class="sale-personal-account-wallet-sum"><?=$bonus?></div>
							<div class="sale-personal-account-wallet-currency">
	
							</div>
						</div>
						<?
					  }
				}
				?>
			</div>
		</div>
	</div>
	<?if($ostalos):?>
		<div class="info-bonus-block">Текущий вид карты: "<strong>КП Бронза</strong>", оборот <strong><?=$userTotal?></strong> руб. До следующего уровня осталось <strong><?=$ostalos?></strong> руб.</div>
	<?endif?>
</div>