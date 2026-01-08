<?php
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function getSellerProducts($sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = $conn->prepare("
        SELECT product_id, product_name, category_name, price, image, availability, status
        FROM products_table
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $sellerId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $db->closeConnection($conn);
    return $products;
}

function updateProduct($productId, $name, $price, $sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = $conn->prepare("UPDATE products_table SET product_name = ?, price = ? WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("sdii", $name, $price, $productId, $sellerId);
    $success = $stmt->execute();

    $db->closeConnection($conn);
    return $success;
}

function deleteProduct($productId, $sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = $conn->prepare("DELETE FROM products_table WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $productId, $sellerId);
    $success = $stmt->execute();

    $db->closeConnection($conn);
    return $success;
}

function updateProductAvailability($productId, $availability, $sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = $conn->prepare("UPDATE products_table SET availability = ? WHERE product_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $availability, $productId, $sellerId);
    $success = $stmt->execute();

    $db->closeConnection($conn);
    return $success;
}

?>
