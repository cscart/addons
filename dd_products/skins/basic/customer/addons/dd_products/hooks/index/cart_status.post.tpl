<input type="hidden" value="{$smarty.session.cart.amount}" id="cart_products_count" />

<div id="dd_cart_content_init" class="list-container hidden">
	{assign var="_cart_products" value=$smarty.session.cart.products|array_reverse:true}
	<table>
	{foreach from=$_cart_products key="key" item="p"}
	{if !$p.extra.parent}
		{assign var="_image" value=$p.product_id|fn_get_image_pairs:'product':'M':true:true}
		<tr>
			<td>{include file="common_templates/image.tpl" image_width="40" images=$_image object_type="product" obj_id="dd_`$p.product_id`" show_thumbnail="Y"}</td>
			<td><a href="{"products.view?product_id=`$p.product_id`"|fn_url}">{$p.product_id|fn_get_product_name|escape}</a>{if !"CHECKOUT"|defined || $force_items_deletion}{include file="buttons/button.tpl" but_href="checkout.delete.from_status?cart_id=`$key`" but_meta="cm-ajax cm-ajax-force" but_rev="cart_status*" but_role="delete" but_name="delete_cart_item"}{/if}
			<p><strong class="valign">{$p.amount}</strong>&nbsp;x&nbsp;{include file="common_templates/price.tpl" value=$p.display_price span_id="price_`$key`" class="none"}
			</p></td>
		</tr>
	{/if}
	{/foreach}
	</table>
</div>