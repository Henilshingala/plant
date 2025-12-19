<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $zip_code = $conn->real_escape_string($_POST['zip_code']);

    // Update user address
    $update_query = "UPDATE users SET 
                    address = '$address',
                    city = '$city',
                    state = '$state',
                    zip_code = '$zip_code'
                    WHERE id = $user_id";
    
    if ($conn->query($update_query)) {
        $_SESSION['success'] = "Delivery address updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating delivery address.";
    }
}

header("Location: profile.php");
exit();
?> 