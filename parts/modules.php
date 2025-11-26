<?php include "../styles/order.php" ?>

<div class="admin__modules">
    <?php
    session_start();
    asort($order);
    echo "<div class='dragDrop' id='dragDrop'>";

    foreach ($order as $k => $o) {
        echo "<div class='dragDrop__item' draggable='true'>$k</div>";
    }
    echo "</div>";

    ?>
    <button id="saveOrderBtn" class="save-btn">Сохранить порядок</button>

</div>