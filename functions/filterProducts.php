<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

if (!mysqli_set_charset($link, "utf8mb4")) {
    echo "Ошибка при загрузке набора символов utf8mb4: ";
    mysqli_error($link);
    exit();
}

$category = isset($_POST['category']) ? $_POST['category'] : '';

$search = isset($_POST['search']) ? $_POST['search'] : '';
$from = isset($_POST['from']) ? $_POST['from'] : 0;
$to = isset($_POST['to']) ? $_POST['to'] : 0;
$suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
$rating = isset($_POST['rating']) ? $_POST['rating'] : 0;
$subCat = isset($_POST['subCat']) ? $_POST['subCat'] : '';
if (!empty($subCat)) {
    $category =  $subCat;
}


if ($category !== '') {

    $queryCatQuestion = "select category_id, category_parent_id from category where category_name = '$category'";
    $result = mysqli_query($link, $queryCatQuestion) or die("Ошибка выполнения запроса: " . mysqli_error($link));
    $rowCat = mysqli_fetch_assoc($result);
    $categoryId = $rowCat['category_id'];
    $parentId = $rowCat['category_parent_id'];
    if ($parentId == 0) {
        $subCatsQuery = "select category_id from category where category_parent_id = $categoryId";
        $subCatsResult = mysqli_query($link, $subCatsQuery);
        $subCatIds = [];
        while ($subCat = mysqli_fetch_assoc($subCatsResult)) {
            $subCatIds[] = $subCat['category_id'];
        }
        if (!empty($subCatIds)) {
            $in = implode(',', $subCatIds);
            $querySelectProduct = "select * from product where product_category in ($in)";
        } else {
            $querySelectProduct = "select * from product where product_category = $categoryId";
        }
    } else {
        $querySelectProduct = "select * from product where product_category = $categoryId";
    }
}

if (!empty($search)) {
    $querySelectProduct .= " and product_name like '%$search%'";
}
if ($from > 0) {
    $querySelectProduct .= " and product_price >= $from";
}
if ($to > 0) {
    $querySelectProduct .= " and product_price <= $to";
}
if (!empty($suppliers)) {
    $suppliersList = implode(",", $suppliers);
    $querySelectProduct .= " and product_supplier IN ($suppliersList)";
}
if ($rating > 0) {
    $querySelectProduct .= " and product_rating >= $rating";
}
if (!empty($subCat)) {
    $querySelectProduct .= " and product_category = (select category_id from category where category_name = '$subCat')";
}
$result = mysqli_query($link, $querySelectProduct) or die("Ошибка выполнения запроса: " . mysqli_error($link));

$row = mysqli_num_rows($result);
$filtered = [];
if ($row > 0) {
    for ($i = 0; $i < $row; $i++) {
        $rowProduct = mysqli_fetch_assoc($result);
        array_push($filtered, $rowProduct);
    }
}

if (!empty($filtered)) {
    foreach ($filtered as $item) {
        echo "
            <div class='recomendation__card'>
                <div class='recomendation__card__img' style='background-image: url({$item['product_image']})'></div>
                <div class='recomendation__card__short__description'>
                    <div class='recomendation__card__title'>{$item['product_name']}</div>
                    <div class='recomendation__card__price'>{$item['product_price']} галлеонов</div>
                    <div class='recomendation__card__rating'>
                        <img src='/img/svg/star.svg'>
                        <p>{$item['product_rating']}</p>
                    </div>
                    <button class='recomendation__card__button'>
                        <img src='/img/svg/basket.svg' class='recomendation__card__button__image'>
                        <p>Корзина</p>
                    </button>
                </div>
            </div>";
    }
} else {
    echo "<p class='recomendation__cards__nothing'><b>Ничего не найдено.</b></p>";
}
