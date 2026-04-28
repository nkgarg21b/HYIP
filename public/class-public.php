<?php
class HYIP_Public {

    public function __construct() {
        add_shortcode('hyip_dashboard', [$this, 'dashboard']);
    }

    public function dashboard() {
        if (!is_user_logged_in()) {
            return '<p>Please login to view dashboard</p>';
        }

        $user_id = get_current_user_id();
        $balance = HYIP_Wallet::get_balance($user_id);
        $plans = HYIP_Plans::get_plans();

        ob_start();

        echo "<h2>Your Balance: ₹$balance</h2>";

        echo "<h3>Available Plans</h3>";

        foreach ($plans as $plan) {
            echo "<div style='border:1px solid #ddd;padding:15px;margin-bottom:10px;'>";
            echo "<strong>{$plan->name}</strong><br>";
            echo "ROI: {$plan->roi_percentage}%<br>";
            echo "Duration: {$plan->duration_days} days<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='plan_id' value='{$plan->id}'>";
            echo "<input type='number' name='amount' placeholder='Amount' required>";
            echo "<button type='submit' name='invest'>Invest</button>";
            echo "</form>";
            echo "</div>";
        }

        if (isset($_POST['invest'])) {
            HYIP_Investments::invest($user_id, $_POST['plan_id'], $_POST['amount']);
            echo "<p>Investment Successful</p>";
        }

        return ob_get_clean();
    }
}
