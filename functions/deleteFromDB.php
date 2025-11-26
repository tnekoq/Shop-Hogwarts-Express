<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");

if (isset($_POST['product_id'], $_SESSION['email'])) {
    $product_id = (int)$_POST['product_id'];

    $email = mysqli_real_escape_string($link, $_SESSION['email']);
    $userRes = mysqli_query($link, "select user_id from user where user_email = '$email'");
    if (!$userRes || mysqli_num_rows($userRes) == 0) {
        echo "Пользователя нет, идите домой";
        exit();
    }
    $user_id = mysqli_fetch_assoc($userRes)['user_id'];

    $orderRes = mysqli_query($link, "select orders_id from orders where orders_user = $user_id and orders_status = 'cart'");
    if (!$orderRes || mysqli_num_rows($orderRes) == 0) {
        echo "Корзины тоже";
        exit();
    }
    $order_id = mysqli_fetch_assoc($orderRes)['orders_id'];

    $deleteRes = mysqli_query($link, "delete from ordersItem where ordersItem_order = $order_id and ordersItem_product = $product_id");
    if (!$deleteRes) {
        echo "Ошибка при удалении: " . mysqli_error($link);
        exit();
    }

    if (isset($_SESSION['cart'])) {
        $newCart = [];
        foreach ($_SESSION['cart'] as $id) {
            if ($id != $product_id) {
                $newCart[] = $id;
            }
        }
        $_SESSION['cart'] = $newCart;
    }
    

    echo "Удалено";
}
?>
