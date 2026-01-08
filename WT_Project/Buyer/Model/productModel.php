<?php
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function getAllProductsGroupedByCategory() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT * FROM products_table 
             WHERE status = 'approved'
             ORDER BY category_name";

    $result = mysqli_query($conn, $sql);

    $productsByCategory = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productsByCategory[$row['category_name']][] = $row;
        }
    }

    $db->closeConnection($conn);
    return $productsByCategory;
}

function getProducts($search = "", $category = "") {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $search = mysqli_real_escape_string($conn, $search);
    $category = mysqli_real_escape_string($conn, $category);

    $sql = "SELECT product_id, product_name, description, price, category_name, image
        FROM products_table
        WHERE status = 'approved'";

    if ($search !== "") {
        $sql .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
    }

    if ($category !== "") {
        $sql .= " AND category_name = '$category'";
    }

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

?>
