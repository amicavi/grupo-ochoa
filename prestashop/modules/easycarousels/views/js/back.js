/**
*  @author    Amazzing
*  @copyright Amazzing
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*
*/

var ajax_action_path;
var blockAjax = false;

$(document).ready(function(){

	ajax_action_path = window.location.href.replace('#', '')+'&ajax=1';
	activateSortable();

	$(document).on('change', '.hookSelector', function(){
		var hook_name = $(this).val();
		$('.hook_content#'+hook_name).addClass('active').siblings().removeClass('active');
		$('#settings_content').hide().html('');
		$('.callSettings').removeClass('active');
	});

	$('.hookSelector').change();

	$(document).on('click', '.callSettings', function(e){
		e.preventDefault();
		$el = $(this);

		if ($el.hasClass('active'))
		{
			$('#settings-content').slideUp(function(){
				$(this).html('');
				$('.callSettings').removeClass('active');
			});
			return;
		}

		$('#settings-content').hide().html('');
		$('.callSettings').removeClass('active');
		var settings_type = $(this).data('settings');
		var hook_name = $(this).closest('form').find('.hookSelector').val();
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=CallSettingsForm&settings_type='+settings_type+'&hook_name='+hook_name,
			dataType : 'json',
			success: function(r)
			{
				console.dir(r);
				if ('form_html' in r){
					$('#settings-content').html(utf8_decode(r.form_html)).slideDown().tooltip({selector: '.label-tooltip'});
					$el.addClass('active');
				}
			},
			error: function(r)
			{
				$('#settings-content').hide().html('');
				$('.callSettings').removeClass('active');
				console.warn(r.responseText);
			}
		});
	});

	$(document).on('click', '.chk-action', function(e){
		e.preventDefault();
		var $checkboxes = $(this).closest('#settings_content').find('input[type="checkbox"]');
		if ($(this).hasClass('checkall')){
			$checkboxes.each(function(){
				$(this).prop('checked', true);
			});
		}
		else if ($(this).hasClass('uncheckall')){
			$checkboxes.each(function(){
				$(this).prop('checked', false);
			});
		}
		else if ($(this).hasClass('invert')){
			$checkboxes.each(function(){
				$(this).prop('checked', !$(this).prop('checked'));
			});
		}
	});

	$(document).on('click', '.saveHookSettings', function(e){
		e.preventDefault();
		var hook_name = $(this).attr('data-hook');
		var data = $(this).closest('form').serialize();
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=SaveHookSettings&'+data,
			dataType : 'json',
			success: function(r)
			{
				console.dir(r);
				if (r.saved){
					$('#settings-content').slideUp(function(){
						$('.callSettings').removeClass('active');
						$(this).html('');
						$.growl.notice({ title: '', message: savedTxt});
					});
				}
			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});
	
		$(document).on('click', '.hide-settings', function(){
		$('.callSettings.active').click();
	});

	$(document).on('click', '.chk-action', function(e){
		e.preventDefault();
		var $checkboxes = $(this).closest('#settings-content').find('input[type="checkbox"]');
		if ($(this).hasClass('checkall')){
			$checkboxes.each(function(){
				$(this).prop('checked', true);
			});
		}
		else if ($(this).hasClass('uncheckall')){
			$checkboxes.each(function(){
				$(this).prop('checked', false);
			});
		}
		else if ($(this).hasClass('invert')){
			$checkboxes.each(function(){
				$(this).prop('checked', !$(this).prop('checked'));
			});
		}
	});

	$(document).on('click', '.bulk-select, .bulk-unselect', function(e){
		e.preventDefault();
		var checked = $(this).hasClass('bulk-select');
		$('.carousel-item:visible .carousel-box').prop('checked', checked);
	});

	$(document).on('click', '[data-bulk-act]', function(e){
		e.preventDefault();
		$('.bulk-actions-error').remove();
		var ids = [];
		$('.carousel-box:checked').each(function(){
			ids.push($(this).val());
		});
		var act = $(this).data('bulk-act');
		if (act == 'delete' && ids.length && !confirm(areYouSureTxt))
			return;
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=BulkAction',
			dataType : 'json',
			data: {
				act : act,
				ids : ids
			},
			success: function(r)
			{
				if ('errors' in r){
					var err = '<div class="bulk-actions-error" style="display:none;">'+utf8_decode(r.errors)+'</div>';
					$('.bulk-actions').removeClass('open').before(err);
					$('.bulk-actions-error').slideDown();
				}
				else if (r.success){
					$.growl.notice({ title: '', message: r.reponseText});
					blockAjax = true;
					switch (act){
						case 'enable':
							for (var i in ids)
								$('.carousel-item[data-id="'+ids[i]+'"] .activateCarousel').addClass('action-enabled').removeClass('action-disabled').find('input').prop('checked', true);
						break;
						case 'disable':
							for (var i in ids)
								$('.carousel-item[data-id="'+ids[i]+'"] .activateCarousel').removeClass('action-enabled').addClass('action-disabled').find('input').prop('checked', false);
						break;
						case 'group_in_tabs':
						case 'ungroup':
							var checked = act == 'group_in_tabs';
							for (var i in ids)
								$('.carousel-item[data-id="'+ids[i]+'"] [name="in_tabs"]').prop('checked', checked);
						break;
						case 'delete':
							removeCarouselRows(ids);
						break;
					}
					blockAjax = false;
				}

			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});

	$(document).on('click', '.addCarousel', function(e){
		e.preventDefault();
		scrollUpAllCarousels();
		var $carousel_list = $(this).closest('.hook_content').find('.carousel_list');
		var hook_name = $(this).closest('.hook_content').attr('id');
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=CallCarouselForm&id_carousel=0&hook_name='+hook_name,
			dataType : 'json',
			success: function(r)
			{
				$newItem = $(utf8_decode(r));
				$newItem.appendTo($carousel_list).addClass('open').find('.carousel-details').slideDown().find('#carousel_type').change();
				$newItem.tooltip({selector: '.label-tooltip'});
				var carousels_num = $('#'+hook_name+' .carousel-item:visible').length;
				$('.hookSelector').find('option[value="'+hook_name+'"]').text(hook_name+' ('+carousels_num+')');
			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});

	$(document).on('click', '.activateCarousel', function(e){
		e.preventDefault();
		$checkbox = $(this).find('input[type="checkbox"]');
		$checkbox.prop('checked', !$checkbox.prop('checked')).change();
		if ($(this).closest('.carousel-item').attr('data-id') == 0)
			$(this).toggleClass('action-enabled action-disabled');
	});

	$(document).on('change', '.toggleable_param', function(e){
		var $parent = $(this).closest('.carousel-item');
		var id_carousel = $parent.attr('data-id');
		if (blockAjax || id_carousel == 0)
			return;
		var param_name = $(this).attr('name');
		var param_value = $(this).prop('checked') ? 1 : 0;

		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=ToggleParam&param_name='+param_name+'&param_value='+param_value+'&id_carousel='+id_carousel,
			dataType : 'json',
			success: function(r)
			{
				console.dir(r);
				if(r.success){
					$.growl.notice({ title: '', message: savedTxt});
					if (param_name == 'active')
						$parent.find('.activateCarousel').toggleClass('action-enabled action-disabled');
				}
				else
					$.growl.error({ title: '', message: failedTxt});

			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});

	$(document).on('click', '.editCarousel', function(e){
		e.preventDefault();
		scrollUpAllCarousels();
		var $item = $(this).closest('.carousel-item');
		id = $item.attr('data-id');
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=CallCarouselForm&id_carousel='+id,
			dataType : 'json',
			success: function(r)
			{
				// console.dir(r);
				var newItemHTML = $(utf8_decode(r)).html();
				$item.html(newItemHTML).addClass('open').find('.carousel-details').slideDown().find('#carousel_type').change();
				$item.tooltip({selector: '.label-tooltip'});
			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});

	$(document).on('click', '.scrollUp', function(e){
		e.preventDefault();
		scrollUpAllCarousels();
	});

	$(document).on('click', '.deleteCarousel', function(){
		if (!confirm(areYouSureTxt))
			return;
		var $item = $(this).closest('.carousel-item');
		id = $item.attr('data-id');
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=DeleteCarousel&id_carousel='+id,
			dataType : 'json',
			success: function(r)
			{
				if (r.deleted)
					removeCarouselRows(id);
			},
			error: function(r)
			{
				console.warn(r.responseText);
			}
		});
	});

	$(document).on('change', '#carousel_type', function(){

		// special options for products/manufacturers/suppliers
		var show_hide = ['product', 'manufacturer', 'supplier'];
		if ($(this).val() == 'manufacturers')
			show_hide = ['manufacturer', 'supplier', 'product'];
		else if ($(this).val() == 'suppliers')
			show_hide = ['supplier', 'manufacturer', 'product'];

		$('.'+show_hide[0]+'_option').removeClass('hidden');
		$('.'+show_hide[1]+'_option, .'+show_hide[2]+'_option').addClass('hidden');
		var img_type = $('select[name="tpl_settings[image_type]"] option.saved').not('.hidden').first().val();
		if (!img_type)
			img_type = $('select[name="tpl_settings[image_type]"] option').not('.hidden').first().val();
		$('select[name="tpl_settings[image_type]"]').val(img_type).change();

		// special options for some carousel types
		$('.special_option').addClass('hidden').closest('.special').hide();
		$('.special_option.'+$(this).val()).removeClass('hidden').closest('.special').show();

		// update name field if it is not saved yet
		var carousel_name = $.trim($(this).find('option:selected').text());
		$('input[data-saved="false"]').val(carousel_name);
	});

	$(document).on('click', '#saveCarousel', function(e){
		e.preventDefault();
		var $item = $(this).closest('.carousel-item');
		$item.find('.ajax_errors').slideUp().html('');
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=saveCarousel',
			data: {
				id_carousel: $item.attr('data-id'),
				hook_name: $item.closest('.hook_content').attr('id'),
				carousel_data: $item.find('form').serialize(),
			},
			dataType : 'json',
			success: function(r)
			{
				// console.dir(r);
				if ('errors' in r){
					$item.prepend(utf8_decode(r.errors));
					$('html, body').animate({
						scrollTop: $item.offset().top - 100
					}, 500);
					return;
				}
				else{
					$.growl.notice({ title: '', message: r.responseText});
					$item.find('form').slideUp(function(){
						$item.replaceWith(utf8_decode(r.updated_form_header));
					});
				}
			},
			error: function(r)
			{
				$item.find('.ajax_errors').slideDown().append('<div>'+r.responseText+'</div>');
			}
		});
	});
	
	$(document).on('click', '.lang_switcher', function(){
		var id_lang = $(this).attr('data-id-lang');
		$('.multilang').hide();
		$('.multilang.lang_'+id_lang).show();
	});

	$(document).on('click', '.importer .import', function(){
		$('input[name="carousels_data_file"]').click();
	}).on('change', 'input[name="carousels_data_file"]', function(){
		if (!this.files || this.files[0].type != 'text/plain')
			return;
		$('.importer .import i').toggleClass('icon-cloud-upload icon-refresh icon-spin');
		var data = new FormData();
		data.append($(this).attr('name'), $(this).prop('files')[0]);
		$.ajax({
			type: 'POST',
			url: ajax_action_path+'&action=ImportCarousels',
			dataType : 'json',
			processData: false,
			contentType: false,
			data: data,
			success: function(r)
			{
				if ('errors' in r)
					$('.importer').closest('.panel').before(utf8_decode(r.errors));
				else if ('upd_html' in r){
					$upd = $('<div>'+utf8_decode(r.upd_html)+'</div>');
					$('.all-carousels').replaceWith($upd.find('.all-carousels'));
					$('.all-carousels').find('.hookSelector').change();
					$('.all-carousels').before($upd.find('.module_confirmation'));
					activateSortable();
				}
				$('.importer .import i').toggleClass('icon-cloud-upload icon-refresh icon-spin');
			},
			error: function(r)
			{
				console.warn(r.responseText);
				$('.importer .import i').toggleClass('icon-cloud-upload icon-refresh icon-spin');
			}
		});
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
});

function scrollUpAllCarousels(){
	$('.carousel-item').removeClass('open').find('.carousel-details').slideUp(function(){$(this).html('')});
}

function removeCarouselRows(ids){
	if (!$.isArray(ids))
		ids = [ids];
	for (var i in ids){
		$('.carousel-item[data-id="'+ids[i]+'"]').fadeOut(function(){
			if (!$(this).closest('.carousel-item').next().length) {
				var hook_name = $('.hookSelector').val();
				var carousels_num = $('#'+hook_name+' .carousel-item:visible').length;
				$('.hookSelector').find('option[value="'+hook_name+'"]').text(hook_name+' ('+carousels_num+')');
			}
		});
	}
}

function activateSortable(){
	$('.carousel_list').sortable({
		placeholder: 'new_position_placeholder',
		handle: '.dragger',
		update: function(event, ui) {
			var ordered_ids = [];
			ui.item.closest('.carousel_list').find('.carousel-item').each(function(){
				ordered_ids.push($(this).attr('data-id'));
			});
			$.ajax({
				type: 'POST',
				url: ajax_action_path+'&action=UpdatePositionsInHook',
				dataType : 'json',
				data: {
					ordered_ids: ordered_ids,
				},
				success: function(r)
				{
					if('successText' in r)
						$.growl.notice({ title: '', message: r.successText});
				},
				error: function(r)
				{
					$.growl.notice({ title: '', message: r.responseText});
				}
			});
		}
	});
}

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