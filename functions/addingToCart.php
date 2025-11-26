<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (!in_array($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $productId;
        echo 'added'; 
    } else {
        $_SESSION['cart'] = array_diff($_SESSION['cart'], [$productId]);
        echo 'removed';

        $email = $_SESSION['email'];
        $query = "select user_id from user where user_email = '$email'";
        $result = mysqli_query($link, $query);
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['user_id'];

        $query = "select orders_id from orders where orders_user = $user_id and orders_status = 'cart'";
        $result = mysqli_query($link, $query);
        $order = mysqli_fetch_assoc($result);
        
        if ($order) {
            $order_id = $order['orders_id'];

            $delete = "delete from ordersItem where ordersItem_order = $order_id AND ordersItem_product = $productId";
            mysqli_query($link, $delete);
        } else {
            echo "Не удалось найти заказ пользователя.";
        }
    }
}
