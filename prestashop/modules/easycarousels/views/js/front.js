/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

$(document).ready(function(){

	var resizeTimer;
	var initialClasses = {};
	var carousels = {};
	var quickViewForced = false;

	// .easycarousels wrapper with ajaxpath is generated in easycarousels.php->displayNativeHook
	$('.easycarousels').each(function(){
		var $el = $(this);
		$.ajax({
			type: 'POST',
			url: $(this).attr('data-ajaxpath'),
			dataType : 'json',
			success: function(r)
			{
				$el.replaceWith(utf8_decode(r.carousels_html));
				$('.c_wrapper').each(function(){
					if ($(this).is(':visible'))
						renderCarousel($(this).closest('.easycarousel').attr('id'));

					if ($(this).closest('.column').length){
						if ($(this).closest('.easycarousel').hasClass('carousel_block'))
							$(this).closest('.easycarousel').addClass('block');
						if ($(this).closest('.column').hasClass('accordion'))
							try {accordion('disable'); accordion('enable');}catch(e){};
					}
				});
				if ($('.easycarousels .quick-view').length && !quickViewForced)
					try {quick_view(); quickViewForced = true;}catch(e){};
			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});

	$(window).resize(function(){
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function() {
			$('.c_wrapper.rendered').removeClass('rendered');
			for (var id in carousels)
				if (carousels[id].is(':visible'))
					renderCarousel(id);
		}, 200);
	});

	$(document).on('click', function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass('easycarousel_tabs'))
			$('.easycarousel_tabs').addClass('closed');
	}).on('click', '.responsive_tabs_selection', function(){
		var closed = $(this).parent().hasClass('closed');
		$('.easycarousel_tabs').addClass('closed');
		if (closed)
			$(this).closest('.easycarousel_tabs').removeClass('closed');
	}).on('click', '.easycarousel_tabs li.carousel_title', function(){
		var text = $(this).text();
		$(this).closest('ul').addClass('closed').find('.responsive_tabs_selection span').html(text);
		var id = $(this).find('a').attr('href');
		if (id)
			renderCarousel(id.replace('#', ''));
	}).on('click', '.column.accordion h3.carousel_title', function(){
		if ($(this).hasClass('active'))
			renderCarousel($(this).parent().attr('id'));
	});

	function renderCarousel(id){
		$container = $('#'+id).find('.c_wrapper');
		if ($container.hasClass('rendered'))
			return;

		$container.addClass('rendered');
		var settings = $container.data('settings');
		var w = $(window).width();
		itemsNum = 1;
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

		var contanerWidth = $container.closest('.easycarousel').innerWidth();
		var slideWidth = Math.round(contanerWidth / itemsNum);

		if (slideWidth < settings.min_width) {
			itemsNum = parseInt(contanerWidth / settings.min_width);
			slideWidth = parseInt(contanerWidth / itemsNum);
		}

		var loop = $container.find('.c_col').length <= itemsNum ? 0 : settings.l;
		var params = {
			pager : settings.p == 1 ? true : false,
			controls: settings.n == 1 ? true : false,
			moveSlides: parseInt(settings.m),
			speed: parseInt(settings.s),
			maxSlides: itemsNum,
			minSlides: itemsNum,
			slideWidth: slideWidth,
			responsive: false,
			swipeThreshold: 1,
			useCSS: true,
			oneToOneTouch: false,
			infiniteLoop: loop == 1 ? true : false,
			prevText:'',
			nextText:'',
			onSliderLoad: function(){
				$container.attr('class', initialClasses[id]+' items-num-'+itemsNum).closest('.bx-wrapper').css('max-width', '100%').find('.c_col').css({'float':'', 'display':'inline-block'});
			 },
		};

		if (id in carousels)
			carousels[id].reloadSlider(params);
		else {
			if (settings.n == 1 && settings.n_hover == 1 && !isMobile)
				$container.addClass('n-hover');
			initialClasses[id] = $container.attr('class');
			carousels[id] = $container.bxSlider(params);
		}

		if ($container.closest('.in_tabs').hasClass('compact_on'))
		{
			var $tabList = $container.closest('.in_tabs').find('ul.nav-tabs');
			var $lastLi = $tabList.find('li.carousel_title').last();
			$tabList.closest('.in_tabs').removeClass('compact');
			if ($lastLi.prev().hasClass('carousel_title') && $lastLi.offset().top != $lastLi.prev().offset().top)
				$tabList.closest('.in_tabs').addClass('compact');
		}
	}

	function utf8_decode (utfstr) {
		var res = '';
		for (var i = 0; i < utfstr.length;) {
			var c = utfstr.charCodeAt(i);

			if (c < 128)
			{
				res += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224))
			{
				var c1 = utfstr.charCodeAt(i+1);
				res += String.fromCharCode(((c & 31) << 6) | (c1 & 63));
				i += 2;
			}
			else
			{
				var c1 = utfstr.charCodeAt(i+1);
				var c2 = utfstr.charCodeAt(i+2);
				res += String.fromCharCode(((c & 15) << 12) | ((c1 & 63) << 6) | (c2 & 63));
				i += 3;
			}
		}
		return res;
	}
});