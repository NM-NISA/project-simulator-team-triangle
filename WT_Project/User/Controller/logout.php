<?php 
session_start();

session_destroy();

Header("Location: /WT_Project/User/View/login.php");

?>