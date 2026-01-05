<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

$email    = trim($_POST['email'] ?? "");
$password = trim($_POST['password'] ?? "");

$hasError = false;

$_SESSION["previousValues"] = [
    "email" => $email
];

if ($email === "") {
    $_SESSION["emailErr"] = "Email is required";
    $hasError = true;
} else {
    unset($_SESSION["emailErr"]);
}

if ($password === "") {
    $_SESSION["passwordErr"] = "Password is required";
    $hasError = true;
} else {
    unset($_SESSION["passwordErr"]);
}

if ($hasError) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$sql = "SELECT * FROM user_table WHERE email='$email' AND user_type='$userType' AND status='active'";
$result = $db->signin($conn, "user_table", $email);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["userId"] = $user["user_id"];
        $_SESSION["userName"] = $user["name"];
        $_SESSION["userEmail"] = $user["email"];
        $_SESSION["userPassword"] = $user["password"];
        $_SESSION["userType"] = $user["user_type"];
        $_SESSION["userPhone"] = $user["phone"];
        $_SESSION["userStatus"] = $user["status"];

        if ($user["user_type"] === "buyer") {
            header("Location: /WT_Project/Buyer/View/buyerDashboard.php");
        } elseif ($user["user_type"] === "seller") {
            header("Location: /WT_Project/Seller/View/sellerDashboard.php");
        } else {
            header("Location: /WT_Project/Admin/View/adminDashboard.php");
        }
        exit;

    } else {
        $_SESSION["loginErr"] = "Invalid password";
        header("Location: /WT_Project/User/View/login.php");
        exit;
    }

} else {
    $_SESSION["loginErr"] = "Invalid email or inactive account";
    header("Location: /WT_Project/User/View/login.php");
    exit;
}
?>
