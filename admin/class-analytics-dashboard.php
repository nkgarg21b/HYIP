<?php
class HYIP_Analytics_Dashboard {

    public static function register() {
        add_menu_page(
            'HYIP Analytics',
            'Analytics',
            'manage_options',
            'hyip-analytics',
            [self::class, 'render'],
            'dashicons-chart-line'
        );
    }

    public static function render() {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_transactions';

        $total_deposits = $wpdb->get_var("SELECT SUM(amount) FROM $table WHERE type='deposit'");
        $total_withdrawals = $wpdb->get_var("SELECT SUM(amount) FROM $table WHERE type='withdrawal'");
        $total_roi = $wpdb->get_var("SELECT SUM(amount) FROM $table WHERE type='roi'");

        $daily = $wpdb->get_results("SELECT DATE(created_at) as day, SUM(amount) as total FROM $table GROUP BY day ORDER BY day ASC LIMIT 30");

        ?>
        <div class="wrap">
            <h1>HYIP Analytics</h1>

            <div style="display:flex;gap:20px;margin-bottom:20px;">
                <div><strong>Total Deposits:</strong> ₹<?php echo $total_deposits ?: 0; ?></div>
                <div><strong>Total Withdrawals:</strong> ₹<?php echo $total_withdrawals ?: 0; ?></div>
                <div><strong>Total ROI:</strong> ₹<?php echo $total_roi ?: 0; ?></div>
            </div>

            <canvas id="hyipChart" height="100"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const ctx = document.getElementById('hyipChart');

        const labels = <?php echo json_encode(array_column($daily, 'day')); ?>;
        const data = <?php echo json_encode(array_column($daily, 'total')); ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Volume',
                    data: data,
                    fill: false,
                    tension: 0.3
                }]
            }
        });
        </script>
        <?php
    }
}
