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

<div class="bootstrap amazzingblog clearfix{if empty($blog->general_settings.user_comments)} no-comments{/if}">
	<div class="col-lg-2 col-md-3">
		<div class="list-group">
			<a href="#posts" class="list-group-item active"><i class="icon-pencil"></i> {l s='Posts' mod='amazzingblog'}</a>
			<a href="#categories" class="list-group-item"><i class="icon-sitemap"></i> {l s='Categories' mod='amazzingblog'}</a>
			<a href="#comments" class="list-group-item user-comments"><i class="icon-comments"></i> {l s='Comments' mod='amazzingblog'}{if $new_comments_num} <span class="badge">{$new_comments_num|intval}</span>{/if}</a>
			<a href="#blocks" class="list-group-item"><i class="icon-plus-square"></i> {l s='Blocks' mod='amazzingblog'}</a>
			<a href="#imgtypes" class="list-group-item"><i class="icon-image"></i> {l s='Image types' mod='amazzingblog'}</a>
			<a href="#settings" class="list-group-item"><i class="icon-cogs"></i> {l s='Settings' mod='amazzingblog'}</a>
			<a href="#import" class="list-group-item"><i class="icon-file-zip-o"></i> {l s='Import/Export' mod='amazzingblog'}</a>
		</div>
	</div>
	<div class="col-lg-10 col-md-9 panel">
		<div id="posts" class="tab-content active" data-resource="Post">
			<h3>{l s='Posts' mod='amazzingblog'}<span class="badge total">{$total_posts_num|intval}</span></h3>
			{include file="../front/sorting.tpl"}			
			<form class="filter-by clearfix">				
				<label class="col-lg-2">{l s='Filter by category' mod='amazzingblog'}</label>
				<div class="col-lg-2">
					{include file="./category-tree.tpl" type = 'select' name = 'id_category' checked = ['-'] class = 'filter-by'}
				</div>
			</form>
			<button class="btn btn-default add"><i class="icon-plus"></i> {l s='New post' mod='amazzingblog'}</button>
			<div class="list">
			{foreach $posts as $post}
				{include file="./post-form.tpl" post=$post}
			{/foreach}
			</div>
			{$pagination_posts} {* can not be escaped *}
		</div>
		<div id="categories" class="tab-content">
			<h3>{l s='Categories' mod='amazzingblog'}</h3>
			{include file="./category-tree.tpl"
				type = 'full'
				name = 'cat_ids[]'
				checked = [$root_id]
			}
		</div>
		<div id="comments" class="tab-content" data-resource="Comment">
			<h3>{l s='Comments' mod='amazzingblog'}<span class="badge total">{$total_comments_num|intval}</span></h3>			
			<form class="filter-by clearfix">
				{foreach $comment_filters as $k => $filter}
					<label class="col-lg-1">{$filter.label|escape:'html':'UTF-8'}</label>
					<div class="col-lg-2">
						<select name="{$k|escape:'html':'UTF-8'}" class="filter-by">
							<option value="-">-</option>
						{foreach $filter.options as $name => $opt}
							<option value="{$name|escape:'html':'UTF-8'}">{$opt|truncate:55|escape:'html':'UTF-8'}</option>
						{/foreach}
						</select>
					</div>
				{/foreach}				
			</form>
			<div class="list">
			{foreach $comments as $comment}
				{include file="./comment-form.tpl" comment=$comment}
			{/foreach}
			</div>
			{$pagination_comments} {* can not be escaped *}
		</div>
		<div id="blocks" class="tab-content" data-resource="Block">
			<h3>{l s='Blocks' mod='amazzingblog'}</h3>
			<form class="settings form-horizontal clearfix">
				<label class="control-label col-lg-1" for="hookSelector">
					{l s='Select hook' mod='amazzingblog'}
				</label>
				<div class="col-lg-3">
					<select class="hookSelector">
						{foreach $hooks item=qty key=hk}
							<option value="{$hk|escape:'html':'UTF-8'}"> {$hk|escape:'html':'UTF-8'} ({$qty|intval}) </option>
						{/foreach}
					</select>
				</div>
				{*
				<div class="col-lg-6 hook-settings">
					<i class="icon icon-wrench"></i>
					{l s='Hook settings' mod='amazzingblog'}:
					<a href="#" class="callSettings" data-settings="exceptions">{l s='page exceptions' mod='amazzingblog'}</a> |
					<a href="#" class="callSettings" data-settings="positions">{l s='module positions' mod='amazzingblog'}</a>
				</div>
				*}
			</form>
			<button class="btn btn-default add"><i class="icon-plus"></i> {l s='New block' mod='amazzingblog'}</button>
			<div class="list">
			{foreach $blocks as $block}
				{include file="./block-form.tpl" block=$block}
			{/foreach}
			</div>
		</div>
		<div id="imgtypes" class="tab-content"  data-resource="ImgTypesSettings">
			<h3>{l s='Image types' mod='amazzingblog'}</h3>
			<div class="alert alert-info">{l s='Format: "width*height". For example: 55*55' mod='amazzingblog'}</div>
			{include file="./settings-form.tpl" settings=$img_types_settings type='img_settings'}			
		</div>
		<div id="import" class="tab-content"  data-resource="Import">
			<h3>{l s='Import/Export data' mod='amazzingblog'}</h3>
			<form action="" method="post">
				<input type="hidden" name="action" value="exportData">
				<button type="submit" class="export btn btn-default">
					<i class="icon icon-cloud-download icon-lg"></i>
					{l s='Export blog data' mod='amazzingblog'}
				</button>
				<button class="importData btn btn-default">
					<i class="icon icon-cloud-upload icon-lg"></i>
					{l s='Import blog data' mod='amazzingblog'}
				</button>
				<span class="progress-notification grey-note hidden"> <i class="icon-refresh icon-spin"></i> {l s='Data is being imported. Please don\'t close/refresh this window' mod='amazzingblog'}</span>
				<div class="hidden"><input type="file" name="zipped_data"></div>
			</form>
		</div>
		<div id="settings" class="tab-content"  data-resource="GeneralSettings">
			<h3>{l s='General settings' mod='amazzingblog'}</h3>
			{include file="./settings-form.tpl" settings=$general_settings type='general_settings'}
		</div>
	</div>
</div>