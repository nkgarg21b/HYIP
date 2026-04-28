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
        echo '<h1>HYIP Dashboard</h1>';
        echo '<p>System Active</p>';
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
