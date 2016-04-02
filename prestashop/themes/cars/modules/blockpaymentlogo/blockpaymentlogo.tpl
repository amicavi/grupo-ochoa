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

<!-- Block payment logo module -->
<div id="paiement_logo_block_left" class="paiement_logo_block">
	<a href="{$link->getCMSLink($cms_payement_logo)|escape:'html'}">
		<img src="{$img_dir}visa_icon.png" alt="Visa" />
		<img src="{$img_dir}maestro_icon.png" alt="Maestro" />
		<img src="{$img_dir}ms_card_icon.png" alt="MasterCard" />
		<img src="{$img_dir}amex_icon.png" alt="Amex" />
		<img src="{$img_dir}pay_icon.png" alt="PayPal" />
	</a>
</div>
<!-- /Block payment logo module -->