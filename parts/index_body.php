<div class="category__list">
    <ul>
        <?php
        $queryCategories = "select * from category";
        $result = mysqli_query($link, $queryCategories) or die("Ошибка выполнения запроса: " . mysqli_error($link));
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            for ($i = 0; $i < 8; $i++) {
                $rowCategory = mysqli_fetch_assoc($result);
                if ($rowCategory['category_parent_id'] == 0) {

                    echo "
                <a href='/pages/categories.php?name={$rowCategory['category_name']}'>
                    <li>
                        <img src='{$rowCategory['category_icon']}' alt='{$rowCategory['category_name']}'>
                        <p>{$rowCategory['category_name']}</p>
                    </li>
                </a>";
                }
            }
        }
        ?>
    </ul>
</div>
<div class="main__align">
    <section class="banner">
        <div class="banner__image"></div>
        <div class="banner__description">
            <p>Скидки к осеннему обучению</p>
            <p class="banner__sale">50% на все товары</p>
        </div>
    </section>
    <section class="popular">
        <h2 class="popular__title">
            Популярные категории
        </h2>
        <div class="popular__cards">
            <?php

            $querySelectCategories = "SELECT * FROM category";
            $result = mysqli_query($link, $querySelectCategories) or die("Ошибка выполнения запроса: " . mysqli_error($link));
            $row = mysqli_num_rows($result);
            if ($row > 0) {
                for ($i = 0; $i < 4; $i++) {
                    $rowCategory = mysqli_fetch_assoc($result);
                    echo "
                        <div class=popular__card>
                            <div class=popular__card__img style='background-image: url({$rowCategory['category_image']})'></div>
                            <div class=popular__card__title>{$rowCategory['category_name']}</div>
                        </div>";
                }
            }
            ?>
        </div>
    </section>
    <section class="recomendation">
        <h2 class="recomendation__title">
            Рекомендации
        </h2>
        <div class="recomendation__cards">
            <?php
            session_start();
            $querySelectProduct = "SELECT * FROM product";
            $result = mysqli_query($link, $querySelectProduct) or die("Ошибка выполнения запроса: " . mysqli_error($link));
            $row = mysqli_num_rows($result);
            if ($row > 0) {
                for ($i = 0; $i < $row; $i++) {
                    $rowProduct = mysqli_fetch_assoc($result);
                    $icon = '/img/svg/basket.svg';
                    if (isset($_SESSION['cart']) && in_array($rowProduct['product_id'], $_SESSION['cart'])) {
                        $icon = '/img/svg/star.svg';
                    }
                    echo "
                        <div class=recomendation__card>
                            <div class=recomendation__card__img style='background-image: url({$rowProduct['product_image']})'></div>
                            <div class=recomendation__card__short__description>
                                <div class=recomendation__card__title>{$rowProduct['product_name']}</div>
                                <div class=recomendation__card__price>{$rowProduct['product_price']} галлеонов</div>
                                <div class=recomendation__card__rating><img src='./img/svg/star.svg'><p>{$rowProduct['product_rating']}</p></div>
                                <form method='post' action='' class='formForCart'><input hidden name='productId' value='{$rowProduct['product_id']}'><button type='submit' name='toCart' class=recomendation__card__button>
                                <img class='recomendation__card__button__image' src='{$icon}'>
                                <p>Корзина</p></button></form>
                            </div>
                        </div>";
                }
            }

            ?>

        </div>
    </section>
</div>
<br>
<br>
<br>
<?php include "footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('submit', '.formForCart', function(e) {
        e.preventDefault();

        const form = $(this);
        const productId = form.find('input[name="productId"]').val();
        const button = form.find('.recomendation__card__button');
        const img = button.find('.recomendation__card__button__image');

        $.ajax({
            url: '/functions/addingToCart.php',
            type: 'POST',
            data: {
                productId: productId
            },
            success: function(response) {
                const result = response.trim();
                if (result === 'added') {
                    img.attr('src', '/img/svg/star.svg');
                } else if (result === 'removed') {
                    img.attr('src', '/img/svg/basket.svg');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка AJAX:', error);
            }
        });
    });
</script>