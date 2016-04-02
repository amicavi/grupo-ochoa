{if $MENU != ''}
	<!-- Menu -->
	<div id="block_top_menu" class="sf-contener clearfix col-md-10 col-sm-9 col-xs-12">
		<div class="cat-title">{l s="Categories" mod="blocktopmenu"}</div>
		<ul class="sf-menu clearfix menu-content">
			{$MENU}
			{if $MENU_SEARCH}
				<li class="sf-search noBack">
					<form id="searchbox" action="{$link->getPageLink('search')|escape:'html':'UTF-8'}" method="get">
						<p>
							<input type="hidden" name="controller" value="search" />
							<input type="hidden" value="position" name="orderby"/>
							<input type="hidden" value="desc" name="orderway"/>
							<input id="input-search-menu" type="text" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|escape:'html':'UTF-8'}{/if}" />
							<button type="submit" name="submit_search_menu" class="button-search-menu">
							</button>
							<span class="fake-button"></span>
						</p>
					</form>
				</li>
			{/if}
		</ul>
	</div>
	<!--/ Menu -->
{/if}