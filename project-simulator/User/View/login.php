<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

if (
    isset($_SESSION["isLoggedIn"]) &&
    $_SESSION["isLoggedIn"] === true &&
    isset($_SESSION["userType"])
) {
    if ($_SESSION["userType"] === 'buyer') {
        header("Location: ../../Buyer/View/buyerDashboard.php");
    } elseif ($_SESSION["userType"] === 'seller') {
        header("Location: ../../Seller/View/sellerDashboard.php");
    } elseif ($_SESSION["userType"] === 'admin') {
        header("Location: ../../Admin/View/adminDashboard.php");
    }
    exit;
}

$emailErr    = $_SESSION["emailErr"] ?? "";
$passwordErr = $_SESSION["passwordErr"] ?? "";
$loginErr    = $_SESSION["loginErr"] ?? "";

$previousValues = $_SESSION["previousValues"] ?? [];

unset(
    $_SESSION["emailErr"],
    $_SESSION["passwordErr"],
    $_SESSION["loginErr"],
    $_SESSION["previousValues"]
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        .content{
            height: 500px;
            text-align: center;
        }
        .login-box{
            background-color: white;
            width: 400px;
            border: 1px solid gray;
            margin: 35px auto;
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
<!-- <pre><?php echo $previousValues["name"];?></php></pre> -->
<form method="post" onsubmit="" action="/WT_Project/User/Controller/handleLoginValidation.php" enctype="multipart/form-data">
<div class="main">

    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h2><span style="color: rgb(106, 109, 255); font-size: 50px; font-style: italic; font-weight: bolder;">Campus </span><span style = "color:gray;">Marketplace</span></h2>
                </td>
                <td align="right">
                    <a href="/WT_Project/User/View/Home.php">Home</a> | <a href="/WT_Project/User/View/login.php">Login</a> | <a href="/WT_Project/User/View/signup.php">Signup</a>
                </td>
            </tr>
            <hr style = "border: 10px solid rgb(0, 0, 141);">
            <hr style = "border: 1px solid rgb(0, 0, 141);">
        </table>
        <hr style = "border: 1px solid rgb(0, 0, 141);">
        <hr style = "border: 10px solid rgb(0, 0, 141);">
    </div>

    <div class="content">

        <div class="login-box">
            <b>LOGIN</b>
            <br><br>
            Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type="text" placeholder="Enter your email" name="email" value="<?php echo $previousValues["email"] ?? "" ?>"/><br>
            <p style="color:red;"><?php echo $emailErr; ?></p>
            Password : <input type="password" placeholder="Enter your password" name="password"/><br>
            <p style="color:red;"><?php echo $passwordErr; ?></p>

            <hr>

            <button type="submit" onclick="">Login</button>
            <p style="color:red;"><?php echo $loginErr; ?></p>
        </div>

    </div>

    <footer>
        Copyright © 2025
    </footer>

</div>
</form>
</body>
</html>
