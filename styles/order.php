<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
$query = "select modules_name, modules_order from modules";
$result = mysqli_query($link, $query);
$rows = mysqli_num_rows($result);
$order = [];
for ($i=0; $i < $rows; $i++) { 
    $myModules = mysqli_fetch_assoc($result);
    $name = $myModules['modules_name'];
    $order[$name] = $myModules['modules_order'];
}
?>
<style>
.banner {
    order: <?= $order['Баннер'] ?>;
}
.popular {
    order: <?= $order['Популярные'] ?>;
}
.recomendation {
    order: <?= $order['Рекомендации'] ?>;
}
</style>
