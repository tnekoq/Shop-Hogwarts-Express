<?php
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

$querySelectProduct = "select * from product";
$result = mysqli_query($link, $querySelectProduct) or die("Ошибка запроса: " . mysqli_error($link));

$output = '';
if (mysqli_num_rows($result) > 0) {
    while ($rowProduct = mysqli_fetch_assoc($result)) {
        $icon = '/img/svg/change.svg';
        $deleteIcon = '/img/svg/delete.svg';
        $output .= "
        <div class='recomendation__card' data-id='{$rowProduct['product_id']}'>
            <div class='recomendation__card__img' style='background-image: url({$rowProduct['product_image']})'></div>
            <div class='recomendation__card__short__description'>
                <div class='recomendation__card__title'>{$rowProduct['product_name']}</div>
                <div class='recomendation__card__price'>{$rowProduct['product_price']} галлеонов</div>
                <div class='recomendation__card__rating'><img src='/img/svg/star.svg'><p>{$rowProduct['product_rating']}</p></div>
                <div class='recomendation__card__buttons'>
                    <button class='deleteProduct recomendation__card__button' data-id='{$rowProduct['product_id']}'>
                        <img class='recomendation__card__button__image' src='$deleteIcon'>
                    </button>
                    <form method='post' action='' class='formForCart'>
                        <input hidden name='productId' value='{$rowProduct['product_id']}'>
                        <button type='submit' name='editProduct' class='editProduct recomendation__card__button'>
                            <img class='recomendation__card__button__image' src='$icon'><p>Изменить</p>
                        </button>
                    </form>

                </div>
            </div>
        </div>";
    }
}
echo $output;
