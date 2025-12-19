<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user details
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// Get cart items
$cart_query = "SELECT c.*, p.name, p.price, p.stock 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = $user_id";
$cart_result = $conn->query($cart_query);

if ($cart_result->num_rows === 0) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: cart.php");
    exit();
}

$total = 0;
$items = [];
while ($item = $cart_result->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $items[] = $item;
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Create order
        $order_query = "INSERT INTO orders (user_id, total_amount) VALUES ($user_id, $total)";
        $conn->query($order_query);
        $order_id = $conn->insert_id;
        
        // Add order items and update stock
        foreach ($items as $item) {
            // Add order item
            $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})";
            $conn->query($order_item_query);
            
            // Update product stock
            $new_stock = $item['stock'] - $item['quantity'];
            $update_stock_query = "UPDATE products SET stock = $new_stock WHERE id = {$item['product_id']}";
            $conn->query($update_stock_query);
        }
        
        // Clear cart
        $clear_cart_query = "DELETE FROM cart WHERE user_id = $user_id";
        $conn->query($clear_cart_query);
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to order confirmation
        header("Location: order_confirmation.php?id=$order_id");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error'] = "An error occurred while processing your order. Please try again.";
        header("Location: cart.php");
        exit();
    }
}

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container">
    <h2 class="mb-4">Checkout</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Shipping Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Shipping Address</label>
                        <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Order Summary</h4>
                </div>
                <div class="card-body">
                    <?php foreach ($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?>
                            </div>
                            <div>
                                ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong>₹<?php echo number_format($total, 2); ?></strong>
                    </div>

                    <form action="process_payment.php" method="POST">
                        <button type="submit" class="btn btn-success w-100">
                            Pay with Razorpay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 