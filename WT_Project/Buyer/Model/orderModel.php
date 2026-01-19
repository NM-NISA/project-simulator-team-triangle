<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/WT_Project/User/Model/db.php');

function addOrder($userId, $productId, $quantity, $totalAmount, $deliveryLocation, $mobile){
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = mysqli_prepare($conn, "INSERT INTO order_table (user_id, product_id, quantity, total_amount, delivery_location, mobile) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiiisi", $userId, $productId, $quantity, $totalAmount, $deliveryLocation, $mobile);
    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    $db->closeConnection($conn);

    return $result;
}

function getOrderStatusByUserId($userId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT status, COUNT(*) as total_orders 
            FROM order_table 
            WHERE user_id = ? 
            GROUP BY status";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $statuses = [];
    while($row = $result->fetch_assoc()) {
        $statuses[$row['status']] = $row['total_orders'];
    }

    $db->closeConnection($conn);
    return $statuses;
}

function getTotalPurchasedProducts($userId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT COUNT(*) AS total 
            FROM order_table 
            WHERE user_id = ? AND status = 'completed'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $db->closeConnection($conn);
    return $row['total'] ?? 0;
}
?>
