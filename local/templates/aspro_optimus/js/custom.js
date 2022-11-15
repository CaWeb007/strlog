/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/
//НОВОГОДНИЙ СНЕГ
/*$(document).ready(function(){
    snowStorm.snowColor = '#99ccff';   // blue-ish snow!?
    snowStorm.flakesMaxActive = 96;    // show more snow on screen at once
    snowStorm.useTwinkleEffect = true; // let the snow flicker in and out of view
    snowStorm.zIndex = 1000; // let the snow flicker in and out of view
});*/
$(document).ready(function(){

	//setTimeout(function(){$('.sort-list-wrapper').css('width', $(".sort-list-wrapper > .sort-list").width());
//						  $('.group-list-wrapper').css('width', $(".group-list-wrapper > .group-list").width());},200);

    function hideSliderText(){
        $('.flex-next, .flex-prev').text('');
    }setTimeout(hideSliderText, 100);

/*Страница регистрации*/
	$('.registration-tab').click(function(){
		$('.registration-tab').removeClass('registration-tab-active');
		$(this).addClass('registration-tab-active');
	});

	$('.registration-fiz-tab').click(function(){
		$('.registration-ur-face').fadeOut(0);
		$('.registration-fiz-face').fadeIn(0);
	});
	$('.registration-ur-tab').click(function(){
		$('.registration-fiz-face').fadeOut(0);
		$('.registration-ur-face').fadeIn(0);
	});
/*/Страница регистрации*/

/*Сортировка и группировка в разделе товаров*/
	$('.first-group-item').click(function(){
		//$('.group-item').fadeToggle(0);
		if($('.group-item').is(':visible') != true){
			$(".group-list-wrapper").css({width:$(this).parent().width()+1,height:$(this).parent().height()+1});
			$('.group-item').fadeIn(0);
		} else {
			$('.group-item').fadeOut(0);
		}
	});

	$(document).click(function (e){
		var grouperList = $(".group-list");
		if (!grouperList.is(e.target)
		    && grouperList.has(e.target).length === 0) {
			$(grouperList).children('.group-item').hide();
		}
	});

	$('.first-sort-item').click(function(){
		//$('.sort-item').fadeToggle(0);
		if($('.sort-item').is(':visible') != true){
			$(".sort-list-wrapper").css({width:$(this).parent().width()+2,height:$(this).parent().height()+2});
			$('.sort-item').fadeIn(0);
		} else {
			$('.sort-item').fadeOut(0);
		}
	});
    $(document).click(function (e){
        var sorterList = $(".sort-list");
        if (!sorterList.is(e.target)
            && sorterList.has(e.target).length === 0) {
            $(sorterList).children('.sort-item').hide();
        }
    });
/*/Сортировка и группировка в разделе товаров*/

/*Our projects*start*used by vue.js*/
    /*var dropCount = 0;
    var drop = new Vue({
        el: '#our-projects',
        data: {
            isDropped: false,
            isScaled: false, //Переворот стрелочки
        },
        methods: {
            dropProjectsEnter: function(){
                dropCount++;
               // if(dropCount%2 == 0){
                    this.isDropped = true;
                    this.isScaled = false; //Переворот стрелочки
              //  }else{
              //      this.isDropped = true;
              //      this.isScaled = false; //Переворот стрелочки. Заменить на true, если нужен переворот при нажатии
              //  }
            },
            dropProjectsLeave: function(){
                dropCount++;
                //if(dropCount%2 == 0){
                //    this.isDropped = false;
                //    this.isScaled = false; //Переворот стрелочки
                //}else{
                    this.isDropped = false;
                    this.isScaled = false; //Переворот стрелочки. Заменить на true, если нужен переворот при нажатии
              //  }
            }
        }
    });*/

/*Our projects*end*/


$(document).ready(function() {
	$('[data-tooltip=tooltip]').tooltipster({'side':'bottom','theme':'tooltipster-borderless'});
});

/*Top menu*start*/
    $('.strlog-top-dropdown-menu-first-point').mouseenter(function() {
       // if ($(this).attr('data-click-count') === '0') {
            $('.strlog-top-dropdown-menu-first-point').attr('data-click-count', '0');
            $(this).attr('data-click-count', '1');
            $('.strlog-top-dropdown-submenu').fadeOut(0);
            $(this).children('.strlog-top-dropdown-submenu').fadeIn(0);
      //  } else {
	}).mouseleave(function(){
            $('.strlog-top-dropdown-menu-first-point').attr('data-click-count', '0');
            $('.strlog-top-dropdown-submenu').fadeOut(0);
      //  }
    });
/*Top menu*end*/
    $(document).click(function (e){
/*        var ourProjectsDropdown = $(".our-projects-dropdown-wrapper");
        var ourProjects = $('.our-projects');
        if (!ourProjectsDropdown.is(e.target) && ourProjectsDropdown.has(e.target).length === 0 && !ourProjects.is(e.target) && ourProjects.has(e.target).length === 0) {
            drop.isDropped = false;
            drop.isScaled = false; //Переворот стрелочки
            dropCount = 0;
        }*/
        var topDropdownMenu = $(".strlog-top-dropdown-menu-first-point");
        var topDropdownSubmenu = $('.strlog-top-dropdown-submenu');
        if (!topDropdownMenu.is(e.target) && topDropdownMenu.has(e.target).length === 0 && !topDropdownSubmenu.is(e.target) && topDropdownSubmenu.has(e.target).length === 0) {
            $(topDropdownMenu).attr('data-click-count', '0');
            $(topDropdownSubmenu).fadeOut(0);
        }
    });

	/* Проверка ширины экрана. Начало. */
	function isMobile(docWidth)
	{
		if(typeof(docWidth) == "undefined")
			docWidth = 960;
	
		if($(window).width() > docWidth) {
			return false;
		} else {
			return true;
		}
	}
	/* Проверка ширины экрана. Конец. */

	/* Отключить в мобильной версии событие mouseenter и mouseleave */
	function disableMouseEnter()
	{
		if(isMobile(768)){
			$(document).off('mouseenter', '.menu_top_block>li:not(.full)');
			$(document).off('mouseenter', '.menu_top_block>li .dropdown>li');
			$(document).off('mouseleave', '.menu_top_block>li:not(.full)');
			$(document).off('mouseleave', '.menu_top_block>li .dropdown>li');
		}
	}
	
	$(window).resize(function(){
		disableMouseEnter();
	});
	disableMouseEnter();

	/* Дополняем событие mouseenter */
	$(document).on('mouseenter', '.menu_top_block:not(.main-catalogfirst)>li>.dropdown>li', function(event){
		if(isMobile(768) == false){
			$('body').addClass("js-css");

			/*if($('div').is('.menu_top_block.catalog_block') == false || $('.menu_top_block.catalog_block').is(':visible') != true)*/
				if($("div").is("#desktop-menu-backdrop") == false){
					$('body').append('<div id="desktop-menu-backdrop" class="modal-backdrop in catalog-menu-backdrop"></div>');
				}
	
			/*$(this).on('mouseleave', function(){
				var leaveTimerBG = setTimeout(function(){
					$('body').removeClass('js-css');
					$('#desktop-menu-backdrop').remove();
				}, delayTime);
	
				$(this).on('click', function(){
					if(leaveTimerBG){
						clearTimeout(leaveTimerBG);
						leaveTimerBG = false;
					}
				});
			});/*/
		}
	});

	$(document).on('mouseenter', '.menu_top_block:not(.main-catalogfirst)>li:not(.full)', function(event){

		if(isMobile(768) == false){
			$('body').addClass("js-css");

			//if($('div').is('.menu_top_block.catalog_block') == false || $('.menu_top_block.catalog_block').is(':visible') != true)
				if($("div").is("#desktop-menu-backdrop") == false){
					$('body').append('<div id="desktop-menu-backdrop" class="modal-backdrop in catalog-menu-backdrop"></div>');
				}
	
			$(this).on('mouseleave', function(){
				var leaveTimerBG = setTimeout(function(){
					$('body').removeClass('js-css');
					$('#desktop-menu-backdrop').remove();
				}, delayTime);
	
				$(this).on('click', function(){
					if(leaveTimerBG){
						clearTimeout(leaveTimerBG);
						leaveTimerBG = false;
					}
				});
			});
		}
	});

	/* */
	$(".menu_top_block.catalog_block").on('mouseenter',function(){
		if(isMobile(768) == false){
				$('body').addClass("js-css");
	
				if($("div").is("#desktop-menu-backdrop") == false){
					$('body').append('<div id="desktop-menu-backdrop" class="modal-backdrop in catalog-menu-backdrop"></div>');
				}
		
				$(this).on('mouseleave', function(){
					var leaveTimerBG = setTimeout(function(){
						$('body').removeClass('js-css');
						$('#desktop-menu-backdrop').remove();
					}, delayTime);
		
					$(this).on('click', function(){
						if(leaveTimerBG){
							clearTimeout(leaveTimerBG);
							leaveTimerBG = false;
						}
					});
				});
			}
	});

	/* Назначаем событие onclick в мобильной версии за место mouseenter и mouseleave */
	$(document).on('click', '.menu_top_block>li:not(.full)', function(event){
	
		if(isMobile(768)){
			if(event.target != this) return;

			$(document).off('mouseenter', '.menu_top_block>li:not(.full)');
			$(document).off('mouseenter', '.menu_top_block>li .dropdown>li');
			$(document).off('mouseleave', '.menu_top_block>li:not(.full)');
			$(document).off('mouseleave', '.menu_top_block>li .dropdown>li');
			$(document).off('mouseleave', '.menu_top_block.catalogfirst>li>.dropdown>li.full');

			var $submenu = $(this).find('>.dropdown');
	
			$submenu.addClass('mob');
		
			if(!$submenu.hasClass('visible')){
				$('body, html, #main').css({'overflow':'hidden'});

				$submenu.addClass('visible');
	
				if(leaveTimer){
					clearTimeout(leaveTimer);
					leaveTimer = false;
				}
	
				if($submenu.length){
	
					var $menu = $(this).parents('.menu');
					var $wrapmenu = $menu.parents('.wrap_menu');
					var wrapMenuWidth = $wrapmenu.actual('outerWidth');
					var wrapMenuLeft = $wrapmenu.offset().left;
					var wrapMenuRight = wrapMenuLeft + wrapMenuWidth;
					var left = wrapMenuRight - ($(this).offset().left + $submenu.actual('outerWidth'));
					if(window.matchMedia('(min-width: 951px)').matches && $(this).hasClass('catalog') && ( $('.banner_auto').hasClass('catalog_page') || $('.banner_auto').hasClass('front_page'))){
						return;
					}
					if(left < 0){
						$submenu.css({left: left + 'px'});
					}
					var curPosCss = $(".top_data_wrapper").css('position');
					var $offset = $(".top_data_wrapper").offset();
					var cTop = $offset.top;
					if(curPosCss == "fixed")
						cTop = 0;

					var $wr = $(".top_data_wrapper").height();console.log($offset,cTop);
					var $h = $(window).height()-cTop-$wr;
					$submenu.stop().slideDown(animationTime, function(){
						$submenu.css({height: $h, 'overflow':'visible'});
					});
	
			
					/*$(this).on('mouseleave', function(){
						var leaveTimer = setTimeout(function(){
							$submenu.stop().slideUp(animationTime, function(){
								$submenu.css({left: ''});
							});
							$submenu.removeClass('visible');
						}, delayTime);
		
						$(this).on('click', function(){
							if(leaveTimer){
								clearTimeout(leaveTimer);
								leaveTimer = false;
							}
						});
					});*/
				}
	
			} else {
				$('body, html, #main').css({'overflow':'auto'});
				$submenu.removeClass('visible');
			
				var leaveTimer = setTimeout(function(){
					$submenu.stop().slideUp(animationTime, function(){
						$submenu.css({left: ''});
					});
				}, delayTime);
			}
		}
	});

	/* Фиксированная синяя шапка */
	$(document).scroll(function(){
		var sTop = $(this).scrollTop();
		if(sTop >= 41){
			$('header').addClass("header-top-fixed");
		} else {
			$('header').removeClass("header-top-fixed");
		}
	});


	$(".alert .close").on("click",function(e){
		e.preventDefault();
		$(this).closest('.alert').fadeOut(700);
	});

});

if(!funcDefined("sendSocEvent")) {
	var sendSocEvent = function (eventName,url) {
		waitLayer(100, function() {
			if(typeof(eventName) != "undefined") {
				yaCounter37983465.reachGoal(eventName); 
				dataLayer.push({
					"event": eventName
				});
			}
			if(typeof(url) != "undefined") {
				var g = gtag_report_conversion(url);
			}
		});
	}
}

if(!funcDefined("showAuthFormPopup")) {
	var showAuthFormPopup = function () {
		name = 'show_auth_form_popup';
		$('body').find('.'+name+'_frame').remove();
		$('body').find('.'+name+'_trigger').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		$('body').append('<div class="'+name+'_trigger"></div>');
		$('.'+name+'_frame').jqm({trigger: '.'+name+'_trigger', onHide: function(hash) { onHidejqm(name,hash) }, onLoad: function( hash ){ onLoadjqm( name, hash ); }, ajax: arOptimusOptions["SITE_DIR"]+'ajax/show_auth_form_popup.php'});
		$('.'+name+'_trigger').click();
	}
}