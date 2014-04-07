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


if ( !defined('AREA') ) { die('Access denied'); }

function fn_dd_products_calculate_cart($cart)
{
	if (defined('AJAX_REQUEST')) {
		$is_empty = fn_cart_is_empty($cart);

		$view = Registry::get('view');
		$view->assign('cart_is_empty', $is_empty);

		$view->display('addons/dd_products/hooks/categories/view.post.tpl');
	}
	
	return true;
}


?>