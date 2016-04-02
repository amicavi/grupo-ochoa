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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="banner-item clearfix" data-id="{$id_banner|intval}">
	<form method="post" action="" class="form-horizontal">
	<div class="banner-header clearfix">
		<input type="checkbox" value="{$id_banner|intval}" class="banner-box">
		<span class="banner-name">
			<span class="banner-preview">
				<span {if isset($banner.header_img)}style="background-image:url({$banner.header_img|escape:'html':'UTF-8'})"{/if}>
					{if isset($banner.header_html)}HTML{/if}
				</span>
			</span>
			{$banner.title|escape:'html':'UTF-8'}
		</span>

		<span class="actions pull-right">
			<label class="label-checkbox">
				<input type="checkbox" name="in_carousel" value="1" class="toggleable_param"{if $banner.in_carousel} checked{/if}>
				{l s='In carousel' mod='custombanners'}
			</label>
			<a class="activateBanner list-action-enable action-{if $banner.active}enabled{else}disabled{/if}" href="#" title="{l s='Active' mod='custombanners'}">
				<i class="icon-check"></i>
				<i class="icon-remove"></i>
				<input type="checkbox" name="active" value="1" class="toggleable_param hidden"{if $banner.active} checked{/if}>
			</a>
			<i class="dragger act icon icon-arrows-v icon-2x"></i>
			<div class="btn-group pull-right">
				<button title="{l s='Edit' mod='custombanners'}" class="editBanner btn btn-default">
					<i class="icon-pencil"></i> {l s='Edit' mod='custombanners'}
				</button>
				<button title="{l s='Scroll Up' mod='custombanners'}" class="scrollUp btn btn-default">
					<i class="icon icon-minus"></i> {l s='Cancel' mod='custombanners'}
				</button>
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<i class="icon-caret-down"></i>
				</button>
				<ul class="dropdown-menu">
					<li class="dont-hide">
						<a href="#" class="toggle-hook-list"><i class="icon-copy"></i> {l s='Copy to hook' mod='custombanners'}</a>
						<div class="dynamic-hook-list" style="display:none;">
							<button class="btn btn-default copyToAnotherHook">{l s='OK' mod='custombanners'}</button>
						</div>
					</li>
					<li class="dont-hide">
						<a href="#" class="toggle-hook-list"><i class="icon-arrow-left"></i> {l s='Move to hook' mod='custombanners'}</a>
						<div class="dynamic-hook-list" style="display:none;">
							<button class="btn btn-default moveToAnotherHook">{l s='OK' mod='custombanners'}</button>
						</div>
					</li>
					<li>
						<a class="deleteBanner" href="#" onclick="event.preventDefault();">
							<i class="icon icon-trash"></i>
							{l s='Delete' mod='custombanners'}
						</a>
					</li>
				</ul>
			</div>

		</span>
	</div>

	<div class="banner-details" style="display:none;">
		<div class="ajax-errors alert alert-danger" style="display:none"></div>
		{foreach from=$input_fields item=field key=input_name}
		{if isset($field.display_name)}
		<div class="form-group{if !isset($banner.content.$input_name)} empty{/if}">
			<label class="control-label col-lg-1">
				{if isset($field.tooltip)}
					<span class="label-tooltip" data-toggle="tooltip" title="{$field.tooltip|escape:'html':'UTF-8'}">
				{/if}
					{$field.display_name|escape:'html':'UTF-8'}
				{if isset($field.tooltip)}
					</span>
				{/if}
				<a href="#" class="show-field" title="{l s='Add' mod='custombanners'}"><i class="icon-plus"></i></a>
			</label>
			<div class="col-lg-10 clearfix">
			{foreach from=$languages item=lang}
				{assign id_lang $lang.id_lang}
				{if isset($banner.content.$input_name.$id_lang)}
					{assign value $banner.content.$input_name.$id_lang}
				{else}
					{assign value 0}
				{/if}
				<div class="multilang lang-{$id_lang|intval}" data-lang="{$id_lang|intval}" style="{if $id_lang != $id_lang_current}display: none;{/if}">
				{if $input_name == 'img'}
					<div class="img-holder{if $value} has-img{/if}">
						<div class="img-uploader">
							<i class="icon-file-image-o"></i>
							{l s='Drag your image here, or' mod='custombanners'}
							<a href="#" class="img-browse">{l s='browse' mod='custombanners'}</a>
							<input type="file" name="banner_img_{$id_lang|intval}" style="display:none;">
							<input type="hidden" class="banner_img_name" name="banner_data[{$id_lang|intval}][img]" value="{if $value}{basename($value)|escape:'html':'UTF-8'}{/if}">
						</div>
						{if $value}
							<img src="{$value|escape:'html':'UTF-8'}">
						{/if}
						<i class="icon-upload upload-new act" title="{l s='Upload new' mod='custombanners'}"></i>
					</div>
				{else if $input_name == 'link'}
					<select name="banner_data[{$id_lang|intval}][link][type]" class="col-lg-3 linkTypeSelector">
						{foreach $field.selector key=k item=type}
							<option value="{$k|escape:'html':'UTF-8'}"{if $value && $value.type == $k} selected{/if}>{$type|escape:'html':'UTF-8'}</option>
						{/foreach}
					</select>
					<div class="input-group link-type col-lg-9" data-type="{if $value && $value.type}{$value.type|escape:'html':'UTF-8'}{else}custom{/if}">
						<span class="input-group-addon">
							<span class="any">{l s='Any URL' mod='custombanners'}</span>
							<span class="by_id">
								<span class="label-tooltip" data-toggle="tooltip" title="{l s='Just add the ID of selected resource. For example: \"10\"' mod='custombanners'}">
									<i class="icon-info-circle"></i>
								</span>
								{l s='ID' mod='custombanners'}
							</span>
						</span>
						<input type="text" name="banner_data[{$id_lang|intval}][link][href]" value="{if $value && $value.href}{$value.href|escape:'html':'UTF-8'}{/if}">
						<span class="input-group-addon">
							<label class="label-checkbox">
								<input type="checkbox" name="banner_data[{$id_lang|intval}][link][_blank]" value="1"{if $value && isset($value._blank)} checked="checked"{/if}>
								{l s='new window' mod='custombanners'}
							</label>
						</span>
					</div>
				{else if $input_name == 'html'}
					<textarea class="mce" name="banner_data[{$id_lang|intval}][{$input_name|escape:'html':'UTF-8'}]" id="banner_{$id_banner|intval}_{$input_name|escape:'html':'UTF-8'}_{$id_lang|intval}">{if $value}{$value|escape:'html':'UTF-8'}{/if}</textarea>
				{else if $input_name == 'class'}
					<div class="input-group all-langs">
						<input type="text" name="banner_data[{$id_lang|intval}][class]" value="{if $value}{$value|escape:'html':'UTF-8'}{/if}">
						<span class="input-group-addon act show-classes">
							{l s='Predefined classes' mod='custombanners'}
							<i class="icon-angle-down"></i>
						</span>
					</div>
				{else if $input_name == 'restricted'}
					<select name="banner_data[{$id_lang|intval}][restricted][type]" class="col-lg-3">
						{foreach $field.selector key=k item=type}
							<option value="{$k|escape:'html':'UTF-8'}"{if $value && $value.type == $k} selected{/if}>{$type|escape:'html':'UTF-8'}</option>
						{/foreach}
					</select>
					<div class="input-group col-lg-9 all-langs wider-tooltip">
						<span class="input-group-addon">
							<span class="label-tooltip" data-toggle="tooltip" title="{l s='Specify IDs of selected resources, where you want to display this banner. For example: \"11, 15, 18\"' mod='custombanners'}">
								<i class="icon-info-circle"></i>
							</span>
							{l s='IDs' mod='custombanners'}
						</span>
						<input type="text" name="banner_data[{$id_lang|intval}][restricted][ids]" value="{if $value && $value.ids}{', '|implode:$value.ids}{/if}">
					</div>
				{else}
					<input type="text" name="banner_data[{$id_lang|intval}][{$input_name|escape:'html':'UTF-8'}]" id="banner_{$id_banner|intval}_{$input_name|escape:'html':'UTF-8'}_{$id_lang|intval}" value="{if $value}{$value|escape:'html':'UTF-8'}{/if}" class="{if isset($field.all_langs)}all-langs{/if}">
				{/if}
				</div>
			{/foreach}
			</div>
			<div class="col-lg-1">
				{if !isset($field.all_langs)}
				<div class="banner-langs">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						{foreach from=$languages item=lang}
							<span class="multilang lang-{$lang.id_lang|intval}" style="{if $lang.id_lang != $id_lang_current}display:none;{/if}">{$lang.iso_code|escape:'html':'UTF-8'}</span>
						{/foreach}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach from=$languages item=lang}
						<li>
							<a href="#" onclick="event.preventDefault(); selectLanguage($(this), {$lang.id_lang|intval})">
								{$lang.name|escape:'html':'UTF-8'}
							</a>
						</li>
						{/foreach}
					</ul>
				</div>
				{/if}
				<i class="icon-times hide-field act" title="{l s='Remove' mod='custombanners'}"></i>
			</div>
		</div>
		{/if}
		{/foreach}
		<div class="p-footer">
			<input type="hidden" name="hook_name" value="{$banner.hook_name|escape:'html':'UTF-8'}">
			<button class="saveBanner btn btn-default">
				<i class="process-icon-save"></i>
				{l s='Save' mod='custombanners'}
			</button>
			<label class="label-checkbox">
				<input type="checkbox" name="lang_source" value="{$id_lang_current|intval}" >
				{l s='Copy all data from selected language to others' mod='custombanners'}
			</label>
		</div>
	</div>
	</form>
</div>