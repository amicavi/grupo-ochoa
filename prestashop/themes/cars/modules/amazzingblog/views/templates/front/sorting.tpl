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

<div class="sorting clearfix">	
	{l s='Sort by' mod='amazzingblog'}:	
	{foreach $sorting_options as $k => $o} 
		<a href="#" class="order-by{if $k == $order_by} active{/if}{if $k == 'position'} hidden{/if}{if $k == 'comments'} user-comments{/if}" data-by="{$k|escape:'html':'UTF-8'}">{$o|escape:'html':'UTF-8'}</a>
	{/foreach}
	<a href="#" class="order-way{if $order_way == 'DESC'} active{/if}" data-way="DESC"><i class="icon-caret-down"></i></a>
	<a href="#" class="order-way{if $order_way == 'ASC'} active{/if}" data-way="ASC"><i class="icon-caret-up"></i></a>
</div>