<header>
    <a href="../index.php">
        <div class="new">
            <div class="logo">
            </div>
    </a>
    <button class="header__button" id="menuButton">Каталог</button>
    <div id="sidebar" class="header__sidebar">
        <button id="closeButton" class='header__sidebar__close__button'>закрой меня</button>

        <?php
        session_start();
        mysqli_report(MYSQLI_REPORT_OFF);
        $link = mysqli_connect("localhost", "your_user", "your_password", "hogwarts");

        if (!mysqli_set_charset($link, "utf8mb4")) {
            echo "Ошибка при загрузке набора символов utf8mb4: ";
            mysqli_error($link);
            exit();
        }

        $querySelectCategories = "select category_id,category_name,category_icon, category_parent_id from category";
        $result = mysqli_query($link, $querySelectCategories) or die("Ошибка выполнения запроса: " . mysqli_error($link));
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            for ($i = 0; $i < $row; $i++) {
                $rowCategory = mysqli_fetch_assoc($result);
                if ($rowCategory['category_parent_id'] == 0) {
                    echo "<ul class='header__catalog__list'>
                            <li><div class='header__catalog__list__item'><div class='header__catalog__list__item__name'><a href='/pages/categories.php?name={$rowCategory['category_name']}'><img src='{$rowCategory['category_icon']}' class='header__catalog__list__item__name__icon'><p>{$rowCategory['category_name']}</p></div></a><button  class='header__catalog__list__item__name__button' onclick='setCategoryValue({$rowCategory['category_id']})'><img src='/img/svg/catalog_right_row.svg' class='header__catalog__list__row'></button></a></div></li>
                        </ul>";
                }
            }
        }
        ?>
    </div>
    <div id="overlay" class="overlay"></div>

    <div id="sidebarSubcat" class="header__sidebar__subcategories">
    </div>

    <div id="subOverlay" class="sub__Overlay"></div>

    <input type="search" name="search" class='search'>
    <nav>
        <ul class="references">
            <li><a href="<?php if (isset($_SESSION['enter'])) {
                                echo '/pages/cart.php';
                            } else {
                                echo '/registration/registration.php';
                            } ?>" class="img"><img src="/img/icons/nav/basket.svg" alt="Busket icon"></a></li>
            <li><a href="<?php if (isset($_SESSION['enter'])) {
                                echo '/pages/orders.php';
                            } else {
                                echo '/registration/registration.php';
                            } ?>" class="img"><img src="/img/icons/nav/orders.svg" alt="Orders icon"></a></li>
            <li><a href="" class="img"><img src="/img/icons/nav/favorites.svg" alt="Favorites icon"></a></li>
            <li><a href="<?php if (isset($_SESSION['enter']) && $_SESSION['enter']) {
                                echo '/pages/admin.php?name=Изменение%модулей';
                            } else if (isset($_SESSION['enter']) && !$_SESSION['enter']) {
                                echo '/pages/PA.php';
                            } else {
                                echo '/registration/registration.php';
                            } ?>" class="img"><img src="/img/icons/nav/PA.svg" alt="Personal account icon"> </a></li>
        </ul>
    </nav>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const menuButton = document.getElementById('menuButton');
    const sidebar = document.getElementById('sidebar');
    const sidebarSubcat = document.getElementById('sidebarSubcat');
    const closeButton = document.getElementById('closeButton');
    const overlay = document.getElementById('overlay');
    const subOverlay = document.getElementById('subOverlay');
    const subCategoryButton = document.getElementsByClassName('header__catalog__list__item__name__button');

    menuButton.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
    });
    Array.from(subCategoryButton).forEach(button => {
        button.addEventListener('click', () => {
            sidebarSubcat.classList.add('active');
            subOverlay.classList.add('active');
        })
    })
    closeButton.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
    subOverlay.addEventListener('click', () => {
        sidebarSubcat.classList.remove('active');
        subOverlay.classList.remove('active');
    });

    document.addEventListener('click', (event) => {

        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickInsideOverlay = overlay.contains(event.target);
        const isClickOnButton = menuButton.contains(event.target);

        const isClickInsideSubSidebar = sidebarSubcat.contains(event.target);
        const isClickInsideSubOverlay = subOverlay.contains(event.target);

        if (isClickInsideSubSidebar && isClickInsideSubOverlay) {
            sidebarSubcat.classList.remove('active');
            subOverlay.classList.remove('active');
        }

        if (isClickInsideSidebar && isClickOnButton) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });

    function setCategoryValue(categoryId) {
    $.ajax({
        url: '/functions/loadSubcategories.php',
        type: 'POST',
        data: { value: categoryId },
        success: function(response) {
            document.getElementById('sidebarSubcat').innerHTML = response;
            document.getElementById('sidebarSubcat').classList.add('active');
            document.getElementById('subOverlay').classList.add('active');
        }
    });
}


</script>