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

<div class="panel clearfix">
	<form action="" method="post">
	<h3>
		{l s='Page exceptions for %s' mod='custombanners' sprintf=$hook_name}
		<i class="icon-times hide-settings" title="{l s='Hide' mod='custombanners'}"></i>
	</h3>
	<div class="alert alert-info">
		{l s='Check pages, where this hook shouldn\'t be displayed' mod='custombanners'}
	</div>
	<div class="exc_panel" style="/*display:none;*/">
		<a href="#" class="chk-action checkall">{l s='Check all' mod='custombanners'}</a>
		<a href="#" class="chk-action uncheckall">{l s='Unheck all' mod='custombanners'}</a>
		<a href="#" class="chk-action invert">{l s='Invert selection' mod='custombanners'}</a>
		{foreach $settings item=exc_group}
			<div class="exc-group clearfix">
				<h4>{$exc_group.group_name|escape:'html':'UTF-8'}</h4>
				{foreach $exc_group.values key=controller item=checked}
					<label class="label-checkbox col-lg-3" title="{$controller|escape:'html':'UTF-8'}">
						<input type="checkbox" name="exceptions[]" value="{$controller|escape:'html':'UTF-8'}"{if $checked} checked="checked"{/if}> {$controller|escape:'html':'UTF-8'}
					</label>
				{/foreach}
			</div>
		{/foreach}
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