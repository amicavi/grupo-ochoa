/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

$(document).ready(function(){
	var carouselBlog = $('.js-blogCarousel .posts-list').bxSlider({
			nextText: '',
			prevText:'',
			maxSlides:2,
			minSlides:2,
			slideWidth:585,
			pager:false,
			infiniteLoop:0
	});
	$(window).resize(function(){
		if (($(window).width() + scrollCompensate())<768){
			if (carouselBlog.length != 0){
					carouselBlog.reloadSlider({
					nextText: '',
					prevText:'',
					maxSlides:1,
					minSlides:1,
					pager:false,
					infiniteLoop:0
				});
			}
		}else{
			if (carouselBlog.length != 0){
				carouselBlog.reloadSlider({
					nextText: '',
					prevText:'',
					maxSlides:2,
					minSlides:2,
					slideWidth:585,
					pager:false,
					infiniteLoop:0
				});
			}
		}
	});
});