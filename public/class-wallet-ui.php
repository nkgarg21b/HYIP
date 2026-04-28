<?php
class HYIP_Wallet_UI {

    public static function render() {
        if (!is_user_logged_in()) return 'Login required';

        $user_id = get_current_user_id();
        $balance = HYIP_Wallet::get_balance($user_id);

        return '<h3>Wallet</h3>
        <p>Balance: ₹'.$balance.'</p>';
    }
}
