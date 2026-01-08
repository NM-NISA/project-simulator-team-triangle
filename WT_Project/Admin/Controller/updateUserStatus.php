<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userType'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_POST['user_id'] ?? '';
$status = $_POST['status'] ?? '';

if (!$userId || !in_array($status, ['active', 'blocked'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();

$stmt = $conn->prepare("UPDATE user_table SET status = ? WHERE user_id = ?");
$stmt->bind_param("si", $status, $userId);
$success = $stmt->execute();

$db->closeConnection($conn);

echo json_encode(['success' => $success]);
?>