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

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (AREA == 'A') {
		$_auth = NULL;
	} else {
		$_auth = &$auth;
	}

	if ($mode == 'update') {
		if (!empty($_REQUEST['default_cc'])) {
			$cards_data = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
			if (!empty($cards_data)) {
				$cards = unserialize(fn_decrypt_text($cards_data));
				foreach ($cards as $cc_id => $val) {
					$cards[$cc_id]['default'] = $_REQUEST['default_cc'] == $cc_id ? true : false;
				}
				$cards_data = array (
					'credit_cards' => fn_encrypt_text(serialize($cards))
				);
				db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cards_data, $_REQUEST['profile_id']);
			}
		}

		return array(CONTROLLER_STATUS_OK, "profiles.update" . $suffix);
	}

	if ($mode == 'update_cards') {
		if (fn_is_restricted_admin($_REQUEST) == true) {
			return array(CONTROLLER_STATUS_DENIED);
		}
		$suffix = '';
		if (!empty($_REQUEST['profile_id']) && !empty($_REQUEST['payment_info'])) {
			$cards_data = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
			$cards = empty($cards_data) ? array() : unserialize(fn_decrypt_text($cards_data));

			$id = empty($_REQUEST['card_id']) ? 'cc_' . TIME : $_REQUEST['card_id'];
			$cards[$id] = $_REQUEST['payment_info'];
			$cards[$id]['default'] = empty($cards_data) ? true : (empty($_REQUEST['default_cc']) ? false : true);

			$cards_data = array (
				'credit_cards' => fn_encrypt_text(serialize($cards))
			);
			db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cards_data, $_REQUEST['profile_id']);

			if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
				$suffix = "?profile_id=$_REQUEST[profile_id]";
			}
			if (AREA == 'A' && !empty($_REQUEST['user_id'])) {
				$suffix .= "?user_id=$_REQUEST[user_id]";
			}
		}
		return array(CONTROLLER_STATUS_OK, "profiles.update" . $suffix);
	}
}

if ($mode == 'update') {
	if (AREA == 'C') {
		Registry::set('navigation.tabs.credit_cards', array (
			'title' => fn_get_lang_var('credit_cards'),
			'js' => true
		));
		$credit_cards = fn_get_static_data_section('C', true, 'credit_card');
		$view->assign('credit_cards', $credit_cards);
		$card_names = array();
		foreach ($credit_cards as $val) {
			$card_names[$val['param']] = $val['descr'];
		}
		$view->assign('card_names', $card_names);
		$condition = '';
		if (!empty($_REQUEST['profile_id'])) {
			$condition .= db_quote("AND profile_id = ?i", $_REQUEST['profile_id']);
		}
		if (!empty($auth['user_id'])) {
			$condition .= db_quote("AND user_id = ?i", $auth['user_id']);
		}
		$profile_cards = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE 1 $condition");
		$view->assign('profile_cards', empty($profile_cards) ? array() : unserialize(fn_decrypt_text($profile_cards)));
	}
} elseif ($mode == 'delete_card') {

	if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}

	if (!empty($_REQUEST['card_id']) && !empty($_REQUEST['profile_id'])) {
		$cards_data = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
		if (!empty($cards_data)) {
			$cards = unserialize(fn_decrypt_text($cards_data));

			$is_default = $cards[$_REQUEST['card_id']]['default'];
			unset($cards[$_REQUEST['card_id']]);
			if ($is_default && !empty($cards)) {
				reset($cards);
				$cards[key($cards)]['default'] = true;
			}

			$cards_data = array (
				'credit_cards' => empty($cards) ? '' : fn_encrypt_text(serialize($cards))
			);
			db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cards_data, $_REQUEST['profile_id']);

			if (AREA == 'A') {
				$uid = empty($_REQUEST['user_id']) ? $auth['user_id'] : $_REQUEST['user_id'];
			} else {
				$uid = $auth['user_id'];
			}
			return array(CONTROLLER_STATUS_OK, "profiles.update?user_id=$uid&profile_id=$_REQUEST[profile_id]");
		}
	}
	exit;
}

?>