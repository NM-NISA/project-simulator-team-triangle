<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/Buyer/Model/productModel.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/Buyer/Model/orderModel.php';

$productsByCategory = getAllProductsGroupedByCategory();

if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["userType"] !== 'buyer') {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}
$name = $_SESSION["userName"];
$userType = $_SESSION["userType"];
$status = $_SESSION["userStatus"];
$userId = $_SESSION['userId']; 
$orderStatuses = getOrderStatusByUserId($userId);
$totalPurchased = getTotalPurchasedProducts($userId);

$seeReviewsProductId = $_GET['see_reviews'] ?? null;
$reviewsForProduct = [];

if ($seeReviewsProductId) {
    $seeReviewsProductId = intval($seeReviewsProductId); 
    include_once $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/Buyer/Model/reviewModel.php';
    $reviewsForProduct = getReviewsByProductId($seeReviewsProductId);
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<script>
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
        h3 {
            width: 100%;
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: #7a7a7aff;
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
        .review-box {
            margin-top: 10px;
            background: #e0e0e0;
            padding: 10px;
            border-radius: 5px;
            max-height: 300px;
        }
        .single-review h4 {
            margin: 0;
        }
        .availability {
            padding: 5px 10px;
            radius: 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #a5a5a5;
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
                    <h3>Status</h3>
                    <p><?php echo $_SESSION["userStatus"]; ?></p>
                </div>
                <div class="card">
                    <h3>Total Purchased products</h3>
                    <p><?= htmlspecialchars($totalPurchased) ?></p>
                </div>
                <div class="card">
                    <h3>Order Status</h3>
                    <?php if (!empty($orderStatuses)): ?>
                        <ul style="list-style:none; padding-left:0;">
                        <?php foreach($orderStatuses as $status => $count): ?>
                            <li><?= htmlspecialchars($status) ?>: <?= $count ?></li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                    <p>No orders yet</p>
                    <?php endif; ?>
                </div>
            </div>
            <div><h3>Available Products</h3></div>
            <?php if (empty($productsByCategory)): ?>
                <p>No products available.</p>
            <?php else: ?>

            <?php foreach ($productsByCategory as $category => $items): ?>
                <h2><?= htmlspecialchars($category) ?></h2>

                <div class="product-container">
                    <?php foreach ($items as $product): ?>
                        <div class="product-box">
                            <img src="/WT_Project/Seller/Public/Uploads/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['product_name']) ?>">
                            <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                            <div class="availability"><?= htmlspecialchars($product['availability']) ?></div><br>
                            <p class="price"><?= htmlspecialchars($product['price']) ?> TK</p>
                            <a href="/WT_Project/Buyer/View/placeOrder.php?product_id=<?= $product['product_id'] ?>&product_name=<?= urlencode($product['product_name']) ?>&price=<?= $product['price'] ?>&image=<?= urlencode($product['image']) ?>" class="buy-btn">Buy Now</a>
                            <a href="#" class="buy-btn" onclick="addToCart(<?= $product['product_id'] ?>)">Add To Cart</a>
                            <br><br>
                            <a href="/WT_Project/Buyer/View/review.php?product_id=<?= $product['product_id'] ?>" class="buy-btn">Review</a>
                            <br><br>
                            <hr>
                            <br>
                            <a href="?see_reviews=<?= $product['product_id'] ?>">See Reviews</a>

                            <?php if ($seeReviewsProductId == $product['product_id']): ?>
                            <div class="review-box">
                                <?php if (empty($reviewsForProduct)): ?>
                                    <p>No reviews yet.</p>
                                <?php else: ?>
                                <?php foreach ($reviewsForProduct as $r): ?>
                                    <div class="single-review">
                                        <small><?= htmlspecialchars($r['review_date']) ?></small>
                                        <h4><?= htmlspecialchars($r['name']) ?></h4>
                                        <p>Rating: <?= htmlspecialchars($r['rating']) ?></p>
                                        <p><?= htmlspecialchars($r['description']) ?></p>
                                        <hr>
                                    </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</div>
</form>
</body>
</html>
