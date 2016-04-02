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

{if !empty($pagination_settings)}
	{include file='./pagination.tpl' settings=$pagination_settings}
{/if}
{if $posts}
	<div class="posts-list">
	{foreach $posts as $post}
		{$link = $blog->getPostLink($post.id_post)}
		<div class="post-item{if !empty($blog->general_settings.date)} has-date{/if}">
			<div class="post-item__inner row">
				{if !empty($post.cover)}
				<div class="post-cover col-xs-4">
				{/if}
					<a class="post-cover__link flash-effect" href="{$link|escape:'html':'UTF-8'}" title="{l s='Read more' mod='amazzingblog'}">
					{$src = $blog->img_dir|cat:'posts/'|cat:$post.id_post|cat:'/'|cat:$settings.cover_type|cat:'/'|cat:$post.cover}
					<img src="{$src|escape:'html':'UTF-8'}" class="img-responsive">
					{if !empty($blog->general_settings.date)}
						{$split_date = $blog->prepareDate($post[$blog->general_settings.date])}
						<p class="post-date">
							{foreach $split_date as $k => $fragment}
								<span class="{$k|escape:'html':'UTF-8'}">{$fragment|escape:'html':'UTF-8'}</span>
							{/foreach}
						</p>
						{/if}
					</a>
				{if !empty($post.cover)}
				</div>
				{/if}
				<div class="post-content col-xs-8">
				<div class="post-infos">
					{foreach ['views' => 'eye', 'comments' => 'comment'] as $stat => $icon}
						{if !empty($settings[$stat|cat:'_num'])}
							<span class="post-infos_item {$stat|escape:'html':'UTF-8'}-num"><i class="fa fa-{$icon|escape:'html':'UTF-8'}"></i> {$post.$stat|intval}</span>
						{/if}
					{/foreach}
				</div>
					<h3 class="post-title">
						{if !empty($settings.link_title)}
						<a href="{$link|escape:'html':'UTF-8'}">
						{/if}
							{$post.title|escape:'html':'UTF-8'}
						{if !empty($settings.link_title)}
						</a>
						{/if}
					</h3>
					<div class="post-description">
						{$post.content|strip_tags|truncate:$settings.truncate:'...'|escape:'html':'UTF-8'}
					</div>
					{if !empty($settings.read_more)}
						<a class="post-more btn btn-default" href="{$link|escape:'html':'UTF-8'}" title="{l s='Read more' mod='amazzingblog'}">{l s='Read more' mod='amazzingblog'}</a>
					{/if}
				</div>
				</div>
		</div>			
	{/foreach}
	</div>
	{if !empty($pagination_settings)}
		{include file='./pagination.tpl' settings=$pagination_settings}
	{/if}
{else}
	<div class="alert-warning">{l s='No posts' mod='amazzingblog'}</div>
{/if}
