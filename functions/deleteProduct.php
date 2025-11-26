<?php
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

if (isset($_POST['id'])) {
    $productId = (int)$_POST['id'];
    $query = "delete FROM product WHERE product_id = $productId";

    if (mysqli_query($link, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
