<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order']) && is_array($_POST['order'])) {
        $order = $_POST['order'];
        $link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
        foreach($order as $k=>$v){
            $query = "update modules set modules_order = $k where modules_name = '$v'";
            $result = mysqli_query($link, $query);
        }
    }
}
