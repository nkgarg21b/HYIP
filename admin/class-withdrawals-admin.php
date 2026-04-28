<?php
class HYIP_Withdrawals_Admin {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'Withdrawals',
            'Withdrawals',
            'manage_options',
            'hyip-withdrawals',
            [self::class, 'page']
        );
    }

    public static function page() {
        if (isset($_GET['approve'])) {
            HYIP_Withdrawals::approve(intval($_GET['approve']));
            echo '<div class="updated"><p>Approved</p></div>';
        }

        if (isset($_GET['reject'])) {
            HYIP_Withdrawals::reject(intval($_GET['reject']));
            echo '<div class="error"><p>Rejected & Refunded</p></div>';
        }

        $withdrawals = HYIP_Withdrawals::get_all();

        echo '<h1>Withdrawal Requests</h1>';
        echo '<table border="1" cellpadding="10">';
        echo '<tr><th>ID</th><th>User</th><th>Amount</th><th>Status</th><th>Action</th></tr>';

        foreach ($withdrawals as $w) {
            echo '<tr>';
            echo '<td>'.$w->id.'</td>';
            echo '<td>'.$w->user_id.'</td>';
            echo '<td>₹'.$w->amount.'</td>';
            echo '<td>'.$w->status.'</td>';
            echo '<td>';

            if ($w->status == 'pending') {
                echo '<a href="?page=hyip-withdrawals&approve='.$w->id.'">Approve</a> | ';
                echo '<a href="?page=hyip-withdrawals&reject='.$w->id.'">Reject</a>';
            }

            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
