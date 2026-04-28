<?php
class HYIP_Upgrader {

    const VERSION = '1.0.0';

    public static function init() {
        $stored = get_option('hyip_version');

        if ($stored !== self::VERSION) {
            self::run_migrations();
            update_option('hyip_version', self::VERSION);
        }
    }

    private static function run_migrations() {
        HYIP_Database::create_tables();
    }
}
