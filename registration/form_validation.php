<?php

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 


function clear($str)
{
    $str = trim($str);
    $str = strip_tags($str);
    $str = stripslashes($str);

    return $str;
}
function logError($message)
{
    $logFile = fopen('log.txt', "a+");
    $logMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    fwrite($logFile, $logMessage);
}
$errorContainer = [];
mysqli_report(MYSQLI_REPORT_OFF);
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

if (!mysqli_set_charset($link, "utf8mb4")) {
    echo "Ошибка при загрузке набора символов utf8mb4: ";
    mysqli_error($link);
    exit();
}

$regexpName = "/[A-ZА-ЯЁ][a-zа-яё]+/u";
if (isset($_POST['name']) && !preg_match($regexpName, $_POST['name'])) {
    $errorContainer['name'] = "Имя не соответствует требованиям.";
    logError("Ошибка регистрации: Неверный формат имени.");
}
if (isset($_POST['surname']) && !preg_match($regexpName, $_POST['surname'])) {
    $errorContainer['surname'] = "Фамилия не соответствует требованиям.";
    logError("Ошибка регистрации: Неверный формат фамилии.");
}
if (isset($_POST['email'])) {
    $regexpEmail = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    if (!preg_match($regexpEmail, $_POST['email'])) {
        $errorContainer['email'] = "Email не соответствует требованиям.";
        logError("Ошибка регистрации: Неверный формат email.");
    } else {

            $email = mysqli_real_escape_string($link, $_POST['email']);
            $querySelectEmail = "SELECT user_id FROM user WHERE user_email='$email'";
            $result = mysqli_query($link, $querySelectEmail) or die("Ошибка выполнения запроса: " . mysqli_error($link));
            if (mysqli_num_rows($result) > 0) {
                $errorContainer['email'] = "Пользователь с таким email уже существует.";
                logError("Ошибка регистрации: Email уже занят.");
            }
    }
}


$regexpPassword = "/^[%?^#\$_][a-zA-Z0-9]{8,}/";
if (isset($_POST['password1']) && !preg_match($regexpPassword, $_POST['password1'])) {
    $errorContainer['password1'] = "Пароль не соответствует требованиям.";
    logError("Ошибка регистрации: Неверный формат пароля.");
}

if (isset($_POST['password2']) && $_POST['password1'] != $_POST['password2']) {
    $errorContainer['password2'] = 'Пароли не совпадают';
    logError("Ошибка регистрации: Пароли не совпадают.");
}

if (isset($_POST['phone'])) {
    $regexpPhone = "/^\+375\(\d{2}\)\d{3}-\d{2}-\d{2}$/";
    if (!preg_match($regexpPhone, $_POST['phone'])) {
        $errorContainer['phone'] = "Телефон не соответствует требованиям.";
        logError("Ошибка регистрации: Неверный формат телефона.");
    } else {

            $phone = mysqli_real_escape_string($link, $_POST['phone']);
            $querySelectPhone = "SELECT user_id FROM user WHERE user_phone='$phone'";
            $result = mysqli_query($link, $querySelectPhone) or die("Ошибка выполнения запроса: " . mysqli_error($link));
            if (mysqli_num_rows($result) > 0) {
                $errorContainer['phone'] = "Пользователь с таким телефоном уже существует.";
                logError("Ошибка регистрации: Телефон уже занят.");
            }
    }
}
