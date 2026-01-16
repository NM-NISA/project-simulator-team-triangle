<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function addReview($userId, $productId, $title, $description, $rating)
{
    $db = new DatabaseConnection();
    $con = $db->openConnection();

    $sql = "INSERT INTO review_table (user_id, product_id, title, description, rating)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iisss", $userId, $productId, $title, $description, $rating);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $db->closeConnection($con);
        return $result;
    }

    $db->closeConnection($con);
    return false;
}

function getReviewsByProductId($productId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $sql = "SELECT r.review_date, u.name AS name, r.rating, r.description
            FROM review_table r
            JOIN user_table u ON r.user_id = u.user_id
            WHERE r.product_id = ?
            ORDER BY r.review_date DESC";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo "SQL Error: " . mysqli_error($conn);
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }

    $db->closeConnection($conn);
    return $reviews;
}
?>
