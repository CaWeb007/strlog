<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
$id = $USER->GetID();
$rsUser = CUser::GetByID($id);
$arUser = $rsUser->Fetch();
//edebug($arUser);

?>

<?if(!$USER->IsAuthorized() || empty($arUser['UF_BONUS_PARTNER'])):?>
<div class="prof-col bonus-program">

	<h4 class="personal-header">Бонусная программа</h4>

	<div class="description">
		<div class="desc-img"></div>
		<div class="desc-text">Получайте бонусы при покупке в магазине или на сайте.</div>
	</div>

	<!--NO PARTNER-->
	<input name="bonus-check" id="bonus-check" type="checkbox">
	<label class="bonus-check-button" for="bonus-check">Хочу стать участником <a href="/bonusnaya-karta/" target="_blank" href="">бонусной программы</a> и накапливать бонусы.</label>

	<div class="no_partner_info">
		<div class="no_partner_row birthday">
			<label class="no_partner_label">Дата рождения</label>
			<div class="date-elem">
				<select name="REGISTER[PERSONAL_DAY]"></select>
				<select name="REGISTER[PERSONAL_MONTH]"></select>
				<select name="REGISTER[PERSONAL_YEAR]"></select>
			</div>
			<input class="no_partner_input" id="beathday" size="30" type="text" name="REGISTER[PERSONAL_BIRTHDAY]" value="" placeholder="" onclick="BX.calendar({node: this, field: this, bTime: false});">

		</div>
		<div class="no_partner_row gender">
			<label class="no_partner_label">Пол</label>
			<select class="no_partner_select">
				<option></option>
				<option value="M">Мужской</option>
				<option value="F">Женский</option>
			</select>
			<input id="personal_gender" type="text" name="PERSONAL_GENDER" value="muzskoy" style="display: none;">
		</div>
	</div>

	<p>Соглашаясь на участие в бонусной программе, вы подтверждаете своё согласие на обработку Ваших персональных данных и согласие с <a href="/bonusnaya-karta/" target="_blank">условиями участия в Программе</a></p>
	<!--//NO PARTNER-->

</div>


<?else:?>
<div class="prof-col bonus-program">

	<h4 class="personal-header">Бонусная программа</h4>

	<div class="description">
		<div class="desc-img"></div>
		<div class="desc-text">
			<p>Вам доступно 100 бонусов. 1 бонус = 1 руб.
				Вы можете оплатить бонусами не более 30%
				от стоимости товара.</br>Внимание, на заказ оплаченный бонусами,
			бонусы от покупки не начисляются.</p>
		</div>

	</div>

	<!-- PARTNER-->
	<div class="row-form">
		<label>Введите количество бонусов к оплате</label>
		<input type="text" name="bonus">
	</div>
	<div class="bx_ordercart_order_sum">
		<a href="" onclick="" id="" class="checkout">пересчитать стоимость заказа</a>
	</div>
	<!--//PARTNER-->

</div>
<?endif;?>
<br><br><br><br><br><br><span style="font-weight:bold;color: red;">Внимание! Заказы, оформленные внерабочее время офиса, обрабатываются на следующий день с 9:00-11:00 (по Иркутскому времени).</span>