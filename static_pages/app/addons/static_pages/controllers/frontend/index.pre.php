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

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'index') {

    $company_condition = '';

    if (fn_allowed_for('ULTIMATE')) {
        $company_condition = db_quote(' AND ?:pages.company_id = ?i', Registry::get('runtime.company_id'));
    }

    $main_page_id = db_get_field("SELECT page_id FROM ?:pages WHERE is_main = 'Y' ?p", $company_condition);

    if (!empty($main_page_id)) {
        $page = fn_get_page_data($main_page_id);

        Tygh::$app['view']->assign('page', $page);

        if (!empty($page['template'])) {
            $path_template = PATH_TO_STATIC_PAGE . $page['template'] . '.tpl';

            if ($file = Tygh\Themes\Themes::areaFactory('C')->getContentPath('templates/' . $path_template)) {
                Tygh::$app['view']->assign('template', $file[Tygh\Themes\Themes::PATH_ABSOLUTE]);
            } else {
                return array(CONTROLLER_STATUS_NO_PAGE);
            }
        }

        if (!empty($page['page_title'])) {
            Tygh::$app['view']->assign('page_title', $page['page_title']);
        }

        if (!empty($page['meta_description']) || !empty($page['meta_keywords'])) {
            Tygh::$app['view']->assign('meta_description', $page['meta_description']);
            Tygh::$app['view']->assign('meta_keywords', $page['meta_keywords']);
        }

        Tygh::$app['view']->assign('content_tpl', 'views/pages/view.tpl');

        $_REQUEST['dispatch'] = 'pages.view';
        $_REQUEST['page_id'] = $main_page_id;
    }
}