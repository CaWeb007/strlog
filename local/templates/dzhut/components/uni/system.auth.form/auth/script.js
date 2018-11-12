jQuery(document).ready(function($){

//Вывод бонусов из файла xml
	var data = $('#check-bonus').serialize();
	$.ajax({
		type: 'POST',
		url: '/xml/bonus.php',
		data: data,
		success: function(success){
			if(success != ''){
				$('.personal-bonus').text(success);
			}else{
				$('.personal-bonus').text('0');
			}
		}
	});
/*	
	function xmlParser(xml){
		$(xml).find('user').each(function(){
			var e = $(this);
			var user_phone = e.find('user_phone').text();
			var user_bonus = e.find('user_bonus').text();
			if(data == user_phone){
				$('.personal-bonus').text(user_bonus);
			}
			if($('.personal-bonus').text() == ''){
				$('.personal-bonus').text('0');
			}
		});
	}
*/
	$(".authorization #auth-reg-spoler").append($(".auth-form-wrap"));
	$(".authorization #auth-reg-spoler").append($(".bx-auth-reg"));

	var $callbackForm = $('.call-bacl-form'),
		$authForm = $('.auth-form-wrap'),
		$regForm = $('.bx-auth-reg');

	$("#auth,#registr").on('click',function(e){
		e.preventDefault();
			if($(this).attr('id') == 'auth'){

				if ( $callbackForm.hasClass('open') ) {
					$callbackForm.velocity({
						maxHeight: 0,
						opacity: 0
					}, 500, 'swing').removeClass('open');
				} else if ( $regForm.hasClass('open') ) {
					$regForm.velocity({
						maxHeight: 0,
						opacity: 0
					}, 500, 'swing').removeClass('open');
				}

				if ( !$('.auth-form-wrap').hasClass('open') ) {
					$('.auth-form-wrap').velocity({
						maxHeight: 175,
						opacity: 1
					}, 500, 'swing').addClass('open');
					} else {
					$('.auth-form-wrap').velocity({
						maxHeight: 0,
						opacity: 0
					}, 500, 'swing').removeClass('open');
				}
			}
			if($(this).attr('id') == 'registr'){

	 			if ( $callbackForm.hasClass('open') ) {
					$callbackForm.velocity({
						maxHeight: 0,
						opacity: 0
					}, 500, 'swing').removeClass('open');
				} else if ( $authForm.hasClass('open') ) {
					$authForm.velocity({
						maxHeight: 0,
						opacity: 0
					}, 500, 'swing').removeClass('open');
				}

				if ( !$('.bx-auth-reg').hasClass('open') ) {
					$('.bx-auth-reg').velocity({
						maxHeight: 370,
						opacity: 1
					}, 500, 'swing').addClass('open');
					} else {
					$('.bx-auth-reg').velocity({
						maxHeight: 0,
						opacity: 0
					}, 500, 'swing').removeClass('open');
				}
			}
	})
	$("#closeAH, #closeRG, #closeCB").on('click',function(e){
		e.preventDefault();
		if($(this).attr('id') == 'closeAH'){
			$("#auth").trigger("click");
		}
		if($(this).attr('id') == 'closeRG'){
			$("#registr").trigger("click");
		}
		if($(this).attr('id') == 'closeCB'){
			$("#call-back").trigger("click");
		}
	})
})

/* function initForm(){
	jQuery('.link-btn.club').on('click',function(e){
		e.preventDefault();
		jQuery('#dark-matter').fadeIn(300);
		jQuery('#form').fadeIn(300);
		jQuery('#form .message').fadeOut(300);
		jQuery('#form form').fadeIn(300);
	});

	jQuery('#form .close, #dark-matter').click(function(e) {
		e.preventDefault();

		jQuery('#dark-matter').fadeOut(300);
		jQuery('#form').fadeOut(300);
	});

	jQuery('#form input[type="submit"]').on('click',function(e){
		e.preventDefault();
		var res = validateName('#form input[type="text"]');
		res = res && validateTel('#form input[type="tel"]');
		res = res && validateEmail('#form input[type="email"]');
		res = res && validateLength('#form input[type="str"]', 1);

		if (!res) {
			return false;
		}

		jQuery.ajax({
			cache: false,
			url:     "/email.php", //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {
				'email' : jQuery( '#form input[type="email"]' ).val(),
				'tel' : jQuery( '#form input[type="tel"]' ).val(),
				'name' : jQuery( '#form input[type="text"]' ).val(),
				'str' : jQuery( '#form input[type="str"]' ).val()
			},
			success: function(result) {
				jQuery('#form .message').fadeIn(300);
				jQuery('#form form').fadeOut(300);
			}
		});

	});
}

function validateLength( _input, _minLength, _maxLength ) {
		var minLength = _minLength || -1;
		var maxLength = _maxLength || 100000;

		var result = ( ( jQuery( _input ).val().length >= minLength ) && ( jQuery( _input ).val().length <= maxLength ) );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'заполните поле')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', '')
						.off('focus')
						.parent()
							.removeClass('error');
				})
				.parent()
					.addClass( 'error' );

		return result;
	}

function validateName( _input ) {
		var reg_name = /^[_a-zA-Z0-9а-яА-ЯёЁ ]+$/i;

		var result = reg_name.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'заполните поле')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'имя')
						.off('focus')
						.parent()
							.removeClass('error');
				})
				.parent()
					.addClass( 'error' );

		return result;
	}

function validateTel( _input ) {
		var reg_tel = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{5,10}$/i;

		var result = reg_tel.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'заполните поле')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'телефон')
						.off('focus')
						.parent()
							.removeClass('error');
				})
				.parent()
					.addClass( 'error' );

		return result;
	}

function validateEmail( _input ) {
		var reg_email = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

		var result = reg_email.test( jQuery( _input ).val() );

		if ( result ) jQuery( _input ).removeClass( 'error' )
		else jQuery( _input )
				.attr('placeholder', 'заполните поле')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', 'e-mail')
						.off('focus')
						.parent()
							.removeClass('error');
				})
				.parent()
					.addClass( 'error' );

		return result;
	}

function validateSelect( _input ) {

		var result = jQuery( _input + ' option:selected' ).index( _input + ' option' ) != 0;
		var ch = jQuery( _input.replace( /-/g, '_' ) + '_chosen' );

		if ( result ) ch.add( _input ).removeClass( 'error' )
		else ch.add( _input )
				.attr('placeholder', 'заполните поле')
				.focus(function() {
					jQuery(this)
						.attr('placeholder', '')
						.off('focus')
						.parent()
							.removeClass('error');
				})
				.parent()
					.addClass( 'error' );

		return result;
	}

 */