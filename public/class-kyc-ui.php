<?php
class HYIP_KYC_UI {

    public static function render() {
        if (!is_user_logged_in()) return 'Please login';

        $user_id = get_current_user_id();
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_kyc';

        if (isset($_POST['submit_kyc'])) {
            $wpdb->insert($table, [
                'user_id' => $user_id,
                'pan' => sanitize_text_field($_POST['pan']),
                'aadhaar' => sanitize_text_field($_POST['aadhaar']),
                'bank_account' => sanitize_text_field($_POST['bank']),
                'ifsc' => sanitize_text_field($_POST['ifsc']),
                'status' => 'pending'
            ]);
            echo '<p>KYC Submitted</p>';
        }

        return '<form method="post">
            <input name="pan" placeholder="PAN" required><br>
            <input name="aadhaar" placeholder="Aadhaar" required><br>
            <input name="bank" placeholder="Bank Account" required><br>
            <input name="ifsc" placeholder="IFSC" required><br>
            <button name="submit_kyc">Submit KYC</button>
        </form>';
    }
}
