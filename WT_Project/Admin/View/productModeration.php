<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/productModel.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$name = $_SESSION['userName'] ?? '';
$email = $_SESSION['userEmail'] ?? '';
$password = $_SESSION['userPassword'] ?? '';
$userType = $_SESSION['userType'] ?? '';

$products = getAllProducts();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Moderation</title>
    <script>
         function handleAction(btn) {
            let row = btn.closest("tr");
            let statusCell = row.querySelector(".status");
            if (btn.innerText === "Pending") {
                btn.innerText = "Approve";
                btn.classList.remove("pending");
                btn.classList.add("approve");
                statusCell.innerText = "Approved";
            } else if (btn.innerText === "Approve") {
                btn.innerText = "Reject";
                btn.classList.remove("approve");
                btn.classList.add("reject");
                statusCell.innerText = "Rejected";
            } else {
                btn.innerText = "Pending";
                btn.classList.remove("reject");
                btn.classList.add("pending");
                statusCell.innerText = "Pending";
            }
        }
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
            width: 600px;
            border: 1px solid gray;
            margin: 35px auto;
            padding: 15px;
        }
        .info-box table{
            width: 100%;
            border-collapse: collapse;
        }
        .info-box th, td{
            padding: 15px;
            font-size: 18px;
        }
        .info-box th{
            font-weight: 700;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-box tr{
            border-bottom: 1px solid #e5e7eb;
        }
        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .status.pending {
            color: #2563eb;
            font-weight: bold;
        }
        .status.approved {
            color: #16a34a;
            font-weight: bold;
        }
        .status.rejected {
            color: #dc2626;
            font-weight: bold;
        }
        button.approve {
            background-color: #16a34a;
        }
        button.approve:hover {
            background-color: #15803d;
        }
        button{
            width: 75px;
            height: 35px;
            border-radius: 8px;
            border: none;
            color: white;
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
            <b>Product Moderation</b>
            <hr>
            <table>
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                    <td><?= htmlspecialchars($product['seller_name']) ?></td>
                    <td class="status <?= $product['status'] ?>"><?= htmlspecialchars($product['status']) ?></td>
                    <td>
                        <form method="post" action="/WT_Project/Admin/Controller/productModerationController.php">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <select name="status" required>
                            <option value="pending" <?= $product['status']=='pending'?'selected':'' ?>>Pending</option>
                            <option value="approved" <?= $product['status']=='approved'?'selected':'' ?>>Approved</option>
                            <option value="rejected" <?= $product['status']=='rejected'?'selected':'' ?>>Rejected</option>
                        </select>
                        <button type="submit" class="approve">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <footer>
        Copyright © 2025
    </footer>
</div>
</body>
</html>
