<?php
/******************************************************************************
*                                                                             *
*     Copyright (c) 2004 Simbirsk Technologies LTD. All rights reserved.      *
*                                                                             *
*******************************************************************************
*                                                                             *
* CS-Cart  is  commercial  software,  only  users  who have purchased a valid *
* license through  http://www.cs-cart.com/  and  accept  to the terms of this *
* License Agreement can install this product.                                 *
*                                                                             *
*******************************************************************************
* THIS  CS-CART  SHOP END-USER LICENSE AGREEMENT IS A LEGAL AGREEMENT BETWEEN *
* YOU  AND  YOUR  COMPANY (COLLECTIVELY, "YOU") AND SIMBIRSK TECHNOLOGIES LTD *
* (HEREINAFTER  REFERRED  TO   AS  "THE AUTHOR")  FOR  THE  SOFTWARE  PRODUCT *
* IDENTIFIED  ABOVE,  WHICH  INCLUDES  COMPUTER   SOFTWARE  AND  MAY  INCLUDE *
* ASSOCIATED   MEDIA,   PRINTED  M ATERIALS,   AND   "ONLINE"  OR  ELECTRONIC *
* DOCUMENTATION  (COLLECTIVELY,  THE  "SOFTWARE").  BY  USING  THE  SOFTWARE, *
* YOU  SIGNIFY YOUR AGREEMENT TO ALL TERMS, CONDITIONS, AND NOTICES CONTAINED *
* OR  REFERENCED  HEREIN.  IF  YOU  ARE NOT  WILLING  TO  BE  BOUND  BY  THIS *
* AGREEMENT, DO NOT INSTALL OR USE THE SOFTWARE.                              *
*                                                                             *
* PLEASE   READ  THE   FULL  TEXT  OF  SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS *
* ALSO AVAILABLE AT THE FOLLOWING URL: http://www.cs-cart.com/license.html    *
******************************************************************************/

//
// $Id$
//

if ( !defined('AREA') ) { die('Access denied'); }

function fn_custom_countries_selectbox_manual_countries_list(&$fields, $section, &$addon_options)
{
	$fields[$section]['manual_list'] = array (
		'type' => 'M',
		'description' => fn_get_lang_var('ccs_manual_list'),
		'variants' => fn_get_simple_countries(true),
		'tooltip' => fn_get_lang_var('ccs_manual_list_tooltip'),
	);
}

function fn_custom_countries_selectbox_format_countries($_countries) {

	$countries = array(
		'popular' => array(),
		'alphabetical' => $_countries
	);
	
	$selectbox_style = Registry::get('addons.custom_countries_selectbox.selectbox_style');
	
	if ($selectbox_style == 'manual') {
		$manual_list = Registry::get('addons.custom_countries_selectbox.manual_list');
		$codes = array_keys($manual_list);
	}
	
	if ($selectbox_style == 'popular') {
		$codes = fn_custom_countries_selectbox_get_popular_counties();
	}
	
	$matches = array();
	foreach ($_countries as $k => $v) {
		if (in_array($v['code'], $codes)) {
			$matches[$v['code']] = $k;
		}
	}
	
	foreach ($codes as $code) {
		$countries['popular'][] = $_countries[$matches[$code]];
		unset($countries['alphabetical'][$matches[$code]]);
	}
	
	return $countries;
}

function fn_custom_countries_selectbox_get_popular_counties()
{
	Registry::register_cache('custom_countries_selectbox', (SECONDS_IN_DAY * 7), CACHE_LEVEL_TIME);
			
	if (Registry::is_exist('custom_countries_selectbox')) {
		return Registry::get('custom_countries_selectbox');
	}

	$codes = array();
	
	$bil = db_get_hash_single_array("SELECT COUNT(*) as count, b_country as code FROM ?:orders GROUP BY b_country", array('code', 'count'));
	$ship = db_get_hash_single_array("SELECT COUNT(*) as count, s_country as code FROM ?:orders GROUP BY b_country", array('code', 'count'));
	
	foreach ($ship as $code => $count) {
		if (!empty($bil[$code])) {
			$bil[$code] += $count;
		} else {
			$bil[$code] = $count;
		}
	}
	
	arsort($bil);
	
	$count = Registry::get('addons.custom_countries_selectbox.popular_count');
	if ($count < 1) {
		$count = 1;
	}
	$codes = array_slice(array_keys($bil), 0, $count);
	
	Registry::set('custom_countries_selectbox', $codes);
	return $codes;
}

?>