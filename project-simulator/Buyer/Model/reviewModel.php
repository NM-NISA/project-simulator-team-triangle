<?php
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function addReview($userId, $title, $description, $rating)
{
    $db = new DatabaseConnection();
    $con = $db->openConnection();

    $sql = "INSERT INTO review_table (user_id, title, description, rating)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isss", $userId, $title, $description, $rating);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $db->closeConnection($con);
        return $result;
    }

    $db->closeConnection($con);
    return false;
}
