<?php
class HYIP_Payout_Logger {

    public static function log($withdrawal_id, $request, $response, $status) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_payout_logs';

        $wpdb->insert($table, [
            'withdrawal_id' => $withdrawal_id,
            'request_payload' => json_encode($request),
            'response_payload' => json_encode($response),
            'status' => $status,
            'created_at' => current_time('mysql')
        ]);
    }
}
