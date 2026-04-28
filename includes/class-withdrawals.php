<?php
class HYIP_Withdrawals {

    public static function request($user_id, $amount) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        $balance = HYIP_Wallet::get_balance($user_id);
        if ($balance < $amount) return false;

        // Deduct balance
        HYIP_Wallet::update_balance($user_id, $amount, 'debit');

        $wpdb->insert($table, [
            'user_id' => $user_id,
            'amount' => $amount,
            'status' => 'pending'
        ]);

        HYIP_Transactions::log($user_id, 'withdraw_request', $amount, 'pending');

        return true;
    }

    public static function get_all() {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';
        return $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
    }

    public static function approve($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        $wpdb->update($table, ['status' => 'approved'], ['id' => $id]);
    }

    public static function reject($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        $withdrawal = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d", $id));

        // Refund
        HYIP_Wallet::update_balance($withdrawal->user_id, $withdrawal->amount, 'credit');

        $wpdb->update($table, ['status' => 'rejected'], ['id' => $id]);
    }
}
