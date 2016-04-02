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
{$cover = basename($cover)}
{$main_img = basename($main_img)}
{foreach $images as $k => $img}
{$basename = basename($img)}
<div class="img-set{if $basename == $cover} cover{/if}{if $basename == $main_img} main_img{/if}" data-img="{$basename|escape:'html':'UTF-8'}">
	<div class="img-holder">
		<img src="{$img|escape:'html':'UTF-8'}">
		<div class="img-actions">
			<a href="#" class="set-cover act" title="{l s='Cover' mod='amazzingblog'}"><i class="icon-th-list"></i></a>
			<a href="#" class="set-main act" title="{l s='Main' mod='amazzingblog'}"><i class="icon-image"></i></a>
			<a href="#" class="delete-img act" title="{l s='Delete' mod='amazzingblog'}"><i class="icon-trash"></i></a>
		</div>
	</div>	
	<div class="img-types">
		<a href="#" class="current-type">{$image_types.s.display_name|escape:'html':'UTF-8'}</a>		
		<div class="available-types">
		{foreach $image_types as $t => $type}			
			<a href="#" data-type="{$t|escape:'html':'UTF-8'}" class="img-type">{$type.display_name|escape:'html':'UTF-8'}</a>			
		{/foreach}
		<a href="#" data-type="original" class="img-type">{l s='Original' mod='amazzingblog'}</a>
		</div>
	</div>
</div>
{/foreach}
