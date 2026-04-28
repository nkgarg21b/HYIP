<?php
class HYIP_Investments {

    public static function invest($user_id, $plan_id, $amount) {
        global $wpdb;

        $plans_table = $wpdb->prefix . 'hyip_plans';
        $inv_table   = $wpdb->prefix . 'hyip_investments';

        $plan = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $plans_table WHERE id = %d",
            $plan_id
        ));

        if (!$plan) return false;

        $daily_profit = ($amount * $plan->roi_percentage) / 100;

        $start = current_time('mysql');
        $end   = date('Y-m-d H:i:s', strtotime("+{$plan->duration_days} days"));

        // Deduct from wallet
        HYIP_Wallet::update_balance($user_id, $amount, 'debit');
        HYIP_Transactions::log($user_id, 'investment', $amount);

        // Insert investment
        $wpdb->insert($inv_table, [
            'user_id' => $user_id,
            'plan_id' => $plan_id,
            'amount' => $amount,
            'daily_profit' => $daily_profit,
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'active'
        ]);

        return true;
    }

    public static function process_daily_roi() {
        global $wpdb;

        $inv_table = $wpdb->prefix . 'hyip_investments';

        $investments = $wpdb->get_results("SELECT * FROM $inv_table WHERE status = 'active'");

        foreach ($investments as $inv) {
            if (strtotime($inv->end_date) < time()) {
                $wpdb->update($inv_table, ['status' => 'completed'], ['id' => $inv->id]);
                continue;
            }

            HYIP_Wallet::update_balance($inv->user_id, $inv->daily_profit, 'credit');
            HYIP_Transactions::log($inv->user_id, 'daily_profit', $inv->daily_profit);
        }
    }
}
