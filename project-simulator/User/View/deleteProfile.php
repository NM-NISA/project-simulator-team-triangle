<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || !isset($_SESSION['userId'])) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();  

$userId = $_SESSION['userId']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = "DELETE FROM user_table WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        session_destroy();
        echo "<script>
                alert('Your profile has been permanently deleted.');
                window.location='/WT_Project/User/View/login.php';
              </script>";
        exit;
    } else {
        die("Execute failed: " . $stmt->error);
    }
}
?>
