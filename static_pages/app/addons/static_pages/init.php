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


// Special types
//define('FORM_VARIANT', 'G');
//define('FORM_RECIPIENT', 'J');
//define('FORM_IS_SECURE', 'U');
//define('FORM_SUBMIT', 'L');

fn_register_hooks(
    //'delete_page',
    //'update_page_post',
    //'get_page_data',
    'page_object_by_type',
    'update_page_before'
    //'clone_page',
    //'init_secure_controllers',
    //'settings_variants_image_verification_use_for'
);
