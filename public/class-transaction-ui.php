<?php
class HYIP_Transaction_UI {

    public static function render() {
        if (!is_user_logged_in()) {
            return '<p>Please login to view transactions</p>';
        }

        global $wpdb;
        $user_id = get_current_user_id();
        $table = $wpdb->prefix . 'hyip_transactions';

        // Filters
        $type = $_GET['type'] ?? '';
        $from = $_GET['from'] ?? '';
        $to   = $_GET['to'] ?? '';

        $where = "WHERE user_id = %d";
        $params = [$user_id];

        if ($type) {
            $where .= " AND type = %s";
            $params[] = $type;
        }

        if ($from) {
            $where .= " AND DATE(created_at) >= %s";
            $params[] = $from;
        }

        if ($to) {
            $where .= " AND DATE(created_at) <= %s";
            $params[] = $to;
        }

        // Pagination
        $page = max(1, intval($_GET['pg'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table $where", ...$params));

        $query = "SELECT * FROM $table $where ORDER BY created_at DESC LIMIT %d OFFSET %d";
        $params_with_limit = array_merge($params, [$limit, $offset]);
        $transactions = $wpdb->get_results($wpdb->prepare($query, ...$params_with_limit));

        // Totals
        $total_deposit = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) FROM $table $where AND type='deposit'", ...$params));
        $total_withdraw = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) FROM $table $where AND type='withdrawal'", ...$params));
        $total_roi = $wpdb->get_var($wpdb->prepare("SELECT SUM(amount) FROM $table $where AND type='roi'", ...$params));

        ob_start();
        ?>

        <h2>Transaction History</h2>

        <!-- Summary Cards -->
        <div style="display:flex;gap:20px;margin-bottom:20px;">
            <div><strong>Deposits:</strong> ₹<?php echo $total_deposit ?: 0; ?></div>
            <div><strong>Withdrawals:</strong> ₹<?php echo $total_withdraw ?: 0; ?></div>
            <div><strong>ROI:</strong> ₹<?php echo $total_roi ?: 0; ?></div>
        </div>

        <!-- Filters -->
        <form method="get" style="margin-bottom:20px;">
            <select name="type">
                <option value="">All</option>
                <option value="deposit">Deposit</option>
                <option value="withdrawal">Withdrawal</option>
                <option value="roi">ROI</option>
            </select>
            <input type="date" name="from">
            <input type="date" name="to">
            <button type="submit">Apply</button>
        </form>

        <!-- Table -->
        <table style="width:100%; border-collapse: collapse;">
            <tr style="background:#f5f5f5;">
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php if ($transactions): foreach ($transactions as $tx): ?>
                <tr>
                    <td><?php echo esc_html($tx->type); ?></td>
                    <td>₹<?php echo esc_html($tx->amount); ?></td>
                    <td><?php echo esc_html($tx->status); ?></td>
                    <td><?php echo esc_html($tx->created_at); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="4">No transactions found</td></tr>
            <?php endif; ?>
        </table>

        <!-- Pagination -->
        <div style="margin-top:20px;">
            <?php
            $total_pages = ceil($total / $limit);
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a style="margin-right:10px;" href="?pg='.$i.'">'.$i.'</a>';
            }
            ?>
        </div>

        <?php
        return ob_get_clean();
    }
}
