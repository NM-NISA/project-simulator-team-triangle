<?php
session_start();
include_once("../Model/reviewModel.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_SESSION['userId'];
    $productId = $_POST['product_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $rating = $_POST['rating'];

    if ($title === "" || $description === "" || $rating === "") {
        header("Location: ../View/review.php?error=empty");
        exit;
    }

    $status = addReview($userId, $productId, $title, $description, $rating);

    if ($status) {
        header("Location: ../View/review.php?success=1");
    } else {
        header("Location: ../View/review.php?error=db");
    }
}
?>
