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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{*
{if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
	<div class="wrap_tabs_main row grid_arrows">
		<h2 class="title_main_section"><span><b>{l s='Featured'}</b> {l s='products'}</span></h2>
		<div class="tabs_main clearfix">
			{if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
			 
			        <ul id="home-page-tabs" class="game-tabs nav nav-tabs clearfix">
						{$HOOK_HOME_TAB}
					</ul>
			{/if}
			<div class="tab-content clearfix">
				{$HOOK_HOME_TAB_CONTENT}
			</div>
	    </div>
	</div>
{/if}
*}
<div class="clearfix">
	{if isset($HOOK_HOME) && $HOOK_HOME|trim}
		<!-- hook displayHome -->
		<div class="row">
			{$HOOK_HOME}
		</div>
		<!-- end hook displayHome -->
	{/if}
		<!-- hook displayEasyCarousel2 -->
		<div class="row bottom-block-page">
			<div class="container underline-diamond">
				<div class="clearfix">
					{hook h='displayEasyCarousel2'}
				</div>
			</div>
		</div>
		<!-- hook displayEasyCarousel2 -->
		<!-- hook displayCustomBanners2 -->
		<div class="row line-about-news">
			<div class="container">
				<div class="clearfix">
					{hook h='displayCustomBanners2'}
				</div>
			</div>
		</div>
		<!-- end hook displayCustomBanners2 -->
		<!-- hook displayEasyCarousel3 -->
		<div class="row">
			<div class="container">
				<div class="clearfix">
					{hook h='displayEasyCarousel3'}
				</div>
			</div>
		</div>
		<!-- end hook displayEasyCarousel3 -->
		<!-- hook displayHomeLine-->
		<div class="displayHomeLine">
			<div class="container">
				<div class="row">
					{hook h='displayHomeCustom'}
				</div>
			</div>
		</div>
		<!-- end hook displayHomeLine -->
</div>
