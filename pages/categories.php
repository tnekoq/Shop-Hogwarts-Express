<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категории</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <?php
    require "../parts/header.php";

    $category = isset($_GET['name']) ? $_GET['name'] : '';
    ?>
    <section class="recomendation">
        <h1 class="recomendation__title"><?= $category ?></h1>
        <section class="content">
            <section class="filter">
                <h2 class="filter__title">Фильтр</h2>
                <form method="post" action="" class="filter__form" id="filterForm">
                    <label for="searchProduct" class="filter__form__title">Поиск по названию:</label>
                    <input type="text" name="search" id="searchProduct" class="filter__form__search" /><br>
                    <label for="rangePrice" class="filter__form__title">Диапазон цены:</label>
                    <div class="filter__form__rangePrice">
                        <p>От:</p> <input type="text" name="from" size="10" value="" id="rangePrice" />
                        <p>До:</p><input type="text" name="to" size="10" value="" />
                    </div>
                    <label for="checkSupplier">Производитель:</label>
                    <?php
                    $querySupplier = "select * from supplier";
                    $result = mysqli_query($link, $querySupplier) or die("Ошибка выполнения запроса: " . mysqli_error($link));
                    $row = mysqli_num_rows($result);
                    if ($row > 0) {
                        while ($rowSupplier = mysqli_fetch_assoc($result)) {
                            echo "<div class='filter__form__suppliers'><input type='checkbox' id='checkSupplier' name='suppliers[]' value='{$rowSupplier['supplier_id']}'><p>{$rowSupplier['supplier_name']}</p></div>";
                        }
                    }
                    ?>
                    <label for="rangeRating">Рейтинг:</label>
                    <div class="filter__form__rangeRating">
                        <input type="range" class="filter__form__rangeRating__range" id="rangeRating" name="rating" min="0.0" max="5.0" value="0" step="0.1" oninput="updateValue(this.value)">
                        <span id="rangeValue">0.0</span>
                    </div>
                    <label for="radioSubCat">Подкатегория:</label>

                    <?php


                    $a = "select category_id,category_parent_id from category where category_name = '$category'";
                    $result = mysqli_query($link, $a) or die("Ошибка выполнения запроса: " . mysqli_error($link));
                    $row = mysqli_fetch_assoc($result);
                    if ($row['category_parent_id'] == 0) {
                        $querySubCats = "select category_name from category where category_parent_id = {$row['category_id']}";
                    } else {
                        $querySubCats = "select category_name from category where category_parent_id = {$row['category_parent_id']}";
                    }
                    $resultSubCats = mysqli_query($link, $querySubCats) or die("Ошибка запроса подкатегорий: " . mysqli_error($link));
                    if (mysqli_num_rows($resultSubCats) > 0) {
                        while ($subCat = mysqli_fetch_assoc($resultSubCats)) {
                            $subCatName = $subCat['category_name'];
                            $isChecked = ($subCatName === $category) ? "checked" : "";
                            echo "<div class='filter__form__suppliers'>
                                    <input type='radio' id='radioSubCat_{$subCatName}' name='subCat' value='{$subCatName}' {$isChecked}>
                                    <label for='radioSubCat_{$subCatName}'>{$subCatName}</label>
                                  </div>";
                        }
                    }

                    ?>
                    <!-- <input type="submit" name="submit" value="Искать" class="filter__form__button"> -->
                    <input type="submit" name="show" value="Сбросить фильтр" id="resetBtn" class="filter__form__button">
                </form>
            </section>
            <div class="recomendation__cards recomendation__card__categories" id="productList">
            </div>
        </section>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function updateValue(val) {
            document.getElementById('rangeValue').textContent = val;
        }
        $(function() {
            function fetchProducts(data) {
                $.post("/functions/filterProducts.php", data, function(response) {
                    $(".recomendation__cards").html(response);
                });
            }

            function sendFullForm() {
                const formData = $("#filterForm").serializeArray();
                formData.push({
                    name: 'category',
                    value: "<?= $category ?>"
                });
                fetchProducts(formData);
            }

            $("#filterForm").on("input change", "input, select", function() {
                sendFullForm();
            });

            $("#filterForm").on("submit", function(e) {
                e.preventDefault();
                sendFullForm();
            });

            $("#resetBtn").on("click", function(e) {
                e.preventDefault();
                $("#filterForm")[0].reset();
                sendFullForm();
            });

            sendFullForm();
        });
    </script>

</body>

</html>