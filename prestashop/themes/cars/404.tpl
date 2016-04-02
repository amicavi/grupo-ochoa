{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="pagenotfound">
    	<h1><span>{l s='Ooops!'}</span></h1>
	<h2><span class="p_404">{l s='404.'}</span><span class="not_found">{l s='Page not found'}</span></h2>

	<p>
		{l s='SEEMS LIKE WE LOST ONE'}
	</p>

	<form action="{$link->getPageLink('search')|escape:'html':'UTF-8'}" method="post" class="std form_404">
		<fieldset>
			<div>
				<input id="search_query" name="search_query" type="text" placeholder="{l s='Enter a product name...'}" class="form-control grey" />
                <button type="submit" name="Submit" value="OK" class="btn btn_border button-small"><span>{l s='Ok'}</span></button>
			</div>
		</fieldset>
	</form>

	<div class="buttons"><a class="btn btn-default button-medium" href="{$base_dir}" title="{l s='Home'}"><span><i class="icon-chevron-left left"></i>{l s='Back to home'}</span></a></div>
</div>
