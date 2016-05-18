<div style="text-align: center; background-color: white; padding: 50px;">
    <h3 style="border-bottom: 1px solid #eee;">Iframe Payment</h3>
    <form method="post" action="{"iframe_payment.process"|fn_url}">
        <p><strong>Debug data</strong></p>
        <p>
            <label>Order nonce:</label><br>
            <input type="text" name="order_nonce" value="{$order_nonce}" size="60" readonly>
        </p>
        <p>
            <label>Session ID:</label><br>
            <input type="text" name="sid" value="{$sid}" size="60" readonly>
        </p>
        <p><input type="submit" value="Pay"></p>
    </form>
</div>