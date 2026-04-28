<?php
class HYIP_Database {
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_wallet = $wpdb->prefix . 'hyip_wallet';
        $sql1 = "CREATE TABLE $table_wallet (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT NOT NULL,
            balance DECIMAL(18,2) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $table_txn = $wpdb->prefix . 'hyip_transactions';
        $sql2 = "CREATE TABLE $table_txn (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT,
            type VARCHAR(50),
            amount DECIMAL(18,2),
            status VARCHAR(20),
            reference_id VARCHAR(100),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $table_plans = $wpdb->prefix . 'hyip_plans';
        $sql3 = "CREATE TABLE $table_plans (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            roi_percentage FLOAT,
            duration_days INT,
            min_invest DECIMAL(18,2),
            max_invest DECIMAL(18,2),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $table_inv = $wpdb->prefix . 'hyip_investments';
        $sql4 = "CREATE TABLE $table_inv (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT,
            plan_id BIGINT,
            amount DECIMAL(18,2),
            daily_profit DECIMAL(18,2),
            start_date DATETIME,
            end_date DATETIME,
            status VARCHAR(20)
        ) $charset_collate;";

        $table_withdraw = $wpdb->prefix . 'hyip_withdrawals';
        $sql5 = "CREATE TABLE $table_withdraw (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT,
            amount DECIMAL(18,2),
            status VARCHAR(20),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);
        dbDelta($sql4);
        dbDelta($sql5);
    }
}
