<?php
class HYIP_Wallet {

    public static function create_wallet($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_wallet';

        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE user_id = %d",
            $user_id
        ));

        if (!$exists) {
            $wpdb->insert($table, [
                'user_id' => $user_id,
                'balance' => 0
            ]);
        }
    }

    public static function get_balance($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_wallet';

        return $wpdb->get_var($wpdb->prepare(
            "SELECT balance FROM $table WHERE user_id = %d",
            $user_id
        ));
    }

    public static function update_balance($user_id, $amount, $type = 'credit') {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_wallet';

        if ($type === 'credit') {
            $wpdb->query($wpdb->prepare(
                "UPDATE $table SET balance = balance + %f WHERE user_id = %d",
                $amount, $user_id
            ));
        } else {
            $wpdb->query($wpdb->prepare(
                "UPDATE $table SET balance = balance - %f WHERE user_id = %d",
                $amount, $user_id
            ));
        }
    }
}
