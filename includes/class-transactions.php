<?php
class HYIP_Transactions {

    public static function log($user_id, $type, $amount, $status = 'completed', $ref = '') {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_transactions';

        $wpdb->insert($table, [
            'user_id' => $user_id,
            'type' => $type,
            'amount' => $amount,
            'status' => $status,
            'reference_id' => $ref
        ]);
    }

    public static function get_user_transactions($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_transactions';

        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ));
    }
}
