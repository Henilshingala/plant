<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $full_name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    // Check if email is already taken by another user
    $check_query = "SELECT id FROM users WHERE email = '$email' AND id != $user_id";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Email is already taken by another user.";
        header("Location: profile.php");
        exit();
    }

    // Update user information
    $update_query = "UPDATE users SET full_name = '$full_name', email = '$email' WHERE id = $user_id";
    
    if ($conn->query($update_query)) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating profile.";
    }
}

header("Location: profile.php");
exit();
?> 