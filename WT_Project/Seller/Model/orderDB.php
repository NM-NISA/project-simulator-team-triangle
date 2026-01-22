<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function getOrdersBySeller($sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "
        SELECT 
            o.order_id,
            o.user_id AS buyer_id,
            u.name AS buyer_name,
            o.product_id,
            o.quantity,
            o.total_amount,
            o.order_date,
            o.status,
            p.product_name
        FROM order_table o
        INNER JOIN products_table p 
            ON o.product_id = p.product_id
        INNER JOIN user_table u
            ON o.user_id = u.user_id
        WHERE p.user_id = ?
        ORDER BY o.order_date DESC
    ";


    //injection
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $sellerId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $orders = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    $db->closeConnection($conn);
    return $orders;
}

function updateOrderStatus($orderId, $status) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "UPDATE order_table SET status = ? WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $orderId);
    mysqli_stmt_execute($stmt);

    $db->closeConnection($conn);
}

function countSoldProducts($sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT COUNT(*) as sold_count 
            FROM order_table o
            JOIN products_table p ON o.product_id = p.product_id
            WHERE p.user_id = ? AND o.status = 'completed'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $sellerId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $count = 0;
    if ($row = mysqli_fetch_assoc($result)) {
        $count = $row['sold_count'];
    }

    $db->closeConnection($conn);
    return $count;
}

function totalEarnings($sellerId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT SUM(o.total_amount) as earnings
            FROM order_table o
            JOIN products_table p ON o.product_id = p.product_id
            WHERE p.user_id = ? AND o.status = 'completed'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $sellerId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $sum = 0;
    if ($row = mysqli_fetch_assoc($result)) {
        $sum = $row['earnings'] ?? 0;
    }

    $db->closeConnection($conn);
    return $sum;
}
?>