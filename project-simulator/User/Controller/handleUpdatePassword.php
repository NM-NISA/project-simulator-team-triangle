<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword     = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$userEmail       = $_SESSION['userEmail'];
$userType        = $_SESSION['userType'];

if (!$currentPassword || !$newPassword || !$confirmPassword) {
    $_SESSION['passErr'] = "All fields are required.";
    header("Location: /WT_Project/User/View/changePassword.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$sql = "SELECT password FROM user_table WHERE email = ? AND user_type = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $userEmail, $userType);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    $_SESSION['passErr'] = "User not found.";
    header("Location: /WT_Project/User/View/changePassword.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

if (!password_verify($currentPassword, $user['password'])) {
    $_SESSION['passErr'] = "Current password is incorrect.";
    header("Location: /WT_Project/User/View/changePassword.php");
    exit;
}

if ($newPassword !== $confirmPassword) {
    $_SESSION['passErr'] = "New password and confirm password do not match.";
    header("Location: /WT_Project/User/View/changePassword.php");
    exit;
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updateSql = "UPDATE user_table SET password = ? WHERE email = ? AND user_type = ?";
$updateStmt = mysqli_prepare($conn, $updateSql);
mysqli_stmt_bind_param($updateStmt, "sss", $hashedPassword, $userEmail, $userType);

if (mysqli_stmt_execute($updateStmt)) {
    $_SESSION['userPassword'] = $hashedPassword;
    $_SESSION['passSuccess'] = "Password updated successfully!";
} else {
    $_SESSION['passErr'] = "Failed to update password.";
}

$db->closeConnection($conn);
header("Location: /WT_Project/User/View/viewProfile.php");
exit;
?>
