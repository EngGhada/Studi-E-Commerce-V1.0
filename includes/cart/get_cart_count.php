<?php
session_start();

// Example: Set cart count in the session (simulate this during cart updates)
if (!isset($_SESSION['cartCount'])) {
    $_SESSION['cartCount'] = 0; // Default to 0
}

// Example: Fetch cart count from the database (if required)
// For now, we are just using a session variable for simplicity
$cartCount = $_SESSION['cartCount'];

// Return the cart count as JSON
header('Content-Type: application/json');
echo json_encode(['cartCount' => $cartCount]);
?>
