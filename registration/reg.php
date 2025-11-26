<?php
    session_start();
    include "form_validation.php";
    if(!empty($errorContainer)){
        $_SESSION['errors'] = $errorContainer;
        $_SESSION['formData'] = $_POST;
        header("Location: registration.php");
        exit();
    }else{
        $name = mysqli_real_escape_string($link, $_POST['name']);
        $surname = mysqli_real_escape_string($link, $_POST['surname']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $phone = mysqli_real_escape_string($link, $_POST['phone']);

        $password = $_POST['password1'];
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO user (user_id, user_name, user_surname, user_email, user_phone, user_password, user_isAdmin ) 
          VALUES (NULL, '$name','$surname', '$email', '$phone','$hash',0)";        
        mysqli_query($link,$query) or die("Ошибка ". mysqli_error($link));
        session_write_close();
        
        header("Location: log_in.php");
        exit();
    }
?>