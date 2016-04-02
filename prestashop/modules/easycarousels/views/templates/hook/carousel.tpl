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

{*** NOTE: Main layout starts after functions. ***}

{function displayManufacturerDetails item = ''}
	<a class="item-name" href="{$link->getManufacturerLink($item.id)|escape:'html':'UTF-8'}" title="{l s='More about %s' sprintf=$item.name mod='easycarousels'}">
		<img class="replace-2x img-responsive" src="{$item.image_url|escape:'html':'UTF-8'}" alt="{$item.name|escape:'html':'UTF-8'}"/>
		{$item.name|escape:'html':'UTF-8'}
	</a>
{/function}

{function displaySupplierDetails item = ''}
	<a class="item-name" href="{$link->getSupplierLink($item.id)|escape:'html':'UTF-8'}" title="{l s='More about %s' sprintf=$item.name mod='easycarousels'}">
		<img class="replace-2x img-responsive" src="{$item.image_url|escape:'html':'UTF-8'}" alt="{$item.name|escape:'html':'UTF-8'}"/>
		{$item.name|escape:'html':'UTF-8'}
	</a>
{/function}

{function displayProductDetails item = '' settings = ''}
	{$settings = $settings.tpl}
	<div class="product-container{if !empty($settings.price)} has-price{/if}{if !empty($settings.add_to_cart) || !empty($settings.view_more)} has-buttons{/if}" itemscope itemtype="http://schema.org/Product">
	<div class="left-block">
		<div class="product-image-container">
			<a class="product_img_link"	href="{$item.link|escape:'html':'UTF-8'}" title="{$item.name|escape:'html':'UTF-8'}" itemprop="url">
				<img class="replace-2x img-responsive" src="{$link->getImageLink($item.link_rewrite, $item.id_image, $settings.image_type)|escape:'html':'UTF-8'}" alt="{if !empty($item.legend)}{$item.legend|escape:'html':'UTF-8'}{else}{$item.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($item.legend)}{$item.legend|escape:'html':'UTF-8'}{else}{$item.name|escape:'html':'UTF-8'}{/if}" itemprop="image" />
			</a>
			{if !empty($settings.quick_view)}
				<div class="quick-view-wrapper-mobile">
					<a class="quick-view-mobile" href="{$item.link|escape:'html':'UTF-8'}" rel="{$item.link|escape:'html':'UTF-8'}">
						<i class="icon-eye-open"></i>
					</a>
				</div>
				<a class="quick-view" href="{$item.link|escape:'html':'UTF-8'}" rel="{$item.link|escape:'html':'UTF-8'}">
					<span>{l s='Quick view' mod='easycarousels'}</span>
				</a>
			{/if}
			{if ($settings.price && !$PS_CATALOG_MODE AND ((isset($item.show_price) && $item.show_price) || (isset($item.available_for_order) && $item.available_for_order)))}
				<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					{if isset($item.show_price) && $item.show_price && !isset($restricted_country_mode)}
						<span itemprop="price" class="price product-price">
							{if !$priceDisplay}{convertPrice price=$item.price}{else}{convertPrice price=$item.price_tax_exc}{/if}
						</span>
						<meta itemprop="priceCurrency" content="{$currency->iso_code|escape:'html':'UTF-8'}" />
						{if isset($item.specific_prices) && $item.specific_prices && isset($item.specific_prices.reduction) && $item.specific_prices.reduction > 0}
							{if !empty($settings.displayProductPriceBlock)}
								{hook h="displayProductPriceBlock" product=$item type="old_price"}
							{/if}
							<span class="old-price product-price">
								{displayWtPrice p=$item.price_without_reduction}
							</span>
							{if $item.specific_prices.reduction_type == 'percentage'}
								<span class="price-percent-reduction">-{($item.specific_prices.reduction * 100)|escape:'html':'UTF-8'}%</span>
							{/if}
						{/if}
						{if !empty($settings.displayProductPriceBlock)}
							{hook h="displayProductPriceBlock" product=$item type="price"}
							{hook h="displayProductPriceBlock" product=$item type="unit_price"}
						{/if}
					{/if}
				</div>
			{/if}
			{if !empty($settings.stickers)}
				{if isset($item.new) && $item.new == 1}
					<a class="new-box" href="{$item.link|escape:'html':'UTF-8'}">
						<span class="new-label">{l s='New' mod='easycarousels'}</span>
					</a>
				{/if}
				{if isset($item.on_sale) && $item.on_sale && isset($item.show_price) && $item.show_price && !$PS_CATALOG_MODE}
					<a class="sale-box" href="{$item.link|escape:'html':'UTF-8'}">
						<span class="sale-label">{l s='Sale!' mod='easycarousels'}</span>
					</a>
				{/if}
			{/if}
		</div>
		{if !empty($settings.displayProductDeliveryTime)}
			{hook h="displayProductDeliveryTime" product=$item}
		{/if}
		{if !empty($settings.displayProductPriceBlock)}
			{hook h="displayProductPriceBlock" product=$item type="weight"}
		{/if}
	</div>
	<div class="right-block">
		{if !empty($settings.title)}
			<h5 itemprop="name" class="{if !empty($settings.title_one_line)}one_line{/if}">
				{if isset($item.pack_quantity) && $item.pack_quantity}{$item.pack_quantity|intval|cat:' x '}{/if}
				<a class="product-name" href="{$item.link|escape:'html':'UTF-8'}" title="{$item.name|escape:'html':'UTF-8'}" itemprop="url" >
					{$item.name|truncate:$settings.title:'...'|escape:'html':'UTF-8'}
				</a>
			</h5>
		{/if}
		{if !empty($settings.reference)}
			<p class="product-reference">
				{$item.reference|escape:'html':'UTF-8'}
			</p>
		{/if}
		{if !empty($settings.product_cat)}
			<a class="cat-name" href="{$link->getCategoryLink($item.id_category_default, $item.category)|escape:'html':'UTF-8'}" title="{$item.cat_name|escape:'html':'UTF-8'}">{$item.cat_name|truncate:45:'...'|escape:'html':'UTF-8'}</a>
		{/if}
		{if !empty($settings.product_man) && $item.id_manufacturer}
			<a class="man-name" href="{$link->getManufacturerLink($item.id_manufacturer)|escape:'html':'UTF-8'}" title="{$item.man_name|escape:'html':'UTF-8'}">{$item.man_name|truncate:45:'...'|escape:'html':'UTF-8'}</a>
		{/if}
		{if !empty($settings.displayProductListReviews)}
			{hook h='displayProductListReviews' product=$item hide_thumbnails=empty($settings.thumbnails)|intval}
		{/if}
		{if ($settings.price && !$PS_CATALOG_MODE && ((isset($item.show_price) && $item.show_price) || (isset($item.available_for_order) && $item.available_for_order)))}
		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price main">
			{if isset($item.show_price) && $item.show_price && !isset($restricted_country_mode)}
				<span itemprop="price" class="price product-price">
					{if !$priceDisplay}{convertPrice price=$item.price}{else}{convertPrice price=$item.price_tax_exc}{/if}
				</span>
				<meta itemprop="priceCurrency" content="{$currency->iso_code|escape:'html':'UTF-8'}" />
				{if isset($item.specific_prices) && $item.specific_prices && isset($item.specific_prices.reduction) && $item.specific_prices.reduction > 0}
					{if !empty($settings.displayProductPriceBlock)}
						{hook h="displayProductPriceBlock" product=$item type="old_price"}
					{/if}
					<span class="old-price product-price">
						{displayWtPrice p=$item.price_without_reduction}
					</span>
					{if !empty($settings.displayProductPriceBlock)}
						{hook h="displayProductPriceBlock" id_product=$item.id_product type="old_price"}
					{/if}
					{if $item.specific_prices.reduction_type == 'percentage'}
						<span class="price-percent-reduction">-{($item.specific_prices.reduction * 100)|escape:'html':'UTF-8'}%</span>
					{/if}
				{/if}
				{if !empty($settings.displayProductPriceBlock)}
					{hook h="displayProductPriceBlock" product=$item type="price"}
					{hook h="displayProductPriceBlock" product=$item type="unit_price"}
				{/if}
			{/if}
		</div>
		{/if}
		<div class="button-container">
			{if !empty($settings.view_more)}
				<a itemprop="url" class="button lnk_view btn btn-default" href="{$item.link|escape:'html':'UTF-8'}" title="{l s='View' mod='easycarousels'}">
					<span>{if (isset($item.customization_required) && $item.customization_required)}{l s='Customize' mod='easycarousels'}{else}{l s='More' mod='easycarousels'}{/if}</span>
				</a>
			{/if}
		</div>
		{if isset($item.color_list)}
			<div class="color-list-container">{$item.color_list|escape:'html':'UTF-8'}{* cannot be escaped *}</div>
		{/if}
		{if ($settings.stock && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && ((isset($item.show_price) && $item.show_price) || (isset($item.available_for_order) && $item.available_for_order)))}
			{if isset($item.available_for_order) && $item.available_for_order && !isset($restricted_country_mode)}
				<span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="availability">
					{if ($item.allow_oosp || $item.quantity > 0)}
						<span class="{if $item.quantity <= 0 && !$item.allow_oosp}out-of-stock{else}available-now{/if}">
							<link itemprop="availability" href="http://schema.org/InStock" />{if $item.quantity <= 0}{if $item.allow_oosp}{if !empty($item.available_later)}{$item.available_later|escape:'html':'UTF-8'}{else}{l s='In Stock' mod='easycarousels'}{/if}{else}{l s='Out of stock' mod='easycarousels'}{/if}{else}{if !empty($item.available_now)}{$item.available_now|escape:'html':'UTF-8'}{else}{l s='In Stock' mod='easycarousels'}{/if}{/if}
						</span>
					{elseif (isset($item.quantity_all_versions) && $item.quantity_all_versions > 0)}
						<span class="available-dif">
							<link itemprop="availability" href="http://schema.org/LimitedAvailability" />{l s='Product available with different options' mod='easycarousels'}
						</span>
					{else}
						<span class="out-of-stock">
							<link itemprop="availability" href="http://schema.org/OutOfStock" />{l s='Out of stock' mod='easycarousels'}
						</span>
					{/if}
				</span>
			{/if}
		{/if}
	</div>
	</div>
{/function}

{function generateCarousel hook_name = '' carousel = '' in_tabs = false}
	<div id="{$carousel.type|cat:'_'|cat:$carousel.id_carousel|escape:'html':'UTF-8'}" class="easycarousel {$carousel.settings.tpl.custom_class|escape:'html':'UTF-8'}{if $in_tabs} tab-pane{else} carousel_block{/if}{if $smarty.foreach.cit_details.first} active{/if}{if !$carousel.name && $carousel.settings.carousel.n} nav_without_name{/if}">
		{if !$in_tabs && $carousel.name}
			<h3 class="title_block carousel_title">{$carousel.name|escape:'html':'UTF-8'}</h3>
		{/if}
		<div class="block_content">
			<div class="c_wrapper" data-settings="{$carousel.settings.carousel|json_encode|escape:'html':'UTF-8'}">
				{foreach from=$carousel.items item=item}
					<div class="c_col">
						{foreach from=$item item=i key=k}
							<div class="c_item">
								{if $carousel.type == 'manufacturers'}
									{displayManufacturerDetails item = $i}
								{else if $carousel.type == 'suppliers'}
									{displaySupplierDetails item = $i}
								{else}
									{displayProductDetails item = $i settings = $carousel.settings}
								{/if}
							</div>
						{/foreach}
					</div>
				{/foreach}
			</div>
		</div>
		{if isset($carousel.view_all_link) && $carousel.view_all_link}
			<div class="text-center">
				<a href="{$carousel.view_all_link|escape:'html':'UTF-8'}" class="view_all">{l s='View all' mod='easycarousels'}</a>
			</div>
		{/if}
	</div>
{/function}

<div class="easycarousels {$display_settings.custom_class|escape:'html':'UTF-8'}">
	{if $carousels_in_tabs|count > 0}
	<div class="in_tabs clearfix{if $display_settings.compact_tabs} compact_on{/if}">
		<ul id="{$hook_name|escape:'html':'UTF-8'}_easycarousel_tabs" class="nav nav-tabs easycarousel_tabs closed" data-tabs="tabs" role="tablist">
			{foreach from=$carousels_in_tabs item=carousel name=cit}
				{if $smarty.foreach.cit.first}
					<li class="responsive_tabs_selection title_block">
						<a href="" title="{$carousel.name|escape:'html':'UTF-8'}" onclick="event.preventDefault();">
							<span class="selection">{$carousel.name|escape:'html':'UTF-8'}</span>
							<i class="icon icon-chevron-down"></i>
						</a>
					</li>
				{/if}
				<li class="{if $smarty.foreach.cit.first}first active{/if} carousel_title">
					<a data-toggle="tab" href="#{$carousel.type|cat:'_'|cat:{$carousel.id_carousel|escape:'html':'UTF-8'}">{if isset($carousel.name)}{$carousel.name|escape:'html':'UTF-8'}{/if}</a>
				</li>
			{/foreach}
		</ul>
		<div class="tab-content">
		{foreach from=$carousels_in_tabs item=carousel name=cit_details}
			{generateCarousel hook_name = $hook_name carousel = $carousel in_tabs = true}
		{/foreach}
		</div>
	</div>
	{/if}
	{if $carousels_one_by_one|count > 0}
	<div class="one_by_one clearfix">
		{foreach from=$carousels_one_by_one item = carousel}
			{generateCarousel hook_name = $hook_name carousel = $carousel}
		{/foreach}
	</div>
	{/if}
</div>