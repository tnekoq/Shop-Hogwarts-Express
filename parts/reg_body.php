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

<body>
    <div class="registration__layout">

    </div>
    <div class="registration">

        <h3 class="registration__title">Регистрация</h3>

        <form action="reg.php" method="POST" id="form_test" class="registration__inputs">

            <label>Введите Ваше имя:</label>
            <input type="text" name="name" id="name" class="input <?php echo isset($errorContainer['name']) ? "error_input" : "" ?>" required placeholder="Имя" value="<?php echo isset($formData['name']) ? $formData['name'] : 'Антонина'; ?>"><br>
            <label id="name_error" class="error"><?php echo isset($errorContainer['name']) ? $errorContainer['name'] : "" ?></label>

            <label>Введите Вашу фамилию:</label>
            <input type="text" name="surname" id="surname" class="input <?php echo isset($errorContainer['surname']) ? "error_input" : "" ?>" required placeholder="Фамилия" value="<?php echo isset($formData['surname']) ? $formData['surname'] : ''; ?>"><br>
            <label id="surname_error" class="error"><?php echo isset($errorContainer['surname']) ? $errorContainer['name'] : "" ?></label>

            <label>Введите e-mail:</label>
            <input type="email" name="email" id="email" class="input  <?php echo isset($errorContainer['email']) ? "error_input" : "" ?>" required placeholder="Email" value="<?php echo isset($formData['email']) ? $formData['email'] : 'noreply@steampowered.com'; ?>"><br>
            <label id="email_error" class="error"><?php echo isset($errorContainer['email']) ? $errorContainer['email'] : "" ?></label>

            <label>Введите Ваш телефон</label>
            <input type="phone" name="phone" id="phone" class="input  <?php echo isset($errorContainer['phone']) ? "error_input" : "" ?>" required placeholder="Телефон" value="<?php echo isset($formData['phone']) ? $formData['phone'] : '+375(29)345-45-45'; ?>"><br>
            <label id="phone_error" class="error"><?php echo isset($errorContainer['phone']) ? $errorContainer['phone'] : "" ?></label>

            <label>Придумайте пароль:</label>
            <input type="password" name="password1" id="password1" class="input  <?php echo isset($errorContainer['password1']) ? "error_input" : "" ?>" required placeholder="Пароль" value="<?php echo isset($formData['password1']) ? $formData['password1'] : ''; ?>"><br>
            <label id="password1_error" class="error"><?php echo isset($errorContainer['password1']) ? $errorContainer['password1'] : "" ?></label>

            <label>Повторите пароль:</label>
            <input type="password" name="password2" id="password2" class="input  <?php echo isset($errorContainer['password2']) ? "error_input" : "" ?>" required placeholder="Повторите пароль" value="<?php echo isset($formData['password2']) ? $formData['password2'] : ''; ?>"><br>
            <label id="password2_error" class="error"><?php echo isset($errorContainer['password2']) ? $errorContainer['password2'] : "" ?></label>


            <input type="submit" name="submit" value="Отправить" id="send_data">
            <a href="/registration/log_in.php" class='reg__PA'>Уже есть свой акк?</a>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('input').bind('blur', function(e) {
            let requestData;
            const controlName = e.target.name;

            if (controlName == "password1") {
                requestData = {
                    'password1': $('#password1').val()
                };
            }

            if (controlName == "password2" && $('#password1').val()) {
                requestData = {
                    'password1': $('#password1').val(),
                    'password2': $('#password2').val()
                };
            }

            if (controlName != "password1" && controlName != "password2") {
                requestData = {
                    [controlName]: e.target.value
                };
            }
            $.ajax({
                type: "POST",
                url: "response.php",
                data: requestData,
                dataType: "json",
                success: function(data) {
                    if (data.result == 'success') {
                        for (var goodField in requestData) {
                            $('#' + goodField + '_error').hide();
                            $('#' + goodField).removeClass('error_input');
                        }
                    } else {
                        for (var errorField in data.text_error) {
                            $('#' + errorField + '_error').html(data.text_error[errorField]);
                            $('#' + errorField + '_error').show();
                            $('#' + errorField).addClass('error_input');
                        }
                    }
                }
            });
            return false;
        });
    </script>

    </html>