<?php $active = $_GET['name'] ?? ''; ?>
<div class="sidebar"> 
    <a href='/pages/support.php?name=Заказы' class='support'>
        <div class="<?= $active == 'Заказы' ? 'active' : '' ?>">Заказы</div>
    </a>
    <a href='/registration/log_out.php?name=logOut' class='logOut'>
        <div class="<?= $active == 'logOut' ? 'active' : '' ?>">Выйти из аккаунта</div>
    </a>
</div>