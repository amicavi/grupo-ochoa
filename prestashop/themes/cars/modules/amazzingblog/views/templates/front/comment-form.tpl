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

<form class="new-comment" enctype="multipart/form-data">
	<input type="hidden" name="id_post" value="{$id_post|intval}">
	<input type="hidden" name="action" value="SubmitComment">
	<div class="ajax-errors"></div>
	<div class="user-avatar">		
		<div class="avatar-img"{if !empty($user_data.avatar)} style="background-image:url({$avatars_dir|cat:$user_data.avatar|escape:'html':'UTF-8'})"{/if}></div>
		<div class="hidden"><input class="avatar-file" type="file" name="avatar_file"></div>
		<a href="#" class="edit-avatar"><i class="fa fa-pencil"></i></a>
	</div>			
	<div class="field{if empty($user_data.user_name)} show-input{/if}" data-field="user_name">
		<span class="saved user-name">{$user_data.user_name|escape:'html':'UTF-8'} <a href="#" class="edit-name"><i class="fa fa-pencil"></i></a></span>
		<input class="form-control" type="text" value="{$user_data.user_name|escape:'html':'UTF-8'}" name="user_name" placeholder="{l s='Your Name' mod='amazzingblog'}">
	</div>
	<div class="field" data-dield="content">					
		<textarea id="new-comment-content" rows="2" name="content" placeholder="{l s='Your comment' mod='amazzingblog'}"></textarea>
	</div>
	<button class="btn btn-default submit-comment">{l s='Submit' mod='amazzingblog'}</button>
</form>
	