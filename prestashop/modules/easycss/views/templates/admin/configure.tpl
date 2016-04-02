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

{function newTr class='' selector='' img=''}
	<tr class="{$class|escape:'html':'UTF-8'}">
		<td class="selector">
			<input type="text" value="{$selector|escape:'html':'UTF-8'}" class="selector-input">
			<input type="hidden" value="{$selector|escape:'html':'UTF-8'}" class="selector-prev">
		</td>
		<td class="img">
			<input type="file" name="img" class="img-file">
			{if empty($img)}{$img = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='}{/if}
				<img src="{$img|escape:'html':'UTF-8'}" class="img-preview{if empty($img)} hidden{/if}">
			</div>
		</td>
		<td class="actions">
			<button class="btn btn-default save-rule"><i class="icon-save"></i><i class="icon-refresh icon-spin hidden"></i></button>
			<button class="btn btn-default delete-rule"><i class="icon-trash"></i></button>
		</div>
		</td>
	</tr>
{/function}
<div class="bootstrap panel easycss clearfix">
	<table class="table">
		<tr>
			<th>{l s='Selector' mod='easycss'}</th>
			<th>{l s='Image' mod='easycss'}</th>
			<th></th>
		</tr>
		{newTr class='blank hidden'}
		{foreach $rules as $selector => $img}
			{newTr class='' selector=$selector img=$img}
		{/foreach}
	</table>
	<button class="btn btn-default add-rule"><i class="icon-plus"></i> {l s='New' mod='easycss'}</button>
</div>