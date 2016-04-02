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

{$type = 'text'}{if !empty($field.type)}{$type = $field.type}{/if}
{$name = $k}{if !empty($field.input_name)}{$name = $field.input_name}{/if}

<div class="multilang-wrapper">
{foreach from=$languages item=lang}					
	{$id_lang = $lang.id_lang}
	{$value = ''}
	{if !empty($field.value.$id_lang)}
		{$value = $field.value.$id_lang}
	{else if !empty($field.value.$id_lang_current)}
		{$value = $field.value.$id_lang_current}
	{/if}	
	<div class="multilang lang-{$id_lang|intval}" data-lang="{$id_lang|intval}" style="{if $id_lang != $id_lang_current}display: none;{/if}">
		{if $type == 'mce'}			
			{$id = $field.type|cat:'-'|cat:$id_lang|cat:'-'|cat:uniqid()}
			<textarea id="{$id|escape:'html':'UTF-8'}" name="{$field.input_name|escape:'html':'UTF-8'}[{$id_lang|intval}]" class="{$k|escape:'html':'UTF-8'} mce">{$value|escape:'html':'UTF-8'}</textarea>
		{else}
			<input type="text" name="{$field.input_name|escape:'html':'UTF-8'}[{$id_lang|intval}]" value="{$value|escape:'html':'UTF-8'}" class="{$k|escape:'html':'UTF-8'}{if !empty($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}">
		{/if}
	</div>
{/foreach}
	<div class="multilang-selector pull-right">	
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
</div>