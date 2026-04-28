<?php
class HYIP_Payment_Handler {

    public static function handle_callback() {
        if (!isset($_POST['razorpay_payment_id'], $_POST['razorpay_signature'], $_POST['razorpay_order_id'])) {
            wp_die('Invalid payment');
        }

        $payment_id = sanitize_text_field($_POST['razorpay_payment_id']);
        $order_id   = sanitize_text_field($_POST['razorpay_order_id']);
        $signature  = sanitize_text_field($_POST['razorpay_signature']);
        $amount     = floatval($_POST['amount']);

        $payload = $order_id . '|' . $payment_id;

        if (!HYIP_Razorpay::verify_signature($payload, $signature)) {
            wp_die('Signature verification failed');
        }

        $user_id = get_current_user_id();

        // Prevent duplicate credit
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_transactions';
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE reference_id = %s",
            $payment_id
        ));

        if ($exists) {
            wp_die('Already processed');
        }

        HYIP_Deposits::credit_wallet($user_id, $amount, $payment_id);

        echo 'OK';
        exit;
    }
}
