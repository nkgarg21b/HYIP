<?php
if (!is_user_logged_in()) return;

$user_id = get_current_user_id();
$balance = HYIP_Wallet::get_balance($user_id);

$min = get_option('hyip_min_withdraw', 500);
$max = get_option('hyip_max_withdraw', 50000);
$cooldown_hours = get_option('hyip_withdraw_cooldown', 24);

$last = HYIP_Transactions::get_last_withdrawal($user_id);
$cooldown_ok = true;
if ($last) {
    $last_time = strtotime($last->created_at);
    if (time() - $last_time < ($cooldown_hours * 3600)) {
        $cooldown_ok = false;
    }
}

$nonce = wp_create_nonce('hyip_withdraw');
?>

<h3>Withdraw Funds</h3>
<p>Balance: ₹<?php echo $balance; ?></p>

<form method="post">
    <input type="hidden" name="hyip_nonce" value="<?php echo $nonce; ?>">
    <input type="number" name="withdraw_amount" placeholder="Enter Amount" required>
    <button type="submit" name="withdraw_btn">Request Withdrawal</button>
</form>

<h3>Your Withdrawal History</h3>
<?php
$history = HYIP_Withdrawals::get_user_withdrawals($user_id);
if ($history) {
    echo '<table border="1" cellpadding="8"><tr><th>Amount</th><th>Status</th><th>Date</th></tr>';
    foreach ($history as $h) {
        echo '<tr><td>₹'.$h->amount.'</td><td>'.$h->status.'</td><td>'.$h->created_at.'</td></tr>';
    }
    echo '</table>';
} else {
    echo '<p>No withdrawals yet</p>';
}

if (isset($_POST['withdraw_btn'])) {

    if (!isset($_POST['hyip_nonce']) || !wp_verify_nonce($_POST['hyip_nonce'], 'hyip_withdraw')) {
        echo "<p>Security check failed</p>";
        return;
    }

    $amount = floatval($_POST['withdraw_amount']);

    if ($amount < $min) {
        echo "<p>Minimum withdrawal is ₹$min</p>";
        return;
    }

    if ($amount > $max) {
        echo "<p>Maximum withdrawal is ₹$max</p>";
        return;
    }

    if ($amount > $balance) {
        echo "<p>Insufficient balance</p>";
        return;
    }

    if (!$cooldown_ok) {
        echo "<p>You can only withdraw once every $cooldown_hours hours</p>";
        return;
    }

    $result = HYIP_Withdrawals::request($user_id, $amount);

    if ($result) {
        echo "<p>Withdrawal request submitted</p>";
    } else {
        echo "<p>Error processing request</p>";
    }
}
