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
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
					</div><!-- .row -->
				</div><!-- #columns -->
			</div><!-- .columns-container -->
			{if isset($HOOK_FOOTER)}
				<!-- Footer -->
					<footer id="footer" class="clearfix round-border-container-second top-position-border">
							<div class="blackgraund">
								<div class="container">
									<div class="row">
										{$HOOK_FOOTER}
									</div>
								</div>
							</div>
						<div class="copyright col-xs-12">
							<div class="inner_copyright row">
								<div class="container">
									{hook h="footerBottom"}
									<div class="wrap_copy">
										<span class="copy_a">© {$smarty.now|date_format:"%Y"} {l s='Created By'} <span>{l s='Prestapro.'}</span> {l s='All right reserved'}</span>
									</div>
									
								</div>
								
							</div>
							
						</div>
						<div id="back-top">
									<a href="#">
										<i class="font-up-open-big"></i>
									</a>
								</div>
					</footer>
					<!-- #footer -->
			{/if}
		</div><!-- #page -->
{/if}
{include file="$tpl_dir./global.tpl"}
	</body>
</html>