<?php
class HYIP_Transaction_UI {

    public static function render() {
        if (!is_user_logged_in()) {
            return '<p>Please login to view transactions</p>';
        }

        global $wpdb;
        $user_id = get_current_user_id();
        $table = $wpdb->prefix . 'hyip_transactions';

        $transactions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC LIMIT 50",
            $user_id
        ));

        ob_start();

        echo '<h2>Transaction History</h2>';

        echo '<style>
            .hyip-table { width:100%; border-collapse: collapse; }
            .hyip-table th, .hyip-table td { padding:10px; border:1px solid #ddd; }
            .hyip-table th { background:#f5f5f5; }
        </style>';

        echo '<table class="hyip-table">';
        echo '<tr><th>Type</th><th>Amount</th><th>Status</th><th>Date</th></tr>';

        if ($transactions) {
            foreach ($transactions as $tx) {
                echo '<tr>';
                echo '<td>'.esc_html($tx->type).'</td>';
                echo '<td>₹'.esc_html($tx->amount).'</td>';
                echo '<td>'.esc_html($tx->status).'</td>';
                echo '<td>'.esc_html($tx->created_at).'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">No transactions found</td></tr>';
        }

        echo '</table>';

        return ob_get_clean();
    }
}
