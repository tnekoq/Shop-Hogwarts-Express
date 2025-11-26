<?php 
session_start();
$active = $_GET['name'] ?? 'Изменение%модулей'; 
$link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");
$query = "select user_name from user where user_email = '{$_SESSION['email']}'";
$result = mysqli_query($link,$query) or die ('ОШибка: '. mysqli_error($link));
$myAdmin = mysqli_fetch_assoc($result);
$name = $myAdmin['user_name'];

?>
<div class="sidebar"> 
    <h2><?php echo $name; ?></h2>
    <h2>Модули</h2>
    <a href='?name=Изменение%модулей' class=''>
        <div class="theLastOne <?= $active == 'Изменение%модулей' ? 'active' : '' ?>">Изменение модулей</div>
    </a>
    <h2>Каталог</h2>
    <a href='?name=Категории' class=''>
        <div class="<?= $active == 'Категории' ? 'active' : '' ?>">Категории</div>
    </a>
    <a href='?name=Товары' class=''>
        <div class="theLastOne <?= $active == 'Товары' ? 'active' : '' ?>">Товары</div>
    </a>
    <h2>Заказы</h2>
    <a href='?name=Поиск' class=''>
        <div class="m <?= $active == 'Поиск' ? 'active' : '' ?>">Поиск</div>
    </a>

    <a href='/registration/log_out.php?name=logOut' class='logOut'>
        <div class="<?= $active == 'logOut' ? 'active' : '' ?>">Выйти из аккаунта</div>
    </a>    
</div>