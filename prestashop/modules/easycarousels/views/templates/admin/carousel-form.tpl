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

{$id_carousel = $carousel.id_carousel}
<div class="carousel-item clearfix" data-id="{$id_carousel|intval}">
<form method="post" action="" class="form-horizontal">
	<div class="carousel-header clearfix">
		<input type="checkbox" value="{$id_carousel|intval}" class="carousel-box" title="{l s='Bulk actions' mod='easycarousels'}">
		<span class="carousel-name">
			{if $id_carousel && !$carousel.name}
				{l s='Carousel %d' sprintf=$id_carousel mod='easycarousels'}
			{else}
				{$carousel.name|escape:'html':'UTF-8'}
			{/if}
		</span>
		<span class="actions pull-right">
			<label class="label-checkbox">
				<input type="checkbox" name="in_tabs" value="1" class="toggleable_param"{if $carousel.in_tabs} checked{/if}>
				{l s='In tabs' mod='easycarousels'}
			</label>
			<a class="activateCarousel list-action-enable action-{if $carousel.active == 1}enabled{else}disabled{/if}" href="#" title="{l s='Activate/Deactivate' mod='easycarousels'}">
				<i class="icon-check"></i>
				<i class="icon-remove"></i>
				<input type="checkbox" name="active" value="1" class="toggleable_param hidden"{if $carousel.active} checked{/if}>
			</a>
			<div class="actions btn-group pull-right">
				<button title="{l s='Edit' mod='easycarousels'}" class="editCarousel btn btn-default" data-id="{$id_carousel|intval}">
					<i class="icon-pencil"></i> {l s='Edit' mod='easycarousels'}
				</button>
				<button title="{l s='Scroll Up' mod='easycarousels'}" class="scrollUp btn btn-default">
					<i class="icon icon-minus"></i> {l s='Cancel' mod='easycarousels'}
				</button>
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<i class="icon-caret-down"></i>
				</button>
				<ul class="dropdown-menu">
					<li>
						<a class="deleteCarousel" href="#" onclick="event.preventDefault();">
							<i class="icon icon-trash"></i>
							{l s='Delete' mod='easycarousels'}
						</a>
					</li>
				</ul>
				<i class="dragger icon icon-arrows-v icon-2x"></i>
			</div>
		</span>
	</div>
	{if $full}
	<div class="carousel-details" style="display:none;">
		<div class="ajax_errors alert alert-danger" style="display:none;"></div>
		<div class="col-lg-4">
			<div class="form-group">
				<label class="control-label col-lg-6" for="carousel_type">
					{l s='Carousel type' mod='easycarousels'}
				</label>
				<div class="col-lg-6">
					<select name="type" id="carousel_type">
						{foreach $type_names as $group_name => $options}
						<optgroup label="{$group_name|escape:'html':'UTF-8'}">
							{foreach $options as $input_name => $display_name}
								<option value="{$input_name|escape:'html':'UTF-8'}"{if $carousel.type == $input_name} selected{/if}>
									{$display_name|escape:'html':'UTF-8'}
								</option>
							{/foreach}
						</optgroup>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="form-group">
				<label class="control-label col-lg-3" for="carousel-name">
					<span class="label-tooltip" data-toggle="tooltip" title="{l s='You may leave this field empty for carousels that are not in tabs' mod='easycarousels'}">{l s='Carousel title' mod='easycarousels'}</span>
				</label>
				<div class="col-lg-9 has-lang-selector">
					{foreach from=$languages item=lang}
						<input type="text" name="name_multilang[{$lang.id_lang|intval}]" class="multilang lang_{$lang.id_lang|intval}" {if isset($carousel.name_multilang[$lang.id_lang])}value="{$carousel.name_multilang[$lang.id_lang]|escape:'html':'UTF-8'}"{else}data-saved="false"{/if} style="{if $lang.id_lang != $id_lang_current}display:none;{/if}"
						/>
					{/foreach}
					<div class="languages pull-right">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							{foreach from=$languages item=lang}
								<span class="multilang lang_{$lang.id_lang|intval}" style="{if $lang.id_lang != $id_lang_current}display:none;{/if}">{$lang.iso_code|escape:'html':'UTF-8'}</span>
							{/foreach}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li>
								<a href="#" class="lang_switcher" data-id-lang="{$lang.id_lang|intval}" onclick="event.preventDefault();">
									{$lang.name|escape:'html':'UTF-8'}
								</a>
							</li>
							{/foreach}
						</ul>
					</div>
				</div>
			</div>
		</div>

		{function renderSettings s_type='' s_name='' rows=5 cols=3}
		<div class="{$s_type|escape:'html':'UTF-8'}">
			{if $s_name}
				<div class="col-lg-12">
					<h4 class="col-lg-10 col-lg-offset-2 settings-title">
						{$s_name|escape:'html':'UTF-8'}
					</h4>
				</div>
			{/if}
			{for $col = 0 to ($cols-1)}
			{$column_fields = $fields[$s_type]|array_slice:($col*$rows):$rows}
				{if $column_fields}
				<div class="col-lg-{12/$cols|intval}">
				{foreach $column_fields as $k => $field}
					{if isset($carousel.settings.$s_type.$k)}
						{$field.value = $carousel.settings.$s_type.$k}
					{/if}
					{if $s_type == 'carousel' && $col > 1}{$grid = [10, 2]}{else}{$grid = [6, 6]}{/if}
					{$id = $k|cat:'_'|cat:$id_carousel}
					{$input_name = 'settings['|cat:$s_type|cat:']['|cat:$k|cat:']'}
					<div class="form-group{if isset($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}">
						<label class="control-label col-lg-{$grid[0]|intval}" for="{$id|escape:'html':'UTF-8'}">
							{if isset($field.tooltip)}
								<span class="label-tooltip" data-toggle="tooltip" title="{$field.tooltip|escape:'html':'UTF-8'}{* cannot be escaped *}">
							{/if}
								{$field.name|escape:'html':'UTF-8'}{* cannot be escaped *}
							{if isset($field.tooltip)}
								</span>
							{/if}
						</label>
						<div class="col-lg-{$grid[1]|intval}">
							{if $field.type == 'switcher'}
								<span class="switch prestashop-switch">
									<input type="radio" id="{$id|escape:'html':'UTF-8'}" name="{$input_name|escape:'html':'UTF-8'}" value="1" {if $field.value} checked="checked"{/if} >
									<label for="{$id|escape:'html':'UTF-8'}">
										{$yes_no_types = ['randomize', 'consider_cat', 'a', 'p', 'n', 'title_one_line']}
										{if in_array($k, $yes_no_types)}
											{l s='Yes' mod='easycarousels'}
										{else}
											{l s='Show' mod='easycarousels'}
										{/if}
									</label>
									<input type="radio" id="{$id|escape:'html':'UTF-8'}_0" name="{$input_name|escape:'html':'UTF-8'}" value="0" {if !$field.value}checked="checked"{/if} >
									<label for="{$id|escape:'html':'UTF-8'}_0">
										{if in_array($k, $yes_no_types)}
											{l s='No' mod='easycarousels'}
										{else}
											{l s='Hide' mod='easycarousels'}
										{/if}
									</label>
									<a class="slide-button btn"></a>
								</span>
							{else if $field.type == 'select'}
								<select name="{$input_name|escape:'html':'UTF-8'}">
									{if $carousel.type == 'manufacturers'}
										{$current_option_type = 'manufacturer_option'}
									{else if $carousel.type == 'suppliers'}
										{$current_option_type = 'supplier_option'}
									{else}
										{$current_option_type = 'product_option'}
									{/if}
									{foreach $field.select as $opt_class => $options}
										{foreach $options as $opt_name => $display_name}
											{if $current_option_type == $opt_class && $field.value == $opt_name}
												{$saved = true}
											{else}
												{$saved = false}
											{/if}
											<option class="{$opt_class|escape:'html':'UTF-8'}{if $saved} saved{/if}" value="{$opt_name|escape:'html':'UTF-8'}" {if $saved} selected{/if}>{$display_name|escape:'html':'UTF-8'}</option>
										{/foreach}
									{/foreach}
								</select>
							{else}
								<input type="text" name="{$input_name|escape:'html':'UTF-8'}" id="{$id|escape:'html':'UTF-8'}" value="{$field.value|escape:'html':'UTF-8'}">
							{/if}
						</div>
					</div>
				{/foreach}
				</div>
				{/if}
			{/for}
		</div>
		{/function}

		{renderSettings s_type='special' s_name='' rows=5}
		{renderSettings s_type='php' s_name='' rows=1}
		{renderSettings s_type='tpl' s_name={l s='Template settings' mod='easycarousels'} rows=6}
		{renderSettings s_type='carousel' s_name={l s='Carousel settings' mod='easycarousels'}}

		<div class="p-footer">
			<input type="hidden" name="hook_name" value="{$carousel.hook_name|escape:'html':'UTF-8'}">
			<button id="saveCarousel" class="btn btn-default">
				<i class="process-icon-save"></i>
				{l s='Save' mod='easycarousels'}
			</button>
		</div>
	</div>
	{/if}
</form>
</div>