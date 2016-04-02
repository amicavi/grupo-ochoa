{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{function switcher_option label='' tooltip='' id='' input_name='' current_value='' class='' child=''}
	<div class="form-group {$class|escape:'html':'UTF-8'}">
		<label class="control-label col-lg-7">
			{if $tooltip == ''}
				{$label|escape:'html':'UTF-8'}
			{else}
				<span class="label-tooltip" data-toggle="tooltip" title="{$tooltip|escape:'html':'UTF-8'}">
					{$label|escape:'html':'UTF-8'}
				</span>
			{/if}
		</label>
		<div class=" col-lg-5">		
			<span class="switch prestashop-switch">
				<input type="radio" id="{$id|escape:'html':'UTF-8'}" name="{$input_name|escape:'html':'UTF-8'}" value="1"{if $current_value} checked="checked"{/if}{if $child} data-child="{$child|escape:'html':'UTF-8'}"{/if}>
				<label for="{$id|escape:'html':'UTF-8'}">
					{l s='Yes' mod='custombanners'}
				</label>
				<input type="radio" id="{$id|escape:'html':'UTF-8'}_0" name="{$input_name|escape:'html':'UTF-8'}" value="0" {if !$current_value}checked="checked"{/if}{if $child} data-child="{$child|escape:'html':'UTF-8'}"{/if}>
				<label for="{$id|escape:'html':'UTF-8'}_0">
					{l s='No' mod='custombanners'}
				</label>
				<a class="slide-button btn"></a>
			</span>
		</div>
	</div>
{/function}
<div class="bootstrap panel clearfix">
<form method="post" action="" class="form-horizontal">
	<h3>
		{l s='Carousel settings for %s' mod='custombanners' sprintf=$hook_name}
		<i class="icon-times hide-settings" title="{l s='Hide' mod='custombanners'}"></i>
	</h3>
	<div class="col-lg-5">
		{switcher_option
			label = {l s='Display pagination?' mod='custombanners'}
			tooltip = ''
			id = 'show_pagination'
			input_name = 'settings[p]'
			current_value = {$settings.p|intval}		
		}
		{switcher_option
			label={l s='Display navigation arrows?' mod='custombanners'}
			tooltip=''
			id = 'show_navigation'
			input_name = 'settings[n]'
			current_value = {$settings.n|intval}
			child = 'arrows_on_hover'
		}
		<div class="form-group">
			<label class="control-label col-lg-7">
				{l s='Show arrows' mod='custombanners'}
			</label>
			<div class=" col-lg-5">		
				<select name="settings[n_hover]" id="arrows_on_hover">
					<option value="0"{if $settings.n_hover == 0} selected="selected"{/if}>{l s='All the time' mod='custombanners'}</option>
					<option value="1"{if $settings.n_hover == 1} selected="selected"{/if}>{l s='Only on hover' mod='custombanners'}</option>
				</select>
			</div>
		</div>
		{switcher_option
			label={l s='Enable autoplay?' mod='custombanners'}
			tooltip=''
			id = 'autoplay'
			input_name = 'settings[a]'
			current_value = {$settings.a|intval}
		}		
		<div class="form-group">
			<label class="control-label col-lg-7">
				{l s='Animation speed (ms)' mod='custombanners'}				
			</label>
			<div class=" col-lg-5">		
				<input type="text" name="settings[s]" value="{$settings.s|intval}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-7">
				<span class="label-tooltip" data-toggle="tooltip" title="{l s='Number of slides moved per transition. Set 0 to move all visible slides'  mod='custombanners'}">
					{l s='Slides moved per transition' mod='custombanners'}
				</span>
			</label>
			<div class=" col-lg-5">		
				<input type="text" name="settings[m]" value="{$settings.m|intval}">
			</div>
		</div>
	</div>
	<div class="col-lg-7">
		<div class="form-group">
			<label class="control-label col-lg-7">
				{l s='Visible items (default)' mod='custombanners'}
			</label>
			<div class=" col-lg-1">		
				<input type="text" name="settings[i]" value="{$settings.i|intval}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-7">
				<span class="label-tooltip" data-toggle="tooltip" title="{l s='If display width is less than 1200px, this number of items will be visible.'  mod='custombanners'}">
					{l s='Visible items on displays < 1200px' mod='custombanners'}
				</span>
			</label>
			<div class=" col-lg-1">		
				<input type="text" name="settings[i_1200]" value="{$settings.i_1200|intval}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-7">
				{l s='Visible items on displays < 992px' mod='custombanners'}
			</label>
			<div class=" col-lg-1">		
				<input type="text" name="settings[i_992]" value="{$settings.i_992|intval}">
			</div>
		</div>			
		<div class="form-group">
			<label class="control-label col-lg-7">
				{l s='Visible items on displays < 768px' mod='custombanners'}		
			</label>
			<div class=" col-lg-1">		
				<input type="text" name="settings[i_768]" value="{$settings.i_768|intval}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-lg-7">
				{l s='Visible items on displays < 480px' mod='custombanners'}			
			</label>
			<div class=" col-lg-1">		
				<input type="text" name="settings[i_480]" value="{$settings.i_480|intval}">
			</div>			
		</div>
	</div>	
	<div class="p-footer clearfix">	
		<input type="hidden" name="hook_name" value="{$hook_name|escape:'html':'UTF-8'}">
		<input type="hidden" name="settings_type" value="{$settings_type|escape:'html':'UTF-8'}">
		<button class="saveHookSettings btn btn-default">
			<i class="process-icon-save"></i>
			{l s='Save' mod='custombanners'}		
		</button>
	</div>
</form>	
</div>
<script type="text/javascript">
	function showChildFields($el){	
		if ($el.val() == 1)
			$('#settings-content #'+$el.data('child')).closest('.form-group').show();
		else
			$('#settings-content #'+$el.data('child')).closest('.form-group').hide();	
	}
	$(document).on('change', 'input[data-child]', function(){		
		showChildFields($(this));		
	});
	$('input[data-child]:checked').each(function(){
		showChildFields($(this));
	});	
</script>