<?php
require_once 'config.php';

// Update all products stock to 50
$query = "UPDATE products SET stock = 50";
$result = $conn->query($query);

if ($result) {
    echo "Successfully updated all product quantities to 50.";
} else {
    echo "Error updating product quantities: " . $conn->error;
}

$conn->close();
?> 