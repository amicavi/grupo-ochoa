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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="wrap-friend">
	<a id="send_friend_button" class="btn btn-default btn-usefull" href="#send_friend_form">
		<span>{l s='Send to a friend' mod='sendtoafriend'}</span>
	</a>
</div>
	<div style="display: none;">
		<div id="send_friend_form">
			<h2  class="page-subheading">
				{l s='Send to a friend' mod='sendtoafriend'}
			</h2>
			<div class="row">
				<div class="product clearfix col-xs-12">
					<img src="{$link->getImageLink($stf_product->link_rewrite, $stf_product_cover, 'home_default')|escape:'html':'UTF-8'}" alt="{$stf_product->name|escape:'html':'UTF-8'}" class="img-responsive"/>
					<div class="product_desc">
						<p class="product_name">
							<strong>{$stf_product->name}</strong>
						</p>
						{$stf_product->description_short}
					</div>
				</div><!-- .product -->
				<div class="send_friend_form_content col-xs-12" id="send_friend_form_content">
					<div id="send_friend_form_error" class="alert alert-danger" style="display: none;"></div>
					<div id="send_friend_form_success"></div>
					<div class="form_container clearfix row">
						<p class="text text1 col-sm-6 col-xs-12">
							<label for="friend_name">
								{l s='Name of your friend' mod='sendtoafriend'} <sup class="required">*</sup> :
							</label>
							<input id="friend_name" class="form-control"  name="friend_name" type="text" value=""/>
						</p>
						<p class="text text2 col-sm-6 col-xs-12">
							<label for="friend_email">
								{l s='E-mail address of your friend' mod='sendtoafriend'} <sup class="required">*</sup> :
							</label>
							<input id="friend_email" class="form-control" name="friend_email" type="text" value=""/>
						</p>
					</div>
					<div class="submit">
						<div class="col-xs-6 text1">
							<a class="closefb button grey_btn" href="#">
								{l s='Cancel' mod='sendtoafriend'}
							</a>
						</div>
						<div class="col-xs-6 text2">
							<button id="sendEmail" class="button" name="sendEmail" type="submit">
								<span>{l s='Send' mod='sendtoafriend'}</span>
							</button>
						</div>
					</div>
				</div> <!-- .send_friend_form_content -->
			</div>
		</div>
	</div>
{addJsDef stf_secure_key=$stf_secure_key}
{addJsDefL name=stf_msg_success}{l s='Your e-mail has been sent successfully' mod='sendtoafriend' js=1}{/addJsDefL}
{addJsDefL name=stf_msg_error}{l s='Your e-mail could not be sent. Please check the e-mail address and try again.' mod='sendtoafriend' js=1}{/addJsDefL}
{addJsDefL name=stf_msg_title}{l s='Send to a friend' mod='sendtoafriend' js=1}{/addJsDefL}
{addJsDefL name=stf_msg_required}{l s='You did not fill required fields' mod='sendtoafriend' js=1}{/addJsDefL}
