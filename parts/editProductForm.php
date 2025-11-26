    <?php
    $query = "select * from product where product_id = $productId";
    $result = mysqli_query($link, $query);
    $product = mysqli_fetch_assoc($result);
    ?>

    <h3 class="edit__title">Редактирование товара: <?= $product['product_name'] ?></h3>
    <form enctype="multipart/form-data" method="post" action="/functions/saveProductEdit.php" id='productChange'>
        <div class="vert_align">
            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>" required>
            <label>Название: </label>
            <input type="text" name="product_name" value="<?= $product['product_name'] ?>" required>
            <label>Цена: </label>
            <input type="number" name="product_price" value="<?= $product['product_price'] ?>" required>
            <label>Описание: </label>
            <input type="text" name="product_description" value="<?= $product['product_description'] ?>" required>
            <label>Категория: </label>
            <select name="product_category" size="1">
                <?php
                $querySelectCategory = "select * from category";
                $result = mysqli_query($link, $querySelectCategory) or die("Ошибка " . mysqli_error($link));
                $result = mysqli_query($link, $querySelectCategory) or die("Ошибка " . mysqli_error($link));
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ((int)$row['category_parent_id'] !== 0) {
                            $selected = ($row['category_id'] == $product['product_category']) ? "selected" : "";
                            echo "<option value='{$row['category_id']}' $selected>{$row['category_name']}</option>";
                        }
                    }
                }
                ?>
            </select>
            <label>Производитель: </label>
            <select name="product_supplier" size="1">
                <?php
                $querySelectCategory = "select supplier_id,supplier_name from supplier";
                $result = mysqli_query($link, $querySelectCategory) or die("Ошибка " . mysqli_error($link));
                if ($result) {
                    $rows = 0;
                    $rows = mysqli_num_rows($result);
                    if ($rows !== 0) {
                        for ($i = 0; $i < $rows; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            foreach ($row as $key => $value) {
                                if ($key == "supplier_name" && $row['supplier_id'] == $product['product_supplier']) {
                                    echo "<option selected value='{$row['supplier_id']}'>$value</option>";
                                } else if ($key == "supplier_name") {
                                    echo "<option value='{$row['supplier_id']}'>$value</option>";
                                }
                            }
                        }
                    }
                }
                ?>
            </select>
            <input type="submit" value="Сохранить" name="saveEdit">

        </div>
        <div class="product__image">
            <input type="file" accept="image/png, image/jpeg" name="productNewPhoto" value="Выбрать фото" id="productNewPhoto"><br>
            <img id="productPreviewImage" src='<?= $product['product_image'] ?>' />
        </div>

    </form>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {

            document.getElementById('productNewPhoto').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('productPreviewImage');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            });
        })
    </script>