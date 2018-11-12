$(document).ready(function(){

	// заполняем select выбора даты рождения значениями
	writeDate();

	/* ПОдтвердить телефон */
	$("#checkTelForm1").on('click',function(e){
		e.preventDefault();
		var $orderPhone = validateTel($('form[name="form1"] input[name="PERSONAL_PHONE"]'));
			if ($orderPhone) {
				
				
				
			userPhone = $('form[name="form1"] input[name="PERSONAL_PHONE"]').val();
				
				$.ajax({
					url:'http://стройлогистика.рф/local/templates/strlog/components/uni/main.register/register2/ajax.php',
					type:'post',
					dataType:'json',
					beforeSend:function(){
						BX.showWait();
					},
					data:{userPhone:userPhone},
					success:function(data){
					console.log(data)
					if(data.user == "Y"){
						$("form[name='form1'] .topltip_error_top").show();	
					}else{
							$('form[name="form1"] input[name="CODE"]').show();
								$('form[name="form1"] #sendCodeForm1').show();
								$('form[name="form1"] #re-confirm-top-form1').show();
								$('form[name="form1"] .desc_confirm_top').show();
								$("form[name='form1'] #checkTelForm1").hide();
					}	
					
					},
					complete:function(){
						BX.closeWait();
					}
				})
				
				
			}
			
	})
	
	/* Отправить код на проверку */
	$("#sendCodeForm1").on('click',function(e){
		e.preventDefault();
		var $orderPhone = validateCode($('form[name="form1"] input[name="CODE"]'),1,4);
		
		if(!$orderPhone){
			return false;
		}
			var Code = $('form[name="form1"] input[name="CODE"]').val();
			
			$.ajax({
					url:'http://стройлогистика.рф/local/templates/strlog/components/uni/main.register/register2/ajax.php',
					type:'post',
					dataType:'json',
					beforeSend:function(){
						BX.showWait();
					},
					data:{Code:Code},
					
					success:function(data){
						console.log(data);
						if(data.code_confirm == "Y"){
							$("#sendCodeForm1").addClass('success');
							$("form[name='form1'] input[name='CODE']").val("");
							$("form[name='form1'] input[name='CODE']").attr({'disabled':'disabled'});
							$("#re-confirm-top-form1").hide();
							$(".desc_confirm_top").hide();
							$("#CHECKPHONE").val("Y");	
						}else{
							$(".desc_confirm_top").hide();
							$(".re_desc_confirm_top_error").show();
							$("form[name='form1'] input[name='CODE']").addClass('error');
						}
						$("form[name='form1'] input[name='CODE']").on('focus',function(){
							$(".re_desc_confirm_top_error").hide();
							$(".desc_confirm_top").show();
							$(this).removeClass('error');
						})
					
						

					},
					complete:function(){
						BX.closeWait();
					}
				})
		
		
	})
	
	/* Отправить код повторно */
	$("#re-confirm-top-form1").on('click',function(e){
		e.preventDefault();
		
			var $orderPhone = validateTel($('form[name="form1"] input[name="PERSONAL_PHONE"]'));
				if($orderPhone){
					var Reconfirm = "Y";
					userPhone = $('form[name="form1"] input[name="PERSONAL_PHONE"]').val();
					$.ajax({
						url:'http://стройлогистика.рф/local/templates/strlog/components/uni/main.register/register2/ajax.php',
						type:'post',
						dataType:'json',
						beforeSend:function(){
							BX.showWait();
						},
						data:{Reconfirm:Reconfirm,userPhone:userPhone},
						success:function(data){
							
						if(data.code_confirm == "Y"){
							$("#sendCodeForm1").addClass('success');
							$("form[name='form1'] input[name='CODE']").val("");
							$("form[name='form1'] input[name='CODE']").attr({'disabled':'disabled'});
							$("#re-confirm-top-form1").hide();
							$(".desc_confirm_top").hide();
							$("#CHECKPHONE").val("Y");	
						}else{
							$(".desc_confirm_top").hide();
							$(".re_desc_confirm_top_error").show();
							$("form[name='form1'] input[name='CODE']").addClass('error');
						}
						$("form[name='form1'] input[name='CODE']").on('focus',function(){
							$(".re_desc_confirm_top_error").hide();
							$(".desc_confirm_top").show();
							$(this).removeClass('error');
						})
						
						},

							complete:function(){
								BX.closeWait();
						}
					})
				}
					
		
		
	});
	
	$("#re-confirm-top-form1").hover(function(e){
		$(".re-desc-confirm").addClass('active');
	},function(){
		$(".re-desc-confirm").removeClass('active');
	});

	// work hover
	$("#re-confirm-top-form1").hover(function(){
		$(".desc_confirm_top").hide();
		$(".re_desc_confirm_top").show();
	}, function(){
			$(".desc_confirm_top").show();
			$(".re_desc_confirm_top").hide();
		}
	);

	// work
	$(".topltip_error_top .close").on('click',function(){
		$(this).parent().hide()
	});

	//work 
	$(".topltip_error_top .orange.login").on('click',function(e){
		e.preventDefault();
		$("#auth").trigger('click');
	});

});

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

		var day_option = '';
		var default_day = $('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').data("default");
		for (var i = 0; i < 31; i++) {
			if (days[i] == default_day) {
				day_option += '<option value="' +  days[i] + '" selected="selected">' + days[i] + '</option>';
				continue;
			}
			day_option += '<option value="' +  days[i] + '">' + days[i] + '</option>';
		}

		var month_option = '';
		var default_month = $('form[name="form1"] select[name="REGISTER[PERSONAL_MONTH]"]').data("default");
		for(var key in month) {
			if (month[key].number == default_month) {
				month_option += '<option value="' +  month[key].number + '" data-monthid="'+ month[key].id +'" selected="selected">' + month[key].name + '</option>';
				continue;
			}
			month_option += '<option value="' + month[key].number + '" data-monthid="'+ month[key].id +'">' + month[key].name + '</option>';
		}

		var year_option = '';
		var default_year = $('form[name="form1"] select[name="REGISTER[PERSONAL_YEAR]"]').data("default");
		for (var i = year.max_year; i >= 1930 ; i--) {
			if (i == default_year) {
				year_option += '<option value="' +  i + '" selected="selected">' + i + '</option>';
				continue;
			}
			year_option += '<option value="' +  i + '">' + i + '</option>';
		}

		$('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);
		$('form[name="form1"] select[name="REGISTER[PERSONAL_MONTH]"]').append(month_option);
		$('form[name="form1"] select[name="REGISTER[PERSONAL_YEAR]"]').append(year_option);
	

		$('form[name="form1"] select[name="REGISTER[PERSONAL_MONTH]"]').on('change', {month: month, days: days} ,function(event) {
			if ($(this).val() == "no") {
				return false;	
			}

			// произведем замену даты в скрытом поле
			replaceDateBirthday(event);

			var month_id = $(this).children('option:selected').data('monthid');


			// запомним выбранный пользователем ранее день
			if ($(this).siblings('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').val() != "no") {
				var day = $(this).siblings('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').val();
			}

			// очистим select 
			$(this).siblings('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').empty();

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

			$(this).siblings('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);


		});

		$('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').on('change', function(event) {
			replaceDateBirthday(event);
		});

		$('form[name="form1"] select[name="REGISTER[PERSONAL_YEAR]"]').on('change', function(event) {
			replaceDateBirthday(event);
		});

	}

/* функция заменяет дату рождения в скрытом поле */
function replaceDateBirthday(event) {
	var day = $('form[name="form1"] select[name="REGISTER[PERSONAL_DAY]"]').val();
	var month = $('form[name="form1"] select[name="REGISTER[PERSONAL_MONTH]"]').val();
	var year = $('form[name="form1"] select[name="REGISTER[PERSONAL_YEAR]"]').val();

	var birthday_string = day + "." + month + "." + year;
	$('form[name="form1"] input[name="PERSONAL_BIRTHDAY"]').val(birthday_string);
}




function removeElement(arr, sElement)
{
	var tmp = new Array();
	for (var i = 0; i<arr.length; i++) if (arr[i] != sElement) tmp[tmp.length] = arr[i];
	arr=null;
	arr=new Array();
	for (var i = 0; i<tmp.length; i++) arr[i] = tmp[i];
	tmp = null;
	return arr;
}

function SectionClick(id)
{
	var div = document.getElementById('user_div_'+id);
	if (div.className == "profile-block-hidden")
	{
		opened_sections[opened_sections.length]=id;
	}
	else
	{
		opened_sections = removeElement(opened_sections, id);
	}

	document.cookie = cookie_prefix + "_user_profile_open=" + opened_sections.join(",") + "; expires=Thu, 31 Dec 2020 23:59:59 GMT; path=/;";
	div.className = div.className == 'profile-block-hidden' ? 'profile-block-shown' : 'profile-block-hidden';
}




