<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/User/Model/db.php");

    function getAllProductsGroupedByCategory() {
        $db = new DatabaseConnection();
        $conn = $db->openConnection();

        $sql = "SELECT * FROM products_table 
                 WHERE status = 'approved'
                 ORDER BY category_name";

        $result = mysqli_query($conn, $sql);

        $productsByCategory = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $productsByCategory[$row['category_name']][] = $row;
            }
        }

        $db->closeConnection($conn);
        return $productsByCategory;
    }

    function getProducts($search = "", $category = "") {
        $db = new DatabaseConnection();
        $con = $db->openConnection();

        $search = $this->conn->real_escape_string($search);
        $category = $this->conn->real_escape_string($category);

        $sql = "SELECT product_name, description, price, category_name 
                FROM products_table 
                WHERE 1";

        if (!empty($search)) {
            $sql .= " AND (product_name LIKE '%$search%' OR description LIKE '%$search%')";
        }

        if (!empty($category)) {
            $sql .= " AND category_name = '$category'";
        }

        $result = $this->conn->query($sql);

        $products = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $db->closeConnection($con);
        return $products;
    }

?>
