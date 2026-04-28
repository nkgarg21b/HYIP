<?php
class HYIP_Public {

    public function __construct() {
        add_shortcode('hyip_dashboard', [$this, 'dashboard']);
        add_shortcode('hyip_kyc', ['HYIP_KYC_UI', 'render']);
        add_shortcode('hyip_withdraw', ['HYIP_Withdrawal_UI', 'render']);
        add_shortcode('hyip_wallet', ['HYIP_Wallet_UI', 'render']);
    }

    public function dashboard() {
        if (!is_user_logged_in()) {
            return '<p>Please login to view dashboard</p>';
        }

        $user_id = get_current_user_id();
        $balance = HYIP_Wallet::get_balance($user_id);
        $plans = HYIP_Plans::get_plans();

        ob_start();

        echo '<style>
            .hyip-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:20px; }
            .hyip-card { background:#1e1e2f; color:#fff; padding:20px; border-radius:12px; }
            .hyip-card h3 { margin:0 0 10px; font-size:16px; }
            .hyip-card p { font-size:22px; font-weight:bold; margin:0; }
            .hyip-nav a { margin-right:15px; text-decoration:none; font-weight:bold; }
            .hyip-plan { border:1px solid #ddd; padding:15px; border-radius:10px; margin-top:15px; }
        </style>';

        echo '<h2>Dashboard</h2>';

        echo '<div class="hyip-grid">';
        echo '<div class="hyip-card"><h3>Wallet Balance</h3><p>₹'.$balance.'</p></div>';
        echo '<div class="hyip-card"><h3>KYC Status</h3><p>'.(HYIP_KYC::is_verified($user_id) ? 'Verified' : 'Pending').'</p></div>';
        echo '<div class="hyip-card"><h3>Active Plans</h3><p>'.count(HYIP_Investments::get_user_investments($user_id)).'</p></div>';
        echo '</div>';

        echo '<div class="hyip-nav" style="margin:20px 0;">';
        echo '<a href="/kyc">KYC</a>';
        echo '<a href="/withdraw">Withdraw</a>';
        echo '<a href="/wallet">Wallet</a>';
        echo '</div>';

        include HYIP_PLUGIN_PATH . 'public/payment-ui.php';

        echo '<h3>Investment Plans</h3>';

        foreach ($plans as $plan) {
            echo '<div class="hyip-plan">';
            echo '<strong>'.$plan->name.'</strong><br>';
            echo 'ROI: '.$plan->roi_percentage.'%<br>';
            echo 'Duration: '.$plan->duration_days.' days<br>';
            echo '<form method="post">';
            echo '<input type="hidden" name="plan_id" value="'.$plan->id.'">';
            echo '<input type="number" name="amount" placeholder="Amount" required>';
            echo '<button type="submit" name="invest">Invest</button>';
            echo '</form>';
            echo '</div>';
        }

        if (isset($_POST['invest'])) {
            HYIP_Investments::invest($user_id, $_POST['plan_id'], $_POST['amount']);
            echo '<p style="color:green;">Investment Successful</p>';
        }

        return ob_get_clean();
    }
}
