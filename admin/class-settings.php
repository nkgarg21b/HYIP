<?php
class HYIP_Settings {
    public static function register() {
        add_submenu_page('hyip-dashboard','Settings','Settings','manage_options','hyip-settings',[self::class,'page']);
    }

    public static function page() {
        if (isset($_POST['save'])) {
            update_option('hyip_razorpay_key', sanitize_text_field($_POST['key']));
            update_option('hyip_razorpay_secret', sanitize_text_field($_POST['secret']));

            update_option('hyip_min_withdraw', intval($_POST['min_withdraw']));
            update_option('hyip_max_withdraw', intval($_POST['max_withdraw']));
            update_option('hyip_withdraw_cooldown', intval($_POST['cooldown']));

            echo '<div class="updated"><p>Settings Saved</p></div>';
        }

        $key = get_option('hyip_razorpay_key');
        $secret = get_option('hyip_razorpay_secret');

        $min = get_option('hyip_min_withdraw', 500);
        $max = get_option('hyip_max_withdraw', 50000);
        $cooldown = get_option('hyip_withdraw_cooldown', 24);

        echo '<h1>HYIP Settings</h1>';

        echo '<h2>Razorpay</h2>';
        echo '<form method="post">';
        echo '<input type="text" name="key" placeholder="Key ID" value="'.$key.'"><br><br>';
        echo '<input type="text" name="secret" placeholder="Secret" value="'.$secret.'"><br><br>';

        echo '<h2>Withdrawal Settings</h2>';
        echo '<label>Minimum Withdrawal (₹)</label><br>';
        echo '<input type="number" name="min_withdraw" value="'.$min.'"><br><br>';

        echo '<label>Maximum Withdrawal (₹)</label><br>';
        echo '<input type="number" name="max_withdraw" value="'.$max.'"><br><br>';

        echo '<label>Cooldown (hours)</label><br>';
        echo '<input type="number" name="cooldown" value="'.$cooldown.'"><br><br>';

        echo '<button name="save">Save Settings</button>';
        echo '</form>';
    }
}
