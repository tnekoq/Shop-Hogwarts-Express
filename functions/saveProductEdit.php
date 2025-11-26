<?php
if (isset($_POST['saveEdit'])) {
    $link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
    mysqli_set_charset($link, "utf8mb4");

    $productId = $_POST['product_id'] ?? null;
    $productName = $_POST['product_name'] ?? null;
    $productPrice = $_POST['product_price'] ?? null;
    $productDescription = $_POST['product_description'] ?? null;
    $productCategory = $_POST['product_category'] ?? null;
    $productSupplier = $_POST['product_supplier'] ?? null;
    $image = $_FILES['productNewPhoto'] ?? null;

    if ($productId && $productName && $productPrice && $productDescription && $productCategory && $productSupplier) {

        $setImage = '';

        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $tmpName = $image['tmp_name'];
            $uploadDir = '/img/uploads/products/';
            $serverUploadDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

            if (!is_dir($serverUploadDir)) {
                mkdir($serverUploadDir);
            }

            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $safeFileName = uniqid('product_', true) . '.' . $extension;
            $targetPath = $serverUploadDir . $safeFileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = $uploadDir . $safeFileName; 
                $setImage = ", product_image = '$imagePath'";
            } else {
                echo "Ошибка: не удалось сохранить изображение.<br>";
            }
        }

        $query = "update product set 
                    product_name = '$productName', 
                    product_price = '$productPrice', 
                    product_description = '$productDescription', 
                    product_category = '$productCategory', 
                    product_supplier = '$productSupplier'
                    $setImage where product_id = '$productId'";

        $result = mysqli_query($link, $query);

        if ($result) {
            header('Location: /pages/admin.php?name=Товары');
        } else {
            echo "Ошибка при обновлении: " . mysqli_error($link);
        }
    } else {
        echo "Ошибка: не все данные переданы.";
    }
}
