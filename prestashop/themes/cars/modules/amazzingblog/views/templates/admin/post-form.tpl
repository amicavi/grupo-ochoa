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

<div class="item clearfix" data-id="{$post.id_post|intval}">
<form method="post" action="" class="form-horizontal">
	<input type="hidden" name="id_post" value="{$post.id_post|intval}">
	<input type="hidden" name="active" value="{$post.active|intval}">
	<input type="hidden" name="author" value="{$post.author|intval}">
	{include file='./item-header.tpl' item=$post type='post'}
	{if !empty($full)}
	<div class="details" style="display:none;">
		{foreach $post.editable_fields as $k => $field}
		{$name = $field.input_name}
		<div class="form-group{if !empty($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}">
			<label class="control-label">
				{if !empty($field.tooltip)}
					<span class="label-tooltip" data-toggle="tooltip" title="{$field.tooltip|escape:'html':'UTF-8'}">{$field.display_name|escape:'html':'UTF-8'}</span>
				{else}
					{$field.display_name|escape:'html':'UTF-8'}
				{/if}
			</label>
			{if empty($field.multilang)}
				{if $k == 'categories'}
					<div class="round-border">
					{include file='./category-tree.tpl'
						type = 'checkbox'
						name = $field.input_name
						checked = $field.value
						id_category_default = $post.id_category_default
					}
					</div>
				{else if $k == 'images'}
					{if !$post.id_post}
						<div class="help-block">{l s='Please save post to activate this field' mod='amazzingblog'}</div>
					{else}
						<div class="images">
							<div class="note">
								<i class="icon-th-list"></i> <span class="b">{l s='Cover' mod='amazzingblog'}</span>: {l s='displayed in listings' mod='amazzingblog'}
								<i class="icon-image"></i> <span class="b">{l s='Main image' mod='amazzingblog'}</span>: {l s='displayed on post page' mod='amazzingblog'}
							</div>
							<div class="img-list">
								{include file="./image-set.tpl" images=$field.value cover=$post.cover main_img=$post.main_img}
							</div>
							<div class="img-set add-new">
								<div class="hidden">
									<div class="img-set upload-progress"><div class="img-holder"><i class="icon-refresh icon-spin act"></i></div></div>
									<input type="file" class="post-img" multiple>
								</div>
								<a href="#" class="add-img act" title="{l s='Browse or drag' mod='amazzingblog'}">+</a>
							</div>
						</div>
					{/if}
				{else}
					{include file="./field.tpl" field=$field k=$k}
				{/if}
			{else}
				{include file="./multilang-field.tpl" field=$field k=$k}
			{/if}
		</div>
		{/foreach}
		<div class="col-lg-12">
			<button class="btn btn-default save"><i class="icon-save"></i> {l s='Save' mod='amazzingblog'}</button>
		</div>
	</div>
	{/if}
</form>
</div>