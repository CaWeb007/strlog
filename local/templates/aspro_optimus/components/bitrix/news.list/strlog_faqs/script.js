$(document).ready(function(){
	$('.strlog-news-item-title').click(function(){
		$('.strlog-news-item-title').removeClass('toggled');
		$(this).addClass('toggled');
		$('.strlog-news-item-desc').fadeOut(0);
		$(this).parent('.news-item').children('.strlog-news-item-desc').fadeToggle(0);
	});
});