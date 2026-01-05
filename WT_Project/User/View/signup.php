<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/WT_Project/User/Model/db.php';

$nameErr = $_SESSION["nameErr"] ?? "";
$emailErr = $_SESSION["emailErr"] ?? "";
$passwordErr = $_SESSION["passwordErr"] ?? "";
$userErr = $_SESSION["userErr"] ?? "";
$signupErr = $_SESSION["signupErr"] ?? "";

$previousValues = $_SESSION["previousValues"] ?? [];

unset($_SESSION["nameErr"], $_SESSION["emailErr"], $_SESSION["passwordErr"], $_SESSION["userErr"], $_SESSION["signupErr"], $_SESSION["previousValues"]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
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
        .signup-box{
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
 <!-- <pre><?php echo $previousValues["email"];?></php></pre> -->
<form method="post" onsubmit="" action="/WT_Project/User/Controller/handleSignupValidation.php" enctype="multipart/form-data">
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
        
        <div class="signup-box">
            <b>SIGNUP</b>
            <br><br>
            Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type="text" placeholder="Enter your name" name="name" value="<?php echo $previousValues["name"] ?? "" ?>"/><br>
            <p style="color:red;"><?php echo $nameErr; ?></p>
            Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type="text" placeholder="Enter your email" name="email" value="<?php echo $previousValues["email"] ?? "" ?>"/><br>
            <p style="color:red;"><?php echo $emailErr; ?></p>
            Password : <input type="password" placeholder="Enter your password" name="password"/><br>
            <p style="color:red;"><?php  echo $passwordErr; ?></p>
            Signup as : <select name="userType">
                <option disabled selected>User Type </option>
                <option value="seller" <?php if(isset($previousValues['userType']) && $previousValues['userType'] == 'seller') echo 'selected'; ?>>Seller</option>
                <option value="buyer" <?php if(isset($previousValues['userType']) && $previousValues['userType'] == 'buyer') echo 'selected'; ?>>Buyer</option>
                <option value="admin" <?php if(isset($previousValues['userType']) && $previousValues['userType'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            <p style="color:red;"><?php  echo $userErr; ?></p>

            <hr>

            <button onclick="">Signup</button>
            <a href="/WT_Project/User/View/login.php">Have an account?</a>
            <p style="color:red;"><?php echo $signupErr; ?></p>
        </div>

    </div>

    <footer>
        Copyright © 2025
    </footer>

</div>
</form>
</body>
</html>