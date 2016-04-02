/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*  
*/

$(document).ready(function(){
	
	if ($('.gbwa_posts.home_carousel, .gbwa_posts.col_carousel').length > 0){
		$('head').append('<script src="'+carousel_js+'"></script>');
		$('head').append('<link rel="stylesheet" href="'+carousel_css+'">');
		$('head').append('<link rel="stylesheet" href="'+carousel_theme_css+'">');				
	
		// home
		$('.gbwa_posts.home_carousel').owlCarousel({
			slideSpeed : 300,
			paginationSpeed : 400,
			items : 1,
			singleItem: true,
			pagination: false,
			navigation: true,
			itemsDesktop : [1199,2],
			itemsDesktopSmall : [980,2],
			itemsTablet: [768,1],
			itemsMobile : [479,1],
			navigationText:[]
		});
		
		// columns	
		$('.gbwa_posts.col_carousel').owlCarousel({		
			  slideSpeed : 300,
			  paginationSpeed : 400,
			  singleItem:true
		});
			
		// normalize heights in carousel
		var carouselHeights = [];
		$('.gbwa_posts.owl-carousel .post_content').each(function(){			
			if ($(this).prop('scrollHeight') != $(this).prop('offsetHeight'))
				$(this).closest('.post').addClass('expandable');
			carouselHeights.push($(this).outerHeight());			
			if ($(this).closest('.owl-item').next().length < 1){
				var h = Math.max.apply(Math,carouselHeights);								
				$(this).css('height', h+'px').closest('.owl-item').prevAll().find('.post_content').css('height', h+'px');
				carouselHeights = [];
			}
		});
	}
	
	if ($('.gbwa_posts.home_grid, .gbwa_posts.grid').length > 0){
		normalizeColumns($('.gbwa_posts.grid'));
	}
	
	$('.gbwa_posts').on('click', '.expand', function(){
		
		var parent = $(this).closest('.post');
		var origHeight = parent.find('.content_wrapper').outerHeight();
		parent.toggleClass('expanded');
		var newHeight = parent.find('.content_wrapper').outerHeight();
		var topPos = parent.offset().top;
		var expanded  = parent.hasClass('expanded');
		
		//normalize columns in same row
		parent.nextAll().each(function(){
			if ($(this).offset().top != topPos)
				return false;			
			if (expanded)
				$(this).find('.content_wrapper').css('margin-bottom', (newHeight-origHeight)+'px');
			else
				$(this).find('.content_wrapper').css('margin-bottom', '0');			
		});
		parent.prevAll().each(function(){
			if ($(this).offset().top != topPos)
				return false;			
			if (expanded)
				$(this).find('.content_wrapper').css('margin-bottom', (newHeight-origHeight)+'px');
			else
				$(this).find('.content_wrapper').css('margin-bottom', '0');
			
		});
	})
});

var processedBlocksNum = 0;
// var rowNum = 0;

function normalizeColumns(wrapper){
	
	var topPostion = 0;	
	var rowItemsNum = 0;
	var rowHeights = [];
	
	elements = wrapper.find('.post_content').slice(processedBlocksNum);		
	// elements = wrapper.find('.post_content');		
	elements.each(function(){
		if ($(this).prop('scrollHeight') != $(this).prop('offsetHeight'))
			$(this).closest('.post').addClass('expandable');	
		
		if ($(this).closest('.post').position().top != topPostion){			
			var h = Math.max.apply(Math,rowHeights);				
			$(this).closest('.post').prevAll().slice(0, rowItemsNum).find('.post_content').css('height', h+'px');		
			// rowNum++;
			// $(this).closest('.post').prevAll().slice(0, rowItemsNum).find('.post_content').css('height', h+'px').find('h4').html('row: '+rowNum+'; height: '+h+'; offset:'+$(this).closest('.post').position().top);
			// if(rowItemsNum < 2)
				// $(this).closest('.post').find('h4').before('<i>less_than_2</i>');
				
			rowItemsNum = 1;
			topPostion = $(this).closest('.post').position().top;			
			rowHeights = [$(this).outerHeight()];	

		}		
		else{
			rowItemsNum++;					
			rowHeights.push($(this).outerHeight());
		}
		processedBlocksNum++;
		
		// last item
		if ($(this).closest('.post').next().length < 1){
			var h = Math.max.apply(Math,rowHeights);			
			$(this).css('height', h+'px').closest('.post').prevAll().slice(0, rowItemsNum-1).find('.post_content').css('height', h+'px');			
			processedBlocksNum = processedBlocksNum - rowItemsNum;
			rowItemsNum = 0;			
		}
		
	});	
}