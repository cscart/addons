<div class="control-group">
    <label class="control-label" for="elm_page_is_main">{__("addons.static_pages.use_as_main_page")}:</label>
    <div class="controls">
        <input type="hidden" name="page_data[is_main]" value="N">
        <span class="checkbox">
            <input type="checkbox" name="page_data[is_main]" id="elm_page_is_main" {if $page_data.is_main == "Y"}checked="checked"{/if} value="Y">
        </span>
    </div>
</div>

{if $page_type == $smarty.const.PAGE_TYPE_STATIC_PAGE}
    <div class="control-group">
        <label for="elm_page_template" class="control-label">{__("addons.static_pages.template")}:</label>
        <div class="controls">
            {if $static_pages_templates}
                <select id="elm_page_template" name="page_data[template]">
                    {foreach $static_pages_templates as $template}
                        <option value="{$template}"
                                {if $page_data.template == $template}selected="selected"{/if}
                        >{$template}</option>
                    {/foreach}
                </select>
            {else}
                <input type="hidden" name="page_data[template]" value=""/>
                <p>{__("addons.static_pages.no_templates", ["[tpl_dir]" => $static_pages_templates_dir])}</p>
            {/if}
        </div>
    </div>
{/if}

{if $page_type != $smarty.const.PAGE_TYPE_LINK && $page_type != $smarty.const.PAGE_TYPE_STATIC_PAGE}
    <div class="control-group">
        <label class="control-label" for="elm_page_descr">{__("description")}:</label>
        <div class="controls">
            <textarea id="elm_page_descr" name="page_data[description]" cols="55" rows="8" class="cm-wysiwyg input-large">{$page_data.description}</textarea>

            {if $view_uri}
                {include
                file="buttons/button.tpl"
                but_href="customization.update_mode?type=live_editor&status=enable&frontend_url={$view_uri|urlencode}{if "ULTIMATE"|fn_allowed_for}&switch_company_id={$page_data.company_id}{/if}"
                but_text=__("edit_content_on_site")
                but_role="action"
                but_meta="btn-small btn-live-edit cm-post"
                but_target="_blank"}
            {/if}
        </div>
    </div>
{/if}

{if $page_type == $smarty.const.PAGE_TYPE_LINK}
    {include file="views/pages/components/pages_link.tpl"}
{/if}