<?php
if (isset($_POST['saveProduct'])) {
    $link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
    mysqli_set_charset($link, "utf8mb4");

    $productName = $_POST['product_name'] ?? null;
    $productPrice = $_POST['product_price'] ?? null;
    $productDescription = $_POST['product_description'] ?? null;
    $productCategory = $_POST['product_category'] ?? null;
    $productSupplier = $_POST['product_supplier'] ?? null;
    $image = $_FILES['productNewPhoto'] ?? null;

    if ($productName && $productPrice && $productDescription && $productCategory && $productSupplier) {
        $imagePath = '/img/default.jpg';
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $tmpName = $image['tmp_name'];
            $uploadDir = '/img/uploads/products/';
            $serverUploadDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;

            if (!is_dir($serverUploadDir)) {
                mkdir($serverUploadDir, 0777, true);
            }

            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $safeFileName = uniqid('product_', true) . '.' . $extension;
            $targetPath = $serverUploadDir . $safeFileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePath = $uploadDir . $safeFileName;
            } else {
                echo "Ошибка: не удалось сохранить изображение.<br>";
                exit;
            }
        }

        $query = "insert into product (product_name, product_category, product_rating, product_supplier, product_price, product_description, product_image) values ('$productName', '$productCategory', 0, '$productSupplier', '$productPrice', '$productDescription', '$imagePath')";

        $result = mysqli_query($link, $query);

        if ($result) {
            header('Location: /pages/admin.php?name=Товары');
            exit;
        } else {
            echo "Ошибка при добавлении: " . mysqli_error($link);
        }
    } 
}
