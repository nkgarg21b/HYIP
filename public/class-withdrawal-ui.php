<?php
class HYIP_Withdrawal_UI {

    public static function render() {
        if (!is_user_logged_in()) return 'Login required';

        $user_id = get_current_user_id();

        if (!HYIP_KYC::is_verified($user_id)) {
            return '<p style="color:red;">Complete KYC before withdrawal</p>';
        }

        $balance = HYIP_Wallet::get_balance($user_id);

        if (isset($_POST['withdraw'])) {
            $amount = floatval($_POST['amount']);
            HYIP_Withdrawals::request($user_id, $amount);
            echo '<p>Withdrawal Requested</p>';
        }

        return '<p>Balance: ₹'.$balance.'</p>
        <form method="post">
            <input name="amount" placeholder="Amount" required>
            <button name="withdraw">Withdraw</button>
        </form>';
    }
}
