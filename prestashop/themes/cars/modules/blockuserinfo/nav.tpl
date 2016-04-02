<!-- Block user information module NAV  -->
<div class="header_user_info">
	<a id="drop_links" href="#" title="{l s='List of links user info' mod='blockuserinfo'}"><i class="fa fa-user"></i></a>
	<ul id="drop_content_user" class="row">
		<li>
			<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" class="account" rel="nofollow"><span>{l s='My account' mod='blockuserinfo'}</span>
			</a>
		</li>
		<li>
			<a 	class="wish_link" href="{$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|escape:'html':'UTF-8'}" title="{l s='My wishlists' mod='blockuserinfo'}"><span>{l s='Wish List' mod='blockuserinfo'}</span>
			</a>
		</li>
		<li>
			<a href="{$link->getPageLink($order_process, true)|escape:'html':'UTF-8'}" title="{l s='View my shopping cart' mod='blockuserinfo'}" rel="nofollow" class="shop_cart_user"><span>{l s='Shopping Cart' mod='blockuserinfo'}</span>
			</a>
		</li>
		<li>
			{if $is_logged}
				<a class="logout log" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log me out' mod='blockuserinfo'}"><span>{l s='Log out' mod='blockuserinfo'}</span>
				</a>
			{else}
				<a class="login log" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Log in to your customer account' mod='blockuserinfo'}"><span>{l s='Login' mod='blockuserinfo'}</span>
				</a>
			{/if}
		</li>
	</ul>
</div>
<!-- /Block usmodule NAV -->
