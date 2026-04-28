<?php
class HYIP_Loader {
    public function run() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once HYIP_PLUGIN_PATH . 'includes/database.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-wallet.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-transactions.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-plans.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-investments.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-deposits.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-razorpay.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-payment-handler.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-withdrawals.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-kyc.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-cashfree.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-payout-logger.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-payout-processor.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-cashfree-webhook.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-reconciliation.php';
        require_once HYIP_PLUGIN_PATH . 'includes/class-fraud-detector.php';

        require_once HYIP_PLUGIN_PATH . 'admin/class-admin-menu.php';
        require_once HYIP_PLUGIN_PATH . 'admin/class-settings.php';
        require_once HYIP_PLUGIN_PATH . 'admin/class-withdrawals-admin.php';
        require_once HYIP_PLUGIN_PATH . 'admin/class-withdrawals-admin-v2.php';
        require_once HYIP_PLUGIN_PATH . 'admin/class-kyc-admin.php';
        require_once HYIP_PLUGIN_PATH . 'admin/class-dashboard-analytics.php';
        require_once HYIP_PLUGIN_PATH . 'admin/class-shortcodes-page.php';

        require_once HYIP_PLUGIN_PATH . 'public/class-public.php';
        require_once HYIP_PLUGIN_PATH . 'public/class-kyc-ui.php';
        require_once HYIP_PLUGIN_PATH . 'public/class-withdrawal-ui.php';
        require_once HYIP_PLUGIN_PATH . 'public/class-wallet-ui.php';
    }

    private function init_hooks() {
        add_action('admin_menu', ['HYIP_Admin_Menu', 'register_menu']);
        add_action('admin_menu', ['HYIP_Settings', 'register']);
        add_action('admin_menu', ['HYIP_Withdrawals_Admin', 'register']);
        add_action('admin_menu', ['HYIP_Withdrawals_Admin_V2', 'register']);
        add_action('admin_menu', ['HYIP_KYC_Admin', 'register']);
        add_action('admin_menu', ['HYIP_Dashboard_Analytics', 'register']);
        add_action('admin_menu', ['HYIP_Shortcodes_Page', 'register']);

        add_action('user_register', ['HYIP_Wallet', 'create_wallet']);

        if (!wp_next_scheduled('hyip_daily_roi')) {
            wp_schedule_event(time(), 'daily', 'hyip_daily_roi');
        }
        add_action('hyip_daily_roi', ['HYIP_Investments', 'process_daily_roi']);

        add_filter('cron_schedules', function($schedules) {
            $schedules['ten_minutes'] = [
                'interval' => 600,
                'display' => 'Every 10 Minutes'
            ];
            return $schedules;
        });

        if (!wp_next_scheduled('hyip_reconciliation_cron')) {
            wp_schedule_event(time(), 'ten_minutes', 'hyip_reconciliation_cron');
        }
        add_action('hyip_reconciliation_cron', ['HYIP_Reconciliation', 'run']);

        add_action('admin_post_nopriv_hyip_payment_callback', ['HYIP_Payment_Handler', 'handle_callback']);
        add_action('admin_post_hyip_payment_callback', ['HYIP_Payment_Handler', 'handle_callback']);

        add_action('init', function() {
            if (isset($_GET['hyip_webhook']) && $_GET['hyip_webhook'] == 'cashfree') {
                HYIP_Cashfree_Webhook::handle();
            }
        });

        new HYIP_Public();
    }
}
