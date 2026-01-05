<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$name = $_SESSION['userName'] ?? '';
$email = $_SESSION['userEmail'] ?? '';
$password = $_SESSION['userPassword'] ?? '';
$userType = $_SESSION['userType'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
        const price = 40000;
        function changeQty(step) {
               let qtyInput = document.getElementById("qty");
               let qty = parseInt(qtyInput.value);

               qty += step;
               if (qty < 1) qty = 1;

               qtyInput.value = qty;
               document.getElementById("total").innerText = qty * price;
        }
         function handleSubmit() {
            const addressValue = document.getElementById("address").value;
            const mobileValue = document.getElementById("mobile").value;
            let valid = true;
            
            if (!addressValue || addressValue == "") {
                const ErrorElement = document.getElementById("addressErr");
                ErrorElement.innerHTML = "Address is required";
                valid = false;
            } else {
                document.getElementById("addressErr").innerHTML = "";
            }
            if (!mobileValue || mobileValue == "") {
                const ErrorElement = document.getElementById("mobileErr");
                ErrorElement.innerHTML = "Mobile Number is required";
                valid = false;
            } else {
                const mobilePattern = /^[0-9]{11}$/;
                if (!mobilePattern.test(mobileValue)) {
                   document.getElementById("mobileErr").innerHTML = "Mobile Number must contain exactly 11 digits";
                   valid = false;
                } else {
                   document.getElementById("mobileErr").innerHTML = "";
                }
            }
            if(valid){
                document.getElementById("address").value="";
                document.getElementById("mobile").value="";
                alert("Order successfully placed");
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
        .order-box{
            background-color: white;
            width: 500px;
            border: 1px solid gray;
            margin: 35px auto;
            padding: 15px;
            text-align: left;
        }
        .qty-box {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px auto;
        }
        .qty-box button {
            width: 40px;
            height: 40px;
            font-size: 20px;
            border: 1px solid #ccc;
            background: white;
            cursor: pointer;
        }
        .qty-box input {
            width: 60px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ccc;
            height: 40px;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            padding: 10px;
        }
        .product-box {
            width: 40%; 
            padding: 10px;
            border: 2px solid #ccc;
            background-color: #fff;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .product-box img {
            width: 100%;
            max-width: 100px;
            height: auto;
            margin-bottom: 5px;
            border-radius: 4px;
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
        <div class="order-box">
            <b>Add To Cart & Place Orders</b>
            <br><br>
            
            <div class="product-container">
            <div class="product-box">
               <img src="#">
               <br>
               <h3>Product:</h3>
               <p>Price: </p>
            </div>
            <div class="product-box">
                <p>Quantity</p>
                <div class="qty-box">
                <button onclick="changeQty(-1)">-</button>
                <input type="text" id="qty" value="1" readonly>
                <button onclick="changeQty(1)">+</button>
                </div>
                <p class="total">Total: <span id="total">000</span>TK</p>
            </div>
            </div>
            <br>
            <p>Delivery Location </p>
            <textarea type="text" cols="65" id="address"></textarea>
            <p id="addressErr" style="color:red;"></p>
            <p>Mobile No. </p>
            <textarea type="tel" cols="65" id="mobile"></textarea>
            <p id="mobileErr" style="color:red;"></p>

            <hr>

            <button onclick="handleSubmit()">Place Order</button>
        </div>
        </div>
    </div>

    <footer>
        Copyright © 2025
    </footer>

</div>

</body>
</html>
