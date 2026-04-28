<?php
class HYIP_Plans {

    public static function create_plan($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_plans';

        $wpdb->insert($table, $data);
    }

    public static function get_plans() {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_plans';

        return $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
    }
}
