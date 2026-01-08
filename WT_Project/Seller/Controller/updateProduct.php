<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Buyer/Model/productModel.php");

if (!isset($_SESSION['userId'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$sellerId = $_SESSION['userId'];
$productId = $_POST['product_id'] ?? '';
$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';

if ($productId && $name !== '' && $price !== '') {
    $success = updateProduct($productId, $name, $price, $sellerId);
    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
