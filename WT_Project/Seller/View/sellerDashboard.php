<?php
session_start();

if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["userType"] !== 'seller') {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}
$name = $_SESSION["userName"];
$userType = $_SESSION["userType"];
$status = $_SESSION["userStatus"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f4;
        }
        .container{
            width: 100%;
            margin: auto;
        }
        .content-area{
            display: flex;
        }
        .header, .footer{
            padding: 10px;
            background-color: white;
        }
        .footer{
            text-align: center;
            border-bottom: none;
        }
        .sidebar{
            width: 20%;
            border-right: 1px solid #a1a1a1;
            padding: 15px;
        }
        .sidebar .btn {
            width: 100%;
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
        a{
            text-decoration: none;
            color: blue;
        }
        p{
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        h1{
            font-size: 1.5rem;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        h2 {
            width: 100%;
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #013d7e;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            text-align: center;
        }
        .card h3 {
            color: #64748b;
            font-size: 15px;
        }
        .card p {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
        }
        .table-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #e2e8f0;
        }
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
            font-size: 13px;
        }
        .active { background: #22c55e; }
        .pending { background: #f59e0b; }
        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 13px;
        }
        .edit { background: #2563eb; }
        .delete { background: #ef4444; }
        .add {
            background: #16a34a;
            margin-bottom: 15px;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<form method="post" onsubmit="" >
<div class="container">
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h1><span style="color: rgb(106, 109, 255); font-size: 50px; font-style: italic; font-weight: bolder;">Campus </span><span style = "color:gray;">Marketplace</span></h1>
                </td>
                <td align="right">
                    Logged in as <a href="#"><?php echo $_SESSION["userName"]; ?></a> | <a href="/WT_Project/User/View/viewProfile.php">View Profile</a> | <a href="/WT_Project/User/Controller/logout.php">Logout</a>
                </td>
            </tr>
            <hr style = "border: 10px solid rgb(0, 0, 141);">
        </table>
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
        <div class="cards">
            <div class="card">
                <h3>Status</h3>
                <p><?php echo $_SESSION["userStatus"]; ?></p>
            </div>
            <div class="card">
                <h3>Pending Approval</h3>
                <p>0</p>
            </div>
            <div class="card">
                <h3>Sold Products</h3>
                <p>0</p>
            </div>
            <div class="card">
                <h3>Total Earnings</h3>
                <p>000TK</p>
            </div>
        </div>
        <div class="table-box">
            <a class="btn add" href="uploadProduct.php">+ Add New Product</a><br><br>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <td>
                    <div style="display:flex; align-items:center;">
                        <img src="#" style="width:50px; height:50px; border-radius:5px; margin-right:10px;">
                        -
                    </div>
                    </td>
                    <td>-</td>
                    <td><span class="status active">Active</span></td>
                    <td>
                        <button class="btn edit">Edit</button>
                        <button class="btn delete">Delete</button>
                    </td>
                </tr>
            </table>
        </div>
        </div>
    </div>
</div>
</form>
</body>
</html>
