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

{if $post}
	{include file='./breadcrumbs.tpl' current_item=$post.title}
	<h1>{$post.title|escape:'html':'UTF-8'}</h1>
	{if $post.main_img}
		<img src="{$post.main_img|escape:'html':'UTF-8'}" class="img-responsive">
	{/if}
	{if !empty($blog->general_settings.date)}
		{$date = $post[$blog->general_settings.date]}
		<div class="date help-block">{$date|escape:'html':'UTF-8'}</div>
	{/if}
	<div class="content -single">{$post.content} {* can not be escaped *}</div>
	{if !empty($blog->general_settings.social_sharing)}
		<div class="social-sharing -single">
			{l s='Share' mod='amazzingblog'}:
			{foreach $blog->general_settings.social_sharing as $sn}
				<a href="#" class="social-share" data-network="{$sn|escape:'html':'UTF-8'}">
					<i class="fa fa-{$sn|escape:'html':'UTF-8'}"></i>
				</a>
			{/foreach}
		</div>
	{/if}
	<div class="author">{l s='Author' mod='amazzingblog'}: {$post.author.name|escape:'html':'UTF-8'}</div>
	<div class="post-comments">
		<h3>{l s='Comments' mod='amazzingblog'}</h5>		
		<div class="comments-list">
			{foreach $comments as $comment}
				{include file='./comment.tpl' comment=$comment}			
			{/foreach}
		</div>
		{include file='./comment-form.tpl' id_post = $post.id_post}
	</div>
{else}	
	{include file='./breadcrumbs.tpl' current_item=''}
	<div class="alert alert-warning">{l s='This post is not available' mod='amazzingblog'}</div>
{/if}