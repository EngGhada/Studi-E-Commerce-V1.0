<?php
session_start();
header("Content-Type: application/json");
include '../../admin/connect.php';
if (!isset($_SESSION['cartCount'])) {
    $_SESSION['cartCount'] = 0;
}

$userId = $_SESSION['uid'] ?? null;
$data = json_decode(file_get_contents('php://input'), true);
$itemId = $data['item_id'] ?? null;

if (!$userId || !$itemId) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}


try {
    $stmt = $con->prepare("DELETE FROM cart WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
 
    $stmt2 = $con->prepare("SELECT SUM(quantity) AS total FROM cart WHERE user_id = ?");
    $stmt2->execute([$userId]);
    
    $total = $stmt2->fetchColumn();

    $_SESSION['cartCount'] = $total ?? 0; // Update session cart count
    echo json_encode(['success' => true, 'cartCount' => $_SESSION['cartCount']]);
 
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
