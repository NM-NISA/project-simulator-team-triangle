<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function getTotalSales() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT SUM(total_amount) AS total_sales FROM order_table WHERE status='completed'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_sales'] ?? 0;
    }
    return 0;
}

function getTotalOrders() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT COUNT(*) AS total_orders FROM order_table";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_orders'] ?? 0;
    }
    return 0;
}
?>
