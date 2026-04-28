<?php
class HYIP_Upgrader {

    const VERSION = '1.0.1';

    public static function init() {
        $stored = get_option('hyip_version');

        if ($stored !== self::VERSION) {
            self::run_migrations();
            update_option('hyip_version', self::VERSION);
        }
    }

    private static function run_migrations() {
        // 🔥 FIX: Ensure database class is loaded before use
        if (!class_exists('HYIP_Database')) {
            require_once HYIP_PLUGIN_PATH . 'includes/database.php';
        }

        HYIP_Database::create_tables();
    }
}
