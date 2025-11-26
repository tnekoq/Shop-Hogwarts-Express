<?php
function clear($str)
{
    $str = trim($str);
    $str = strip_tags($str);
    $str = stripslashes($str);

    return $str;
}
file_put_contents('debug.txt', print_r($_POST, true));

$errorContainer = [];
mysqli_report(MYSQLI_REPORT_OFF);
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

if (!mysqli_set_charset($link, "utf8mb4")) {
    echo "Ошибка при загрузке набора символов utf8mb4: ";
    mysqli_error($link);
    exit();
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $email = '';
}

$password = '';

if (isset($_POST['password1'])) {
    $password = $_POST['password1'];
}
$email = mysqli_real_escape_string($link, $email);

if (!empty($email)){
    $querySelectEmail = "SELECT user_id, user_password FROM user WHERE user_email='$email'";
    $result = mysqli_query($link, $querySelectEmail);

    if (mysqli_num_rows($result) == 0) {
        $errorContainer['password1'] = "Неправильно введены e-mail или пароль.";
    }
}

if ($email && $password) {
    $querySelectEmail= "SELECT user_password FROM user WHERE user_email='$email'";
    $result = mysqli_query($link, $querySelectEmail);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (!password_verify($password, $row['user_password'])) {
            $errorContainer['password1'] = 'Неправильно введены e-mail или пароль.';
        }        
    }
}
