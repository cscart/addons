<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ($mode == 'update' || $mode == 'add') {

        if ($mode == 'update') {
            $page_type = Tygh::$app['view']->getTemplateVars('page_type');
        } else {
            $page_type = $_REQUEST['page_type'];
        }

        if ($page_type == PAGE_TYPE_STATIC_PAGE) {
            Tygh::$app['view']->assign('static_pages_templates', fn_static_pages_get_available_templates());
            Tygh::$app['view']->assign('static_pages_templates_dir', fn_get_theme_path('[relative]/[theme]/templates/' . PATH_TO_STATIC_PAGE, 'C'));
        }
    }

}
