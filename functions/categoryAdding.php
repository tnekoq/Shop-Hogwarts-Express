<?

if (isset($_FILES['categoryNewPhoto']) && $_FILES['categoryNewPhoto']['error'] == 0 && isset($_POST['categoryNew'])) {    
    $tmpPath = $_FILES['categoryNewPhoto']['tmp_name'];
    $originalName = $_FILES['categoryNewPhoto']['name'];
    $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/img/uploads/categories/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir);
    }
    $destination = $uploadDir . $originalName;
    $destinationRelative = '/img/uploads/categories/' . $originalName;
    if(move_uploaded_file($tmpPath, $destination)){
        $categoryName = $_POST['categoryNew'];
        $link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
        mysqli_set_charset($link, "utf8mb4");
        $query = "insert into category(category_name,category_image,category_icon, category_parent_id) values('$categoryName', '$destinationRelative', '', 0)";
        if(mysqli_query($link, $query)){
            return "OK";
        }else{
            echo mysqli_error($link);
        }
    }


}