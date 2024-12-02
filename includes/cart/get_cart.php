<?php
session_start();
header("Content-Type: application/json");
include "../../connect.php";

$userId = $_SESSION['uid'];

$stmt = $con->prepare("SELECT items.Item_ID as item_id,
                              items.Name,
                              items.Price, 
                              cart.quantity, 
                             (items.Price * cart.quantity) AS Total 
                        FROM 
                             cart
                       JOIN 
                             items
                         ON   cart.item_id = items.Item_ID
                         WHERE cart.user_id = ?");
                         
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cartItems);
?>