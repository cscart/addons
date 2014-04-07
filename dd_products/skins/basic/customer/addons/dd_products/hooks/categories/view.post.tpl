<div class="cm-draggable-cart" id="draggable_cart">
	<a href="{"checkout.cart"|fn_url}">
	{if $cart_is_empty}
		<img border="0" src="{$config.skin_path}/addons/dd_products/images/empty.gif" />
	{else}
		<img border="0" src="{$config.skin_path}/addons/dd_products/images/full.gif" />
	{/if}
	</a>
	{literal}
	<script type="text/javascript">
	//<![CDATA[
	$(function(){
		fn_dd_check_cart_status();
		fn_dd_init_delete_links();
	});
	//]]>
	</script>
	{/literal}
<!--draggable_cart--></div>

<div id="dd_cart_content" class="list-container">
</div>