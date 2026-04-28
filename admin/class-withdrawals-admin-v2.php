<?php
class HYIP_Withdrawals_Admin_V2 {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'Withdrawals V2',
            'Withdrawals V2',
            'manage_options',
            'hyip-withdrawals-v2',
            [self::class, 'page']
        );
    }

    public static function page() {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_withdrawals';

        if (isset($_GET['pay'])) {
            $id = intval($_GET['pay']);
            $withdrawal = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d", $id));

            $result = HYIP_Payout_Processor::process($withdrawal);

            echo '<div class="updated"><p>Status: '.$result['status'].'</p></div>';
        }

        if (isset($_GET['retry'])) {
            $id = intval($_GET['retry']);
            $withdrawal = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d", $id));

            $result = HYIP_Payout_Processor::process($withdrawal);

            echo '<div class="updated"><p>Retry Status: '.$result['status'].'</p></div>';
        }

        $rows = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");

        echo '<h1>Withdrawals (Hardened)</h1>';
        echo '<table border="1" cellpadding="10">';
        echo '<tr><th>ID</th><th>User</th><th>Amount</th><th>Status</th><th>Payout</th><th>Attempts</th><th>Actions</th></tr>';

        foreach ($rows as $w) {
            echo '<tr>';
            echo '<td>'.$w->id.'</td>';
            echo '<td>'.$w->user_id.'</td>';
            echo '<td>₹'.$w->amount.'</td>';
            echo '<td>'.$w->status.'</td>';
            echo '<td>'.$w->payout_status.'</td>';
            echo '<td>'.$w->attempt_count.'</td>';
            echo '<td>';

            if ($w->payout_status !== 'success' && $w->attempt_count < 3) {
                echo '<a href="?page=hyip-withdrawals-v2&pay='.$w->id.'">Pay</a> | ';
                echo '<a href="?page=hyip-withdrawals-v2&retry='.$w->id.'">Retry</a>';
            } else {
                echo 'Completed / Locked';
            }

            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
