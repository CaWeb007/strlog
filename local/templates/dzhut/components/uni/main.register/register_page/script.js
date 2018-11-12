$(document).ready(function(){

	// заполняем select выбора даты рождения значениями
	writeDate1();

	
	  // Check Phone
	$("#checkRegPhone").on('click',function(e){
		e.preventDefault();
		var $orderPhone = validateTel($('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]'));
			if ($orderPhone) {
				
			userPhone = $('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]').val();
				
				$.ajax({
					url:'/local/templates/strlog/components/uni/main.register/register2/ajax.php',
					type:'post',
					dataType:'json',
					beforeSend:function(){
						BX.showWait();
					},
					data:{userPhone:userPhone},
					success:function(data){

					console.log(data)

					if (data.send_sms == "N") {

						$('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]')
						.attr('placeholder', 'Некорректный телефон').val('')
						.focus(function() {
							jQuery(this)
								.attr('placeholder', 'Телефон')
								.off('focus')
								.removeClass('error');
						}).addClass( 'error' );

					} else if(data.user == "Y"){
						$(".topltip_error_top").show();	
					}else{
							$('input[name="CODE"]').show();
								$('#sendCode').show();
								$('#re-confirm-top').show();
								$('.desc_confirm_top').show();
								$("#checkRegPhone").hide();
					}	
					
					
							
		
					},
					complete:function(){
						BX.closeWait();
					}
				})
				
				
			}
			
	})
	// Send Code
	$("#sendCode").on('click',function(e){
		e.preventDefault();
		if ($(this).hasClass("success")) {
			return;
		}
		var $orderPhone = validateCode($('form[name="regform"] input[name="CODE"]'),1,4);
		
		if(!$orderPhone){
			return false;
		}
			var Code = $('form[name="regform"] input[name="CODE"]').val();
			var userPhone = $('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]').val();
			
			$.ajax({
					url:'/local/templates/strlog/components/uni/main.register/register2/ajax.php',
					type:'post',
					dataType:'json',
					beforeSend:function(){
						BX.showWait();
					},
					data:{Code:Code, userPhone:userPhone},
					
					success:function(data){
						if(data.code_confirm == "Y"){
							$("#sendCode").addClass('success');
							$('form[name="regform"] input[name="CODE"]').val("");
							$('form[name="regform"] input[name="CODE"]').attr({'disabled':'disabled'});
							$("#re-confirm-top").hide();
							$(".desc_confirm_top").hide();
							$("#CHECKPHONE").val("Y");	
						} else if(data.user == "Y") {
							$(".topltip_error_top").show();
							$(".desc_confirm_top").hide();
						} else{
							$(".desc_confirm_top").hide();
							$(".re_desc_confirm_top_error").show();
							$('form[name="regform"] input[name="CODE"]').addClass('error');
						}
						$('form[name="regform"] input[name="CODE"]').on('focus',function(){
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
	
	// RECODE
	$("#re-confirm-top").on('click',function(e){
		e.preventDefault();
		
			var $orderPhone = validateTel($('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]'));
				if($orderPhone){
					var Reconfirm = "Y";
					userPhone = $('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]').val();
					$.ajax({
						url:'/local/templates/strlog/components/uni/main.register/register2/ajax.php',
						type:'post',
						dataType:'json',
						beforeSend:function(){
							BX.showWait();
						},
						data:{Reconfirm:Reconfirm,userPhone:userPhone},
						success:function(data){
						if(data.user == "Y") {
							$(".topltip_error_top").show();
							$(".desc_confirm_top").hide();
						};
						
						$('form[name="regform"] input[name="CODE"]').on('focus',function(){
							$(".re_desc_confirm_top_error").hide();
							$(".desc_confirm_top").show();
							$(this).removeClass('error');
						})
						
						},
							error:function(a,b,c) {
								console.log(a,b,c);
							},

							complete:function(){
								BX.closeWait();
						}
					})
				}
					
		
		
	})
	
	$("#re-confirm-top").hover(function(e){
		$(".re-desc-confirm").addClass('active');
	},function(){
		$(".re-desc-confirm").removeClass('active');
	})
	// work hover
	$("#re-confirm-top").hover(function(){
		$(".desc_confirm_top").hide();
		$(".re_desc_confirm_top").show();
	},
	function(){
			$(".desc_confirm_top").show();
			$(".re_desc_confirm_top").hide();
		}
	)
	// work
	$(".topltip_error_top .close").on('click',function(){
		$(this).parent().hide()
	})
	//work 
	$(".topltip_error_top .orange.login").on('click',function(e){
		e.preventDefault();
		$("#auth").trigger('click');
	})
	
})
function validateTel( _input ) {
		var reg_tel = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{5,10}$/i;

		var result = reg_tel.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите ваш телефон')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Телефон')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
}

function validateCode( _input, _minLength, _maxLength ) {
		var minLength = _minLength || -1;
		var maxLength = _maxLength || 100;

		var result = ( ( jQuery( _input ).val().length >= minLength ) && ( jQuery( _input ).val().length <= maxLength ) );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите код')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Код')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}

/* Сделаем заполнение select для ввода даты рождения */
	function writeDate1() {

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

		$('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);
		$('form[name="regform"] select[name="REGISTER[PERSONAL_MONTH]"]').append(month_option);
		$('form[name="regform"] select[name="REGISTER[PERSONAL_YEAR]"]').append(year_option);

		$('form[name="regform"] select[name="REGISTER[PERSONAL_MONTH]"]').on('change', {month: month, days: days} ,function(event) {
			if ($(this).val() == "no") {
				return false;	
			}
			var month_id = $(this).children('option:selected').data('monthid');
			console.log(month[month_id - 1].countday);


			// запомним выбранный пользователем ранее день
			if ($(this).siblings('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').val() != "no") {
				var day = $(this).siblings('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').val();
			}

			// очистим select 
			$(this).siblings('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').empty();

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

			$(this).siblings('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);


		});

	}