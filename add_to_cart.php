<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $user_id = $_SESSION['user_id'];

    // Get product details
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit();
    }

    $product = $result->fetch_assoc();

    // Validate quantity
    if ($quantity <= 0 || $quantity > $product['stock']) {
        $_SESSION['error'] = "Invalid quantity selected.";
        header("Location: product.php?id=$product_id");
        exit();
    }

    // Check if product already in cart
    $check_query = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $cart_item = $check_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;

        if ($new_quantity <= $product['stock']) {
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = {$cart_item['id']}";
            $conn->query($update_query);
            $_SESSION['success'] = "Cart updated successfully!";
        } else {
            $_SESSION['error'] = "Not enough stock available.";
        }
    } else {
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        if ($conn->query($insert_query)) {
            $_SESSION['success'] = "Product added to cart successfully!";
        } else {
            $_SESSION['error'] = "Error adding product to cart.";
        }
    }

    header("Location: cart.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?> <?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $user_id = $_SESSION['user_id'];

    // Get product details
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit();
    }

    $product = $result->fetch_assoc();

    // Validate quantity
    if ($quantity <= 0 || $quantity > $product['stock']) {
        $_SESSION['error'] = "Invalid quantity selected.";
        header("Location: product.php?id=$product_id");
        exit();
    }

    // Check if product already in cart
    $check_query = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $cart_item = $check_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;

        if ($new_quantity <= $product['stock']) {
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = {$cart_item['id']}";
            $conn->query($update_query);
            $_SESSION['success'] = "Cart updated successfully!";
        } else {
            $_SESSION['error'] = "Not enough stock available.";
        }
    } else {
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        if ($conn->query($insert_query)) {
            $_SESSION['success'] = "Product added to cart successfully!";
        } else {
            $_SESSION['error'] = "Error adding product to cart.";
        }
    }

    header("Location: cart.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?> <?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $user_id = $_SESSION['user_id'];

    // Get product details
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit();
    }

    $product = $result->fetch_assoc();

    // Validate quantity
    if ($quantity <= 0 || $quantity > $product['stock']) {
        $_SESSION['error'] = "Invalid quantity selected.";
        header("Location: product.php?id=$product_id");
        exit();
    }

    // Check if product already in cart
    $check_query = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $cart_item = $check_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;

        if ($new_quantity <= $product['stock']) {
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = {$cart_item['id']}";
            $conn->query($update_query);
            $_SESSION['success'] = "Cart updated successfully!";
        } else {
            $_SESSION['error'] = "Not enough stock available.";
        }
    } else {
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        if ($conn->query($insert_query)) {
            $_SESSION['success'] = "Product added to cart successfully!";
        } else {
            $_SESSION['error'] = "Error adding product to cart.";
        }
    }

    header("Location: cart.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?> <?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $user_id = $_SESSION['user_id'];

    // Get product details
    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit();
    }

    $product = $result->fetch_assoc();

    // Validate quantity
    if ($quantity <= 0 || $quantity > $product['stock']) {
        $_SESSION['error'] = "Invalid quantity selected.";
        header("Location: product.php?id=$product_id");
        exit();
    }

    // Check if product already in cart
    $check_query = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $cart_item = $check_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;

        if ($new_quantity <= $product['stock']) {
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = {$cart_item['id']}";
            $conn->query($update_query);
            $_SESSION['success'] = "Cart updated successfully!";
        } else {
            $_SESSION['error'] = "Not enough stock available.";
        }
    } else {
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
        if ($conn->query($insert_query)) {
            $_SESSION['success'] = "Product added to cart successfully!";
        } else {
            $_SESSION['error'] = "Error adding product to cart.";
        }
    }

    header("Location: cart.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?> 