<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/productModel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/ordersModel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/userModel.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$name = $_SESSION['userName'] ?? '';
$email = $_SESSION['userEmail'] ?? '';
$password = $_SESSION['userPassword'] ?? '';
$userType = $_SESSION['userType'] ?? '';

$totalSales = getTotalSales();
$totalUsers = getTotalUsers();
$totalProducts = getTotalProducts();
$pendingListings = getPendingListings();
$totalOrders = getTotalOrders();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports & Analytics</title>
    <script>
         
    </script>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
        }
        b{
            font-size: 25px;
            color: gray;
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
            width: 650px;
            border: 1px solid gray;
            margin: 35px auto;
            padding: 15px;
            text-align: left;
        }
        .card {
            width: 75%;
            margin: auto;
            border: 1px solid #ddd;
            border-radius: 18px;
            padding: 40px;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 100px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .big-number {
            font-size: 54px;
            font-weight: bold;
        }
        .divider {
            margin: 20px 0;
            border-top: 1px solid #ddd;
        }
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
            <b>Reports & Analytics</b>
            <br><br>
            <div class="card">

    <div class="grid">
        <div>
            <div class="section-title">Total Sales</div>
            <div class="big-number"><?= $totalSales ?> TK</div>
        </div>
    </div>
    <div class="divider"></div>
    <div class="grid">
        <div>
            <div class="section-title">User Registrations</div>
            <div class="big-number"><?= $totalUsers ?></div>
        </div>
    </div>
    <div class="divider"></div>
    <div class="grid">
        <div>
            <div class="section-title">Total Products</div>
            <div class="big-number"><?= $totalProducts ?></div>
        </div>
    </div>
    <div class="divider"></div>
    <div class="grid">
        <div>
            <div class="section-title">Total Orders</div>
            <div class="big-number"><?= $totalOrders ?></div>
        </div>
    </div>

            </div>
        </div>
    </div>
    </div>
    <footer>
        Copyright © 2025
    </footer>
</div>
</body>
</html>
