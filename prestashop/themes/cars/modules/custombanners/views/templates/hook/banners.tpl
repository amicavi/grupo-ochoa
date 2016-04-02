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
{if $banners}
<div class="custombanners {$hook_name|escape:'html':'UTF-8'} wow {if $page_name == 'index' && $hook_name|escape:'html':'UTF-8' != 'displayCustomBanners2'}fadeInUp{/if} {if $hook_name|escape:'html':'UTF-8' == 'displayCustomBanners2'}slideInRight{/if}" data-wow-duration="1s" data-wow-offset="100" data-hook="{$hook_name|escape:'html':'UTF-8'}">
	{if $hook_name|escape:'html':'UTF-8' == 'displayCustomBanners2'}
		<h2 class="title_main_section circle-title"><span class="inner-title">{l s='Partners about us' mod='custombanners'}</span></h2>
	{/if}
	<div class="banners-in-carousel" style="display:none;">
		<div class="carousel" data-settings="{$carousel_settings|escape:'html':'UTF-8'}">{* filled dynamically *}</div>
	</div>
	<div class="banners-one-by-one clearfix">
		{if $hook_name|escape:'html':'UTF-8' == 'displayCustomBanners2'}
		<div class="tabs_list col-md-6 col-xs-12 pull-right">
			<div class="row">
			<ul>
				{foreach name=items from=$banners item=banner}
					<li class="addition_{$smarty.foreach.items.iteration} {if $smarty.foreach.items.iteration == 1}active{/if}">
						<a href="#addition_item{$smarty.foreach.items.iteration}" role="tab" data-toggle="tab">
							{if !empty($banner.img)}
								<img src="{$banner.img|escape:'html':'UTF-8'}"{if isset($banner.title)} title="{$banner.title|escape:'html':'UTF-8'}"{/if} class="img-responsive">
							{elseif $banner.title}
								{$banner.title}
							{else}
								{$smarty.foreach.items.iteration} {l s ='Tab' mod='custombanners'}
							{/if}
						</a>
					</li>
				{/foreach}
			</ul>
			</div>
		</div>
		<div class="tab-content tabs_additional clearfix col-md-6 col-xs-12">
		{/if}
		{foreach name=items from=$banners item=banner}
			<div {if $hook_name|escape:'html':'UTF-8' == 'displayCustomBanners2'} role="tabpanel" id="addition_item{$smarty.foreach.items.iteration}"{/if} class="banner-item {if !empty($banner.class)} {$banner.class|escape:'html':'UTF-8'}{/if}{if $banner.in_carousel == 1} in_carousel{/if} {if $hook_name|escape:'html':'UTF-8' == 'displayCustomBanners2'}{if $smarty.foreach.items.iteration == 1}active{/if} tab-pane {/if}" {if $hook_name|escape:'html':'UTF-8' == 'displayHome' && (!empty($banner.img))}style="background-image:url({$banner.img|escape:'html':'UTF-8'});" data-stellar-background-ratio="0.02"{/if}>
				{if $hook_name|escape:'html':'UTF-8' != 'displayHome'}
				{if !empty($banner.img)}
					{if !empty($banner.link.href)}
						<a href="{$banner.link.href|escape:'html':'UTF-8'}"{if isset($banner.link._blank)} target="_blank"{/if}>
					{/if}
				{/if}
				{/if}
				<div class="banner-item-content clearfix">
					{if !empty($banner.img)}
						<img src="{$banner.img|escape:'html':'UTF-8'}"{if isset($banner.title)} title="{$banner.title|escape:'html':'UTF-8'}"{/if} class="banner-img">
					{/if}
				{if !empty($banner.html)}
					<div class="custom-html">
						{$banner.html}
					</div>
				{/if}
				</div>
					{if !empty($banner.img)}
						{if !empty($banner.link.href)}
						</a>
						{/if}
					{/if}
			</div>
		{/foreach}
		{if $hook_name|escape:'html':'UTF-8' == 'displayCustomBanners2'}
			</div>
		{/if}
	</div>
</div>
{/if}