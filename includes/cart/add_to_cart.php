<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
error_log('Debugging add_to_cart.php');

header("Content-Type: application/json");
include "../../admin/connect.php";
if (!isset($_SESSION['cartCount'])) {
    $_SESSION['cartCount'] = 0;
}

// Check if user is logged in
$userId = $_SESSION['uid'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$itemId = $data['item_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$itemId) {
    echo json_encode(['success' => false, 'message' => 'Item ID is missing']);
    exit;
}

try {
    // Insert or update cart
    $stmt = $con->prepare("INSERT INTO cart (user_id, item_id, quantity)  VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE quantity = quantity + ? ");
    $stmt->execute([$userId, $itemId, $quantity, $quantity]);
    
    // Update the cart count in the session
    $_SESSION['cartCount'] += $quantity;

    echo json_encode(['success' => true, 'message' => 'Item added to cart!', 'cartCount' => $_SESSION['cartCount']]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>

