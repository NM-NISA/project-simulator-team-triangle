<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Seller/Model/orderDB.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $status  = $_POST['status'];

    updateOrderStatus($orderId, $status);
}

header("Location: /WT_Project/Seller/View/viewOrders.php");
exit;
?>