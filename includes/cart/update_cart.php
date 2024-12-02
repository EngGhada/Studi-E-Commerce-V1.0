<?php
session_start();
header("Content-Type: application/json");
include '../../connect.php';

$userId = $_SESSION['uid'] ?? 0;
$data = json_decode(file_get_contents('php://input'), true);
$itemId = $data['item_id'] ?? null;
$change = $data['change'] ?? 0;

if (!$userId || !$itemId || !$change) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}
try {
    $stmt = $con->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$change, $userId, $itemId]);

    // Optionally remove items with quantity <= 0
    $stmt = $con->prepare("DELETE FROM cart WHERE quantity <= 0");
    $stmt->execute();
  
      // Recalculate total cart count
    $stmt = $con->prepare("SELECT SUM(quantity) AS cartCount FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cartCount = $stmt->fetchColumn();

   // Update the session variable
   $_SESSION['cartCount'] = $cartCount ?? 0;

   echo json_encode(['success' => true, 'cartCount' => $cartCount]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
