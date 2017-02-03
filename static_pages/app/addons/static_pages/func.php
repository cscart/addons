<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_static_pages_page_object_by_type(&$types)
{
    $types[PAGE_TYPE_STATIC_PAGE] = array(
        'single' => 'addons.static_pages.static_page',
        'name' => 'addons.static_pages.static_pages',
        'add_name' => 'addons.static_pages.add_static_page',
        'edit_name' => 'addons.static_pages.editing_static_page',
        'new_name' => 'addons.static_pages.new_static_page',
    );
}

function fn_static_pages_update_page_before(&$page_data, $page_id, $lang_code)
{
    $company_condition = '';
    $page_data['is_main'] = isset($page_data['is_main']) ? $page_data['is_main'] : 'N';

    if (fn_allowed_for('ULTIMATE')) {
        if ($page_data['is_main'] == 'Y'
            && isset($_REQUEST['share_objects']['pages'][$page_id])
            && count($_REQUEST['share_objects']['pages'][$page_id]) > 1
        ) {
            $page_data['is_main'] = 'N';
            fn_set_notification('W', __('warning'), __('addons.static_pages.shared_page_cant_be_main'));
        } else {
            $company_condition = db_quote(' AND ?:pages.company_id = ?i', $page_data['company_id']);
        }
    }

    if ($page_data['is_main'] == 'Y') {
        db_query("UPDATE ?:pages SET is_main = 'N' WHERE is_main = 'Y' ?p", $company_condition);
    }

    if (isset($page_data['template'])) {
        $page_data['template'] = trim($page_data['template']);
    }
}

function fn_static_pages_get_available_templates()
{
    $list = Tygh\Themes\Themes::areaFactory('C')->getDirContents(array(
        'dir' => 'templates/' . PATH_TO_STATIC_PAGE,
        'get_dirs' => false,
        'get_files' => true,
        'extension' => array('.tpl'),
    ), Tygh\Themes\Themes::STR_MERGE);

    return array_map(function($name) {
        return str_replace('.tpl', '', $name);
    }, array_keys($list));
}