<?php
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

function addToCart($userId, $productId) {
    $db = new DatabaseConnection();
    $conn = $db->openConnection();

    $checkSql = "SELECT quantity FROM cart_table 
                 WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateSql = "UPDATE cart_table 
                      SET quantity = quantity + 1
                      WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
    } else {
        $insertSql = "INSERT INTO cart_table (user_id, product_id, quantity)
                      VALUES (?, ?, 1)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
    }

    $db->closeConnection($conn);
    return true;
}

class CartModel {
    function getCartProducts($userId) {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        $stmt = $conn->prepare("
            SELECT c.cart_id, c.product_id, c.quantity, p.product_name, p.price, p.image
            FROM cart_table c
            JOIN products_table p ON c.product_id = p.product_id
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while($row = $result->fetch_assoc()){
            $products[] = $row;
        }

        $db->closeConnection($conn);
        return $products;
    }

    function updateCartQuantity($userId, $productId, $quantity) {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        if($quantity > 0){
            $stmt = $conn->prepare("UPDATE cart_table SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $userId, $productId);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("DELETE FROM cart_table WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
        }

        $db->closeConnection($conn);
    }

    function deleteFromCart($userId, $cartId) {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        $stmt = $conn->prepare("DELETE FROM cart_table WHERE user_id = ? AND cart_id = ?");
        $stmt->bind_param("ii", $userId, $cartId);
        $stmt->execute();

        $db->closeConnection($conn);
    }
}
?>
