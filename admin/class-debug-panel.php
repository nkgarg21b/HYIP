<?php
class HYIP_Debug_Panel {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'Debug Logs',
            'Debug Logs',
            'manage_options',
            'hyip-debug',
            [self::class, 'page']
        );
    }

    public static function page() {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_error_logs';

        $logs = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC LIMIT 50");

        echo '<h1>Debug Logs</h1>';
        echo '<table border="1" cellpadding="10">';
        echo '<tr><th>Message</th><th>Time</th></tr>';

        if ($logs) {
            foreach ($logs as $log) {
                echo '<tr>';
                echo '<td>'.$log->message.'</td>';
                echo '<td>'.$log->created_at.'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="2">No logs found</td></tr>';
        }

        echo '</table>';
    }
}
