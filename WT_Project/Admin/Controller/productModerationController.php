<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/productModel.php");

if ($_SESSION['userType'] !== 'admin') {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

if (isset($_POST['product_id'], $_POST['status'])) {
    updateProductStatus($_POST['product_id'], $_POST['status']);
}

header("Location: /WT_Project/Admin/View/productModeration.php");
exit;
?>
