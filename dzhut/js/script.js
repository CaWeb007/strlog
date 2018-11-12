$(document).ready(function(){

	$('.order-send').click(function(){
		$('.onload, .popup-order').fadeIn(200);
		var name = $(this).attr('data-name');
		$('.popup-order-good').val('Джут ' + name + 'мм');
	});
	$('.onload, .popup-order-close').click(function(){
		$('.onload, .popup-order').fadeOut(200);
	});

	$('.top-order').submit(function(){
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: 'orders.php',
			data: data,
			success: function(success){
				$('.top-order-l').children('input').css('display', 'none');
				$('.top-order-send-wrapper').children('input').css('display', 'none');
				$('.top-order-thanks').css('display', 'block');
			},
			error: function(error){

			}
		});
	});

	$('.action-order').submit(function(){
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: 'orders.php',
			data: data,
			success: function(success){
				$('.action-data').val('');
			},
			error: function(error){

			}
		});
	});

	$('.popup-order-form').submit(function(){
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: 'orders.php',
			data: data,
			success: function(success){
				$('.popup-order-data').val('');
				$('.popup-order-thanks').css('opacity', '1');
				function hide(){
					$('.onload, .popup-order').fadeOut(200);
					$('.popup-order-thanks').css('opacity', '0');
				}setTimeout(hide, 2000);
			},
			error: function(error){

			}
		});
	});

	$('.block-form-order').submit(function(){
		var data = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: 'orders.php',
			data: data,
			success: function(success){
				$('.block-form-order-thanks i').text($('.block-form-data-name').val());
				$('.block-form-data').val('');
				$('.block-form-order-thanks').fadeIn(200);
				function hide(){
					$('.block-form-order-thanks').fadeOut(200);
				}setTimeout(hide, 2000);
			},
			error: function(error){

			}
		});
	});

	function changeActionColor(){
		function changeActionColorRed(){
			$('.top-action-title').css('color', '#ed1d24');
			$('.top-action-title').css('transition', '1s');
			function changeActionColorWhite(){
				$('.top-action-title').css('color', '#fff');
				$('.top-action-title').css('transition', '1s');
			}setTimeout(changeActionColorWhite, 1000);
		}setTimeout(changeActionColorRed, 1000);
	}setInterval(changeActionColor, 2000);

	$('.soputka').click(function(){
		$('.soputka-wrapper').slideToggle(200);
		var text = $('.soputka b').text();
		$('.soputka b').text(text == '+' ? '-' : '+');
	});

	function mapping(){
	var desc = 0;
	$('.description').each(function(){
		desc++;
		var e = $(this);
		$(e).addClass('description-' + desc);
	});
	}setTimeout(mapping, 500);

	$('.fancybox-win').fancybox();
	$('.fancybox-review').fancybox();
	$('.fancybox-good-image').fancybox();
	$('.block-ukladka-photos').fancybox();

	function slicking(){
		var slick = 0;
		$('.slick-list').each(function(){
			slick++;
			var e = $(this);
			$(e).addClass('slick-list-' + slick);
		});
	}setTimeout(slicking, 500);

	$('.block-ukladka-photo img').mouseover(function(){
		$(this).css('transform', 'scale(1.1)');
		$(this).css('transition', '1s');
	});

	$('.block-ukladka-photo img').mouseout(function(){
		$(this).css('transform', 'scale(1)');
		$(this).css('transition', '1s');
	});

	$('.fancybox-good-image').mouseover(function(){
		$(this).children('.lupa').fadeIn(0);
	});
	$('.fancybox-good-image').mouseout(function(){
		$(this).children('.lupa').fadeOut(0);
	});

});//end docready