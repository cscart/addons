{if $products}

    {script src="js/tygh/exceptions.js"}

    {if !$no_pagination}
        {include file="common/pagination.tpl"}
    {/if}

    {if !$no_sorting}
        {include file="views/products/components/sorting.tpl"}
    {/if}

    {foreach from=$products item=product key=key name="products"}
        {capture name="capt_options_vs_qty"}{/capture}
        {hook name="products:product_block"}
            {assign var="obj_id" value=$product.product_id}
            {assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
            {include file="common/product_data.tpl" product=$product min_qty=true}

            <div class="ty-product-list clearfix">

                {assign var="form_open" value="form_open_`$obj_id`"}
                {$smarty.capture.$form_open nofilter}
                {if $bulk_addition}
                    <input class="cm-item ty-float-right ty-product-list__bulk" type="checkbox" id="bulk_addition_{$obj_prefix}{$product.product_id}" name="product_data[{$product.product_id}][amount]" value="{if $js_product_var}{$product.product_id}{else}1{/if}" {if ($product.zero_price_action == "R" && $product.price == 0)}disabled="disabled"{/if} />
                {/if}
                
                <div class="ty-product-list__image">
                    {hook name="products:product_block_image"}
                    <span class="cm-reload-{$obj_prefix}{$obj_id} image-reload" id="list_image_update_{$obj_prefix}{$obj_id}">
                        {if !$hide_links}
                            <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">
                            <input type="hidden" name="image[list_image_update_{$obj_prefix}{$obj_id}][link]" value="{"products.view?product_id=`$product.product_id`"|fn_url}" />
                        {/if}

                        {assign var="discount_label" value="discount_label_`$obj_prefix``$obj_id`"}
                        {$smarty.capture.$discount_label nofilter}

                        <input type="hidden" name="image[list_image_update_{$obj_prefix}{$obj_id}][data]" value="{$obj_id_prefix},{$settings.Thumbnails.product_lists_thumbnail_width},{$settings.Thumbnails.product_lists_thumbnail_height},product" />
                        {include file="common/image.tpl" image_width=$settings.Thumbnails.product_lists_thumbnail_width obj_id=$obj_id_prefix images=$product.main_pair image_height=$settings.Thumbnails.product_lists_thumbnail_height}

                        {if !$hide_links}
                            </a>
                        {/if}
                    <!--list_image_update_{$obj_prefix}{$obj_id}--></span>
                    <div class="ty-product-list__rating">
                        {assign var="rating" value="rating_$obj_id"}
                        {$smarty.capture.$rating nofilter}
                    </div>
                    {/hook}
                </div>
                <div class="ty-product-list__content">
                {hook name="products:product_block_content"}
                    {if $js_product_var}
                        <input type="hidden" id="product_{$obj_prefix}{$product.product_id}" value="{$product.product}" />
                    {/if}
                    {* res_delete_1 *}
                    {if $item_number == "Y"}<strong>{$smarty.foreach.products.iteration}.&nbsp;</strong>{/if}
                    {assign var="sku" value="sku_$obj_id"}{$smarty.capture.$sku nofilter}
                    {* /res_delete_1 *}

                    <div class="ty-product-list__info">
                        <div class="ty-product-list__item-name">
                            {assign var="name" value="name_$obj_id"}
                            {$smarty.capture.$name nofilter}
                        </div>

                        <div class="ty-product-list__price">
                            {assign var="old_price" value="old_price_`$obj_id`"}
                            {if $smarty.capture.$old_price|trim}
                                {$smarty.capture.$old_price nofilter}
                            {/if}

                            {assign var="price" value="price_`$obj_id`"}
                            {$smarty.capture.$price nofilter}

                            {assign var="clean_price" value="clean_price_`$obj_id`"}
                            {$smarty.capture.$clean_price nofilter}

                            {assign var="list_discount" value="list_discount_`$obj_id`"}
                            {$smarty.capture.$list_discount nofilter}
                        </div>
                        
                        
                        <div class="ty-product-list__feature">
                            {assign var="product_features" value="product_features_`$obj_id`"}
                            {$smarty.capture.$product_features nofilter}
                        </div>
                        <div class="ty-product-list__description">
                            {assign var="prod_descr" value="prod_descr_`$obj_id`"}
                            {$smarty.capture.$prod_descr nofilter}
                        </div>
                        
                        {if !$smarty.capture.capt_options_vs_qty}
                            <div class="ty-product-list__option">
                                {assign var="product_options" value="product_options_`$obj_id`"}
                                {$smarty.capture.$product_options nofilter}
                            </div>

                            {assign var="product_amount" value="product_amount_`$obj_id`"}
                            {$smarty.capture.$product_amount nofilter}
                            
                            <div class="ty-product-list__qty">
                                {assign var="qty" value="qty_`$obj_id`"}
                                {$smarty.capture.$qty nofilter}
                            </div>
                        {/if}

                        {assign var="advanced_options" value="advanced_options_`$obj_id`"}
                        {$smarty.capture.$advanced_options nofilter}

                        {assign var="min_qty" value="min_qty_`$obj_id`"}
                        {$smarty.capture.$min_qty nofilter}

                        {assign var="product_edp" value="product_edp_`$obj_id`"}
                        {$smarty.capture.$product_edp nofilter}
                    </div>
                    
                    <div class="ty-product-list__control">
                        {assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
                        {$smarty.capture.$add_to_cart nofilter}
                    </div>
                {/hook}
                </div>
                {assign var="form_close" value="form_close_`$obj_id`"}
                {$smarty.capture.$form_close nofilter}
            </div>
            {if !$smarty.foreach.products.last}
            <hr />
            {/if}
        {/hook}
    {/foreach}

    {if $bulk_addition}
        <script type="text/javascript">
            (function(_, $) {

                $(document).ready(function() {

                    $.ceEvent('on', 'ce.commoninit', function(context) {
                        if (context.find('input[type=checkbox][id^=bulk_addition_]').length) {
                            context.find('.cm-picker-product-options').switchAvailability(true, false);
                        }
                    });

                    $(_.doc).on('click', '.cm-item', function() {
                        $('#opt_' + $(this).prop('id').replace('bulk_addition_', '')).switchAvailability(!this.checked, false);
                    });
                });

            }(Tygh, Tygh.$));
        </script>
    {/if}

    {if !$no_pagination}
        {include file="common/pagination.tpl" force_ajax=$force_ajax}
    {/if}

{/if}

{capture name="mainbox_title"}{$title}{/capture}
