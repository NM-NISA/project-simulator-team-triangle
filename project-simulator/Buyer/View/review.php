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
<title>Review & Rating</title>
<script>
function handleSubmit() {
    let valid = true;

    const title = document.getElementById("title").value.trim();
    const description = document.getElementById("description").value.trim();
    const rating = document.getElementsByName("rating");

    if (title === "") {
        document.getElementById("titleErr").innerHTML = "Title is required";
        valid = false;
    } else {
        document.getElementById("titleErr").innerHTML = "";
    }

    if (description === "") {
        document.getElementById("descriptionErr").innerHTML = "Description is required";
        valid = false;
    } else {
        document.getElementById("descriptionErr").innerHTML = "";
    }

    let selected = false;
    for (let i = 0; i < rating.length; i++) {
        if (rating[i].checked) {
            selected = true;
            break;
        }
    }

    if (!selected) {
        document.getElementById("ratingErr").innerHTML = "Please select a rating";
        valid = false;
    } else {
        document.getElementById("ratingErr").innerHTML = "";
    }

    return valid; // ✅ allow submit only if valid
}
</script>
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
        .review-box{
            background-color: white;
            width: 500px;
            border: 1px solid gray;
            margin: 20px auto;
            padding: 15px;
            text-align: left;
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
        <div class="review-box">
            <b>Product Review & Rating</b>
            <hr>
            <?php if (isset($_GET['success'])): ?>
                <p style="color:green; font-weight:bold;">Review submitted successfully!</p>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <p style="color:red;">Failed to submit review. Try again.</p>
            <?php endif; ?>
            <form method="POST" action="/WT_Project/Buyer/Controller/handleProductReview.php" onsubmit="return handleSubmit();">
            <p>Review Title</p>
            <textarea id="title" name="title" placeholder="Write product name"></textarea>
            <p id="titleErr" style="color:red;"></p>

            <p>Description</p>
            <textarea id="description" name="description" rows="5"  placeholder="Write a review"></textarea>
            <p id="descriptionErr" style="color:red;"></p>

            <label>Rating</label>
            <input type="radio" name="rating" value="1★"> 1 ★
            <input type="radio" name="rating" value="2★"> 2 ★
            <input type="radio" name="rating" value="3★"> 3 ★
            <input type="radio" name="rating" value="4★"> 4 ★
            <input type="radio" name="rating" value="5★"> 5 ★
            <p id="ratingErr" style="color:red;"></p>

            <button type="submit">Submit Review</button>
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
