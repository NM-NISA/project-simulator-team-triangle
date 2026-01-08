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

function getSellerProducts($sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = $conn->prepare("
        SELECT product_id, product_name, price, image, availability, status
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

function getAllProductsForAdmin() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT 
                p.product_id,
                p.product_name,
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
