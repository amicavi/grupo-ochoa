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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}" lang="{$lang_iso}">
	<head>
		<title>{$meta_title|escape:'html':'UTF-8'}</title>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{if isset($meta_description)}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
{/if}
{if isset($meta_keywords)}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
{/if}
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />
		<link rel="shortcut icon" href="{$favicon_url}" />
		<link href="{$css_dir}bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="{$css_dir}maintenance.css" rel="stylesheet" type="text/css" />
		 <link href="{$css_dir}autoload/color.css" rel="stylesheet" type="text/css" />
		 <link href="{$css_dir}autoload/fonts.css" rel="stylesheet" type="text/css" />
		<link href="{$css_dir}autoload/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,700,300,800,400&subset=cyrillic-ext,latin' rel='stylesheet' type='text/css'>
	</head>
	<body>
			<div id="maintenance" class="clearfix">
				<div class="container">
					 <div class="logo">
						<img src="{$logo_url}" alt="logo"/>
					 </div>
						
						<div class="wrap_counter">
						<div id="countdown" class="clearfix"></div>
						</div>
						<h1 class="maintenance-heading"><span>{l s='WE ARE LAUNCHING SOON'}</span></h1>
						{*<div id="message">
							{l s='In order to perform website maintenance, our online store will be temporarily offline.'}
							{l s='We apologize for the inconvenience and ask that you please try again later.'}
						</div>*}
						<div id="maintenance_footer" class="clearfix">
						{$HOOK_MAINTENANCE}
						</div>
						
				</div>
		 </div>
	</body>
</html>
