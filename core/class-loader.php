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
        require_once HYIP_PLUGIN_PATH . 'admin/class-admin-menu.php';
        require_once HYIP_PLUGIN_PATH . 'public/class-public.php';
    }

    private function init_hooks() {
        add_action('admin_menu', ['HYIP_Admin_Menu', 'register_menu']);
        add_action('user_register', ['HYIP_Wallet', 'create_wallet']);

        if (!wp_next_scheduled('hyip_daily_roi')) {
            wp_schedule_event(time(), 'daily', 'hyip_daily_roi');
        }
        add_action('hyip_daily_roi', ['HYIP_Investments', 'process_daily_roi']);

        add_action('admin_post_nopriv_hyip_payment_callback', ['HYIP_Payment_Handler', 'handle_callback']);
        add_action('admin_post_hyip_payment_callback', ['HYIP_Payment_Handler', 'handle_callback']);

        new HYIP_Public();
    }
}
