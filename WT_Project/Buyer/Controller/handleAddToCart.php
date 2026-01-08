<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if (!isset($_POST['product_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Buyer/Model/cartModel.php";

$userId = $_SESSION['userId'];
$productId = intval($_POST['product_id']);

addToCart($userId, $productId);

echo json_encode(["success" => true, "message" => "Added to cart"]);
?>