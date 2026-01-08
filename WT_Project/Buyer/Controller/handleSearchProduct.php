<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    echo json_encode([]);
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/Buyer/Model/productModel.php';

$search   = $_POST['search'] ?? '';
$category = $_POST['category'] ?? '';

$products = getProducts($search, $category);

echo json_encode($products);
?>
