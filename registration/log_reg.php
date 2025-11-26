<?php
    session_start();
    include "log_validation.php";
    if(!empty($errorContainer)){
        $_SESSION['errors'] = $errorContainer;
        $_SESSION['formData'] = $_POST;
        header("Location: log_in.php");
        exit();
    }else{
        $_SESSION['email'] = $_POST['email'];
        $isAdminQuery = "select user_isAdmin from user where user_email = '{$_POST['email']}'";
        $result = mysqli_query($link, $isAdminQuery) or die("Ошибка выполнения запроса: " . mysqli_error($link));
        $enter = mysqli_fetch_assoc($result);
        $_SESSION['enter'] = $enter['user_isAdmin'];
        if($enter['user_isAdmin']){
            header("Location: ../pages/admin.php");
        }else{
            header("Location: ../index.php");
        }
        exit();
    }