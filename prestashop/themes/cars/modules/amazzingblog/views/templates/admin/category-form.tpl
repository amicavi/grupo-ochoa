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

<div class="category-form details tab-content clearfix" data-resource="Category">
	<form method="post" action="" class="form-horizontal">		
		<input type="hidden" name="id_category" value="{$category.id_category|intval}">		
		<input type="hidden" name="active" value="{$category.active|intval}">		
		{foreach $category.editable_fields as $k => $field}
		<div class="form-group {if !empty($field.class)}{$field.class|escape:'html':'UTF-8'}{/if}">				
			<label class="control-label col-lg-2">{$field.display_name|escape:'html':'UTF-8'}</label>
			<div class="col-lg-10">
			{if $k == 'id_parent'}
				{if $category.id_category != $root_id}
					{include file="./category-tree.tpl" type = 'select' name = 'id_parent' checked = $field.value}
				{/if}
			{else if empty($field.multilang)}
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
	</form>
</div>