<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/WT_Project/Buyer/Model/productModel.php");

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$name = $_SESSION['userName'] ?? '';
$email = $_SESSION['userEmail'] ?? '';
$password = $_SESSION['userPassword'] ?? '';
$userType = $_SESSION['userType'] ?? '';

$sellerId = $_SESSION['userId'];
$products = getSellerProducts($sellerId);
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Listings</title>
<script>
function handleStatus(btn, productId) {
    let status;
    if (btn.innerText === "Available") status = "Limited";
    else if (btn.innerText === "Limited") status = "Sold Out";
    else if (btn.innerText === "Sold Out") status = "Available";

    btn.innerText = status;

    const xhr = new XMLHttpRequest();
    const formData = new FormData();
    formData.append("product_id", productId);
    formData.append("availability", status);

    xhr.open("POST", "/WT_Project/Seller/Controller/updateAvailability.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const res = JSON.parse(xhr.responseText);
            if (!res.success) alert("Failed to update availability");
        }
    };
    xhr.send(formData);
}
function handleEdit(btn, productId) {
    const row = btn.closest("tr");
    const name = row.querySelector(".name");
    const price = row.querySelector(".price");

    if (btn.innerText === "Edit") {
        name.contentEditable = true;
        price.contentEditable = true;
        btn.innerText = "Save";
    } else {
        name.contentEditable = false;
        price.contentEditable = false;
        btn.innerText = "Edit";

        fetch("/WT_Project/Seller/Controller/updateProduct.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body:
                "product_id=" + productId +
                "&name=" + name.innerText +
                "&price=" + price.innerText.replace(" TK", "")
        });
    }
}
function handleDelete(btn, productId) {
    if (!confirm("Delete this product?")) return;

    fetch("/WT_Project/Seller/Controller/deleteProduct.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "product_id=" + productId
    })
    .then(() => {
        btn.closest("tr").remove();
    });
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
        .info-box td{
            padding: 8px;
            font-size: 18px;
            text-align: left;
        }
        .info-box tr{
            border-bottom: 1px solid #e5e7eb;
        }
        .available{
            background: lightgray;
            color: black;
        }
        .edit{
            background: #2e8b57;
        }
        .save{
            background: blue;
        }
        .remove{
            background: red;
        }
        img{
            height: 50px;
            width: 50px;
        }
        button{
            width: 75px;
            height: 30px;
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
            <b>Manage Listings</b>
            <hr>
            <table>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr class="item" id="row-<?= $product['product_id'] ?>">
                    <td>
                    <img src="/WT_Project/Seller/Public/Uploads/<?= htmlspecialchars($product['image']) ?>" alt="product">
                    </td>
                    <td>
                    <h3 class="name"><?= htmlspecialchars($product['product_name']) ?></h3>
                    <p class="price"><?= htmlspecialchars($product['price']) ?> TK</p>
                    <small>Status: <?= $product['status'] ?></small>
                    </td>
                    <td style="text-align:right;">
                    <?php if ($product['status'] === 'approved'): ?>
                    <button class="available" onclick="handleStatus(this, <?php echo $product['product_id']; ?>)"><?php echo $product['availability']; ?></button>
                    <br><br>

                    <button class="edit" onclick="handleEdit(this, <?= $product['product_id'] ?>)"> Edit </button>
                    <button class="remove" onclick="handleDelete(this, <?= $product['product_id'] ?>)"> Remove </button>
                    <?php else: ?>
                    <span style="color:red;">Not Approved</span>
                    <?php endif; ?>
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