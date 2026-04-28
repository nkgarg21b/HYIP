<?php
class HYIP_Deposits {

    public static function create_order($user_id, $amount) {
        $order_id = 'HYIP_' . time() . '_' . $user_id;
        return [
            'order_id' => $order_id,
            'amount' => $amount
        ];
    }

    public static function credit_wallet($user_id, $amount, $ref = '') {
        HYIP_Wallet::update_balance($user_id, $amount, 'credit');
        HYIP_Transactions::log($user_id, 'deposit', $amount, 'completed', $ref);
    }
}
