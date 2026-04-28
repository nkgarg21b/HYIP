<?php
class HYIP_Setup_Wizard {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'Setup Wizard',
            'Setup Wizard',
            'manage_options',
            'hyip-setup',
            [self::class, 'page']
        );
    }

    public static function page() {
        echo '<h1>HYIP Setup Wizard</h1>';

        if (isset($_POST['run_setup'])) {
            self::create_page('Dashboard', 'hyip_dashboard', 'dashboard');
            self::create_page('KYC', 'hyip_kyc', 'kyc');
            self::create_page('Withdraw', 'hyip_withdraw', 'withdraw');
            self::create_page('Wallet', 'hyip_wallet', 'wallet');

            echo '<p style="color:green;">Pages created successfully!</p>';
        }

        echo '<form method="post">';
        echo '<p>This will automatically create all required pages with correct shortcodes.</p>';
        echo '<button name="run_setup" style="padding:10px 20px;font-size:16px;">Run Setup</button>';
        echo '</form>';
    }

    private static function create_page($title, $shortcode, $slug) {
        $existing = get_page_by_path($slug);
        if ($existing) return;

        wp_insert_post([
            'post_title' => $title,
            'post_name' => $slug,
            'post_content' => "[$shortcode]",
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
    }
}
