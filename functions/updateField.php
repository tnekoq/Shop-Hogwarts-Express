<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");
if (isset($_POST['id'])) {
    $query = "update category set category_name = '{$_POST['value']}' where category_id='{$_POST['id']}'";
    $result = mysqli_query($link, $query);
    if ($result) {
        echo "OK";
    } else {
        echo "Ошибка: " . mysqli_error($link);
    }
    
}