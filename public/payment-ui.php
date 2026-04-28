<?php
if (!is_user_logged_in()) return;
$user_id = get_current_user_id();
$key = get_option('hyip_razorpay_key');
?>
<h3>Deposit Funds</h3>
<input type="number" id="amount" placeholder="Enter Amount">
<button onclick="payNow()">Pay with Razorpay</button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function payNow(){
    var amount = document.getElementById('amount').value;

    var options = {
        "key": "<?php echo $key; ?>",
        "amount": amount * 100,
        "currency": "INR",
        "name": "HYIP Investment",
        "handler": function (response){
            fetch("", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "razorpay_payment_id=" + response.razorpay_payment_id + "&amount=" + amount
            }).then(()=>location.reload());
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
}
</script>

<?php
if (isset($_POST['razorpay_payment_id'])) {
    HYIP_Deposits::credit_wallet($user_id, $_POST['amount'], $_POST['razorpay_payment_id']);
    echo "<p>Deposit Successful</p>";
}
