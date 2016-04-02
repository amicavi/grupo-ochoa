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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $banners}
<div class="custombanners row {$hook_name|escape:'html':'UTF-8'}" data-hook="{$hook_name|escape:'html':'UTF-8'}">
	<div class="banners-in-carousel" style="display:none;">
		<div class="carousel" data-settings="{$carousel_settings|escape:'html':'UTF-8'}">{* filled dynamically *}</div>
	</div>
	<div class="banners-one-by-one">
		<div class="row">
		{foreach $banners item=banner}
			<div class="banner-item{if !empty($banner.class)} {$banner.class|escape:'html':'UTF-8'}{/if}{if $banner.in_carousel == 1} in_carousel{/if}">
				<div class="banner-item-content">
				{if !empty($banner.img)}
					{if !empty($banner.link.href)}
					<a href="{$banner.link.href|escape:'html':'UTF-8'}"{if isset($banner.link._blank)} target="_blank"{/if}>
					{/if}
						<img src="{$banner.img|escape:'html':'UTF-8'}"{if isset($banner.title)} title="{$banner.title|escape:'html':'UTF-8'}"{/if} class="banner-img">
					{if !empty($banner.link.href)}
					</a>
					{/if}
				{/if}
				{if !empty($banner.html)}
					<div class="custom-html">
						{$banner.html|escape:'html':'UTF-8'}{* can not be escaped *}
					</div>
				{/if}
				</div>
			</div>
		{/foreach}
		</div>
	</div>
</div>
{/if}