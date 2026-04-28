<?php
class HYIP_Razorpay {

    public static function get_key() {
        return get_option('hyip_razorpay_key');
    }

    public static function get_secret() {
        return get_option('hyip_razorpay_secret');
    }

    public static function verify_signature($payload, $signature) {
        $expected = hash_hmac('sha256', $payload, self::get_secret());
        return hash_equals($expected, $signature);
    }
}
