<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <title>Корзина</title>
</head>

<body>
    <?php include "../parts/header.php"; ?>

    <section class="cart">
        <h1 class='cart__title'>Корзина</h1>
        <div class='cart__cards'>

            <?php
            session_start();
            $myUser = 0;
            $link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

            if (!mysqli_set_charset($link, "utf8mb4")) {
                echo "Ошибка при загрузке набора символов utf8mb4: ";
                mysqli_error($link);
                exit();
            }
            $query = "select * from user where user_email = '{$_SESSION['email']}'";
            $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса: " . mysqli_error($link));
            $rows = mysqli_num_rows($result);
            for ($i = 0; $i < $rows; $i++) {
                $myUsers = mysqli_fetch_assoc($result);
                $myUser = $myUsers['user_id'];
            }

            $items = [];
            $queryUser = "select * from orders where orders_user = $myUser and orders_status='cart'";
            $result = mysqli_query($link, $queryUser) or die("Ошибка выволенния запроса: " . mysqli_error($link));
            $rows = mysqli_num_rows($result);

            if ($rows == 0) {
                $query = "insert into orders(orders_id,orders_user, orders_status,orders_date) values(NULL,$myUser, 'cart', NOW())";
                $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса: " . mysqli_error($link));

                $queryUser = "select * from orders where orders_user = $myUser and orders_status='cart' ORDER BY orders_id DESC LIMIT 1";
                $result = mysqli_query($link, $queryUser) or die("Ошибка выволенния запроса: " . mysqli_error($link));
            }

            $result = mysqli_query($link, $queryUser) or die("Ошибка выволенния запроса: " . mysqli_error($link));
            $rows = mysqli_num_rows($result);
            $myOrder = 0;

            if ($rows > 0) {
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_assoc($result);
                    $myOrder = $row['orders_id'];
                }
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $cart = $_SESSION['cart'];
            
            foreach ($cart as $v) {
                $query = "select * from ordersItem where ordersItem_product = $v and ordersItem_order  = $myOrder";
                $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса: " . mysqli_error($link));
                $rows = mysqli_num_rows($result);
            
                if ($rows == 0) {
                    $query = "insert into ordersItem(ordersItem_id,ordersItem_order,ordersItem_product,ordersItem_quantity) values (NULL, $myOrder, $v, 1)";
                    $result = mysqli_query($link, $query) or die("Ошибка выволенния запроса: " . mysqli_error($link));
                }
            }
            
            $query = "select * from ordersItem where ordersItem_order = $myOrder";
            $result = mysqli_query($link, $query) or die("Ошибка выволенния запроса: " . mysqli_error($link));
            $rows = mysqli_num_rows($result);
            for ($i = 0; $i < $rows; $i++) {
                $a = mysqli_fetch_assoc($result);
                $query = "select a.product_id, a.product_name,a.product_price, a.product_image, b.category_name from product a left join category b on a.product_category=b.category_id  where product_id = '{$a['ordersItem_product']}'";
                $resultProduct = mysqli_query($link, $query) or die("Ошибка выполнения запроса: " . mysqli_error($link));
                $rowsProducts = mysqli_num_rows($resultProduct);
                for ($j = 0; $j < $rowsProducts; $j++) {
                    $b = mysqli_fetch_assoc($resultProduct);
                    echo "
                                <div class='cart__cards__card'>
                                    <div class='cart__cards__card__image' style='background-image: url({$b['product_image']})'>
                                    </div>
                                    <div class='cart__cards__card__description'>
                                        <div class='cart__cards__card__title'>
                                            <h2>{$b['product_name']}</h2>
                                        </div>
                                        <div class='cart__cards__card__category'>
                                            <span>Категория: {$b['category_name']}</span>
                                        </div>
                                        <div class='cart__cards__card__price'><span>{$b['product_price']} галлен(ов)</span></div>
                                        <div class='cart__cards__card__buttons'>
                                            <form action='' method='post' class='cart__form'>
                                                        <input type='hidden' name='product_id' value='{$a['ordersItem_product']}'>

                                                    <div class='cart__cards__card__buttons__count'>
                                                        <button type='button' class='cart__cards__card__buttons__count__minus' onclick='updateValueMinus(this)'>-</button>
                                                        <input class='cart__cards__card__buttons__count__count' name='qty' value='{$a['ordersItem_quantity']}'>
                                                        <button type='button' class='cart__cards__card__buttons__count__plus' onclick='updateValuePlus(this)'>+</button>
                                                    </div>
                                                    <div>
                                                        <input type='button' name='delete' value='Удалить' class='cart__cards__card__button' onclick='deleteValue(this,event)'>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                            </div>";
                }
            }

            ?>
        </div>
        <form action="buy.php" method='post' class='cart__buy__form'>
            <input type="submit" name='buy' class='cart__buy' value='Заказать'>
        </form>

    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function updateValueMinus(button) {
            const form = button.closest('.cart__form');
            const input = form.querySelector('input[name="qty"]');
            let value = parseInt(input.value);
            if (value > 1) {
                value--;
                input.value = value;

                const productId = form.querySelector('input[name="product_id"]').value;
                updateQuantityInDB(productId, value);
            }
        }

        function updateValuePlus(button) {
            const form = button.closest('.cart__form');
            const input = form.querySelector('input[name="qty"]');
            let value = parseInt(input.value);
            value++;
            input.value = value;

            const productId = form.querySelector('input[name="product_id"]').value;
            updateQuantityInDB(productId, value);
        }

        function updateQuantityInDB(productId, quantity) {
            $.ajax({
                url: '/functions/updateQuantity.php',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    console.log("Обновлено:", response);
                },
                error: function(xhr, status, error) {
                    console.error("Ошибка обновления:", error);
                }
            });
        }

        function deleteValue(button) {
            const form = button.closest('.cart__form');
            const productId = form.querySelector('input[name="product_id"]').value;

            $.ajax({
                url: '/functions/deleteFromDB.php',
                type: 'POST',
                data: {
                    product_id: productId,
                },
                success: function(response) {
                    console.log("Удалено:", response);

                    const card = button.closest('.cart__cards__card');
                    if (card) {
                        card.remove();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Ошибочка!", error);
                }
            });
        }
    </script>

</body>

</html>