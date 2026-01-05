<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$name  = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = $_SESSION['userEmail']; 
$userType = $_SESSION['userType'];

$previousValues = [
    "name" => $name,
    "email" => $email,
    "phone" => $phone,
    "userType" => $userType
];

$errors = [];

if ($name == "") {
    $errors["name"] = "Name cannot be empty";
}
if (strlen($phone) != 11) {
    $errors["phone"] = "Phone must be 11 digits";
}

if (!empty($errors)) {
    $_SESSION["nameErr"] = $errors["name"] ?? "";
    $_SESSION["phoneErr"] = $errors["phone"] ?? "";
    $_SESSION["previousValues"] = $previousValues;

    header("Location: /WT_Project/User/View/editProfile.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$sql = "UPDATE user_table 
        SET name = ?, phone = ?
        WHERE email = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $name, $phone, $email);
mysqli_stmt_execute($stmt);

$_SESSION['userName']  = $name;
$_SESSION['userPhone'] = $phone;

$db->closeConnection($conn);

header("Location: /WT_Project/User/View/viewProfile.php");
exit;
?>
