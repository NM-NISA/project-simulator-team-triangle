<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Buyer/Model/productModel.php");

if (!isset($_SESSION['userId'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$sellerId = $_SESSION['userId'];
$productId = $_POST['product_id'] ?? '';

if ($productId) {
    $success = deleteProduct($productId, $sellerId);
    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
