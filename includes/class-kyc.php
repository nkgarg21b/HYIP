<?php
class HYIP_KYC {

    public static function submit($user_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_kyc';

        $wpdb->insert($table, [
            'user_id' => $user_id,
            'pan' => sanitize_text_field($data['pan']),
            'aadhaar' => sanitize_text_field($data['aadhaar']),
            'bank_account' => sanitize_text_field($data['bank']),
            'ifsc' => sanitize_text_field($data['ifsc']),
            'status' => 'pending'
        ]);
    }

    public static function get($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_kyc';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE user_id=%d", $user_id));
    }

    public static function approve($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_kyc';
        $wpdb->update($table, ['status' => 'approved'], ['id' => $id]);
    }

    public static function reject($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_kyc';
        $wpdb->update($table, ['status' => 'rejected'], ['id' => $id]);
    }

    public static function is_verified($user_id) {
        $kyc = self::get($user_id);
        return ($kyc && $kyc->status === 'approved');
    }
}
