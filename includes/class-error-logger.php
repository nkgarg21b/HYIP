<?php
class HYIP_Error_Logger {

    public static function init() {
        set_error_handler([self::class, 'handle_error']);
        set_exception_handler([self::class, 'handle_exception']);
    }

    public static function handle_error($errno, $errstr, $errfile, $errline) {
        self::log_entry("ERROR: $errstr in $errfile on line $errline");
    }

    public static function handle_exception($exception) {
        self::log_entry("EXCEPTION: " . $exception->getMessage());
    }

    public static function log_entry($message) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_error_logs';

        if ($wpdb) {
            $wpdb->insert($table, [
                'message' => $message,
                'created_at' => current_time('mysql')
            ]);
        }
    }
}
