<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/Buyer/Model/productModel.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}
$userType = $_SESSION["userType"];
$userName = $_SESSION["userName"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse & Search</title>
<script>
function fetchProducts() {
    const search = document.getElementById("searchInput").value;
    const category = document.getElementById("categorySelect").value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/WT_Project/Buyer/Controller/handleSearchProduct.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        const container = document.getElementById("productContainer");
        container.innerHTML = "";

        let products;
        try {
            products = JSON.parse(this.responseText);
        } catch (e) {
            console.error(this.responseText);
            container.innerHTML = "<p>Server error</p>";
            return;
        }

        if (products.length === 0) {
            container.innerHTML = "<p>No products found.</p>";
            return;
        }

        products.forEach(p => {
            container.innerHTML += `
                <div class="product-box">
                    <img src="/WT_Project/Seller/Public/Uploads/${p.image}" alt="${p.product_name}" class="product-image">
                    <h3>${p.product_name}</h3>
                    <p class="price">${p.price} TK</p>
                    <a href="/WT_Project/Buyer/View/placeOrder.php?product_id=${p.product_id}&product_name=${encodeURIComponent(p.product_name)}&price=${p.price}&image=${p.image}" class="buy-btn">Buy Now</a>
                    <a href="#" class="buy-btn" onclick="addToCart(${p.product_id}); return false;">Add To Cart</a>
                </div>
            `;
        });
    };

    xhr.send(
        "search=" + encodeURIComponent(search) +
        "&category=" + encodeURIComponent(category)
    );
}

window.onload = function () {
    fetchProducts();
    document.getElementById("searchInput").onkeyup = fetchProducts;
    document.getElementById("categorySelect").onchange = fetchProducts;
};
function addToCart(productId) {
    const xhr = new XMLHttpRequest();
    const formData = new FormData();

    formData.append("product_id", productId);

    xhr.open("POST", "/WT_Project/Buyer/Controller/handleAddToCart.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message);
                } catch (e) {
                    alert("Invalid server response");
                }
            } else {
                alert("Request failed. Please try again.");
            }
        }
    };

    xhr.send(formData);
}
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
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }
        .product-box {
            width: 30%; 
            padding: 20px;
            border: 2px solid #ccc;
            background-color: #fff;
            text-align: center;
            transition: all 0.3s ease-in-out;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .product-box img {
            width: 100%;
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .product-box h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        .product-box p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 15px;
        }
        .product-box .price {
            font-size: 1.2rem;
            color: #ff0202;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .product-box .buy-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }
        .product-box .buy-btn:hover {
            background-color: #0056b3;
        }
        .product-box:hover {
            outline: 3px solid #007bff;
            outline-offset: 10px;
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
            <textarea rows="1" cols="50" id="searchInput" name="searchInput" placeholder="Search by keyword"></textarea><br>
            <span style="font-size: 15px;">Search by Category</span>
            <select id="categorySelect" name="categorySelect">
                <option value="">Category All </option>
                <option value="Books">Books</option>
                <option value="Clothing">Clothing</option>
                <option value="Electronics">Electronics</option>
                <option value="Food">Food</option>
                <option value="Stationery">Stationery</option>
                <option value="Gadgets">Gadgets</option>
                <option value="Furniture">Furniture</option>
                <option value="Sports">Sports</option>
                <option value="Miscellaneous">Miscellaneous</option>
            </select><br><br>
            <div id="productContainer" class="product-container">
                <!-- Products will appear here -->
            </div>
        </div>
    </div>
</div>
</form>
</body>
</html>
