<?php
session_start();
require_once 'config.php';
require_once 'includes/RazorpayHelper.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$razorpay = new RazorpayHelper();

// Get payment details
$razorpay_payment_id = $_GET['razorpay_payment_id'];
$razorpay_order_id = $_GET['razorpay_order_id'];
$razorpay_signature = $_GET['razorpay_signature'];

// Verify payment
if ($razorpay->verifyPayment($razorpay_payment_id, $razorpay_order_id, $razorpay_signature)) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get cart items and total
        $cart_query = "SELECT c.*, p.price, p.stock 
                      FROM cart c 
                      JOIN products p ON c.product_id = p.id 
                      WHERE c.user_id = $user_id";
        $cart_result = $conn->query($cart_query);
        
        if ($cart_result->num_rows === 0) {
            throw new Exception("Cart is empty");
        }

        // Calculate total
        $total = 0;
        while ($item = $cart_result->fetch_assoc()) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create order
        $order_query = "INSERT INTO orders (user_id, total_amount, payment_status, razorpay_order_id, razorpay_payment_id, razorpay_signature) 
                       VALUES ($user_id, $total, 'completed', '$razorpay_order_id', '$razorpay_payment_id', '$razorpay_signature')";
        
        if (!$conn->query($order_query)) {
            throw new Exception("Error creating order: " . $conn->error);
        }
        
        $order_id = $conn->insert_id;
        
        // Reset cart result pointer
        $cart_result->data_seek(0);
        
        // Add order items and update stock
        while ($item = $cart_result->fetch_assoc()) {
            // Add order item
            $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})";
            
            if (!$conn->query($order_item_query)) {
                throw new Exception("Error adding order item: " . $conn->error);
            }
            
            // Update product stock
            $new_stock = $item['stock'] - $item['quantity'];
            $update_stock_query = "UPDATE products SET stock = $new_stock WHERE id = {$item['product_id']}";
            
            if (!$conn->query($update_stock_query)) {
                throw new Exception("Error updating stock: " . $conn->error);
            }
        }
        
        // Clear cart
        $clear_cart_query = "DELETE FROM cart WHERE user_id = $user_id";
        if (!$conn->query($clear_cart_query)) {
            throw new Exception("Error clearing cart: " . $conn->error);
        }
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to order confirmation
        header("Location: order_confirmation.php?id=$order_id");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Log the error
        error_log("Payment processing error: " . $e->getMessage());
        
        $_SESSION['error'] = "An error occurred while processing your order: " . $e->getMessage();
        header("Location: cart.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Payment verification failed. Please try again.";
    header("Location: cart.php");
    exit();
} 