<?php
session_start();
require_once 'config.php';
require_once 'includes/RazorpayHelper.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user details
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

$razorpay = new RazorpayHelper();

// Get cart total
$cart_query = "SELECT SUM(p.price * c.quantity) as total 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = $user_id";
$cart_result = $conn->query($cart_query);
$cart_total = $cart_result->fetch_assoc()['total'];

if ($cart_total <= 0) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: cart.php");
    exit();
}

// Create Razorpay order
$receipt_id = 'ORDER_' . time() . '_' . $user_id;
$order = $razorpay->createOrder($cart_total, $receipt_id);

if (isset($order['error'])) {
    $_SESSION['error'] = "Error creating payment order: " . $order['error']['description'];
    header("Location: cart.php");
    exit();
}

// Store order details in session
$_SESSION['razorpay_order_id'] = $order['id'];
$_SESSION['receipt_id'] = $receipt_id;

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_status, created_at) VALUES (?, ?, 'completed', NOW())");
$stmt->bind_param("id", $user_id, $cart_total);
$stmt->execute();
$order_id = $conn->insert_id;

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Processing Payment</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                    </div>
                    <h5>Please wait while we redirect you to Razorpay...</h5>
                    <p class="text-muted">If you are not redirected automatically, please click the button below.</p>
                    <button class="btn btn-success" onclick="initiatePayment()">Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function initiatePayment() {
        var options = {
            "key": "<?php echo RAZORPAY_KEY_ID; ?>",
            "amount": "<?php echo $order['amount']; ?>",
            "currency": "<?php echo RAZORPAY_CURRENCY; ?>",
            "name": "<?php echo COMPANY_NAME; ?>",
            "description": "Order Payment",
            "order_id": "<?php echo $order['id']; ?>",
            "handler": function (response) {
                // Send payment details to verify_payment.php
                window.location.href = "verify_payment.php?razorpay_payment_id=" + response.razorpay_payment_id + 
                                     "&razorpay_order_id=" + response.razorpay_order_id + 
                                     "&razorpay_signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "<?php echo htmlspecialchars($user['full_name']); ?>",
                "email": "<?php echo htmlspecialchars($user['email']); ?>",
                "contact": "<?php echo htmlspecialchars($user['phone']); ?>"
            },
            "theme": {
                "color": "#28a745"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }

    // Automatically initiate payment after 2 seconds
    setTimeout(initiatePayment, 2000);
</script>

<?php require_once 'includes/footer.php'; ?> 