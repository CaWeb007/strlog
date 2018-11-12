 $(document).ready(function() {
	/*jQuery("#register form").submit(function(e){
	e.preventDefault();
		jQuery.post(jQuery(this).attr('action'),jQuery(this).serialize(),function(data){
			console.log(data);
			
		});
		
	})*/

 	// заполняем select выбора даты рождения значениями
	writeDate();

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

		$('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').append(day_option);
		$('form[name="regform"] select[name="REGISTER[PERSONAL_MONTH]"]').append(month_option);
		$('form[name="regform"] select[name="REGISTER[PERSONAL_YEAR]"]').append(year_option);

		$('form[name="regform"] select[name="REGISTER[PERSONAL_MONTH]"]').on('change', {month: month, days: days} ,function(event) {
			if ($(this).val() == "no") {
				return false;	
			}
			var month_id = $(this).children('option:selected').data('monthid');


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


});