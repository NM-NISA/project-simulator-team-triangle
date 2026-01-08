<?php
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function getAllProductsForAdmin() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT 
                p.product_id,
                p.product_name,
                p.category_name,
                p.status,
                u.name AS seller_name
            FROM products_table p
            JOIN user_table u ON p.user_id = u.user_id
            WHERE u.user_type = 'seller'
            ORDER BY p.product_id DESC";

    $result = mysqli_query($conn, $sql);
    $products = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }

    $db->closeConnection($conn);
    return $products;
}

function updateProductStatus($productId, $status) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = $conn->prepare(
        "UPDATE products_table SET status = ? WHERE product_id = ?"
    );
    $stmt->bind_param("si", $status, $productId);
    $success = $stmt->execute();

    $db->closeConnection($conn);
    return $success;
}

?>
