<?php
session_start();
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id > 0) {
    $sqlOrder = "select orders.*, user.user_surname from orders join user on orders.orders_user = user.user_id where orders.orders_id = $order_id limit 1";
    $resOrder = mysqli_query($link, $sqlOrder);
    if (mysqli_num_rows($resOrder) == 0) {
        echo "<p>Заказ не найден.</p>";
        exit;
    }
    $order = mysqli_fetch_assoc($resOrder);
    echo "<div class='information'>";
    echo "<h2><b>Заказ</b> №{$order['orders_id']}</h2>";
    echo "<p><b>Пользователь:</b>{$order['user_surname']}</p>";
    echo "<p><b>Статус:</b> {$order['orders_status']}</p>";
    echo "<p><b>Дата:</b> {$order['orders_date']}</p>";

    $sqlSum = "select sum(product.product_price * ordersitem.ordersitem_quantity) as total_sum,sum(ordersitem.ordersitem_quantity) as total_qty from ordersitem join product on ordersitem.ordersitem_product = product.product_id WHERE ordersitem.ordersitem_order = $order_id";
    $resSum = mysqli_query($link, $sqlSum);
    $sumData = mysqli_fetch_assoc($resSum);

    echo "<p><b>Всего товаров: </b>" . ($sumData['total_qty'] ?? 0) . "</p>";
    echo "<p class='sum'><b>Общая сумма: </b>" . ($sumData['total_sum'] ?? 0) . " галлеонов</p>";
    echo "</div>";
    echo '<div class="recomendation__cards">';
    
    $sqlItems = "select product.*, ordersitem.ordersitem_quantity from ordersitem join product on ordersitem.ordersitem_product = product.product_id where ordersitem.ordersitem_order = $order_id";

    $resItems = mysqli_query($link, $sqlItems);
    
    if (mysqli_num_rows($resItems) == 0) {
        echo "<p>В заказе нет товаров.</p>";
    } else {
        while ($item = mysqli_fetch_assoc($resItems)) {
            echo "
            <div class='recomendation__card'>
                <div class='recomendation__card__img' style='background-image: url({$item['product_image']})'></div>
                <div class='recomendation__card__short__description'>
                    <div class='recomendation__card__title'>{$item['product_name']}</div>
                    <div class='recomendation__card__price'>{$item['product_price']} галлеонов</div>
                    <div class='countCard'><b>Количество:</b> {$item['ordersitem_quantity']}</div>
                </div>
            </div>";
        }
    }
    echo '</div>';
    echo "<a href='admin.php?name=Поиск' class='read1'>Назад</a>";

} else {
    $sqlOrders = "select orders.*, user.user_surname from orders join user on orders.orders_user = user.user_id";
    $resOrders = mysqli_query($link, $sqlOrders);
    
    while ($order = mysqli_fetch_assoc($resOrders)) {
        echo "<div class='order__block'>";
        echo "<h3>Заказ №{$order['orders_id']} — {$order['user_surname']} ({$order['orders_status']}) от {$order['orders_date']}</h3>";
        echo "<a href='?name=Заказ&id={$order['orders_id']}' class='read'>Просмотреть</a>";
        echo "</div>";
    }
}
?>
