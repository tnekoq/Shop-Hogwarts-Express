<?php
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

$search = $_POST['search'] ?? '';
$statuses = $_POST['status'] ?? [];

$sql = "select orders.*, user.user_surname from orders join user on orders.orders_user = user.user_id where orders.orders_status != 'cart'";

if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($link, $search);
    $sql .= " and user.user_surname like '%$search_safe%'";
}

if (!empty($statuses)) {
    $escaped_statuses = array_map(function($s) use ($link) {
        return "'" . mysqli_real_escape_string($link, $s) . "'";
    }, $statuses);
    $statusList = implode(',', $escaped_statuses);
    $sql .= " and orders.orders_status in ($statusList)";
}

$result = mysqli_query($link, $sql);

if (!$result) {
    echo "Ошибка запроса: " . mysqli_error($link);
    exit;
}

if (mysqli_num_rows($result) == 0) {
    echo "<p>Нет заказов по заданным критериям.</p>";
    exit;
}

while ($order = mysqli_fetch_assoc($result)) {
    echo "<div class='order__block'>";
    echo "<h3>Заказ №{$order['orders_id']} — {$order['user_surname']} ({$order['orders_status']}) от {$order['orders_date']}</h3>";
    echo "<a href='admin.php?name=Заказ&id={$order['orders_id']}' class='read'>Просмотреть</a>";
    echo "</div>";
}
?>
