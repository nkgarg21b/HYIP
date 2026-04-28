<?php
class HYIP_KYC_Admin {

    public static function register() {
        add_submenu_page(
            'hyip-dashboard',
            'KYC Requests',
            'KYC',
            'manage_options',
            'hyip-kyc',
            [self::class, 'page']
        );
    }

    public static function page() {
        global $wpdb;
        $table = $wpdb->prefix . 'hyip_kyc';

        if (isset($_GET['approve'])) {
            HYIP_KYC::approve(intval($_GET['approve']));
            echo '<div class="updated"><p>KYC Approved</p></div>';
        }

        if (isset($_GET['reject'])) {
            HYIP_KYC::reject(intval($_GET['reject']));
            echo '<div class="error"><p>KYC Rejected</p></div>';
        }

        $kycs = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");

        echo '<h1>KYC Requests</h1>';
        echo '<table border="1" cellpadding="10">';
        echo '<tr><th>ID</th><th>User</th><th>PAN</th><th>Aadhaar</th><th>Status</th><th>Action</th></tr>';

        foreach ($kycs as $k) {
            echo '<tr>';
            echo '<td>'.$k->id.'</td>';
            echo '<td>'.$k->user_id.'</td>';
            echo '<td>'.$k->pan.'</td>';
            echo '<td>'.$k->aadhaar.'</td>';
            echo '<td>'.$k->status.'</td>';
            echo '<td>';

            if ($k->status == 'pending') {
                echo '<a href="?page=hyip-kyc&approve='.$k->id.'">Approve</a> | ';
                echo '<a href="?page=hyip-kyc&reject='.$k->id.'">Reject</a>';
            }

            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
