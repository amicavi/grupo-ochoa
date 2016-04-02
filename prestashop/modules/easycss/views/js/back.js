/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

var ajax_action_path = window.location.href.split('#')[0]+'&ajax=1',
	blockAjax = false;

$(document).ready(function(){

	$(document).on('click', 'a[href="#"]', function(e){
		e.preventDefault();
	}).on('click', '.add-rule', function(){
		var $table = $(this).prev(),
			newHTML = $table.find('tr.blank').html();
		$table.append('<tr>'+newHTML+'</tr>');
	}).on('click', '.save-rule', function(){
		var $tr = $(this).closest('tr'),
			selector = $tr.find('.selector-input').val(),
			selector_prev = $tr.find('.selector-prev').val(),
			files = $tr.find('.img-file').prop('files'),
			data = new FormData();
		if (files.length){
			if (/^image/.test(files[0].type))
				data.append('img', files[0]);
			else
				alert(imgWarning);
		}
		data.append('selector', selector);
		data.append('selector_prev', selector_prev);
		$('.thrown-errors').remove();
		$tr.find('.icon-refresh').removeClass('hidden').siblings().addClass('hidden');
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=SaveRule',
			dataType : 'json',
			processData: false,
			contentType: false,
			data: data,
			success: function(r){
				console.dir(r);
				$tr.find('.icon-save').removeClass('hidden').siblings().addClass('hidden');
				if ('errors' in r) {
					$tr.find('td.img').append(utf8_decode(r.errors));
				} else {
					if (r.img)
						$tr.find('img').attr('src', r.img).removeClass('hidden');
					$tr.find('.selector-prev').val(selector);
					$.growl.notice({ title: '', message: savedTxt});
				}
			},
			error: function(r){
				console.warn(r.responseText);
			}
		});
	}).on('click', '.delete-rule', function(){
		if (!confirm(areYouSureTxt))
			return false;
		var $tr = $(this).closest('tr'),
			data = {selector: $tr.find('.selector-prev').val()};
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=DeleteRule',
			dataType : 'json',
			data: data,
			success: function(r){
				console.dir(r);
				if (r.deleted)
					$tr.fadeOut();
				else
					$.growl.error({ title: '', message: failedTxt});
			},
			error: function(r){
				console.warn(r.responseText);
			}
		});
	});
});

function utf8_decode(utfstr) {
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