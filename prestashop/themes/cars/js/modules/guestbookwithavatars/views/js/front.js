/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

$(document).ready(function(){

	if ($('.addForm .allow_html').length > 0)
		 $('head').append('<script src="//tinymce.cachefly.net/4/tinymce.min.js"></script>');

	// load more
	$('.gbwa').on('click', '#loadMore', function(e){
		e.preventDefault();
		var currentIds = [];
		$('.gbwa .gbwa_posts .post').each(function(){
			currentIds.push($(this).attr('data-idpost'));
		})
		loadMorePosts(currentIds);
	});

	// add new
	$('.gbwa').on('click', '#addNew', function(){
		//At least one div.post inside $('.gbwa .gbwa_posts') is required for proper animation
		if ($('.gbwa .gbwa_posts').find('.post').first().length < 1);
			$('.gbwa .gbwa_posts').append('<div class="post"></div>');
		
		if ($('.addForm .allow_html').length > 0){
			tinymce.init({
				// language_url : '/ps16/js/tiny_mce/langs/ru.js',
				selector: '#post_content',
				plugins: 'bbcode paste emoticons',
				toolbar1: 'bold italic underline emoticons',
				menubar: false,
				statusbar: false,
				paste_as_text: true,
				forced_root_block: false,
			});
		}
		$(this).hide().siblings('.addForm').show();
	});

	// avatar
	$('.gbwa').on('click', '.imgholder > div', function(){
		$(this).next().find('input').click();
	});

	$('.gbwa').on('change', 'input[name="avatar_file"]', function(){
		var el = $(this);
		el.closest('.imgholder').removeClass('alert-danger');
		var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader)
			return;
        if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onloadend = function(){
				el.closest('.imgholder').removeClass('alert-danger').find('.avatarImg').css('background-image', 'url('+this.result+')').addClass('requires-update');
            }
        }
		else
			el.closest('.imgholder').addClass('alert-danger').find('.avatarImg').removeClass('requires-update');
    });

	//send post
	$('.addForm').on('submit', function(e){
		 e.preventDefault();
		 addPost();
	});

	$('.gbwa').on('click', '.red-border input, .red-border textarea, .red-border .mce-edit-area', function(){
		$(this).closest('.red-border').removeClass('red-border').find('.field_error').html('').hide();
	});

	// ajax progress
	$('body').append('<div id="re-progress"><div class="progress-inner"></div></div>');
	$(document).ajaxStart(function(){
		$('#re-progress .progress-inner').width(0).fadeIn('fast').animate({'width':'70%'},500);
	})
	.ajaxSuccess(function(){
		$('#re-progress .progress-inner').animate({'width':'100%'},500,function(){
			$(this).fadeOut('fast');
		});
	})
})

function loadMorePosts(currentIds){
	$('#loadMore i').show().siblings().hide();
	// $('#loadMore i').toggleClass('icon-angle-down-double icon-refresh icon-spin');
	$.ajax({
		type: 'POST',
		url: gbwa_ajax_path,
		dataType : 'json',
		data: {
			ids_to_exclude: currentIds,
			ajaxAction: 'loadMore'
		},
		success: function(r)
		{
			$('#loadMore i').hide().siblings().show();
			// $('#loadMore i').toggleClass('icon-angle-down-double icon-refresh icon-spin');
			if (r.posts)
				$('.gbwa .gbwa_posts').append(utf8_decode(r.posts));
			else
				$('#loadMore').hide();
			// normalizeColumns defined in front_simple.js
			normalizeColumns($('.gbwa_posts.grid'));
		},
		error: function(r)
		{
			console.dir('unknown error');
			console.dir(r);
			$('#loadMore span').show().siblings().hide();
		}
	});
}

function addPost(){
	
	// test
	// $('#submitPost').removeClass('blocked');
	// var postsWrapper = $('.gbwa .gbwa_posts');
	// var firstPost = $('.gbwa .gbwa_posts .post').first();
	// var initialStyle = {
		// 'top' : postsWrapper.outerHeight()+'px',
		// 'position' : 'absolute',
		// 'z-index' : '99999',
	// }
	// var finalStyle = {
		// 'left' : '0',
		// 'top' : '0',
		// 'position' : 'relative',
	// }
	// var animateStyle = {
		// 'top': postsWrapper.find('.post').first().position().top+'px',
		// 'left': postsWrapper.find('.post').first().position().left+'px',
	// }
	// var newPostInDom = $('<div class="post" data-idpost="5454">'+firstPost.html()+'</div>');
	// newPostInDom.appendTo(postsWrapper).css(initialStyle);
	// newPostInDom.animate(
	// animateStyle,
	// 1000,
	// function(){
		// newPostInDom.addClass('just_added').css(finalStyle).insertBefore(firstPost);
		// postsWrapper.children().last().remove();
		// processedBlocksNum = 0;
		// normalizeColumns(postsWrapper);
	// });
	// $('html, body').animate({
		// scrollTop: postsWrapper.offset().top - 35
	// }, 700);

	// return;
	// test
	$('.addForm .ajax_errors').removeClass('alert alert-danger').html('');
	$('.addForm .field_error').html('').hide();
	$('.red-border').removeClass('red-border');
	var formData = new FormData($('#add_new_post')[0]);
	$.ajax({
		type: 'POST',
		url: gbwa_ajax_path,
		dataType : 'json',
		data: formData,
		contentType: false,
        processData: false,
		cache: false,
		success: function(r)
		{
			console.dir(r);
			// return;
			$('#submitPost').removeClass('blocked');
			if(r.hasError){
				for (var e in r.errors)
					if ($('.field.'+e).length > 0){
						$('.field.'+e).find('.field_error').show().html(r.errors[e]).parent().addClass('red-border');
					}
					else
						$('.addForm .ajax_errors').addClass('alert alert-danger').append('<div>'+r.errors[e]+'</div>');
			}
			else if (r.instant_publish){
				$('.addForm').hide().find('input, textarea').val('');
				var postsWrapper = $('.gbwa .gbwa_posts');
				var newPostInDom = $(utf8_decode(r.new_post));

				var initialStyle = {
					'top' : postsWrapper.outerHeight()+'px',
					'position' : 'absolute',
					'z-index' : '99999',
				}
				var animatePosition = postsWrapper.find('.post').first().position();
				var animateStyle = {
					'top': animatePosition.top+'px',
					'left': animatePosition.left+'px',
				}
				var finalStyle = {
					'left' : '0',
					'top' : '0',
					'position' : 'relative',
				}
				newPostInDom.appendTo(postsWrapper).css(initialStyle).animate(
					animateStyle,
					1000,
					function(){
						newPostInDom.addClass('just_added').css(finalStyle).prependTo(postsWrapper);
						if ($('#loadMore').is(':visible'))
							postsWrapper.find('.post').last().remove();
						// normalizeColumns defined in front_simple.js
						processedBlocksNum = 0;
						normalizeColumns(postsWrapper);
					}
				);
				$('html, body').animate({
					scrollTop: postsWrapper.offset().top - 35
				}, 700);
			}
			else {
				$('.addForm').hide();
				$('#thanks_message').show();
			}
		},
		error: function(r)
		{
			console.dir('unknown error');
			console.dir(r);
			$('#submitPost').removeClass('blocked');
		}
	});
}

/**
 * Copy of the php function utf8_decode()
 */
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