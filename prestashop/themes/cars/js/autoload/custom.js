var scrollTimer;
$(document).ready(function(){
		/*searchFocus();*/
		searchFocusMenu();
		topOffSetPage();
		$('.amazing-search').appendTo('#block_top_menu');
	// sticky menu + scroll top
	$(window).scroll(function(){
		clearTimeout(scrollTimer);
		scrollTimer = setTimeout(function() {
			var scrollY = $(window).scrollTop();
			if ($(window).width() + scrollCompensate() > 767) {
				var $headerHeight = $('#header').outerHeight(),
					$navbarHeight = $('#topMain').outerHeight(),
					$mainHeight = $('.main_panel').outerHeight(),
					mTop = $mainHeight - $headerHeight;
				if (scrollY > $navbarHeight)
					$('#header').addClass('fixedHeader').css('margin-top', mTop + 'px');
				else
					$('#header').removeClass('fixedHeader').css('margin-top', '0');
			}else {
				$('#header').removeClass('fixedHeader').css('margin-top', '0');
			}
			if (scrollY > 150)
				$('#back-top').fadeIn();
			else
				$('#back-top').fadeOut();
		}, 100);
	}).trigger('scroll');
	$('#back-top').click(function() {
		$('body,html').animate({scrollTop: 0}, 1000);
	});

	// customer links dropdown
	$('#drop_links').click(function() {
		$(this).parent().toggleClass('opened').find('#drop_content_user').slideToggle();
	});

		$(window).scroll(function(){
			if($('#header').hasClass('fixedHeader')){
				if($(window).width() + scrollCompensate() < 768){
					$(".dropdown_links").slideUp();
					$('.header_user_info').removeClass("opened");
				}
				$('.localization_block .current').removeClass('active');
				$('.localization_block ul').slideUp();
			}
		});

		$(window).resize(function(){
  			initMasonry();
  			/*searchFocus();*/
  			searchFocusMenu();
  			topOffSetPage();
		});

			setTimeout(function(){ 
				initMasonry();
			},1500);

});

function initMasonry(){
	var $container = $('.displayCustomBanners4 .banners-one-by-one');
	var $containerWidth = $('.displayCustomBanners4 .banners-one-by-one').outerWidth();
	var widthColumn = $containerWidth/4;
	if ($(window).width() + scrollCompensate() >767){
		$container.masonry({
			columnWidth: widthColumn,
			itemSelector: '.banner-item'
		});
		$container.addClass('masonry-active');
	}else {
		if($container.hasClass('masonry-active')){
			$container.masonry('destroy');
			$container.removeClass('masonry-active');
		}
	}

}
/*function searchFocus(){
	if(scrollCompensate() + $(window).width()<481){
		$('#search_query_top').focus(function(){
			$(this).parent().parent().css({left:'-2px'});
		});
		$('#search_query_top').focusout(function(){
			$(this).parent().parent().css({left:''});
		});
	}else{
		$('#search_query_top').off();
		$('#search_query_top').parent().parent().css({left:''});
	}
}*/
function searchFocusMenu(){
	var getSearch = $('#amazing_block_top');
	if (getSearch.length != 0){
		if(scrollCompensate() + $(window).width()>767){
			$('.button-search-menu').fadeOut('fast');
			$('.amazing-search').click(function(){
				$(this).addClass('search-active-menu');
				$('.button-search-menu').fadeIn('fast');
			});
				$(document).on('click',function(e){
					 if ($(e.target).closest('.amazing-search').length) return; 
			 		$('.amazing-search').removeClass('search-active-menu');
			 		$('.button-search-menu').fadeOut('fast');
			 		e.stopPropagation();
				});
			$('.button-search-menu').click(function(){
				$('.button-search-menu').fadeOut('fast');
			});
		}else{
			$('.amazing-search').off();
			$('.button-search-menu').off();
			$('.amazing-search').removeClass('search-active-menu');
			$('.button-search-menu').fadeIn('fast');
		}
	}
}
/*function searchFocusMenu(){
	var getSearch = $('.sf-search');
	if (getSearch.length != 0){
		if(scrollCompensate() + $(window).width()>767){
			$('.button-search-menu').fadeOut('fast');
			getSearch.click(function(){
				$(this).addClass('search-active-menu');
				$('.button-search-menu').fadeIn('fast');
			});
				$(document).on('click',function(e){
					 if ($(e.target).closest('.sf-search').length) return; 
			 		$('.sf-search').removeClass('search-active-menu');
			 		$('.button-search-menu').fadeOut('fast');
			 		e.stopPropagation();
				});
			$('.button-search-menu').click(function(){
				$('.button-search-menu').fadeOut('fast');
			});
		}else{
			$('.sf-search').off();
			$('.button-search-menu').off();
			$('.sf-search').removeClass('search-active-menu');
			$('.button-search-menu').fadeIn('fast');
		}
	}
}*/
function topOffSetPage(){
	var headerHeightConstant = $('#header').outerHeight();
	if ($(window).width() + scrollCompensate() > 768) {
		$('#page').css('padding-top',headerHeightConstant);
	}else{
		$('#page').css('padding-top',0);
	}
}


