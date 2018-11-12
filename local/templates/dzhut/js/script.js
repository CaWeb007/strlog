var customSelect = (function() {
	function applySelect() {
		Select.init({selector: '.reg-row select'});
		Select.init({selector: '.birthday select'});
		Select.init({selector: '.birthday-profile select'});
	}

	return {
		init: applySelect
	}
})();

customSelect.init();

$(document).ready(function($){
//Нашли дешевле*start
	$('.show-find-cheap-popup').click(function(){
		$('.find-cheap-onload, .popup-find-cheap-wrapper').fadeIn(200);
	});
	$('.popup-find-cheap-close, .find-cheap-onload, .notRegOrAuth-popup-find-cheap-close, .notRegOrAuth-cancel').click(function(){
		$('.find-cheap-onload, .popup-find-cheap-wrapper, .notRegOrAuth-popup-find-cheap-wrapper').fadeOut(200);
	});
	$('.go-authorize-find-cheap-popup').click(function(){
		$('.find-cheap-onload, .notRegOrAuth-popup-find-cheap-wrapper').fadeIn(200);
	});
	$('.notRegOrAuth-reg').click(function(){
		$('.to-top').click();
		$('#registr').click();
		$('.find-cheap-onload').click();
	});
	$('.notRegOrAuth-auth').click(function(){
		$('.to-top').click();
		$('#auth').click();
		$('.find-cheap-onload').click();
	});
	$('#form-find-cheap').submit(function(){
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: '/cheapgood.php/',
			data: data,
			success: function(success){
				$('.popup-find-cheap-thanks').css('opacity', '1');
				function hide(){
					$('.popup-find-cheap-thanks').css('opacity', '0');
					$('.find-cheap-onload, .popup-find-cheap-wrapper').fadeOut(200);
				}setTimeout(hide, 2000);
			}
		});
	});
//Нашди дешевле*end

//Я согласен на обработку персональных данных*start
	function agreePersonalData(agreeButton, sendButton){
		if(agreeButton.is(':checked')){
			sendButton.css('pointer-events', 'auto');
			sendButton.css('user-select', 'auto');
			sendButton.css('cursor', 'pointer');
		}else{
			sendButton.css('pointer-events', 'none');
			sendButton.css('user-select', 'none');
			sendButton.css('cursor', 'default');
		}
	}

//Обратный звонок
	$('#agreePersonalCheckbox').click(function(){
		agreePersonalData($(this), $('#agreePersonalSend'));
	});

//Корзина
	$('#basketAgreePersonalCheckbox').click(function(){
		agreePersonalData($(this), $('#ORDER_CONFIRM_BUTTON'));
	});

//Нашли дешевле
	$('#findCheapAgreePersonalCheckbox').click(function(){
		agreePersonalData($(this), $('.find-cheap-send'));
	});
//Я согласен на обработку персональных данных*end

//Rules*start
	$('.rules-dropdown-button').click(function(){
		$('.rules-dropdown').slideToggle(200);
	});
//Rules*end

	$('.bx-filter-popup-result').mouseout(function(){
		$('.bx-filter-popup-result-arrow').css('transition', '0.2s')
	});

	$('.bt-hide').on('click', function() {
		$(this)
			.parents('.bx-filter-parameters-box')
			.find('.bx-filter-parameters-box-title')
			.trigger('click');
	});

	catalogDropdownModef();

	// Slider

	$('.slide-item:nth-of-type(n+2)').css({
		display: 'block'
	});

	$('.slide-list').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
		fade: true,
		autoplay: true,
		autoplaySpeed: 5000,
		pauseOnHover: false,
		speed: 500
	});

	// Set thumbnails

	var widthCounter = 0;

	$('.slide-list .slide-item').each(function(){
		var text = $(this).find('.slide-pagin b').text(),
		index = $(this).attr('data-slick-index'),
		thumb = $('.slick-dots li')[index];

		$(thumb).html(text);

		widthCounter++;
	});

	$('.slick-dots li').css({
		width: ( 100 / widthCounter ) + '%'
	});



	// Set placeholder for search input

	$('.search-form input[type="text"]').attr('placeholder', 'Поиск среди товаров');

	// Set dropdown width and cols


	function slice(a, n) {
	    var len = a.length,out = [], i = 0;
	    while (i < len) {
	        var size = Math.ceil((len - i) / n--);
	        out.push(a.slice(i, i += size));
	    }
	    return out;
	}


	$('.bx_vertical_menu_advanced > ul > li.dropdown').on('mouseenter', function() {

		// Cols

		var list = $(this).find($('li.parent'));
		var slicedList = slice(list,3);

		$(this).find($('.bx_children_container')).html("<div class='columns'></div>");

		for (var i = 0; i < slicedList.length; i++) {
			$(this).find('.columns').append('<div class="column"></div>');
			for (var j = 0; j < slicedList[i].length; j++) {
				$(this).find('.columns .column').eq(i).append(slicedList[i][j]);
			}
		}

		// Width and height

		var contentWidth = $('.right-area').outerWidth();
		var menuHeight = $('.bx_vertical_menu_advanced ul').outerHeight();

		$('.bx_vertical_menu_advanced > ul > li.dropdown .bx_children_container').css({
			width: (contentWidth + 1600) / 3,
			minHeight: menuHeight
		});

	});


	var $callbackForm = $('.call-bacl-form'),
		$authForm = $('.auth-form-wrap'),
		$regForm = $('.bx-auth-reg');





	// Callback form

	$('#call-back').on('click', function(){

		if ( $authForm.hasClass('open') ) {
			$authForm.velocity({
				maxHeight: 0,
				opacity: 0
			}, 500, 'swing').removeClass('open');
		} else if ( $regForm.hasClass('open') ) {
			$regForm.velocity({
				maxHeight: 0,
				opacity: 0
			}, 500, 'swing').removeClass('open');
		}

		if ( !$callbackForm.hasClass('open') ) {
			$callbackForm.velocity({
				maxHeight: 180,
				opacity: 1
			}, 500, 'swing').addClass('open');
		} else {
			$callbackForm.velocity({
				maxHeight: 0,
				opacity: 0
			}, 500, 'swing').removeClass('open');
		}
	});
	// Callback Send

	$('form[name="SIMPLE_FORM_1"]').append('<div class="strlog-success-message">Ваша заявка принята, мы скоро свяжемся с Вами</div>');
	var $callbackMessage = $('.message');

	$("input[name='web_form_submit']").on('click',function(e){
		e.preventDefault();
		var resName = validateName('form[name="SIMPLE_FORM_1"] input[name="form_text_1"]');
		var resTel  = validateTel('form[name="SIMPLE_FORM_1"] input[name="form_text_2"]');
		var res = resName && resTel;
		if (!res) {
			return false;
		}


		var Form = $(this).parent().serialize();
		var FormUrl = $(this).parent().attr('action');
		Form = Form + "&web_form_submit=Y"; // без этой строчки системный компонент не отправит письмо
		$.ajax({
			type:'post',
			url:FormUrl,
			data:Form,
			success:function(data){
				// console.log(data);
				if(data !== ""){
					$('form[name="SIMPLE_FORM_1"] input[name="form_text_1"]').val("");
					$('form[name="SIMPLE_FORM_1"] input[name="form_text_2"]').val("");

					$callbackMessage.addClass('open');

					setTimeout(function() {
						$callbackMessage.removeClass('open');
					}, 5000);
				}

			},

		});

	});

	// Set callback placholders

	$('form[name="SIMPLE_FORM_1"] input[name="form_text_1"]').attr('placeholder', 'Имя');
	$('form[name="SIMPLE_FORM_1"] input[name="form_text_2"]').attr('placeholder', 'Телефон');

	// Login

	$(".auth-form input[name='Login']").on('click',function(e){

		e.preventDefault();
		var resLogin = validatePassLength('form[class="auth-form"] input[name="USER_LOGIN"]',1);
		var resPass = validatePassLength('form[class="auth-form"] input[name="USER_PASSWORD"]',6);

		var res = resLogin && resPass;
		if (!res) {
			return false;
		}

		var Form = $(this).parents('.auth-form').serialize();
		var FormUrl = $(this).parents('.auth-form').attr('action');
		$.ajax({
			type:'post',
			url:FormUrl,
			beforeSend:function(){
				BX.showWait();
			},
			data:Form,
			success:function(data){
				if(data !== ""){
					if($(data).find('.errortext').length > 0){
						$('form[class="auth-form"] div.errormess').remove();
						$('.auth-form').prepend("<div class='errormess'>" + $(data).find('.errortext').text() + "</div>");
					}else{
						window.location.reload();
					}
				}

			},
			complete:function(){
				BX.closeWait();
			}
		});

	});
// Register
		// hidden login val
		$('input[name="REGISTER[EMAIL]"]').keyup(function(){
			$('input[name="REGISTER[LOGIN]"]').attr({'value':$(this).val()});
		})

		$('input[name="REGISTER[PERSONAL_PHONE]"]').inputmask({"mask": "+7(999) 999-99-99"});

		 $("input[name='register_submit_button']").on('click',function(e){

			e.preventDefault();

			// var Form = $(this).parents('.reg-gruop-row').parent().serialize();

			var resName = validateName('form[name="regform"] input[name="REGISTER[NAME]"]');
			var resNameSecond = validateThirdNameOrder('form[name="regform"] input[name="REGISTER[SECOND_NAME]"]');
			var resTel  = validateTel('form[name="regform"] input[name="REGISTER[PERSONAL_PHONE]"]');
			var resConfirmPhone = validateConfirmPhoneRegister('form[name="regform"] #CHECKPHONE');
			var resPass = validatePassLength('form[name="regform"] input[name="REGISTER[PASSWORD]"]',6);

			var resDay = validateDay('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]');
			var resMonth = validateMonth('form[name="regform"] select[name="REGISTER[PERSONAL_MONTH]"]');
			var resYear = validateYear('form[name="regform"] select[name="REGISTER[PERSONAL_YEAR]"]');

			if (resDay && resMonth && resYear) {
				var day = $('form[name="regform"] select[name="REGISTER[PERSONAL_DAY]"]').val();
				var month = $('form[name="regform"] select[name="REGISTER[PERSONAL_MONTH]"]').val();
				var year = $('form[name="regform"] select[name="REGISTER[PERSONAL_YEAR]"]').val();

				var birthday_string = day + "." + month + "." + year;

				$('form[name="regform"] input[name="REGISTER[PERSONAL_BIRTHDAY]"]').val(birthday_string);
			}

			var resBeth = validateBethLength('form[name="regform"] input[name="REGISTER[PERSONAL_BIRTHDAY]"]',9,10);
			var resPassConf = validatePassConfLength('form[name="regform"] input[name="REGISTER[CONFIRM_PASSWORD]"]',6);
			var resLastName = validateLastName('form[name="regform"] input[name="REGISTER[LAST_NAME]"]');
			var resEmail = validateEmail('form[name="regform"] input[name="REGISTER[EMAIL]"]');

			var res = (resName && resPass &&  resPassConf && resEmail && resTel && resLastName && resBeth && resDay && resMonth && resYear && resConfirmPhone) ;
				if (!res) {

					return false;
				}
			var Form = $(this).parents('.reg-gruop-row').parent().serialize();

			$.ajax({
				type:'post',
				url:'/local/templates/strlog/components/uni/main.register/register2/ajax.php',
				dataType:'json',
				data:Form,
				success:function(data){
					console.log(data);
					if(data.status == "success"){
						$('.reg-success').remove();
						$("form[name='regform']").before("<p class='reg-success'>"+data.message+"</p>");

						setTimeout(function(){
							location.href = "/personal/profile/";
						},2000)

					}
					if(data.status == 'error'){
						$('.reg-success').remove();
						$("form[name='regform']").before("<p class='reg-success'>"+data.message+"</p>");
						var HeightError = $(".reg-success").outerHeight();
						var HeightBlock = $('.bx-auth-reg.open').outerHeight();

						$('.bx-auth-reg.open').css({'max-height':HeightError+HeightBlock+10});
						// setTimeout(function(){
							// $('.reg-success').remove();
						// },1500)

					}

				},

			})
		 })

	// Reviews

	$('#review-form').css('display', 'none');


	// feedback
	// Set callback placholders
	$('form[name="SIMPLE_FORM_2"] input[name="form_text_3"]').attr('placeholder', 'Имя');
	$('form[name="SIMPLE_FORM_2"] input[name="form_email_4"]').attr('placeholder', 'Email');
	$('form[name="SIMPLE_FORM_2"] textarea[name="form_textarea_5"]').attr('placeholder', 'Сообщение');

		$("form[name='SIMPLE_FORM_2'] input[name='web_form_apply']").on('click',function(e){
			e.preventDefault();
			var resName = validateName('form[name="SIMPLE_FORM_2"] input[name="form_text_3"]');
			var resEmail = validateEmail('form[name="SIMPLE_FORM_2"] input[name="form_email_4"]');
			var resText = validateText('form[name="SIMPLE_FORM_2"] textarea[name="form_textarea_5"]',5);
			console.log($('form[name="SIMPLE_FORM_2"] input[name="form_email_4"]').val());
			var res = resName && resEmail && resText;
			if(!res){
				return false;
			}

			var Form = $(this).parents('form').serialize();
			var FormUrl = $(this).parents('form').attr('action');
			$.ajax({
				type:'post',
				url:FormUrl,
				data:Form,
				success:function(data){
					if(data !== ""){
						if($(data).find('.errortext').length > 0){
							$('form[name="SIMPLE_FORM_2"] div.errormess').remove();
							$('form[name="SIMPLE_FORM_2"]').prepend("<div class='errormess'>" + $(data).find('.errortext').text() + "</div>");
						}else{
								$('form[name="SIMPLE_FORM_2"] input[name="form_text_3"]').val("");
								$('form[name="SIMPLE_FORM_2"] input[name="form_email_4"]').val("");
								$('form[name="SIMPLE_FORM_2"] textarea[name="form_textarea_5"]').val("");
								$('form[name="SIMPLE_FORM_2"]').prepend("<div class='success'>Благодарим Вас за сообщение!</div>");
							setTimeout(function(){
								$('form[name="SIMPLE_FORM_2"]').find('.success').remove();
							},3000)
						}
					}

				},

			});

		})


	// To top button

	$('.to-top').on('click', function() {
		$(document.body).velocity('scroll', { duration: 700 });
	});

	// FAQ



/*
	$('.faq-item').on('click', function(){
		var $faqItem = $(this);
		var faqTitleHeight = $(this).find('.faq-title').outerHeight();
		var faqIetemHeight = $faqItem.find('.faq-text').outerHeight() + faqTitleHeight + 65;

		if (!$faqItem.hasClass('open')) {
			$faqItem.addClass('open').css({
				maxHeight: faqIetemHeight
			});

		} else {
			$faqItem.removeClass('open').css({
				maxHeight: faqTitleHeight
			});
		}
	});
*/

	$('.faq-item').click(function(){
		var $faqItem = $(this);
		var faqTitleHeight = $(this).find('.faq-title').outerHeight();
		var faqIetemHeight = $faqItem.find('.faq-text').outerHeight() + faqTitleHeight + 65;
		$('.faq-item').removeClass('open');
		$('.faq-item').css('height', faqTitleHeight);
		$('.faq-item').css('max-height', faqTitleHeight);
		$('.faq-item').css('transition', '0.5s');
		$(this).addClass('open');
		$(this).css('height', faqIetemHeight);
		$(this).css('max-height', faqIetemHeight);
	});




	// Catalog

		// Columns

		var $catalogList = $('.catalog-section-list > ul > li');
		var $catalogSliced = slice($catalogList, 2);

		$('.catalog-section-list').html("<div class='columns'></div>");

		for (var i = 0; i < $catalogSliced.length; i++) {
			$('.catalog-section-list .columns').append('<ul class="column"></ul>');
			for (var j = 0; j < $catalogSliced[i].length; j++) {
				$('.catalog-section-list .columns .column').eq(i).append($catalogSliced[i][j]);
			}
		}

		// Dropdown

		var $catalogDropdowns = $('.catalog-section-list .columns .column > li > ul > li').has('ul');

		$catalogDropdowns.addClass('dropdown');

		$catalogDropdowns.find('ul').css({
			'max-height': 0,
			'overflow': 'hidden'
		});

		$catalogDropdowns.children('a').on('click', function(e){
			e.preventDefault();

			var dropdownHeight = $(this).siblings('ul').find('li').length * 25;

			console.log(dropdownHeight);

			if (!$(this).parent().hasClass('open')) {
				$(this).parent().addClass('open').find('ul').css({
					'max-height': dropdownHeight
				});
			} else {
				$(this).parent().removeClass('open').find('ul').css({
					'max-height': 0
				});
			}
		});

		// Smart filter

		// var $maxPrice = $('.max-price'),
			// $minPrice = $('.min-price');

		// var $text = $maxPrice.add($minPrice).parent().parent().find('i');

		// $minPrice.attr('placeholder', $text.eq(0).text());
		// $maxPrice.attr('placeholder', $text.eq(1).text());

		// $text.text('');


	// Personal




	$('.order .open-order').on('click', function(e) {
		e.preventDefault();

		if( $(this).hasClass('active') ) {
			$(this).removeClass('active');
			$(this).parents('.order').next().addClass('close');

		} else {
			$(this).addClass('active');
			$(this).parents('.order').next().removeClass('close');

		}
	});

	// Item slider

	$('.bx_item_slider ul li').eq(0).addClass('bx_active');

	// $('.bx_item_slider ul li').hover(function(){
	// 	$(this).click();
	// });



	// Reviews validation

	$('.bx_ordercart_order_sum .checkout').attr('onclick', '');



	function validateLength(element) {
		if ( element.val() == '' ) {
			element.addClass('error');
			return false;
		} else {
			element.removeClass('error');
			return true;
		}
	}





	// Award fancybox

	$('.fancybox').fancybox();

	$('#sale_order_props .bx_block.r3x1 textarea').on('focus', function(){
		$(this).removeClass('error');
	});

   // Profile
   $('.left_content ul li.dropdown a.parent').on('click',function(e){
		e.preventDefault();

		$(this).parent().toggleClass('active')
	})
	// Init Oreder JS
	OrderJS();

	// Reviews
	$("#newReview").on('click',function(e){
		e.preventDefault();
			var success = false;
			// если данные поля существуют на странице (если пользователь не авторизован)
			if ($('input[name="AUTHOR_EMAIL"]').length && $('input[name="AUTHOR_NAME"]').length && $("#bxed_MESSAGE").length) {

				var review_email = validateEmail($('input[name="AUTHOR_EMAIL"]'));
				var review_name = validateName($('input[name="AUTHOR_NAME"]'));
				var review_comm = validateText($("#bxed_MESSAGE"), 1);
				if (!review_comm) {
					$("#bx-html-editor-MESSAGE").addClass('error');
				}
				success = review_email && review_name && review_comm; 
			} else {
				var review_comm = validateText($("#bxed_MESSAGE"), 1);
				if (!review_comm) {
					$("#bx-html-editor-MESSAGE").addClass('error');
				}
				success = review_comm;
			} 

			if (success) {

				$.ajax({
					type:'post',
					url:$("#review_add").attr('action'),
					beforeSend:function(){
						var Waiter = BX.showWait('wiatComment')
					},
					data:$("#review_add").serialize(),
					success:function(data){
						if(data){
							$("#review_add_form").fadeOut();
							$("#review_add .alx_reviews_form_vote_items").find('div').removeClass('alx_reviews_form_vote_item_sel');
							$("#review_add_form").find("input").not("#newReview").val("");

							$("#bx-html-editor-MESSAGE").find('iframe').contents().find('body').empty()
							newReview = $(data).find('.alx_reviews_item').first();
							$("#review_show_form").fadeIn('fast');
							newReviewTagA = newReview.prev();

							$(newReview,newReviewTagA).css('opacity',0);
							$('.alx_reviews_list').prepend(newReview);
							$('.alx_reviews_list').prepend(newReviewTagA);
							$('.alx_reviews_list_success').fadeIn();
							setTimeout(function(){
								$('.alx_reviews_list_success').fadeOut();

							},2000)

							$(newReview,newReviewTagA).animate({'opacity':1},'slow','swing');
							$("#bx-html-editor-MESSAGE").removeClass('error');
							
						}
					},
					complete:function(){
						BX.closeWait('wiatComment');
					}
				})
			}
	})

 // top catalog
 $('.bx_catalog_item_controls_blocktwo a').on('click',function(e){
	 if(!$(this).hasClass('success-btn')){

	 	var SuccessBtn = $(this);
	 	var href = $(this).attr('href');

	 	// если товар доступен только под заказ
	 	if ($(this).parent().siblings(".notice_delivery_time").length && $(this).parent().siblings(".notice_delivery_time").css("display") == "none") {

		 	e.preventDefault();
	 		$(this).parent().siblings(".notice_delivery_time").css("display", "block");
	 		var btn = $(this).parent().siblings(".notice_delivery_time").children("button");
	 		
	 		$(btn).on("click", {SuccessBtn: SuccessBtn, href: href}, function(event) {

	 			$(this).parent(".notice_delivery_time").css("display", "none").addClass("ok");

	 			$.ajax({
					 type:'post',
					 url:event.data.href,
					 beforeSend:function(){
						 BX.showWait();
					 },
					 success:function(data){
						 if(data){
							 event.data.SuccessBtn.addClass('success-btn');
							 event.data.SuccessBtn.text('В корзине');
							 event.data.SuccessBtn.attr({'href':'/personal/cart/'});
							 BX.onCustomEvent('OnBasketChange');
						 }
					 },
					 complete:function(){
						 BX.closeWait();
					 }
				 })

	 		});

	 		return;
	 	}

	 	// если открыта всплывашка и вы нажимаем кнопку Добавить в корзину
	 	if ($(this).parent().siblings(".notice_delivery_time").css("display") == "block") {
		 	 e.preventDefault();
			return;
		}

		 	 e.preventDefault();
			 $.ajax({
				 type:'post',
				 url:$(this).attr('href'),
				 beforeSend:function(){
					 BX.showWait();
				 },
				 success:function(data){
					 if(data){
						 SuccessBtn.addClass('success-btn');
						 SuccessBtn.text('В корзине');
						 SuccessBtn.attr({'href':'/personal/cart/'});
						 BX.onCustomEvent('OnBasketChange');
//Кнопка купить
//$(SuccessBtn).parent().parent().fadeOut(1000);
					 }
				 },
				 complete:function(){
					 BX.closeWait();
				 }
			 })
	 }

 })


	 $('.prod-no-item-button-wrapper').click(function(){
		$(this).children('.prod-no-item-popup').css('display', 'flex');
		$('.section-onload').fadeIn(200);
	});

	$('.accept-order').click(function(){
		function sectionOnloadClick(){
			$('.section-onload').click();
		}setTimeout(sectionOnloadClick, 200);
		$(this).parent('.prod-no-item-popup').fadeOut(200);
		$(this).parent().parent().addClass('success-btn');
		$(this).parent().parent().text('В корзине');
		$('.section-onload').fadeOut(200);
	});

	$('.prod-no-item-popup-close').click(function(){
		function sectionOnloadClick(){
			$('.section-onload').click();
		}setTimeout(sectionOnloadClick, 200);
		$(this).parent().fadeOut(200);
	});

	$('.section-onload').click(function(){
		$(this).fadeOut(200);
		$('.prod-no-item-popup').fadeOut(200);
	});

});//end docready
// Add to cart in section list
(function($){
	jQuery(document).ready(function(){

		$(".wrap-buy a").removeAttr('onclick');
		$(".wrap-buy a").not('.success-btn').on('click',function(e){
			if(!$(this).hasClass('success-btn')){
					var url = $(this).attr('href');
					var SuccessBtn = $(this);

					// если товар доступен только под заказ
				 	if ($(this).siblings(".notice_delivery_time").length && $(this).siblings(".notice_delivery_time").css("display") == "none") {
					 	e.preventDefault();
				 		$(this).siblings(".notice_delivery_time").css("display", "block");
				 		var btn = $(this).siblings(".notice_delivery_time").children("button");
				 		
				 		$(btn).on("click", {SuccessBtn: SuccessBtn, url: url}, function(event) {

				 			$(this).parent(".notice_delivery_time").css("display", "none").addClass("ok");

				 			$.ajax({
								 type:'post',
								 url:event.data.url,
								 beforeSend:function(){
									 BX.showWait();
								 },
								 success:function(data){
									 if(data){
										 BX.onCustomEvent('OnBasketChange');
										event.data.SuccessBtn.addClass('success-btn');
										event.data.SuccessBtn.text('В корзине');
										event.data.SuccessBtn.attr({'href':'/personal/cart/'});
										BX.closeWait();
									 }
								 },
								 complete:function(){
									 BX.closeWait();
								 }
							 })

				 		});

				 		return;
				 	}

				 	// если открыта всплывашка и вы нажимаем кнопку Добавить в корзину
				 	if ($(this).siblings(".notice_delivery_time").css("display") == "block") {
					 	 e.preventDefault();
						return;
					}

					e.preventDefault();
					$.ajax({
						type:'post',
						beforeSend:function(){
							BX.showWait();
						},
						url:url,
						success:function(data){
							BX.onCustomEvent('OnBasketChange');
							SuccessBtn.addClass('success-btn');
							SuccessBtn.text('В корзине');
							SuccessBtn.attr({'href':'/personal/cart/'});
							BX.closeWait();
						},

					})
			}

		})

	})
})(jQuery);

	function OrderJS(){
			$('.bx_ordercart_order_sum .checkout').on('click', function() {

		var $orderNameOrder = validateNameOrder($('#ORDER_PROP_1')),
			$orderLastNameOrder = validateLastNameOrder($('#ORDER_PROP_10')),
			$orderLastNameOrder = validateThirdNameOrder($('#ORDER_PROP_11')),
			$orderEmail = validateEmail($('#ORDER_PROP_2')),
			$orderPhone = validateTel($('#ORDER_PROP_3'));

		var resBirth = true;
		// если отмчена опция Участвовать в бонусной программе
		if ($("#bonus-check").prop("checked")) {
			var resDay = true;
			var resMonth = true;
			var resYear = true;

			if ($('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').val() == "no") {
				resDay = false;
			}

			if ($('#ORDER_FORM select[name="REGISTER[PERSONAL_MONTH]"]').val() == "no") {
				resMonth = false;
			}

			if ($('#ORDER_FORM select[name="REGISTER[PERSONAL_YEAR]"]').val() == "no") {
				resYear = false;
			}

			// если день, месяц, год заполнены полностью, либо вообще не заполнено ни одно поле, то отправляем форму
			if ((resDay && resMonth && resYear) || (!resDay && !resMonth && !resYear)) {

				// убирем класс с ошибкой
				$('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"] + a').removeClass("error");
				$('#ORDER_FORM select[name="REGISTER[PERSONAL_MONTH]"] + a').removeClass("error");
				$('#ORDER_FORM select[name="REGISTER[PERSONAL_YEAR]"] + a').removeClass("error");

				if (resDay && resMonth && resYear) {
					var day = $('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]').val();
					var month = $('#ORDER_FORM select[name="REGISTER[PERSONAL_MONTH]"]').val();
					var year = $('#ORDER_FORM select[name="REGISTER[PERSONAL_YEAR]"]').val();

					var birthday_string = day + "." + month + "." + year;

					$('#ORDER_FORM input[name="REGISTER[PERSONAL_BIRTHDAY]"]').val(birthday_string);
				}

				if (!resDay && !resMonth && !resYear) {
					$('#ORDER_FORM input[name="REGISTER[PERSONAL_BIRTHDAY]"]').val("");
				}

				resBirth = true;

			}
			else {

				validateDay('#ORDER_FORM select[name="REGISTER[PERSONAL_DAY]"]');
				validateMonth('#ORDER_FORM select[name="REGISTER[PERSONAL_MONTH]"]');
				validateYear('#ORDER_FORM select[name="REGISTER[PERSONAL_YEAR]"]');

				resBirth = false;
			}
		}

		$('#personal_gender').val($(".no_partner_select").val());

		var $confirmPhone = validateConfirmPhone("#ORDER_PROP_9");
		$orderEmail = validateEmailOrderExist("#ORDER_PROP_2");
		console.log($orderNameOrder , $orderEmail , $orderPhone , $confirmPhone , resBirth);
		if ($orderNameOrder && $orderEmail && $orderPhone && $confirmPhone && resBirth) {
			submitForm('Y');
			return false;
		}else{
			$('html,body').animate({scrollTop:0})
		}


	});

	function validateEmailOrderExist(email) {
		var userEmail = $(email).val();
		result = false;
		$.ajax({
			url:'/local/templates/strlog/components/strlog/sale.order.ajax/sale.order.ajax/ajax.php',
			type:'post',
			async:false,
			dataType:'json',
			beforeSend:function(){
				BX.showWait();
			},
			data:{check_email:true, userEmail:userEmail},
			success:function(data){
				console.log(data);
				if (data.success == "Y") {
					result = true;
				}

				if (data.success == "N") {
					$("#email-tip").addClass("active");
					result = false;
				}

			},
			complete:function(){
				BX.closeWait();
			}
		});

		return result;
	}


  // Check Phone
	$("#check-phone").on('click',function(e){
		e.preventDefault();
		var $orderEmail = validateEmail($('#ORDER_PROP_2')),
			$orderPhone = validateTel($('#ORDER_PROP_3'));
		// убирем сообщение об ошибке

		$("#confirm").css('display','none');
		if ($orderEmail && $orderPhone) {
				userPhone = $('#ORDER_PROP_3').val();
				userEmail = $('#ORDER_PROP_2').val();

				$.ajax({
					url:'/local/templates/strlog/components/strlog/sale.order.ajax/sale.order.ajax/ajax.php',
					type:'post',
					dataType:'json',
					beforeSend:function(){
						BX.showWait();
					},
					data:{userEmail:userEmail,userPhone:userPhone},
					success:function(data){

						 console.log(";;",data);

						if (data.send_sms == "N") {

							$('#ORDER_PROP_3')
							.attr('placeholder', 'Некорректный телефон').val('')
							.focus(function() {
								jQuery(this)
									.attr('placeholder', 'Телефон')
									.off('focus')
									.removeClass('error');
							}).addClass( 'error' );

						} else if(data.email != undefined && data.phone != undefined){
							$('#ORDER_PROP_2').addClass('error');
							$("#email-tip").addClass('active');
							$("#order_phone").addClass('active');
							$('#ORDER_PROP_3').addClass('error');
						} else if (data.email != undefined && data.subemail == "Y") {
							$('#ORDER_PROP_2').addClass('error');
							$("#email-tip").addClass('active');
						}else if(data.email != undefined){
							$('#ORDER_PROP_2').addClass('error');
							$("#email-tip").addClass('active');
						} else if(data.phone != undefined){
							$('#order_phone').addClass('active');
							$('#ORDER_PROP_3').addClass('error');
						} else if(data.confirm == 'Y'){

								$('.confirm-input, .confirm-phone, .re-confirm').addClass('active');
								$('.desc-confirm').addClass('active');
						}

					},
					complete:function(){
						BX.closeWait();
					}
				})
		}


	})

	// toltip close
	$('.topltip-error .close').on('click',function(){
		$(this).parent().removeClass('active');
	})
	// toltip login
	$('.topltip-error .login').on('click',function(e){
		e.preventDefault();
		$('html,body').animate({scrollTop:0});
		$('#auth').trigger('click')
	})
	// Confirm Phone input
		/*if(location.pathname == '/personal/order/make/'){
			if(localStorage.getItem("confPhone")){

				$("#ORDER_PROP_9").attr({'checked':'checked'});
				$(".confirm-input, .confirm-phone-success").addClass('active');
				$('.re-confirm').removeClass('active');
				$("#CONFIRM_PHOE").attr({'disabled':'disabled'})
			}
		}else{
			localStorage.removeItem("confPhone");
		}*/
	// Confirm Phone
	$("#confirm-phone").on('click',function(e){
		e.preventDefault();
		var CodeOk = validateCode($("#CONFIRM_PHOE"),1,4);
		if(CodeOk){
			Code = $("#CONFIRM_PHOE").val();
			if(Code != ''){

					userPhone = $('#ORDER_PROP_3').val();
					$.ajax({
						url:'/local/templates/strlog/components/strlog/sale.order.ajax/sale.order.ajax/ajax.php',
						type:'post',
						dataType:'json',
						beforeSend:function(){
							BX.showWait();
						},
						data:{Code:Code, userPhone:userPhone},
						success:function(data){

							if(data.code_confirm == 'Y'){
								$('.desc-confirm, .re-desc-confirm, .re-confirm').removeClass('active');
								$('.confirm-phone-success').addClass('active');
								$("#CONFIRM_PHOE").val("");
								$("#CONFIRM_PHOE").attr({'disabled':'disabled'})
								$('form[name="ORDER_FORM"] #CONFIRM_PHONE_SUCCESS').val("Y");

								// success ConfirmPhone
								$('form[name="ORDER_FORM"] #ORDER_PROP_9').prop("checked", true);

								setCookie("CONFIRM_PHONE", "Y");
								setCookie("CONFIRM_PHONE_VALUE", $("#ORDER_PROP_3").val());
								// localStorage.setItem("confPhone", "Y");
							}else if(data.user == "Y") {
								$("#order_phone.topltip-error").addClass('active');
							} else {
								// console.log("crash!");
								$("#CONFIRM_PHOE").addClass('error');
							}
						},
						complete:function(){
							BX.closeWait();
						}
					})
			}
		}

	})



	// Focus
	$("#CONFIRM_PHOE").on('focus',function(){
		$(this).removeClass('error');
	})
	// Reconfirm code
	$("#re-confirm").on('click',function(e){
		e.preventDefault();
		var Reconfirm = 1;
		userPhone = $('#ORDER_PROP_3').val();
					$.ajax({
						url:'/local/templates/strlog/components/strlog/sale.order.ajax/sale.order.ajax/ajax.php',
						type:'post',
						dataType:'json',
						beforeSend:function(){
							BX.showWait();
						},
						data:{Reconfirm:Reconfirm,userPhone:userPhone},
						success:function(data){

							// console.log(data);
							if(data.user == "Y") {
								$("#order_phone.topltip-error").addClass('active');
							};

						},

				complete:function(){
					BX.closeWait();
			}
		})
	})
	// Hover Re confirm
	$("#re-confirm").mouseover(function(){
	$('.re-desc-confirm').addClass('active');
	})
	$("#re-confirm").mouseout(function(){
	$('.re-desc-confirm').removeClass('active');
	})
	// work round success
	$("#confirm-phone-success").on('click',function(e){
		e.preventDefault();
	})
	// Phone Mask Order
	$('#ORDER_PROP_3').inputmask({"mask": "+7(999) 999-99-99"});
	// Phone profile mask
	$('input[name="PERSONAL_PHONE"]').inputmask({"mask": "+7(999) 999-99-99"});
	// Wokr round
	$(".blog-add-comment a b").text("Добавить отзыв");
	// BONUS PROGRAMM
		$(".bonus-check-button").click(function(){
			if($('#ORDER_PROP_8').val() == ""){
				$('#ORDER_PROP_8').val("Y");
			}else{
				$('#ORDER_PROP_8').val("N");
			}
		})
	}
	function validateEmail( _input ) {
		var reg_email = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

		var result = reg_email.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите ваш E-mail')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'E-mail')
						.off('focus')
							.removeClass('error');
				})
					.addClass( 'error' );

		return result;
	}
	function validateName( _input ) {
		var reg_name = /^[_a-zA-Z0-9а-яА-ЯёЁ ]+$/i;

		var result = reg_name.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите ваше ФИО')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'ФИО')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}

	function validateLastName( _input ) {
		var reg_name = /^[_a-zA-Z0-9а-яА-ЯёЁ ]+$/i;

		var result = reg_name.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите вашу фамилию')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Фамилия')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}

	function validateConfirmPhoneRegister ( _input ) {
		if ($(_input).val() == "N") {
			$("form[name='regform'] #error_confirm").css("display", "block");
			setTimeout(function() {
				$("form[name='regform'] #error_confirm").css("display", "none");
			}, 3000);
			return false;
		}
		$("form[name='regform'] #error_confirm").css("display", "none");
		return true;
	}

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

	function validatePassLength( _input, _minLength, _maxLength ) {
		var minLength = _minLength || -1;
		var maxLength = _maxLength || 100;

		var result = ( ( jQuery( _input ).val().length >= minLength ) && ( jQuery( _input ).val().length <= maxLength ) );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите пароль')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Пароль')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}
		function validateNameOrder( _input ) {
		var reg_name = /^[_a-zA-Z0-9а-яА-ЯёЁ ]+$/i;

		var result = reg_name.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите ваше Имя')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Имя')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}
		// End OrederHandler
	function validateConfirmPhone(_input){
		result = jQuery(_input).prop('checked');
		if(result === false){
			$("#confirm").css('display','block');
		}
		return result;
	}
	function validateLastNameOrder( _input ) {
		var reg_name = /^[_a-zA-Z0-9а-яА-ЯёЁ ]+$/i;

		var result = reg_name.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите вашу Фамилию')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Фамилия')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}
	function validatePassConfLength( _input, _minLength, _maxLength ) {
		var minLength = _minLength || -1;
		var maxLength = _maxLength || 100;

		var result = ( ( jQuery( _input ).val().length >= minLength ) && ( jQuery( _input ).val().length <= maxLength ) );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Повторите пароль')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Пароль')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}
	function validateBethLength( _input, _minLength, _maxLength ) {
		var minLength = _minLength || -1;
		var maxLength = _maxLength || 100;

		var result = ( ( jQuery( _input ).val().length >= minLength ) && ( jQuery( _input ).val().length <= maxLength ) );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите вашу дату рождения')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Дата рождения')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}
	function validateText( _input, _minLength, _maxLength ) {
		var minLength = _minLength || -1;
		var maxLength = _maxLength || 1000;

		var result = ( ( jQuery( _input ).val().length >= minLength ) && ( jQuery( _input ).val().length <= maxLength ) );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите ваше сообщение')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Сообщение')
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

	function validateThirdNameOrder( _input ) {
		var reg_name = /^[_a-zA-Z0-9а-яА-ЯёЁ ]+$/i;

		var result = reg_name.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'Введите ваше Отчество')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'Отчество')
						.off('focus')
						.removeClass('error');
				}).addClass( 'error' );

		return result;
	}

	function validateDay( _select ) {
		var a = $(_select).next("a");
		if ($(_select).val() == "no") {
			$(a).focus(function() {
					jQuery(this).off('focus').removeClass('error');
				}).addClass('error');
			return false;
		} else {
			$(a).removeClass('error');
			return true;
		}
	}

	function validateMonth( _select ) {
		var a = $(_select).next("a");
		if ($(_select).val() == "no") {
			$(a).focus(function() {
					jQuery(this).off('focus').removeClass('error');
				}).addClass('error');
			return false;
		} else {
			$(a).removeClass('error');
			return true;
		}
	}

	function validateYear( _select ) {
		var a = $(_select).next("a");
		if ($(_select).val() == "no") {
			$(a).focus(function() {
					jQuery(this).off('focus').removeClass('error');
				}).addClass('error');
			return false;
		} else {
			$(a).removeClass('error');
			return true;
		}
	}




	function catalogDropdownModef() {
		if ($('.no-view').length) {
			$('.trigger-cat').addClass('dropdown');
		}
	}
