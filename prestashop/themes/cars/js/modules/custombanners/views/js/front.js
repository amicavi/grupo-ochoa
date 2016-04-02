/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

var breakPoint, resizeTimer;
var carousels = {};

$(document).ready(function(){

	// setResolutionBreakPoint();
	$('.custombanners').each(function(){
		var $carousel_holder = $(this).find('.banners-in-carousel .carousel');
		$(this).find('.banner-item').each(function(){
			if ($(this).hasClass('in_carousel'))
				$(this).appendTo($carousel_holder);
		});
		if ($(this).find('.banners-one-by-one .banner-item').length < 1)
			$(this).find('.banners-one-by-one').hide();
		if ($carousel_holder.children().length > 0) {
			$carousel_holder.parent().show();
			renderCarousel($carousel_holder, false);
			// var hookName = $(this).data('hook');
			// var container_is_narrow = false;
			// if ($(this).innerWidth() < 300)
				// container_is_narrow = true;
			// var settings = $carousel_holder.data('settings');
			// $carousel_holder.parent().show();
			// var itemsNum = breakPoint ? settings[breakPoint] : settings.i;			
			// var width = Math.round($carousel_holder.parent().innerWidth() / itemsNum);
			// carousels[hookName] = $carousel_holder.bxSlider(loadCarouselParams(settings, itemsNum, width));			
			// if (settings.n == 1 && settings.n_hover == 1 && !isMobile)
				// $carousel_holder.closest('.custombanners').addClass('n-hover');
		}
	});
	
	$(window).resize(function(){
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {				
			// setResolutionBreakPoint();
			for (var hookName in carousels){
				renderCarousel(carousels[hookName], true);
				// var settings = carousels[hookName].data('settings');				
				// var itemsNum = breakPoint ? settings[breakPoint] : settings.i;				
				// var width = Math.round(carousels[hookName].parent().innerWidth() / itemsNum);				
				// carousels[hookName].reloadSlider(loadCarouselParams(settings, itemsNum, width));
				// carousels[hookName].closest('.bx-wrapper').css('max-width', '100%');
				// carousels[hookName].find('.banner-item').css('width', width+'px');	
			}					
		}, 200);		
	});
});

function renderCarousel($container, reload){
	var settings = $container.data('settings');
	var w = $(window).width();
	var itemsNum = 1;
	if (w > 1199)
		itemsNum = settings.i;
	else if (w > 991)
		itemsNum = settings.i_1200;
	else if (w > 767)
		itemsNum = settings.i_992;
	else if (w > 479)
		itemsNum = settings.i_768;
	else if (w < 480)
		itemsNum = settings.i_480;
	var slideWidth = Math.round($container.parent().innerWidth() / itemsNum);
	var params = {
		pager : settings.p == 1 ? true : false,
		controls: settings.n == 1 ? true : false,	
		auto: settings.a == 1 ? true : false,			
		moveSlides: settings.m,
		speed: settings.s,
		maxSlides: itemsNum,
		minSlides: itemsNum,
		slideWidth: slideWidth,
		responsive: false,
		swipeThreshold: 1,
		useCSS: true,
		oneToOneTouch: false,
	}
	
	if (reload)
		$container.reloadSlider(params);
	else {		
		var hookName = $container.closest('.custombanners').data('hook');
		carousels[hookName] = $container.bxSlider(params);			
		if (settings.n == 1 && settings.n_hover == 1 && !isMobile)
			$container.closest('.banners-in-carousel').addClass('n-hover');
	}
	$container.closest('.bx-wrapper').css('max-width', '100%');
}


// function loadCarouselParams(settings, itemsNum, slideWidth){
	// var params = {
		// pager : settings.p == 1 ? true : false,
		// controls: settings.n == 1 ? true : false,				
		// moveSlides: settings.m,
		// speed: settings.s,
		// maxSlides: itemsNum,
		// minSlides: itemsNum,
		// slideWidth: slideWidth,
		// responsive: false,
		// swipeThreshold: 1,
		// useCSS: true,
		// oneToOneTouch: false,
	// }
	// return params;
// }

// function setResolutionBreakPoint(){
	// var w = $(window).width();	
	// if (w > 1199)
		// breakPoint = false;
	// else if (w > 991)
		// breakPoint = 'i_1999';
	// else if (w > 767)
		// breakPoint = 'i_992';
	// else if (w > 479)
		// breakPoint = 'i_768';
	// else if (w < 480)
		// breakPoint = 'i_480';
// }