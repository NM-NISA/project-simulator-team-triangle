<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/productModel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/userModel.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Admin/Model/ordersModel.php");

if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["userType"] !== 'admin') {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}
$name = $_SESSION["userName"];
$userType = $_SESSION["userType"];

$totalUsers = getTotalUsers();
$totalProducts = getTotalProducts();
$pendingListings = getPendingListings();
$totalOrders = getTotalOrders();
$products = getAllProductsForAdmin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script>
       
    </script>
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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .card h3 {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
        }
        .table-section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .table-section h2 {
            margin-bottom: 15px;
            color: #1e293b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background: #e2e8f0;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tr:hover { background: #f8fafc; }
        .product {
            font-size: 16px;
            font-weight: bold;
        }
        .product.pending {
            color: #2563eb;
            font-weight: bold;
        }
        .product.approved {
            color: #16a34a;
            font-weight: bold;
        }
        .product.rejected {
            color: #dc2626;
            font-weight: bold;
        }
        .status {
            font-size: 16px;
            font-weight: bold;
        }
        .status.active {
            color: #16a34a;
            font-weight: bold;
        }
        .status.blocked {
            color: #dc2626;
            font-weight: bold;
        }
        .availability {
            font-size: 16px;
            color: #7a7a7a;
            font-weight: bold;
            text-transform: capitalize;
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
            <?php endif; ?>
            <a class="btn" href="/WT_Project/User/View/viewProfile.php">View Profile</a><br><br>
            <a class="btn" href="/WT_Project/User/Controller/logout.php">Logout</a>
        </div>
        <div class="main-content">
        <div class="cards">
            <div class="card">
            <h3>Total Users</h3>
            <p><?= $totalUsers ?></p>
        </div>
        <div class="card">
            <h3>Total Products</h3>
            <p><?= $totalProducts ?></p>
        </div>
        <div class="card">
            <h3>Pending Listings</h3>
            <p><?= $pendingListings ?></p>
        </div>
        <div class="card">
            <h3>Total Orders</h3>
            <p><?= $totalOrders ?></p>
        </div>
    </div>
    <div class="table-section">
        <h2>Recent Product Listings</h2>
        <table>
        <thead>
          <tr>
            <th>Seller</th>
            <th>Product</th>
            <th>Category</th>
            <th>User Status</th>
            <th>Product Status</th>
            <th>Availability</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['seller_name']) ?></td>
                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                    <td class="status <?= $product['user_status'] ?>"><?= htmlspecialchars(ucfirst($product['user_status'])) ?></td>
                    <td class="product <?= $product['product_status'] ?>"><?= htmlspecialchars(ucfirst($product['product_status'])) ?></td>
                    <td class="availability"><?= htmlspecialchars(ucfirst($product['availability'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    </div>
    </div>
</div>
</form>
</body>
</html>
