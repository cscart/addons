<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_iframe_payment_add_payment_processor()
{
    db_query('INSERT INTO ?:payment_processors (processor, processor_script, processor_template, admin_template, callback, type, addon) VALUES (?s, ?s, ?s, ?s, ?s, ?s, ?s)', 'Iframe Payment', 'iframe_payment.php', 'views/orders/components/payments/cc_outside.tpl', 'iframe_payment_admin.tpl', 'Y', 'P', 'iframe_payment');
}

function fn_iframe_payment_delete_payment_processor()
{
    db_query('DELETE FROM ?:payment_processors WHERE processor_script = ?s', 'iframe_payment.php');
}
