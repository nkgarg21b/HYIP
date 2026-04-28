<?php
class HYIP_Dashboard_Analytics {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'Analytics Dashboard',
            'Analytics',
            'manage_options',
            'hyip-analytics',
            [self::class, 'page']
        );
    }

    public static function page() {
        global $wpdb;

        $wallet_table = $wpdb->prefix . 'hyip_wallet';
        $withdraw_table = $wpdb->prefix . 'hyip_withdrawals';
        $txn_table = $wpdb->prefix . 'hyip_transactions';
        $kyc_table = $wpdb->prefix . 'hyip_kyc';

        $total_deposits = $wpdb->get_var("SELECT SUM(amount) FROM $txn_table WHERE type='deposit' AND status='success'");
        $total_withdrawals = $wpdb->get_var("SELECT SUM(amount) FROM $withdraw_table WHERE status='approved'");
        $pending_withdrawals = $wpdb->get_var("SELECT COUNT(*) FROM $withdraw_table WHERE status='pending'");
        $failed_payouts = $wpdb->get_var("SELECT COUNT(*) FROM $withdraw_table WHERE payout_status='failed'");

        $total_users = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");
        $kyc_approved = $wpdb->get_var("SELECT COUNT(*) FROM $kyc_table WHERE status='approved'");
        $kyc_pending = $wpdb->get_var("SELECT COUNT(*) FROM $kyc_table WHERE status='pending'");

        echo '<h1>HYIP Analytics Dashboard</h1>';

        echo '<h2>Financial Overview</h2>';
        echo '<div style="display:flex;gap:20px;">';
        self::card('Total Deposits', '₹'.($total_deposits ?: 0));
        self::card('Total Withdrawals', '₹'.($total_withdrawals ?: 0));
        self::card('Pending Withdrawals', $pending_withdrawals);
        self::card('Failed Payouts', $failed_payouts);
        echo '</div>';

        echo '<h2>User Metrics</h2>';
        echo '<div style="display:flex;gap:20px;">';
        self::card('Total Users', $total_users);
        self::card('KYC Approved', $kyc_approved);
        self::card('KYC Pending', $kyc_pending);
        echo '</div>';
    }

    private static function card($title, $value) {
        echo '<div style="padding:20px;border:1px solid #ccc;border-radius:8px;width:200px;">';
        echo '<h3>'.$title.'</h3>';
        echo '<p style="font-size:20px;font-weight:bold;">'.$value.'</p>';
        echo '</div>';
    }
}
