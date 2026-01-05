<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/Buyer/Model/productModel.php';

$search = $_POST['search'] ?? '';
$category = $_POST['category'] ?? '';

$productModel = new ProductModel();
$products = $productModel->getProducts($search, $category);
$sql = "SELECT product_name, description, price, category_name 
        FROM products_table
        WHERE 1";

if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $sql .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
}

if (!empty($_POST['category'])) {
    $category = $_POST['category'];
    $sql .= " AND category_name = '$category'";
}

echo json_encode($products);
?>
