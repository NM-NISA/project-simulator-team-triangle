<?php
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

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
?>
