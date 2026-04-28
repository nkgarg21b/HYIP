<?php
require_once HYIP_PLUGIN_PATH . 'includes/database.php';
class HYIP_Activator {
    public static function activate() {
        HYIP_Database::create_tables();
    }
}
