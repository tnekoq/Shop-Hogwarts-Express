<?php
mysqli_report(MYSQLI_REPORT_OFF);
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
mysqli_set_charset($link, "utf8mb4");

$category_id = $_POST['value'] ?? 0;

$query = "select category_name from category where category_parent_id = $category_id";
$result = mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<ul class='header__catalog__list'>
                <a href='/pages/categories.php?name={$row['category_name']}'><li><div class='header__catalog__list__item'><div class='header__catalog__list__item__name'><p>{$row['category_name']}</p></div></div></li>
                </a>
              </ul>";
    }
} 
