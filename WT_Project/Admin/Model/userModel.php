<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function getAllUsers() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT user_id, name, email, user_type, status FROM user_table";
    $result = mysqli_query($conn, $sql);

    $users = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }

    $db->closeConnection($conn);
    return $users;
}
function getTotalUsers() {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();
    
    $sql = "SELECT COUNT(*) AS total_users FROM user_table";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['total_users'] ?? 0;
    }

    $db->closeConnection($conn);
    return 0;
}
?>
