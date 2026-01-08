<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$name = $_SESSION['userName'] ?? '';
$email = $_SESSION['userEmail'] ?? '';
$password = $_SESSION['userPassword'] ?? '';
$userType = $_SESSION['userType'] ?? '';


$userId   = $_SESSION['userId'];
$userType = $_SESSION['userType'] ?? '';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name        = trim($_POST['name'] ?? "");
    $category    = $_POST['category'] ?? "";
    $description = trim($_POST['description'] ?? "");
    $price       = trim($_POST['price'] ?? "");

    // Image Handling
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetDir = "../Public/Uploads/";
        $targetFile = $targetDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $error = "Image upload failed";
        }
    }

    if ($name === "" || $category === "" || $description === "" || $price === "") {
        $error = "All fields are required";
    } elseif (!is_numeric($price)) {
        $error = "Price must be numeric";
    } elseif ($error === "") {

        $sql = "INSERT INTO products_table 
                (user_id, category_name, product_name, description, price, image)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $conn = mysqli_connect("127.0.0.1", "root", "", "campus_marketplace");

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssis",
            $userId,
            $category,
            $name,
            $description,
            $price,
            $imageName,
        );

        if ($stmt->execute()) {
            $success = "Product uploaded successfully! Waiting for approval.";
        } else {
            $error = "Database error. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Upload For Sale</title>
<style>
        body{
            font-family: Arial, Helvetica, sans-serif;
        }
        b{
            text-align: center;
            color:gray;
            font-size: 25px;
        }
        textarea{
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
        }
        .main{
            width: 100%;
            margin: auto;
            background-image: linear-gradient(
            rgba(255,255,255,0.6)), url("../Public/CSS/aiub_campus_pic.jpg");
            background-repeat: no-repeat; 
            background-size: cover; 
            background-position: center; 
        }
        .header{
            padding: 10px;
            background-color: white;
        }
        .header img{
            width: 120px;
        }
        a{
            text-decoration: none;
            color: blue;
        }
        .nav{
            float: right;
        }
        .nav a{
            margin-left: 10px;
        }
        .content-area{
            display: flex;
        }
        .sidebar{
            background-color: white;
            width: 20%;
            border-right: 1px solid #a1a1a1;
            padding: 15px;
        }
        .sidebar .btn {
            width: 85%;
            display: inline-block;
            padding: 10px 15px;
            background-color: #0050a7;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }
        .sidebar .btn:hover {
            background-color: #0175f1;
        }
        .main-content{
            width: 80%;
            padding: 15px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }
        .info-box{
            background-color: white;
            width: 500px;
            border: 1px solid gray;
            margin: 20px auto;
            padding: 15px;
            text-align: left;
        }
        .error{ color:red; }
        .success{ color:green; }
        footer{
            text-align: center;
            background-color: white;
            padding: 10px;
        }
</style>
</head>

<body>
<div class="main">
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h2><span style="color: rgb(106, 109, 255); font-size: 50px; font-style: italic; font-weight: bolder;">Campus </span><span style = "color:gray;">Marketplace</span></h2>
                </td>
                <td align="right">
                Logged in as <a href="#"><?php echo $_SESSION["userName"]; ?></a> | <a href="/WT_Project/User/View/viewProfile.php">View Profile</a> | <a href="/WT_Project/User/Controller/logout.php">Logout</a>
                </td>
            </tr>
            <hr style = "border: 10px solid rgb(0, 0, 141);">
            <hr style = "border: 1px solid rgb(0, 0, 141);">
        </table>
        <hr style = "border: 1px solid rgb(0, 0, 141);">
        <hr style = "border: 10px solid rgb(0, 0, 141);">
    </div>
    <div class="content-area">
        <div class="sidebar">
            <?php if ($userType === 'admin'): ?>
                <a class="btn" href="/WT_Project/Admin/View/adminDashboard.php">Dashboard</a><br><br>
                <a class="btn" href="/WT_Project/Admin/View/userManagement.php">User Management</a><br><br>
                <a class="btn" href="/WT_Project/Admin/View/productModeration.php">Product Moderation</a><br><br>
                <a class="btn" href="/WT_Project/Admin/View/report.php">Reports & Analytics</a><br><br>
            <?php elseif ($userType === 'seller'): ?>
                <a class="btn" href="/WT_Project/Seller/View/sellerDashboard.php">Dashboard</a><br><br>
                <a class="btn" href="/WT_Project/Seller/View/uploadProduct.php">Add New Product</a><br><br>
                <a class="btn" href="/WT_Project/Seller/View/viewOrders.php">View Orders</a><br><br>
                <a class="btn" href="/WT_Project/Seller/View/manageListing.php">Manage Listings</a><br><br>
            <?php elseif ($userType === 'buyer'): ?>
                <a class="btn" href="/WT_Project/Buyer/View/buyerDashboard.php">Dashboard</a><br><br>
                <a class="btn" href="/WT_Project/Buyer/View/searchProduct.php">Search Products</a><br><br>
                <a class="btn" href="/WT_Project/Buyer/View/placeOrder.php">Place Order</a><br><br>
                <a class="btn" href="/WT_Project/Buyer/View/review.php">Review</a><br><br>
            <?php endif; ?>
            <a class="btn" href="/WT_Project/User/View/viewProfile.php">View Profile</a><br><br>
            <a class="btn" href="/WT_Project/User/Controller/logout.php">Logout</a>
        </div>
    <div class="main-content">
        <div class="info-box">
            <b>Post / Upload Product For Sale</b>
            <hr>
            <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
            <p>Product Name</p>
            <textarea name="name"  placeholder="Write product name"></textarea>

            <p>Category</p>
            <select name="category">
                <option disabled selected>Select a Category </option>
                <option>Books</option>
                <option>Clothing</option>
                <option>Electronics</option>
                <option>Food</option>
                <option>Stationery</option>
                <option>Gadgets</option>
                <option>Furniture</option>
                <option>Sports</option>
                <option>Miscellaneous</option>
            </select>

            <p>Product Description</p>
            <textarea name="description" rows="5"  placeholder="Write a description"></textarea>

            <p>Product Price</p>
            <textarea name="price"  placeholder="Write product price"></textarea>

            <p>Product Image</p>
            <input type="file" name="image" accept="image/*"><br>
            <p name="error" style="color:red;"></p>

            <button type="submit">Upload</button>
            </form>
        </div>
        </div>
    </div>
    <footer>
           Copyright © 2025
    </footer>
</div>
</body>
</html>
