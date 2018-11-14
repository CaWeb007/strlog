<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{

	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			$APPLICATION->RestartBuffer();
			?>
			<script type="text/javascript">
				window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}

	}
}

$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");
?>
<?if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y"):?>
<div class="left-area">
	<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/top_side_bar_left_menu.php"
	)
	);?>
	<?if(CModule::IncludeModule("iblock")){ $IblockID = 6; // Скачать прайс лист ?>
	<? $File = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$IblockID), false, array(), array("ID", "NAME", "PROPERTY_ATT_DOWNLOAD_PRICE"));
   if($arFile = $File->GetNext()){
		$fileInfo = CFile::GetFileArray($arFile['PROPERTY_ATT_DOWNLOAD_PRICE_VALUE']);?>
		<a target="_blanc" href="<?=$fileInfo['SRC']?>">Скачать прайс - лист</a>
	<?}?>

<?}?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/bottom_side_bar_left_news.php"
	)
);?>

</div>
<div class="right-area">
<?endif;?>


<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<h1 class="bx-title dbg_title order_title">ОФОРМЛЕНИЕ ЗАКАЗА</h1>

<?
if (!function_exists("getColumnName"))
{
	function getColumnName($arHeader)
	{
		return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
	}
}

if (!function_exists("cmpBySort"))
{
	function cmpBySort($array1, $array2)
	{
		if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
			return -1;

		if ($array1["SORT"] > $array2["SORT"])
			return 1;

		if ($array1["SORT"] < $array2["SORT"])
			return -1;

		if ($array1["SORT"] == $array2["SORT"])
			return 0;
	}
}
?>

<div class="bx_order_make hello">
	<?
	if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
	{
		if(!empty($arResult["ERROR"]))
		{
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);
		}
		elseif(!empty($arResult["OK_MESSAGE"]))
		{
			foreach($arResult["OK_MESSAGE"] as $v)
				echo ShowNote($v);
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	}
	else
	{
		if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
		{
			if(strlen($arResult["REDIRECT_URL"]) == 0)
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
			}
		}
		else
		{
			?>
			<script type="text/javascript">

			<?if(CSaleLocation::isLocationProEnabled()):?>

				<?
				// spike: for children of cities we place this prompt
				$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
				?>

				BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
					'source' => $this->__component->getPath().'/get.php',
					'cityTypeId' => intval($city['ID']),
					'messages' => array(
						'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
						'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
						'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
							'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
							'#ANCHOR_END#' => '</a>'
						)).'</div>'
					)
				))?>);

			<?endif?>

			var BXFormPosting = false;
			function submitForm(val)
			{

				/* если выводится надпись (Подтвержден) и этот эл-т есть в DOM  */
				if ($('.confirm-tel').length && !$(".confirm-tel").hasClass("inactive")) {
					deleteCookie("CONFIRM_PHONE");
					deleteCookie("CONFIRM_PHONE_VALUE");
				}

				if (BXFormPosting === true)
					return true;

				BXFormPosting = true;
				if(val != 'Y')
					BX('confirmorder').value = 'N';

				var orderForm = BX('ORDER_FORM');
				BX.showWait();

				<?if(CSaleLocation::isLocationProEnabled()):?>
					BX.saleOrderAjax.cleanUp();
				<?endif?>
				BX.ajax.submit(orderForm, ajaxResult);

				return true;
			}

			function ajaxResult(res)
			{
				var orderForm = BX('ORDER_FORM');
				try
				{
					// if json came, it obviously a successfull order submit

					var json = JSON.parse(res);
					BX.closeWait();

					if (json.error)
					{
						BXFormPosting = false;
						return;
					}
					else if (json.redirect)
					{
						window.top.location.href = json.redirect;
					}
				}
				catch (e)
				{
					// json parse failed, so it is a simple chunk of html

					BXFormPosting = false;
					BX('order_form_content').innerHTML = res;

					<?if(CSaleLocation::isLocationProEnabled()):?>
						BX.saleOrderAjax.initDeferredControl();
					<?endif?>
				}

				BX.closeWait();
				BX.onCustomEvent(orderForm, 'onAjaxSuccess');
				OrderJS();

				/* при изменении способа доставки или оплаты, будем показывать подтвержден номер или нет */
				if (getCookie("CONFIRM_PHONE") == "Y") {

					$(".btn-confirm").removeClass("inactive"); 
					$(".confirm-tel").addClass("inactive"); 

					$("#ORDER_PROP_3").val(getCookie("CONFIRM_PHONE_VALUE"));
					$("#ORDER_PROP_3").data("tel",getCookie("CONFIRM_PHONE_VALUE"));
					$('.desc-confirm, .re-desc-confirm, .re-confirm').removeClass('active');
					$('.confirm-phone-success').addClass('active');
					$("#CONFIRM_PHOE").val("");
					$("#CONFIRM_PHOE").attr({'disabled':'disabled'})
					$('form[name="ORDER_FORM"] #CONFIRM_PHONE_SUCCESS').val("Y");
					$('#CONFIRM_PHOE').addClass("active");

					// success ConfirmPhone
					$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", true);
					
				}

				/* если изменяем номер телефона, то выводим кнопку его подтверждения */
				$('form[name="ORDER_FORM"] input[name="ORDER_PROP_3"]').on('keyup', checkPhoneKeyup);

				// заполним селекты выбора даты значениями
				writeDate();

				// повешаем оформление на селекты выбора даты
				customSelect.init();


			}

			function SetContact(profileId)
			{
				BX("profile_change").value = "Y";
				submitForm();
			}

			
			</script>
			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>
				<form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data" class="order-form" onsubmit="yaCounter37983465.reachGoal('goal_order_success');_gaq.push(['_trackEvent', 'zakaz', 'ok']); return true;">
				<?=bitrix_sessid_post()?>
				<div id="order_form_content">
				<?
			}
			else
			{
				$APPLICATION->RestartBuffer();
			}

			if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
			{
				?>
				<input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
				<?
			}

			if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			{


				if(count($arResult["ERROR"]) == 1){

					foreach($arResult["ERROR"] as $v)
						echo "<p>" . strip_tags($arResult["ERROR"][0],'<p><a>') . "</p>";

				}else{

					foreach($arResult["ERROR"] as $v)
						ShowError($v);
				}

				?>
				<script type="text/javascript">
					top.BX.scrollToNode(top.BX('ORDER_FORM'));
				</script>
				<?
			}?>
			<div class="prop-block">
				<div class="left-order-area">
					<?

					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
					if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
					{
						include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
						include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
					}
					else
					{
						include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
						include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
					}

					//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");?>
				</div>
				<div class="right-order-area">
					<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/bonus_prog.php");?>
				</div>
			</div>

			<div class="summary-block">
				<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
				if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
					echo $arResult["PREPAY_ADIT_FIELDS"];
				?>
			</div>


			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>
					</div>
					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">

				</form>
				<?
				if($arParams["DELIVERY_NO_AJAX"] == "N")
				{
					?>
					<div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
					<?
				}
			}
			else
			{
				?>
				<script type="text/javascript">
					top.BX('confirmorder').value = 'Y';
					top.BX('profile_change').value = 'N';
				</script>
				<?
				die();
			}
		}
	}
	?>
	</div>
</div>

<?if(CSaleLocation::isLocationProEnabled()):?>

	<div style="display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent("bitrix:sale.location.selector.steps", "selSteps", Array(
	
	),
	false
);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.search",
			".default",
			array(
			),
			false
		);?>
	</div>
<?endif?>

<?if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y"):?>
</div>
<?endif?>

<script type="text/javascript">

	function checkPhoneKeyup(event) {
		// если пользователь не авторизован
		if ($(this).data("tel") == "") return;

		var current_tel = $(this).val();
		var default_tel = $(this).data("tel");
		var cookie_tel = getCookie("CONFIRM_PHONE_VALUE");
		$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", true);

		// случай, когда было выполнено повторное подтверждение номера телефона
		if (($('form[name="ORDER_FORM"] .confirm-tel').hasClass("inactive") || !$('form[name="ORDER_FORM"] .confirm-tel').length) && getCookie('CONFIRM_PHONE') == "Y") {

			if (current_tel != current_tel) {
				$('#CONFIRM_PHOE').removeClass('active');
				$('#CONFIRM_PHOE').removeAttr('disabled');
				$('#confirm-phone-success').removeClass('active');
				$('#confirm-phone').removeClass('active');
				$('#re-confirm').removeClass('active');
				$('.desc-confirm').removeClass('active');
				$('form[name="ORDER_FORM"] #CONFIRM_PHONE_SUCCESS').val("N");
				$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", false);
			} else {
				$('#CONFIRM_PHOE').addClass('active');
				$('#CONFIRM_PHOE').attr('disabled', 'disabled');
				$('#confirm-phone-success').addClass('active');
				$('.desc-confirm').removeClass('active');
				$('form[name="ORDER_FORM"] #CONFIRM_PHONE_SUCCESS').val("Y");
				$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", true);
			}

			return;
		}

		if (current_tel != current_tel) {
			$('form[name="ORDER_FORM"] .btn-confirm').removeClass("inactive");
			$('form[name="ORDER_FORM"] .confirm-tel').addClass("inactive");
			$('form[name="ORDER_FORM"] #CONFIRM_PHONE_SUCCESS').val("N");
			$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", false);
		} else {
			$('form[name="ORDER_FORM"] .btn-confirm').addClass("inactive");
			$('form[name="ORDER_FORM"] .confirm-tel').removeClass("inactive");
			$('form[name="ORDER_FORM"] #CONFIRM_PHONE_SUCCESS').val("Y");
			$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", true);
		}
	}

	/* Сделаем заполнение select для ввода даты рождения */
	function writeDate() {

		var date = new Date();
		var days = ['01', '02', '03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
		var month = {
			0: {
				'name': 'Январь',
				'number': '01',
				'countday': 31,
				'id': 1
			}, 
			1:{
				'name' : 'Февраль',
				'number' : '02',
				'countday' : 28,
				'id': 2
			},
			2: {
				'name' : 'Март',
				'number' : '03',
				'countday' : 31,
				'id': 3
			},
			3: {
				'name' : 'Апрель',
				'number' : '04',
				'countday' : 30,
				'id': 4
			},
			4: {
				'name' : 'Май',
				'number' : '05',
				'countday' : 31,
				'id': 5
			},
			5 : {
				'name' : 'Июнь',
				'number' : '06',
				'countday' : 30,
				'id': 6
			},
			6 : {
				'name' : 'Июль',
				'number' : '07',
				'countday' : 31,
				'id': 7
			},
			7 : {
				'name' : 'Август',
				'number' : '08',
				'countday' : 31,
				'id': 8
			},
			8 : {
				'name' : 'Сентябрь',
				'number' : '09',
				'countday' : 30,
				'id': 9
			},
			9 : {
				'name' : 'Октябрь',
				'number' : '10',
				'countday' : 31,
				'id': 10
			},
			10 : {
				'name' : 'Ноябрь',
				'number' : '11',
				'countday' : 30,
				'id': 11
			},
			11 : {
				'name' : 'Декабрь',
				'number' : '12',
				'countday' : 31,
				'id': 12
			},
		};

		var year = {'min_year' : 1930, 'max_year' : date.getFullYear()};

		var day_option = '<option value="no" selected="selected">-</option>';
		for (var i = 0; i < 31; i++) {
			day_option += '<option value="' +  days[i] + '">' + days[i] + '</option>';
		}

		var month_option = '<option value="no" selected="selected">-</option>';
		for(var key in month) {
			month_option += '<option value="' + month[key].number + '" data-monthid="'+ month[key].id +'">' + month[key].name + '</option>';
		}

		var year_option = '<option value="no" selected="selected">-</option>';
		for (var i = year.max_year; i >= 1930 ; i--) {
			year_option += '<option value="' +  i + '">' + i + '</option>';
		}

		$('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);
		$('#ORDER_FORM select[name="REGISTER[PERSONAL_MONTH]"]').append(month_option);
		$('#ORDER_FORM select[name="REGISTER[PERSONAL_YEAR]"]').append(year_option);

		$('#ORDER_FORM select[name="REGISTER[PERSONAL_MONTH]"]').on('change', {month: month, days: days} ,function(event) {
			if ($(this).val() == "no") {
				return false;	
			}
			var month_id = $(this).children('option:selected').data('monthid');
			console.log(month[month_id - 1].countday);


			// запомним выбранный пользователем ранее день
			if ($(this).siblings('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').val() != "no") {
				var day = $(this).siblings('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').val();
			}

			// очистим select 
			$(this).siblings('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').empty();

			// сформируем новый селект с учетом особенностей каждого месяца
			var day_option = '<option value="no">-</option>';
			for (var i = 0; i < month[month_id - 1].countday; i++) {
				// если пользователь выбрал определенный день ранее
				if (day) {
					// если этот это число входит в дни выбранного месяца, то делаем его выбранным
					if (day == days[i]) {
						day_option += '<option selected="selected" value="' +  days[i] + '">' + days[i] + '</option>';
						continue;
					} 
					else {
						day_option += '<option value="' +  days[i] + '">' + days[i] + '</option>';
						continue;
					}

				} 
				else day_option += '<option value="' +  days[i] + '">' + days[i] + '</option>';
			}

			$(this).siblings('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);


		});

	}
</script>

