<?php
if (!is_user_logged_in()) return;
$user_id = get_current_user_id();
?>

<h3>Withdraw Funds</h3>
<form method="post">
    <input type="number" name="withdraw_amount" placeholder="Enter Amount" required>
    <button type="submit" name="withdraw_btn">Request Withdrawal</button>
</form>

<?php
if (isset($_POST['withdraw_btn'])) {
    $amount = floatval($_POST['withdraw_amount']);
    $result = HYIP_Withdrawals::request($user_id, $amount);

    if ($result) {
        echo "<p>Withdrawal request submitted</p>";
    } else {
        echo "<p>Insufficient balance</p>";
    }
}
