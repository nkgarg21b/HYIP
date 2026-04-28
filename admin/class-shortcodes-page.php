<?php
class HYIP_Shortcodes_Page {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'Shortcodes',
            'Shortcodes',
            'manage_options',
            'hyip-shortcodes',
            [self::class, 'page']
        );
    }

    public static function page() {
        echo '<h1>HYIP Shortcodes</h1>';

        echo '<h2>User Shortcodes</h2>';
        echo '<table border="1" cellpadding="10">';
        echo '<tr><th>Feature</th><th>Shortcode</th><th>Description</th></tr>';

        self::row('Dashboard', '[hyip_dashboard]', 'Main user dashboard with plans & investment');
        self::row('KYC', '[hyip_kyc]', 'Submit KYC details');
        self::row('Withdraw', '[hyip_withdraw]', 'Request withdrawal');
        self::row('Wallet', '[hyip_wallet]', 'View wallet balance');

        echo '</table>';

        echo '<h2 style="margin-top:30px;">How to Use</h2>';
        echo '<p>Create a new WordPress page and paste the shortcode inside the content.</p>';

        echo '<ul>';
        echo '<li>/dashboard → [hyip_dashboard]</li>';
        echo '<li>/kyc → [hyip_kyc]</li>';
        echo '<li>/withdraw → [hyip_withdraw]</li>';
        echo '<li>/wallet → [hyip_wallet]</li>';
        echo '</ul>';
    }

    private static function row($feature, $code, $desc) {
        echo '<tr>';
        echo '<td>'.$feature.'</td>';
        echo '<td><code>'.$code.'</code></td>';
        echo '<td>'.$desc.'</td>';
        echo '</tr>';
    }
}
