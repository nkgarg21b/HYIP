<?php
class HYIP_Transaction_UI {

    public static function render() {
        if (!is_user_logged_in()) {
            return '<p>Please login to view transactions</p>';
        }

        global $wpdb;
        $user_id = get_current_user_id();
        $table = $wpdb->prefix . 'hyip_transactions';

        // ✅ Filters
        $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
        $from = isset($_GET['from']) ? sanitize_text_field($_GET['from']) : '';
        $to   = isset($_GET['to']) ? sanitize_text_field($_GET['to']) : '';

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

        $query = "SELECT * FROM $table $where ORDER BY created_at DESC LIMIT 200";
        $transactions = $wpdb->get_results($wpdb->prepare($query, ...$params));

        // ✅ CSV Export
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename=transactions.csv');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['Type', 'Amount', 'Status', 'Date']);

            foreach ($transactions as $tx) {
                fputcsv($output, [$tx->type, $tx->amount, $tx->status, $tx->created_at]);
            }

            fclose($output);
            exit;
        }

        ob_start();
        ?>

        <h2>Transaction History</h2>

        <!-- 🔍 Filters -->
        <form method="get" style="margin-bottom:20px;">
            <label>Type:</label>
            <select name="type">
                <option value="">All</option>
                <option value="deposit">Deposit</option>
                <option value="withdrawal">Withdrawal</option>
                <option value="roi">ROI</option>
            </select>

            <label>From:</label>
            <input type="date" name="from">

            <label>To:</label>
            <input type="date" name="to">

            <button type="submit">Apply</button>
        </form>

        <!-- 📥 Export -->
        <a href="?export=csv" style="margin-bottom:10px;display:inline-block;">
            ⬇ Download CSV
        </a>

        <!-- 📊 Table -->
        <table style="width:100%; border-collapse: collapse;">
            <tr style="background:#f5f5f5;">
                <th style="padding:10px;border:1px solid #ddd;">Type</th>
                <th style="padding:10px;border:1px solid #ddd;">Amount</th>
                <th style="padding:10px;border:1px solid #ddd;">Status</th>
                <th style="padding:10px;border:1px solid #ddd;">Date</th>
            </tr>

            <?php if ($transactions): ?>
                <?php foreach ($transactions as $tx): ?>
                    <tr>
                        <td style="padding:10px;border:1px solid #ddd;"><?php echo esc_html($tx->type); ?></td>
                        <td style="padding:10px;border:1px solid #ddd;">₹<?php echo esc_html($tx->amount); ?></td>
                        <td style="padding:10px;border:1px solid #ddd;"><?php echo esc_html($tx->status); ?></td>
                        <td style="padding:10px;border:1px solid #ddd;"><?php echo esc_html($tx->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="padding:10px;">No transactions found</td>
                </tr>
            <?php endif; ?>
        </table>

        <?php
        return ob_get_clean();
    }
}
