<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * Time to wait for payment notification
 */
fn_define('IFRAME_PAYMENT_NOTIFICATION_TIMEOUT', 30);

if (defined('PAYMENT_NOTIFICATION')) {
    if ($mode == 'process') {
        $order_nonce = $_REQUEST['order_nonce'];
        $session_id = base64_decode($_REQUEST['sid']);
        $repay = $_REQUEST['repay'];
        
        // get cart and auth data from session
        Tygh::$app['session']->resetID($session_id);
        $cart = & Tygh::$app['session']['cart'];
        $auth = & Tygh::$app['session']['auth'];

        if ($repay != 'Y') {
            // place order
            list($order_id, $process_payment) = fn_place_order($cart, $auth);
        } else {
            $order_id = $order_nonce;
        }

        // store additional order data
        if (!empty($order_nonce)) {
            db_query('REPLACE INTO ?:order_data ?m', array(
                // add payment data
                array('order_id' => $order_id,  'type' => 'S', 'data' => TIME),
                // store order nonce
                array('order_id' => $order_id,  'type' => 'E', 'data' => $order_nonce)
            ));
        }

        // process payment notification
        $pp_response = array(
            'order_status' => 'P',
            'transaction_id' => $order_nonce
        );
        fn_finish_payment($order_id, $pp_response, false);

        fn_echo('Payment notification has been processed');

    } elseif ($mode == 'return') {
        $order_nonce = $_REQUEST['order_nonce'];

        $time = $order_id = 0;
        while ($time++ < IFRAME_PAYMENT_NOTIFICATION_TIMEOUT) {
            if ($order_id = db_get_field("SELECT order_id FROM ?:order_data WHERE data = ?s AND type = 'E'", $order_nonce)) {
                break;
            }
            sleep(1);
        }
        if ($order_id) {
            // redirect customer
            fn_order_placement_routines('route', $order_id, false);
        } else {
            // payment gateway takes to much time to process payment: show notice
            fn_set_notification('E', 'Error', 'Payment notification timeout has been exceeded');
            fn_order_placement_routines('checkout_redirect');
        }

    }
    
    exit;

} elseif (defined('IFRAME_MODE') || isset($mode) && $mode == 'repay') {
    // connect to emulated payment gateway
    $session_id = Tygh::$app['session']->getID();
    fn_create_payment_form(
        fn_url('iframe_payment.start'),
        array(
            'order_nonce' => $order_id,
            'sid' => base64_encode($session_id),
            'repay' => (isset($mode) && $mode == 'repay') ? 'Y' : 'N'
        ),
        'Iframe Payment',
        true,
        'get'
    );

}
