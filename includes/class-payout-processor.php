<?php
class HYIP_Payout_Processor {

    public static function process($withdrawal) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        // Prevent duplicate payouts
        if ($withdrawal->payout_status === 'success') {
            return ['status' => 'ALREADY_PROCESSED'];
        }

        // Limit retries
        if ($withdrawal->attempt_count >= 3) {
            return ['status' => 'MAX_RETRIES_REACHED'];
        }

        $transferId = "wd_" . $withdrawal->id;

        $request = [
            'transferId' => $transferId,
            'amount' => $withdrawal->amount,
            'user_id' => $withdrawal->user_id
        ];

        // Call Cashfree
        $response = HYIP_Cashfree::payout($withdrawal);

        $status = 'failed';

        if (isset($response['status']) && $response['status'] === 'SUCCESS') {
            $status = 'success';
        }

        // Update withdrawal
        $wpdb->update($table, [
            'payout_id' => $transferId,
            'payout_status' => $status,
            'attempt_count' => $withdrawal->attempt_count + 1
        ], ['id' => $withdrawal->id]);

        // Log everything
        HYIP_Payout_Logger::log($withdrawal->id, $request, $response, $status);

        return [
            'status' => $status,
            'response' => $response
        ];
    }
}
