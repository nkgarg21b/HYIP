<?php
class HYIP_Admin_Menu {
    public static function register_menu() {
        add_menu_page(
            'HYIP Dashboard',
            'HYIP',
            'manage_options',
            'hyip-dashboard',
            [self::class, 'dashboard_page'],
            'dashicons-chart-line'
        );

        add_submenu_page(
            'hyip-dashboard',
            'Plans',
            'Plans',
            'manage_options',
            'hyip-plans',
            [self::class, 'plans_page']
        );
    }

    public static function dashboard_page() {
        global $wpdb;

        $wallet_table = $wpdb->prefix . 'hyip_wallet';
        $txn_table = $wpdb->prefix . 'hyip_transactions';
        $plans_table = $wpdb->prefix . 'hyip_plans';
        $inv_table = $wpdb->prefix . 'hyip_investments';

        $total_users = $wpdb->get_var("SELECT COUNT(*) FROM $wallet_table");
        $total_plans = $wpdb->get_var("SELECT COUNT(*) FROM $plans_table");
        $total_investments = $wpdb->get_var("SELECT COUNT(*) FROM $inv_table");
        $total_transactions = $wpdb->get_var("SELECT COUNT(*) FROM $txn_table");

        echo '<h1>HYIP Dashboard</h1>';

        echo '<div style="display:flex;gap:20px;flex-wrap:wrap;">';

        echo "<div style='padding:20px;background:#fff;border:1px solid #ddd;'>Users: <strong>$total_users</strong></div>";
        echo "<div style='padding:20px;background:#fff;border:1px solid #ddd;'>Plans: <strong>$total_plans</strong></div>";
        echo "<div style='padding:20px;background:#fff;border:1px solid #ddd;'>Investments: <strong>$total_investments</strong></div>";
        echo "<div style='padding:20px;background:#fff;border:1px solid #ddd;'>Transactions: <strong>$total_transactions</strong></div>";

        echo '</div>';
    }

    public static function plans_page() {
        if (isset($_POST['create_plan'])) {
            HYIP_Plans::create_plan([
                'name' => $_POST['name'],
                'roi_percentage' => $_POST['roi'],
                'duration_days' => $_POST['duration'],
                'min_invest' => $_POST['min'],
                'max_invest' => $_POST['max']
            ]);
            echo '<div class="updated"><p>Plan Created</p></div>';
        }

        echo '<h1>Create Plan</h1>';
        echo '<form method="post">';
        echo '<input type="text" name="name" placeholder="Plan Name" required><br><br>';
        echo '<input type="number" name="roi" placeholder="ROI %" required><br><br>';
        echo '<input type="number" name="duration" placeholder="Duration (days)" required><br><br>';
        echo '<input type="number" name="min" placeholder="Min Invest" required><br><br>';
        echo '<input type="number" name="max" placeholder="Max Invest" required><br><br>';
        echo '<button type="submit" name="create_plan">Create Plan</button>';
        echo '</form>';

        $plans = HYIP_Plans::get_plans();
        echo '<h2>Existing Plans</h2>';
        foreach ($plans as $plan) {
            echo "<p>{$plan->name} - {$plan->roi_percentage}% for {$plan->duration_days} days</p>";
        }
    }
}
