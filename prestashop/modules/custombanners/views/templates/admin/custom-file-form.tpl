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

<form action="" method="post">
	{if $shop_names}
	<div class="alert alert-info">
		{l s='This file will be saved for the following shops: %s' mod='custombanners' sprintf=$shop_names}
	</div>
	{/if}
	<div class="form-group">
		<textarea class="form-control textarea-code" rows="3" name="code">{$code|escape:'html':'UTF-8'}</textarea>
	</div>
	<div class="p-footer clearfix">
		<input type="hidden" name="file_type" value="{$type|escape:'html':'UTF-8'}">
		<button class="saveCustomFile btn btn-default pull-right">
			<i class="icon-save"></i>
			{l s='Save' mod='custombanners'}		
		</button>
	</div>
</form>
