{*
*
*  @author     Prestapro
*  @copyright  2015-2015 Prestapro
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<!-- Amazinsearch module TOP -->
<div id="amazing_block_top" class="amazing-search">
	<form method="get" action="{$link->getPageLink('search', true)|escape:'htmlall':'UTF-8'}" id="searchbox">
			{if $categories}
			<select id="amazing_search_select" name="category_id">
				<option value="">---</option>
				{foreach from=$categories item=category}
					<option {if $category.id_category eq $id_category}selected="selected"{/if} value="{$category.id_category|escape:'htmlall':'UTF-8'}">{$category.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
			{/if}
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query" type="text" id="amazingsearch_query_top" name="search_query" value="{$search_query|escape:'html':'UTF-8'|stripslashes}" />
			<button type="submit" name="submit_search" class="button-search-menu"></button>
			<span class="fake-button"></span>
	</form>
	<div class="amazingsearch_result"></div>
</div>
<script type="text/javascript">
lang_id = "{$lang_id|escape:'htmlall':'UTF-8'}";
shop_id = "{$shop_id|escape:'htmlall':'UTF-8'}";
</script>
<!-- /Amazing search module TOP -->
