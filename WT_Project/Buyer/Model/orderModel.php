<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/WT_Project/User/Model/db.php');

function addOrder($userId, $productId, $quantity, $totalAmount, $deliveryLocation){
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $stmt = mysqli_prepare($conn, "INSERT INTO order_table (user_id, product_id, quantity, total_amount, delivery_location) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiiss", $userId, $productId, $quantity, $totalAmount, $deliveryLocation);
    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    $db->closeConnection($conn);

    return $result;
}
?>
