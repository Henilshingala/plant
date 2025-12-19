<?php
// Database configuration
$host = 'sql100.infinityfree.com';
$username = 'if0_38602234';
$password = 'Future404';
$database = 'if0_38602234_plant';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Common functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCartCount() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }
    return 0;
}

// Error handling
function setError($message) {
    $_SESSION['error'] = $message;
}

function setSuccess($message) {
    $_SESSION['success'] = $message;
}

// Security functions
function sanitize($input) {
    global $conn;
    return $conn->real_escape_string(trim($input));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Cart functions
function addToCart($product_id, $quantity = 1) {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) 
                              VALUES (?, ?, ?)
                              ON DUPLICATE KEY UPDATE quantity = quantity + ?");
        $stmt->bind_param("iiii", $user_id, $product_id, $quantity, $quantity);
        return $stmt->execute();
    }
    return false;
}

function updateCartQuantity($product_id, $quantity) {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? 
                              WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        return $stmt->execute();
    }
    return false;
}

function removeFromCart($product_id) {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }
    return false;
}

// Order functions
function createOrder($user_id, $total_amount, $shipping_address) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) 
                          VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $total_amount, $shipping_address);
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    return false;
}

function addOrderItem($order_id, $product_id, $quantity, $price) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    return $stmt->execute();
}

// Product functions
function getProduct($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getProducts($category_id = null, $search = null) {
    global $conn;
    $query = "SELECT * FROM products WHERE 1=1";
    $params = [];
    $types = "";
    
    if ($category_id) {
        $query .= " AND category_id = ?";
        $params[] = $category_id;
        $types .= "i";
    }
    
    if ($search) {
        $query .= " AND (name LIKE ? OR description LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "ss";
    }
    
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Category functions
function getCategories() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getCategory($category_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Input validation
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

function validatePrice($price) {
    return is_numeric($price) && $price >= 0;
}

function validateQuantity($quantity) {
    return is_numeric($quantity) && $quantity > 0;
}
?> 