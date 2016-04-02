/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

$(document).ready(function(){
	var sharing_name = document.title,
		sharing_url = document.location.href;		
	
	tinymce.init({		
		selector: '#new-comment-content',
		plugins: 'bbcode paste emoticons',
		toolbar1: 'bold italic underline emoticons',
		menubar: false,
		statusbar: false,
		paste_as_text: true,
		forced_root_block: false,
	});
	
	$(document).on('click', '.social-share', function(e){
		e.preventDefault();
		var network = $(this).data('network');
		var popupLink = false;
		switch(network)
		{
			case 'twitter':
				popupLink = 'https://twitter.com/intent/tweet?text='+sharing_name+' '+encodeURIComponent(sharing_url);
			break;
			case 'facebook':
				popupLink = 'http://www.facebook.com/sharer.php?u='+sharing_url;
			break;
			case 'google-plus':
				popupLink = 'https://plus.google.com/share?url='+sharing_url;
			break;
			case 'vk':
				popupLink = 'http://vk.com/share.php?url='+sharing_url;
			break;
			case 'ok':
				popupLink = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.url='+sharing_url;
			break;
		}
		if (popupLink)
			window.open(popupLink, 'sharertwt', 'toolbar=0,status=0,width=640,height=445');
	}).on('click', '.edit-name', function(e){
		e.preventDefault();
		$(this).closest('.field').addClass('show-input');
	}).on('click', '.edit-avatar', function(e){
		e.preventDefault();
		$(this).closest('.user-avatar').find('input[type="file"]').click();
	}).on('change', '.avatar-file', function(e){
		var $el = $(this);		
		var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader)
			return;
        if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onloadend = function(){
				$el.closest('.user-avatar').find('.avatar-img').css('background-image', 'url('+this.result+')');
            }
        }
	}).on('submit', '.new-comment', function(e){
		e.preventDefault();
		var $form = $(this),
			formData = new FormData($form[0]);
		formData.append('ajax', 1);
		$form.find('.red-border').removeClass('red-border');
		$form.find('.ajax-error, .thrown-errors').remove();
		$.ajax({
			type: 'POST',
			url: ajax_path,
			dataType : 'json',
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			success: function(r){
				console.dir(r);	
				if ('errors' in r){
					if (typeof r.errors === 'object'){
						for (var i in r.errors){
							$form.find('[name="'+i+'"]').parent().prepend('<div class="ajax-error alert aler-danger">'+utf8_decode(r.errors[i])+'</div>').children('input, .mce-tinymce').addClass('red-border');
						}
					}
					else
						$form.prepend(utf8_decode(r.errors));
				}
				else if (r.new_comment_html) {
					$('.comments-list').append(utf8_decode(r.new_comment_html));
					$('#new-comment-content').val('');
					tinyMCE.get('new-comment-content').setContent('');
					sendNotification(r.id_comment);
				}
			},
			error: function(r){							
				console.warn(r.responseText);				
			}
		});
	});
	
	function sendNotification(id_comment) {
		$.ajax({
			type: 'POST',
			url: ajax_path,
			dataType : 'json',
			data: {
				ajax: 1,
				action: 'SendNotification',
				id_comment: id_comment,
			},						
			success: function(r){
				console.dir(r);					
			},
			error: function(r){							
				console.warn(r.responseText);				
			}
		});
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