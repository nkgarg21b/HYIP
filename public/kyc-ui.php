<?php
if (!is_user_logged_in()) return;

$user_id = get_current_user_id();
$kyc = HYIP_KYC::get($user_id);

?>

<h3>KYC Verification</h3>

<?php if ($kyc && $kyc->status === 'approved'): ?>
    <p style="color:green;">KYC Approved</p>
<?php elseif ($kyc && $kyc->status === 'pending'): ?>
    <p style="color:orange;">KYC Pending Approval</p>
<?php else: ?>

<form method="post">
    <input type="text" name="pan" placeholder="PAN Number" required><br><br>
    <input type="text" name="aadhaar" placeholder="Aadhaar Number" required><br><br>
    <input type="text" name="bank" placeholder="Bank Account" required><br><br>
    <input type="text" name="ifsc" placeholder="IFSC Code" required><br><br>
    <button type="submit" name="kyc_submit">Submit KYC</button>
</form>

<?php
if (isset($_POST['kyc_submit'])) {
    HYIP_KYC::submit($user_id, $_POST);
    echo "<p>KYC Submitted. Await admin approval.</p>";
}

endif;
?>
