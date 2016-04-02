{*
* 2007-2015 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="item clearfix {$block.hook_name|escape:'html':'UTF-8'}" data-id="{$block.id_block|intval}">
<form method="post" action="" class="form-horizontal">
	<input type="hidden" name="id_block" value="{$block.id_block|intval}">		
	<input type="hidden" name="active" value="{$block.active|intval}">	
	<input type="hidden" name="hook_name" value="{$block.hook_name|escape:'html':'UTF-8'}">	
	{include file='./item-header.tpl' item=$block type='block'}
	{if !empty($full)}	
	<div class="details" style="display:none;">
		{foreach $block.editable_fields as $k => $field}
		<div class="form-group {if !empty($field.class)}{$field.class|escape:'html':'UTF-8'}{/if}">				
			<label class="control-label col-lg-3">{$field.display_name|escape:'html':'UTF-8'}</label>
			<div class="col-lg-7">
			{if empty($field.multilang)}
				{include file="./field.tpl" field=$field k=$k}
			{else}				
				{include file="./multilang-field.tpl" field=$field k=$k}
			{/if}
			</div>
		</div>		
		{/foreach}
		<div class="col-lg-12">
			<button class="btn btn-default save"><i class="icon-save"></i> {l s='Save' mod='amazzingblog'}</button>
		</div>
	</div>
	{/if}
</form>
</div>