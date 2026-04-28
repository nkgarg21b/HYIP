<?php
class HYIP_Fraud_Detector {

    public static function check_withdrawal($withdrawal) {
        global $wpdb;

        $flags = [];

        // Rule 1: Large withdrawal vs avg deposits
        $avg = $wpdb->get_var($wpdb->prepare(
            "SELECT AVG(amount) FROM {$wpdb->prefix}hyip_transactions WHERE user_id=%d AND type='deposit'",
            $withdrawal->user_id
        ));

        if ($avg && $withdrawal->amount > ($avg * 3)) {
            $flags[] = 'high_amount_withdrawal';
        }

        // Rule 2: Too many withdrawals in last 24h
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}hyip_withdrawals WHERE user_id=%d AND created_at >= NOW() - INTERVAL 1 DAY",
            $withdrawal->user_id
        ));

        if ($count > 3) {
            $flags[] = 'frequent_withdrawals';
        }

        // Rule 3: Withdrawal right after deposit
        $last_deposit = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(created_at) FROM {$wpdb->prefix}hyip_transactions WHERE user_id=%d AND type='deposit'",
            $withdrawal->user_id
        ));

        if ($last_deposit && strtotime($withdrawal->created_at) - strtotime($last_deposit) < 3600) {
            $flags[] = 'quick_withdrawal';
        }

        foreach ($flags as $flag) {
            self::log_flag($withdrawal->user_id, $withdrawal->id, $flag);
        }

        return $flags;
    }

    private static function log_flag($user_id, $withdrawal_id, $type) {
        global $wpdb;

        $table = $wpdb->prefix . 'hyip_risk_flags';

        $wpdb->insert($table, [
            'user_id' => $user_id,
            'withdrawal_id' => $withdrawal_id,
            'flag_type' => $type,
            'created_at' => current_time('mysql')
        ]);
    }
}
