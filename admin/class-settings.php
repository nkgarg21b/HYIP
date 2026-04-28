<?php
class HYIP_Settings {
    public static function register() {
        add_submenu_page('hyip-dashboard','Settings','Settings','manage_options','hyip-settings',[self::class,'page']);
    }

    public static function page() {
        if (isset($_POST['save'])) {
            update_option('hyip_razorpay_key', sanitize_text_field($_POST['key']));
            update_option('hyip_razorpay_secret', sanitize_text_field($_POST['secret']));
            echo '<div class="updated"><p>Saved</p></div>';
        }

        $key = get_option('hyip_razorpay_key');
        $secret = get_option('hyip_razorpay_secret');

        echo '<h1>Razorpay Settings</h1>';
        echo '<form method="post">';
        echo '<input type="text" name="key" placeholder="Key ID" value="'.$key.'"><br><br>';
        echo '<input type="text" name="secret" placeholder="Secret" value="'.$secret.'"><br><br>';
        echo '<button name="save">Save</button>';
        echo '</form>';
    }
}
