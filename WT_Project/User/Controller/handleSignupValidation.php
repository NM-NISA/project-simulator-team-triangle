<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

$name     = $_POST['name'] ?? "";
$email    = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";
$userType = $_POST['userType'] ?? "";

$previousValues = [
    "name" => $name,
    "email" => $email,
    "userType" => $userType
];

$errors = [];

if (!$name) {
    $errors["name"] = "Name field is required";
} elseif (!preg_match("/^[a-zA-Z\s-]{2,50}$/", $name)) {
    $errors["name"] = "Name must contain only letters, spaces or hyphens (2-50 chars)";
}

if (!$email) {
    $errors["email"] = "Email field is required";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format";
}

if (!$password) {
    $errors["password"] = "Password field is required";
} elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters";
} elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
    $errors["password"] = "Password must include uppercase, lowercase, number & special character";
}

if (!$userType) {
    $errors["userType"] = "User type selection is required";
}

if (!empty($errors)) {
    $_SESSION["nameErr"] = $errors["name"] ?? "";
    $_SESSION["emailErr"] = $errors["email"] ?? "";
    $_SESSION["passwordErr"] = $errors["password"] ?? "";
    $_SESSION["userErr"] = $errors["userType"] ?? "";
    $_SESSION["previousValues"] = $previousValues;

    header("Location: /WT_Project/User/View/signup.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$check = mysqli_query($conn, "SELECT user_id FROM user_table WHERE email='$email'");
if (!$check) {
    die("SELECT FAILED: " . mysqli_error($conn));
}

if (mysqli_num_rows($check) > 0) {
    $_SESSION["signupErr"] = "Email already registered";
    $_SESSION["previousValues"] = $previousValues;
    $db->closeConnection($conn);
    header("Location: /WT_Project/User/View/signup.php");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$result = $db->signUp($conn, "user_table", $name, $email, $hashedPassword, $userType);
if (!$result) {
    die("INSERT FAILED: " . mysqli_error($conn));
}

$db->closeConnection($conn);

unset($_SESSION["nameErr"], $_SESSION["emailErr"], $_SESSION["passwordErr"], $_SESSION["userErr"], $_SESSION["previousValues"], $_SESSION["signupErr"]);

header("Location: /WT_Project/User/View/login.php");
exit;
?>
