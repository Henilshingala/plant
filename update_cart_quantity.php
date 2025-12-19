<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$cart_id = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$user_id = $_SESSION['user_id'];

if ($cart_id <= 0 || !in_array($action, ['increase', 'decrease'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: cart.php");
    exit();
}

// Get current cart item and product details
$query = "SELECT c.*, p.stock 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.id = $cart_id AND c.user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Cart item not found.";
    header("Location: cart.php");
    exit();
}

$cart_item = $result->fetch_assoc();
$current_quantity = $cart_item['quantity'];
$product_stock = $cart_item['stock'];

// Calculate new quantity
$new_quantity = $action === 'increase' ? $current_quantity + 1 : $current_quantity - 1;

// Validate new quantity
if ($new_quantity <= 0) {
    // If quantity becomes 0 or negative, remove the item
    $delete_query = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
    if ($conn->query($delete_query)) {
        $_SESSION['success'] = "Item removed from cart.";
    }
} elseif ($new_quantity > $product_stock) {
    $_SESSION['error'] = "Not enough stock available.";
} else {
    // Update quantity
    $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = $cart_id AND user_id = $user_id";
    if ($conn->query($update_query)) {
        $_SESSION['success'] = "Cart updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating cart.";
    }
}

header("Location: cart.php");
exit();
?> 