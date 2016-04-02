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

<div class="amazzingblog {$hook_name|escape:'html':'UTF-8'} {if $hook_name|escape:'html':'UTF-8' == 'displayHome'}theme-carousel js-blogCarousel{/if}">
{if $hook_name|escape:'html':'UTF-8' == 'displayHome'}
	<div class="container">
{/if}
{foreach $blocks as $block}
	{$settings = $block.settings}
	<div class="posts-block{if !empty($settings.class)} {$settings.class|escape:'html':'UTF-8'}{/if}">
		<h2 class="title_main_section">
			<a class="inner-title" href="{$all_link|escape:'html':'UTF-8'}" title="{l s='View all' mod='amazzingblog'}">
				{$block.title|escape:'html':'UTF-8'}
			</a>
		</h2>		
		{if !empty($settings.all_link)}
			<a class="btn btn-default post-viewall" href="{$all_link|escape:'html':'UTF-8'}" title="{l s='View all' mod='amazzingblog'}">{l s='View all' mod='amazzingblog'}</a>
		{/if}
		{include file="../front/post-list.tpl" posts=$block.posts settings=$settings}		
	</div>
{/foreach}
{if $hook_name|escape:'html':'UTF-8' == 'displayHome'}
</div>
{/if}
</div>

