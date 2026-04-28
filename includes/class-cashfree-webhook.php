<?php
class HYIP_Cashfree_Webhook {

    public static function handle() {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);

        // Basic validation
        if (!isset($data['transfer']['transferId'])) {
            status_header(400);
            exit('Invalid payload');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        $transferId = $data['transfer']['transferId'];
        $status = strtolower($data['transfer']['status']);

        // Extract withdrawal ID
        $withdrawal_id = intval(str_replace('wd_', '', $transferId));

        $payout_status = ($status === 'success') ? 'success' : 'failed';

        // Update DB based on webhook (source of truth)
        $wpdb->update($table, [
            'payout_status' => $payout_status
        ], ['id' => $withdrawal_id]);

        // Log webhook
        HYIP_Payout_Logger::log($withdrawal_id, ['webhook' => true], $data, $payout_status);

        status_header(200);
        echo 'OK';
        exit;
    }
}
