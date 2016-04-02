{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- MODULE Block specials -->
<div id="special_block_right" class="block products_block">
	<p class="title_block">
		<a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Specials' mod='blockspecials'}">
			{l s='Best price' mod='blockspecials'}
		</a>
	</p>
	<div class="block_content products-block clearfix grid">
	{if $special}
		<ul>
			<li class="clearfix item">
				<a class="wrap_scale products-block-image" href="{$special.link|escape:'html':'UTF-8'}">
					<img 
					class="replace-2x img-responsive" 
					src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'home_default')|escape:'html':'UTF-8'}" 
					alt="{$special.legend|escape:'html':'UTF-8'}" 
					title="{$special.name|escape:'html':'UTF-8'}" />
				</a>
				  {*{if !$PS_CATALOG_MODE}
							 {if $special.specific_prices}
								{assign var='specific_prices' value=$special.specific_prices}
								{if $specific_prices.reduction_type == 'percentage' && ($specific_prices.from == $specific_prices.to OR ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' <= $specific_prices.to && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $specific_prices.from))}
									<span class="tag price-percent-reduction"><span>-{$specific_prices.reduction*100|floatval}%</span></span>
								{/if}
							{/if}
				{/if}*}
				<div class="product-content clearfix">
					
					<h5>
						<a class="product-name" href="{$special.link|escape:'html':'UTF-8'}" title="{$special.name|escape:'html':'UTF-8'}">
							{$special.name|escape:'html':'UTF-8'}
						</a>
					</h5>
					{if isset($special.description_short) && $special.description_short}
						<p class="product-description">
							{$special.description_short|strip_tags:'UTF-8'|truncate:40}
						</p>
					{/if}
					<div class="price-box">
						{if !$PS_CATALOG_MODE}
							<span class="old-price">
								{if !$priceDisplay}
									{displayWtPrice p=$special.price_without_reduction}{else}{displayWtPrice p=$priceWithoutReduction_tax_excl}
								{/if}
						  
							</span>
							<span class="price product-price special-price">
							   {if !$priceDisplay}
									{displayWtPrice p=$special.price}{else}{displayWtPrice p=$special.price_tax_exc}
								{/if}
								  </span>
							
							 
						{/if}
					</div>
				</div>
				<div class="button-container clearfix">
							{hook h='displayProductListReviews' product=$special}
							{if ($special.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $special.available_for_order && !isset($restricted_country_mode) && $special.minimal_quantity <= 1 && $special.customizable != 2 && !$PS_CATALOG_MODE}
								{if (!isset($special.customization_required) || !$special.customization_required) && ($special.allow_oosp || $special.quantity > 0)}
									{if isset($static_token)}
										<a class="btn-square ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$special.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$special.id_product|intval}">
											<span>{l s='Add to cart'}</span>
										</a>
									{else}
										<a class="btn-square ajax_add_to_cart_button" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$special.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$special.id_product|intval}">
											<span>{l s='Add to cart'}</span>
										</a>
									{/if}
								{else}
									<span class="btn-square ajax_add_to_cart_button disabled">
										<span>{l s='Add to cart'}</span>
									</span>
								{/if}
							{/if}
							{hook h='displayProductListFunctionalButtons' product=$special}
						</div>
			</li>
		</ul>
		<div class="border-box">
			<a 
			class="btn btn-default button button-small" 
			href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" 
			title="{l s='All specials' mod='blockspecials'}">
				<span>{l s='All specials' mod='blockspecials'}<i class="icon-chevron-right right"></i></span>
			</a>
		</div>
	{else}
		<div>{l s='No specials at this time.' mod='blockspecials'}</div>
	{/if}
	</div>
</div>
<!-- /MODULE Block specials -->
