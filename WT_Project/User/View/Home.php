<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
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
            background-image: url("../Public/CSS/aiub_campus_pic.jpg");
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
    </div>

    <footer>
        Copyright © 2025
    </footer>

</div>

</body>
</html>
