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
{if !empty($field.options)}
	<select class="{$k|escape:'html':'UTF-8'}{if !empty($field.input_class)} {$field.input_class|escape:'html':'UTF-8'}{/if}" name="{$name|escape:'html':'UTF-8'}">
		{foreach $field.options as $i => $opt}
			<option value="{$i|escape:'html':'UTF-8'}"{if $field.value == $i} selected{/if}>{$opt|escape:'html':'UTF-8'}</option>
		{/foreach}
	</select>
{else if $type == 'checkbox'}
	{foreach $field.boxes as $i => $label}
		<label><input type="checkbox" name="{$name|escape:'html':'UTF-8'}" value="{$i|escape:'html':'UTF-8'}"{if in_array($i, $field.value)} checked{/if}> {$label|escape:'html':'UTF-8'}</label> 
	{/foreach}
{else if $type == 'switcher'}
	<span class="switch prestashop-switch col-lg-3">
		<input type="radio" id="{$k|escape:'html':'UTF-8'}" name="{$name|escape:'html':'UTF-8'}" value="1"{if !empty($field.value)} checked{/if} >
		<label for="{$k|escape:'html':'UTF-8'}">{l s='Yes' mod='amazzingblog'}</label>
		<input type="radio" id="{$k|escape:'html':'UTF-8'}_0" name="{$name|escape:'html':'UTF-8'}" value="0"{if empty($field.value)} checked{/if} >
		<label for="{$k|escape:'html':'UTF-8'}_0">{l s='No' mod='amazzingblog'}</label>
		<a class="slide-button btn"></a>
	</span>
{else}
	<input type="text" name="{$name|escape:'html':'UTF-8'}" value="{$field.value|escape:'html':'UTF-8'}" class="{$k|escape:'html':'UTF-8'}{if !empty($field.input_class)} {$field.input_class|escape:'html':'UTF-8'}{/if}">
{/if}