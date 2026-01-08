<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Buyer/Model/cartModel.php");

if (!isset($_SESSION['userId'])) {
    echo json_encode(["success" => false]);
    exit;
}

$userId = $_SESSION['userId'];
$cartId = $_POST['cart_id'] ?? '';

if ($cartId) {
    $cart = new CartModel();
    $cart->deleteFromCart($userId, $cartId);
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
