<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header("Location: /WT_Project/User/View/login.php");
    exit;
}

$userId = $_SESSION['userId'] ?? '';
$name = $_SESSION['userName'] ?? '';
$email = $_SESSION['userEmail'] ?? '';
$password = $_SESSION['userPassword'] ?? '';
$userType = $_SESSION['userType'] ?? '';

$productId = $_GET['product_id'] ?? '';
$productName = $_GET['product_name'] ?? '';
$productPrice = $_GET['price'] ?? '';
$productImage = $_GET['image'] ?? '';

$productName = urldecode($productName);
$productImage = urldecode($productImage);

include($_SERVER['DOCUMENT_ROOT']."/WT_Project/Buyer/Model/cartModel.php");
$cartModel = new CartModel();
$cartProducts = $cartModel->getCartProducts($userId);

$subTotal = 0;
$grandTotal = 0;
foreach($cartProducts as $p){
    $subTotal += $p['price'] * $p['quantity'];
    $grandTotal = $subTotal;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <script>
        function changeQty(productId, step) {
            const qtyInput = document.getElementById(`qty-${productId}`);
            let qty = parseInt(qtyInput.value);

            qty += step;
            if (qty < 1) qty = 1;
            qtyInput.value = qty;

            const price = parseFloat(qtyInput.dataset.price); 
            const productTotal = qty * price;
            document.getElementById(`total-${productId}`).innerText = productTotal;

            updateGrandTotal();
            updateCart(productId, qty);
        }
        function updateGrandTotal() {
            let subTotal = 0;
            let grandTotal = 0;
            const qtyInputs = document.querySelectorAll('input[id^="qty-"]');
            qtyInputs.forEach(input => {
                const price = parseFloat(input.dataset.price);
                const qty = parseInt(input.value);
                subTotal += price * qty;
            });
            grandTotal = subTotal;
            document.getElementById('sub-total').innerText = subTotal;
            document.getElementById('grand-total').innerText = grandTotal;
        }
        function handleSubmit() {
            const mobileValue = document.getElementById("mobile").value;
            let valid = true;
            
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
                document.getElementById("mobile").value="";
                alert("Order successfully placed");
            }
        }
        function updateCart(productId, quantity) {
            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append("product_id", productId);
            formData.append("quantity", quantity);

            xhr.open("POST", "/WT_Project/Buyer/Controller/handleUpdateCart.php", true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    } else {
                        alert("Request failed. Please try again.");
                    }
                }
            };
            xhr.send(formData);
        }
        function removeProduct(cartId) {
            if (!confirm("Remove this product from cart?")) return;

            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            formData.append("cart_id", cartId);

            xhr.open("POST", "/WT_Project/Buyer/Controller/handleDeleteCart.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        document.getElementById("product-" + cartId).remove();
                        updateGrandTotal();
                    } else {
                        alert("Failed to remove product");
                    }
                }
            };
            xhr.send(formData);
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
            max-width: 50px;
            height: auto;
            margin-bottom: 5px;
            border-radius: 4px;
        }
        .product-box {
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 5px;
            right: 8px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-weight: bold;
        }
        .close-btn:hover {
            background: darkred;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .summary-row span {
            text-align: right;
        }
        .summary-row.grand {
            font-weight: bold;
            font-size: 18px;
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
            <b>Place Order</b>
            <br><br>
            
            <div class="product-container">
            <?php if(empty($cartProducts)): ?>
                <p>No Product on Your Cart.</p>
            <?php else: ?>
                <?php foreach($cartProducts as $product): ?>
                    <div class="product-box" id="product-<?php echo $product['cart_id']; ?>">
                        <button class="close-btn" onclick="removeProduct('<?php echo $product['cart_id']; ?>')">✖</button>
                        <img src="/WT_Project/Seller/Public/Uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p>Quantity</p>
                        <div class="qty-box">
                            <button onclick="changeQty('<?php echo $product['product_id']; ?>', -1)">-</button>
                            <input type="text" id="qty-<?php echo $product['product_id']; ?>" value="<?php echo $product['quantity']; ?>" data-price="<?php echo $product['price']; ?>" readonly>
                            <button onclick="changeQty('<?php echo $product['product_id']; ?>', 1)">+</button>
                        </div>
                        <p>Total: <span id="total-<?php echo $product['product_id']; ?>"><?php echo $product['price'] * $product['quantity']; ?></span> TK</p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
            <p><h3>Delivery Location &nbsp;
            <select>
                <option selected>AIUB Campus </option>
            </select></h3></p>
            <p><h3>Mobile Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="tel" id="mobile"></h3></p>
            <p id="mobileErr" style="color:red;"></p>

            <hr>
            
            <div class="summary-row">
                <p>Sub Total:</p>
                <span id="sub-total"><?php echo $subTotal; ?> TK</span>
            </div>

            <div class="summary-row">
                <p>Delivery Charge:</p>
                <span>0 TK</span>
            </div>

            <div class="summary-row grand">
                <p>Grand Total:</p>
                <span id="grand-total"><?php echo $grandTotal; ?> TK</span>
            </div>
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
