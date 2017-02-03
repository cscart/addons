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

if ($mode == 'view' && !empty($_REQUEST['page_id'])) {

    $page = fn_get_page_data($_REQUEST['page_id'], CART_LANGUAGE);

    if ($page['is_main'] == 'Y') {
        return array(CONTROLLER_STATUS_REDIRECT, 'index.index');
    }

    if ($page['page_type'] == PAGE_TYPE_STATIC_PAGE) {

        if (!empty($page['template'])) {
            $page_name = $page['template'];
        } else {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }

        $path_template = PATH_TO_STATIC_PAGE . $page_name . '.tpl';

        if ($file = Tygh\Themes\Themes::areaFactory('C')->getContentPath('templates/' . $path_template)) {
            Tygh::$app['view']->assign('template', $file[Tygh\Themes\Themes::PATH_ABSOLUTE]);
        } else {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
    }
}
