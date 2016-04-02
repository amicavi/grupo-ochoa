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

{if $multishop_warning}
<div class="alert alert-warning">
	{l s='NOTE: All modifications will be applied to more than one shop' mod='easycarousels'}
	<i class="icon icon-times" data-dismiss="alert"></i>
</div>
{/if}
<div class="ajax_errors_general hidden alert alert-danger"></div>
<div class="bootstrap panel easycarousels all-carousels clearfix">
	<h3><i class="icon icon-image"></i> {l s='Carousels' mod='easycarousels'}</h3>
	<form class="form-horizontal clearfix">
		<label class="control-label col-lg-1" for="hookSelector">
			{l s='Select hook' mod='easycarousels'}
		</label>
		<div class="col-lg-3">
			<select class="hookSelector">
				{foreach $hooks item=qty key=hk}
					<option value="{$hk|escape:'html':'UTF-8'}"> {$hk|escape:'html':'UTF-8'} ({$qty|intval}) </option>
				{/foreach}
			</select>
		</div>
		<div class="col-lg-6 hook-settings">
			<i class="icon icon-wrench"></i>
			{l s='Hook settings' mod='easycarousels'}:
			<a href="#" class="callSettings" data-settings="display">{l s='display' mod='easycarousels'}</a> |
			<a href="#" class="callSettings" data-settings="exceptions">{l s='page exceptions' mod='easycarousels'}</a> |
			<a href="#" class="callSettings" data-settings="positions">{l s='module positions' mod='easycarousels'}</a>
		</div>
	</form>
	<div id="settings-content" style="display:none;">{* filled dinamically *}</div>
	{foreach $hooks item=qty key=hk}
	<div id="{$hk|escape:'html':'UTF-8'}" class="hook_content {if $hk == 'displayHome'}active{/if}">
		{if $hk|substr:0:19 == 'displayEasyCarousel'}
		<div class="alert alert-info">
			{l s='In order to display this hook, insert the following code to any tpl' mod='easycarousels'}:
			{ldelim}hook h='{$hk|escape:'html':'UTF-8'}'{rdelim}
		</div>
		{/if}
		{if $hk == 'displayFooterProduct'}
		<div class="alert alert-info">
			{l s='Here you can display product accessories or other products with same categories/features' mod='easycarousels'}
		</div>
		{/if}
		<div class="carousel_list">
			{if isset($carousels.$hk)}
				{foreach $carousels.$hk item=carousel}
					{include file="./carousel-form.tpl"
						carousel=$carousel
						type_names=$type_names
						full=0
					}
				{/foreach}
			{/if}
		</div>
		<div class="btn-group bulk-actions dropup">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			{l s='Bulk actions' mod='easycarousels'} <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="#"	class="bulk-select"><i class="icon-check-sign"></i> {l s='Select all' mod='easycarousels'}</a></li>
			<li><a href="#" class="bulk-unselect"><i class="icon-check-empty"></i> {l s='Unselect all' mod='easycarousels'}</a></li>
			<li class="divider"></li>
			<li><a href="#" data-bulk-act="enable"><i class="icon-check on"></i> {l s='Enable' mod='easycarousels'}</a></li>
			<li><a href="#" data-bulk-act="disable"><i class="icon-times off"></i> {l s='Disable' mod='easycarousels'}</a></li>
			<li><a href="#" data-bulk-act="group_in_tabs"><i class="icon-plus-square"></i> {l s='Group in tabs' mod='easycarousels'}</a></li>
			<li><a href="#" data-bulk-act="ungroup"><i class="icon-minus-square"></i> {l s='Ungroup' mod='easycarousels'}</a></li>
			<li class="divider"></li>
			<li><a href="#" data-bulk-act="delete"><i class="icon-trash"></i> {l s='Delete' mod='easycarousels'}</a></li>
		</ul>
	</div>
		<button type="button" class="addCarousel btn btn-default">
			<i class="icon icon-plus"></i> {l s='Add new carousel' mod='easycarousels'}
		</button>
	</div>
	{/foreach}
</div>

<div class="bootstrap panel easycarousels importer clearfix">
	<h3><i class="icon icon-file-zip-o"></i> {l s='Importer' mod='easycarousels'}</h3>
	<form method="post" action="" enctype="multipart/form-data">
		<a href="#" data-toggle="modal" data-target="#modal_importer_info" title="{l s='About the importer' mod='easycarousels'}">
			<i class="icon-info-circle importer-info"></i>
		</a>
		<input type="hidden" name="action" value="exportCarousels">
		<button type="submit" class="export btn btn-default">
			<i class="icon icon-cloud-download icon-lg"></i>
			{l s='Export carousels' mod='easycarousels'}
		</button>
	</form>
	<button class="import btn btn-default">
		<i class="icon icon-cloud-upload icon-lg"></i>
		{l s='Import carousels' mod='easycarousels'}
	</button>
	<form action="" method="post" enctype="multipart/form-data" style="display:none;">
		<input type="file" name="carousels_data_file" style="display:none;">
	</form>
</div>