{if $page.page_type == $smarty.const.PAGE_TYPE_STATIC_PAGE}
    {include file=$template}
{else}
    {hook name="pages:page_content"}
        <div {live_edit name="page:description:{$page.page_id}"}>{$page.description nofilter}</div>
    {/hook}

    {capture name="mainbox_title"}<span {live_edit name="page:page:{$page.page_id}"}>{$page.page}</span>{/capture}

    {hook name="pages:page_extra"}
    {/hook}
{/if}
