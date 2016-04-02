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

<!-- Block permanent links module -->
<div id="permanent_links_footer">
	<ul>
	<!-- Sitemap -->
	<li class="sitemap">
		<a href="{$link->getPageLink('sitemap')|escape:'html'}" title="{l s='Sitemap' mod='blockpermanentlinks'}">{l s='Sitemap' mod='blockpermanentlinks'}</a>
	</li>
	<!-- Search -->
	<li class="sitemap">
		<a href="{$link->getPageLink('search')|escape:'html'}" title="{l s='Search' mod='blockpermanentlinks'}">{l s='Search' mod='blockpermanentlinks'}</a>
	</li>
	<!-- Delivery -->
	<li class="contact">
		<a href="{$link->getPageLink('manufacturer')|escape:'html'}" title="{l s='Manufacturers' mod='blockpermanentlinks'}">{l s='Manufacturers' mod='blockpermanentlinks'}</a>
	</li>
	<!-- Secure payment-->
	<li class="contact">
		<a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='View products with a price drop'}">{l s='Price drop' mod='blockpermanentlinks'}
        </a>
	</li>
	<!-- Contact -->
	<li class="contact">
		<a href="{$link->getPageLink('contact', true)|escape:'html'}" title="{l s='Contact' mod='blockpermanentlinks'}">{l s='Contact' mod='blockpermanentlinks'}</a>
	</li>
	</ul>
</div>
<!-- /Block permanent links module -->