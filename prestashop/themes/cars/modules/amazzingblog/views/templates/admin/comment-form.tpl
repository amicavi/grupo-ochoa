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

<form class="comment clearfix{if !$comment.active} not-visible{/if}" enctype="multipart/form-data">
	<input type="hidden" name="id_comment" value="{$comment.id_comment|intval}">
	<a href="{$blog->getPostLink($comment.id_post|intval)}#comment_{$comment.id_comment|intval}" target="_blank">{$comment.post_title|escape:'html':'UTF-8'|truncate:55}:</a>
	<div class="ajax-errors"></div>	
	<div class="user-avatar pull-left">		
		<div class="avatar-img"{if !empty($comment.avatar)} style="background-image:url({$avatars_dir|cat:$comment.avatar|escape:'html':'UTF-8'})"{/if}></div>
		<div class="hidden"><input class="avatar-file" type="file" name="avatar_file"></div>		
	</div>
	<div class="saved">
		<div class="b user-name">{$comment.user_name|escape:'html':'UTF-8'}:</div>
		<div class="comment-content"> {$blog->bbCodeToHTML($comment.content|escape:'html':'UTF-8')}</div>
	</div>
	<div class="input">							
		<div class="col-lg-3" data-field="user_name">			<input class="input" type="text" value="{$comment.user_name|escape:'html':'UTF-8'}" name="user_name">		</div>
		<div class="col-lg-9" data-dield="content">			<textarea id="comment-content-{$comment.id_comment|intval}" class="input" rows="2" name="content">{$comment.content}{* can not be escaped *}</textarea>
		</div>
	</div>
	<div class="comment-info">
		<span class="comment-date">{$comment.date_add|escape:'html':'UTF-8'}</span>		
		<span class="approved-by grey-note action">
			{if $comment.approved_by}
				{l s='Approved by %s' sprintf=$comment.approved_by_name mod='amazzingblog'}
			{else}
				<a href="#" class="approve-comment alert-warning">{l s='approve' mod='amazzingblog'}</a>
			{/if}
		</span>
		<a href="#" class="edit-comment action">{l s='edit' mod='amazzingblog'}</a>		
		<a href="#" class="save-comment action">{l s='save' mod='amazzingblog'}</a>		
		{if $comment.active}
			<a href="#" class="hide-comment action">{l s='hide' mod='amazzingblog'}</a>
		{else}
			<a href="#" class="show-comment action">{l s='show' mod='amazzingblog'}</a>
		{/if}
		<a href="#" class="delete action">{l s='delete' mod='amazzingblog'}</a>
	</div>	
</form>