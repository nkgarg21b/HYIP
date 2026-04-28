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
        require_once HYIP_PLUGIN_PATH . 'admin/class-admin-menu.php';
    }

    private function init_hooks() {
        add_action('admin_menu', ['HYIP_Admin_Menu', 'register_menu']);
        add_action('user_register', ['HYIP_Wallet', 'create_wallet']);
    }
}
