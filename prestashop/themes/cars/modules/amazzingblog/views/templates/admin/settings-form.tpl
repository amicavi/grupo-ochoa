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
<form method="post" action="" class="form-horizontal">
	{foreach $settings as $k => $field}
	{if !empty($field.h)}
		<h4 class="subtitle">{$field.h|escape:'html':'UTF-8'}</h4>
	{/if}
	<div class="form-group{if !empty($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}">				
		<label class="control-label col-lg-3">
			{if !empty($field.tooltip)}
				<span class="label-tooltip" data-toggle="tooltip" title="{$field.tooltip|escape:'html':'UTF-8'}">
			{/if}
				{$field.display_name|escape:'html':'UTF-8'}
			{if !empty($field.tooltip)}
				</span>
			{/if}
		</label>
		<div class="col-lg-7">
		{if empty($field.multilang)}
			{include file="./field.tpl" field=$field k=$k}
		{else}				
			{include file="./multilang-field.tpl" field=$field k=$k}
		{/if}
		</div>
		{if !empty($field.regenerate)}
			<div class="col-lg-1">				
				<div class="progress hidden">
				  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
				  aria-valuemin="0" aria-valuemax="100" style="width:0%">					
				  </div>
				</div>
				<a href="#" class="regenerate-thumbnails" data-type="{$k|escape:'html':'UTF-8'}">{l s='Regenerate' mod='amazzingblog'}</a>
			</div>
		{/if}
	</div>
	{/foreach}
	<div class="col-lg-12">
		<button class="btn btn-default save"><i class="icon-save"></i> {l s='Save' mod='amazzingblog'}</button>
	</div>
</form>
