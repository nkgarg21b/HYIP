<?php
class HYIP_Cashfree_Webhook {

    public static function handle() {
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);

        // 🔐 Signature Verification
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $signature = isset($headers['x-webhook-signature']) ? $headers['x-webhook-signature'] : '';
        $secret = get_option('hyip_cashfree_webhook_secret');

        $computed = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        if (!$secret || $signature !== $computed) {
            status_header(401);
            exit('Invalid signature');
        }

        // Validate payload
        if (!isset($data['transfer']['transferId'])) {
            status_header(400);
            exit('Invalid payload');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        $transferId = $data['transfer']['transferId'];
        $status = strtolower($data['transfer']['status']);

        $withdrawal_id = intval(str_replace('wd_', '', $transferId));

        $payout_status = ($status === 'success') ? 'success' : 'failed';

        $wpdb->update($table, [
            'payout_status' => $payout_status
        ], ['id' => $withdrawal_id]);

        HYIP_Payout_Logger::log($withdrawal_id, ['webhook' => true], $data, $payout_status);

        status_header(200);
        echo 'OK';
        exit;
    }
}
