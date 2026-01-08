<?php
session_start();
header('Content-Type: application/json');

include($_SERVER['DOCUMENT_ROOT']."/WT_Project/Buyer/Model/db.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in.'
    ]);
    exit;
}

$userId = $_SESSION['userId'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;

    if ($productId <= 0 || $quantity <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product or quantity.'
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT quantity FROM cart_table WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE cart_table SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $userId, $productId);
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Cart updated successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update cart.'
            ]);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO cart_table (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Product added to cart.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add product to cart.'
            ]);
        }
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
