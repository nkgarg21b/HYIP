<?php
class HYIP_Autoloader {

    public static function init() {
        spl_autoload_register([self::class, 'autoload']);
    }

    public static function autoload($class) {
        if (strpos($class, 'HYIP_') !== 0) return;

        $base = strtolower(str_replace('HYIP_', '', $class));
        $base = str_replace('_', '-', $base);

        $paths = [
            HYIP_PLUGIN_PATH . 'includes/class-' . $base . '.php',
            HYIP_PLUGIN_PATH . 'admin/class-' . $base . '.php',
            HYIP_PLUGIN_PATH . 'public/class-' . $base . '.php'
        ];

        foreach ($paths as $file) {
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}
