<?
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

if (!mysqli_set_charset($link, "utf8mb4")) {
    echo "Ошибка при загрузке набора символов utf8mb4: ";
    mysqli_error($link);
    exit();
}

$querySelectCategory = "select * from category where category_parent_id = 0";
$result = mysqli_query($link, $querySelectCategory) or die("Ошибка " . mysqli_error($link));
$rows = mysqli_num_rows($result);
echo "<ol>";
if ($rows !== 0) {
    for ($i = 0; $i < $rows; $i++) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['category_id'];
        $name = htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8');

        echo "<li class='category__item' data-id='$id'>
                <div class='editable' data-id='$id'>$name</div>
                <input type='button' value='Удалить' class='category__delete' onclick='deleteValue(this)'>
              </li>";
    }
}
echo "</ol>";
