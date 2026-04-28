<?php
class HYIP_Reconciliation {

    public static function run() {
        global $wpdb;

        $table = $wpdb->prefix . 'hyip_withdrawals';
        $rows = $wpdb->get_results("SELECT * FROM $table WHERE payout_status='pending'");

        foreach ($rows as $w) {
            // Simulate reconciliation (replace with API call later)
            $response = HYIP_Cashfree::payout($w);

            if (isset($response['status']) && $response['status'] === 'SUCCESS') {
                $wpdb->update($table, ['payout_status' => 'success'], ['id' => $w->id]);
            }

            HYIP_Payout_Logger::log($w->id, ['reconcile'=>true], $response, $response['status'] ?? 'unknown');
        }
    }
}
