<?php
session_start();
$formData = [];
$errorContainer = [];
if (isset($_SESSION['formData'])) {
    $formData = $_SESSION['formData'];
    $errorContainer = $_SESSION['errors'];
    unset($_SESSION['formData']);
    unset($_SESSION['errors']);
}

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <div class="registration__layout">
        <div class="registration">
            <h3 class="registration__title">Вход</h3>
            <form action="log_reg.php" method="POST" id="form_test" class="registration__inputs">
                <label><b>Введите email: </b></label>
                <input type="text" name="email" id="email" class="input <?php echo isset($errorContainer['email']) ? "error_input" : "" ?>" required placeholder="Email" value="<?php echo isset($formData['email']) ? $formData['email'] : 'admin1@gmail.com'; ?>"><br>
                <label id="email_error" class="error"><?php echo isset($errorContainer['email']) ? $errorContainer['email'] : "" ?></label>

                <label><b>Введите пароль: </b></label>
                <input type="password" name="password1" id="password1" class="input  <?php echo isset($errorContainer['password1']) ? "error_input" : "" ?>" required placeholder="Пароль" value="<?php echo isset($formData['password1']) ? $formData['password1'] : ''; ?>"><br>
                <label id="password1_error" class="error"><?php echo isset($errorContainer['password1']) ? $errorContainer['password1'] : "" ?></label>

                <input type="submit" name="submit" value="Отправить" id="send_data">
                <a href="/registration/registration.php" class='reg__PA'>Вы - новый волшебник?</a>

            </form>
        </div>
    </div>
</body>
    </html>