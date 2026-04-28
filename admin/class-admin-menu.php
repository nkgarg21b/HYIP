<?php
class HYIP_Admin_Menu {
    public static function register_menu() {
        add_menu_page(
            'HYIP Dashboard',
            'HYIP',
            'manage_options',
            'hyip-dashboard',
            [self::class, 'dashboard_page'],
            'dashicons-chart-line'
        );

        add_submenu_page(
            'hyip-dashboard',
            'Settings',
            'Settings',
            'manage_options',
            'hyip-settings',
            [self::class, 'settings_page']
        );
    }

    public static function dashboard_page() {
        echo '<h1>HYIP Dashboard</h1>';
        echo '<p>Phase 0 Initialized</p>';
    }

    public static function settings_page() {
        echo '<h1>Settings</h1>';
        echo '<p>Coming soon</p>';
    }
}
