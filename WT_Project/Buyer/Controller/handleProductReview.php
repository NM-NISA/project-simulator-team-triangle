<?php
session_start();
include_once("../Model/reviewModel.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_SESSION['userId'];
    $productId = $_POST['product_id'] ?? 0;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $rating = $_POST['rating'];

    if ($productId <= 0) {
        header("Location: /WT_Project/Buyer/View/review.php?error=1");
        exit;
    }

    if ($title === "" || $description === "" || $rating === "") {
        header("Location: ../View/review.php?error=empty");
        exit;
    }

    $status = addReview(intval($userId), intval($productId), $title, $description, $rating);

    if ($status) {
        header("Location: ../View/review.php?success=1");
    } else {
        header("Location: ../View/review.php?error=db");
    }
}
?>
