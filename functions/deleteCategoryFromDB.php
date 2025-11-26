<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");
if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $query = "delete from category where category_id = $category_id";
    $result = mysqli_query($link, $query);
    if ($result) {
        echo "Удалено";
    } else {
        echo "Ошибка при удалении: " . mysqli_error($link);
    }
} else {
    echo "Не передан category_id";
}