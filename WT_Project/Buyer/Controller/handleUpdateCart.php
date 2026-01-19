<?php
session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT']."/WT_Project/Buyer/Model/cartModel.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$cartId  = intval($_POST['cart_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 0);

if ($cartId <= 0 || $quantity <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid cart or quantity'
    ]);
    exit;
}

$cartModel = new CartModel();
$cartModel->updateCartQuantityByCartId($cartId, $quantity);

echo json_encode([
    'success' => true,
    'message' => 'Cart updated successfully'
]);
?>
