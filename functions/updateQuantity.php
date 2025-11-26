<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");

if (isset($_POST['product_id'], $_SESSION['email'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    $email = $_SESSION['email'];
    $query = "select user_id FROM user WHERE user_email = '$email'";
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    $query = "select orders_id from orders where orders_user = $user_id and orders_status = 'cart'";
    $result = mysqli_query($link, $query);
    $order = mysqli_fetch_assoc($result);
    $order_id = $order['orders_id'];

    $update = "update ordersItem set ordersItem_quantity = $quantity where ordersItem_order = $order_id and ordersItem_product = $product_id";

    if (mysqli_query($link, $update)) {
        echo "OK";
    } else {
        echo "Ошибка: " . mysqli_error($link);
    }
}
?>
