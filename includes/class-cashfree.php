<?php
class HYIP_Cashfree {

    public static function payout($withdrawal) {
        $client_id = get_option('hyip_cashfree_app_id');
        $client_secret = get_option('hyip_cashfree_secret');

        $url = "https://api.cashfree.com/payout/v1/requestTransfer";

        $body = [
            "beneId" => "user_" . $withdrawal->user_id,
            "amount" => $withdrawal->amount,
            "transferId" => "wd_" . $withdrawal->id,
            "transferMode" => "banktransfer"
        ];

        $response = wp_remote_post($url, [
            'headers' => [
                'X-Client-Id' => $client_id,
                'X-Client-Secret' => $client_secret,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($body)
        ]);

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
