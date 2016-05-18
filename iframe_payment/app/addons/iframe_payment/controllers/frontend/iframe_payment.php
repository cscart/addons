<?php
/**
 * This controller emulates a payment gateway
 */

if (!defined('BOOTSTRAP')) { die('Access denined'); }

Tygh::$app['view']->assign('order_nonce', $_REQUEST['order_nonce']);
Tygh::$app['view']->assign('sid', $_REQUEST['sid']);
Tygh::$app['view']->display("addons/iframe_payment/views/{$mode}.tpl");

exit;