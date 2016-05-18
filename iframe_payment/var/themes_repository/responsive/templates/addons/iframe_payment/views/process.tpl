<div style="text-align: center; background-color: white; padding: 50px;">
    <h3 style="border-bottom: 1px solid #eee;">Iframe Payment</h3>
    <p>
        Payment has been received
    </p>
    <form method="post" target="_parent" action="{"payment_notification.return?payment=iframe_payment&order_nonce=`$order_nonce`&sid=`$sid`"|fn_url}">
        <input type="submit" value="Continue">
    </form>
    <form method="post" target="_blank" action="{"payment_notification.process?payment=iframe_payment&order_nonce=`$order_nonce`&sid=`$sid`"|fn_url}">
        <input type="submit" value="Send payment notification">
    </form>
</div>