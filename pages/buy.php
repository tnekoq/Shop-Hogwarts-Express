<?php
session_start();

$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");

$email = $_SESSION['email'];

$getuserquery = "select user_id from user where user_email = '$email'";
$userresult = mysqli_query($link, $getuserquery);
$user = mysqli_fetch_assoc($userresult);
$user_id = $user['user_id'];

$updatequery = "update orders set orders_status = 'ordered' where orders_user = $user_id and orders_status = 'cart'";
$result = mysqli_query($link, $updatequery);

unset($_SESSION['cart']);

header("Location: /index.php");
exit();
