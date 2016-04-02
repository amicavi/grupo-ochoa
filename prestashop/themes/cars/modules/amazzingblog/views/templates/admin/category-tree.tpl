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

{function renderCategoryLevel children=''}	
	{foreach $children as $c} 
		<div class="cat-level{if $c.level_depth} child{else} root{if !empty($class)} {$class|escape:'html':'UTF-8'}{/if}{/if}">
			<label>
				{if $type == 'radio' || $type == 'checkbox'}
					<input type="{$type|escape:'html':'UTF-8'}" name="{$name|escape:'html':'UTF-8'}" value="{$c.id_category|intval}"{if isset($checked[$c.id_category])} checked{/if}>
				{/if}
				{$c.title|escape:'html':'UTF-8'}				
			</label>
			{if $type == 'full'}				
				<a href="#" class="act editCategory" data-toggle="modal" data-target="#dynamic-popup" data-id="{$c.id_category|intval}">{l s='edit' mod='amazzingblog'}</a>
				<a href="#" class="act addChild" data-toggle="modal" data-target="#dynamic-popup" data-parent="{$c.id_category|intval}">{l s='add child' mod='amazzingblog'}</a>
				{if $c.id_category != $root_id}
					<a href="#" class="act deleteCategory" data-id="{$c.id_category|intval}">{l s='delete' mod='amazzingblog'}</a>
				{/if}
			{/if}			
			{if !empty($c.children)}
				{renderCategoryLevel children = $c.children}
			{/if}			
		</div>			
	{/foreach}
{/function}

{function renderCategorySelectorLevel}
	{foreach $children as $c}
		<option value="{$c.id_category|intval}"{if $c.id_category == $checked} selected{/if}>{for $i=0 to $c.level_depth}-{/for}{$c.title|escape:'html':'UTF-8'}</option>
		{if !empty($c.children)}
			{renderCategorySelectorLevel children = $c.children}
		{/if}
	{/foreach}
{/function}

{if $type == 'select'}	
	<select name="{$name|escape:'html':'UTF-8'}" class="cat-selector{if !empty($class)} {$class|escape:'html':'UTF-8'}{/if}">
		{if $checked == ['-']}<option value="-">-</option>{/if}
		{renderCategorySelectorLevel children=$category_tree}
	</select>
{else}	
	{renderCategoryLevel children = $category_tree}
	{if $type == 'checkbox'}
		<label class="control-label" for="id_category_default">{l s='Default category' mod='amazzingblog'}</label>
		<select name="id_category_default" id="id_category_default">
			{foreach $checked as $id_cat => $cat_name}
				<option value="{$id_cat|intval}"{if $id_cat == $id_category_default} selected{/if}>{$cat_name|escape:'html':'UTF-8'}</option>
			{/foreach}
		</select>
	{/if}	
{/if}
