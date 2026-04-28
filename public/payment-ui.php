<?php
if (!is_user_logged_in()) return;
$user_id = get_current_user_id();
?>
<h3>Deposit Funds</h3>
<form method="post">
    <input type="number" name="deposit_amount" placeholder="Enter Amount" required>
    <button type="submit" name="deposit_btn">Deposit</button>
</form>

<?php
if (isset($_POST['deposit_btn'])) {
    $amount = $_POST['deposit_amount'];
    $order = HYIP_Deposits::create_order($user_id, $amount);

    echo "<p>Order Created: {$order['order_id']}</p>";
    echo "<p><strong>NOTE:</strong> Payment gateway integration next step</p>";
}
