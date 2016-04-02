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

<div class="header clearfix">
	{$idendifier = 'id_'|cat:$type}
	{if $item.$idendifier}
		<input type="checkbox" value="{$item.$idendifier|intval}" class="box hidden">
	{/if}
	<span class="title">{$item.$idendifier|intval} | {$item.title|truncate:55|escape:'html':'UTF-8'}</span>
	<div class="actions pull-right">
		{if $type == 'post'}
			<span class="post-info">
				<span class="views"> <i class="icon-eye"></i> {$item.views|intval}</span>
				<span class="date_add user-comments"> <i class="icon-comments"></i> {$item.comments|intval}</span>
				<span class="date_add"> <i class="icon-calendar"></i> {$item.date_add|escape:'html':'UTF-8'|date_format:'d-m-y'}</span>
			</span>
		{/if}
		<a class="activate list-action-enable action-{if $item.active}enabled{else}disabled{/if}" href="#" title="{l s='Active' mod='amazzingblog'}">
			<i class="icon-check"></i>
			<i class="icon-remove"></i>
			<input type="checkbox" name="active" value="1" class="toggleable_param hidden"{if $item.active} checked{/if}>
		</a>
		<i class="dragger ready act icon icon-arrows-v icon-2x"></i>
		<div class="btn-group pull-right">
			<button title="{l s='Edit' mod='amazzingblog'}" class="edit btn btn-default">
				<i class="icon-pencil"></i> {l s='Edit' mod='amazzingblog'}
			</button>
			<button title="{l s='Scroll Up' mod='amazzingblog'}" class="scrollUp btn btn-default">
				<i class="icon icon-minus"></i> {l s='Cancel' mod='amazzingblog'}
			</button>
			<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
				<li>
					<a class="act delete" href="#">
						<i class="icon icon-trash"></i>
						{l s='Delete' mod='amazzingblog'}
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>