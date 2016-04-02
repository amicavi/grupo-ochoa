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

{include file='./breadcrumbs.tpl' current_item=$category.title}

<h2>{$category.title|escape:'html':'UTF-8'}</h2>
{if !empty($category.description)}
	<div class="category-description">
		{$category.description} {* can not be escaped *}
	</div>
{/if}
{if !empty($subcategories)}
	<h4>Subcategories:</h2>
	<div class="subcategories">
	{foreach $subcategories as $cat}
		<div class="subcategory"><a href="{$blog->getCategoryLink($cat.id_category|intval)}" title="{$cat.title|escape:'html':'UTF-8'}">{$cat.title|escape:'html':'UTF-8'}</a></div>
	{/foreach}
	</div>	
	<br>
	<br>
{/if}

{if $posts}
	<div class="dynamic-posts">
		{include file='./post-list.tpl' settings=$blog->general_settings}
	</div>
{/if}
