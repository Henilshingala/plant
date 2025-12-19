<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$cart_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

if ($cart_id <= 0) {
    $_SESSION['error'] = "Invalid cart item.";
    header("Location: cart.php");
    exit();
}

// Verify the cart item belongs to the user
$check_query = "SELECT * FROM cart WHERE id = $cart_id AND user_id = $user_id";
$check_result = $conn->query($check_query);

if ($check_result->num_rows === 0) {
    $_SESSION['error'] = "Cart item not found.";
    header("Location: cart.php");
    exit();
}

// Remove the item from cart
$delete_query = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
if ($conn->query($delete_query)) {
    $_SESSION['success'] = "Item removed from cart successfully!";
} else {
    $_SESSION['error'] = "Error removing item from cart.";
}

header("Location: cart.php");
exit();
?> 