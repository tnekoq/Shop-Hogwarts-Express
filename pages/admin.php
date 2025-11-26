<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <?php include "../styles/order.php" ?>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <?php session_start();
    include "../parts/header.php";
    $title = "Изменение модулей";
    if (isset($_GET['name'])) {
        $title = str_replace("%", " ", $_GET['name']);
    }
    ?>
    <div class="admin__align">
        <?php require "../parts/sidebar_admin.php"; ?>
        <div class="admin__container">
            <h2 class='popular__title'><?php echo $title ?></h2>
            <div class="admin__content">
                <?php
                $pages = [
                    "Изменение модулей" => "../parts/modules.php",
                    "Категории" => "../parts/categories.php",
                    "Товары" => "../parts/products.php",
                    "Поиск" => "../parts/search.php",
                    "Новый товар" => "../parts/newProduct.php",
                    "Заказ" => "../parts/myOrder.php",
                    "Назад" => "../parts/search.php"
                ];
                $content = $pages[$title] ?? null;
                if (isset($_POST['editProduct']) && isset($_POST['productId'])) {
                    $productId = (int)$_POST['productId'];
                    require "../parts/editProductForm.php";
                } elseif ($content) {
                    require_once $content;
                }
                

                ?>

            </div>
        </div>

    </div>

    <?php include "../parts/footer.php"; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const container = document.getElementById('dragDrop');
        let draggedItem = null;

        container.addEventListener('dragstart', (e) => {
            if (e.target.classList.contains('dragDrop__item')) {
                draggedItem = e.target;
            }
        });

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            const target = e.target;
            if (target !== draggedItem && target.classList.contains('dragDrop__item')) {
                target.classList.add('drag-over');
            }
        });

        container.addEventListener('dragleave', (e) => {
            if (e.target.classList.contains('dragDrop__item')) {
                e.target.classList.remove('drag-over');
            }
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            const target = e.target;
            if (target !== draggedItem && target.classList.contains('dragDrop__item')) {
                target.classList.remove('drag-over');
                const children = Array.from(container.children);
                const draggedIndex = children.indexOf(draggedItem);
                const targetIndex = children.indexOf(target);

                if (draggedIndex < targetIndex) {
                    container.insertBefore(draggedItem, target.nextSibling);
                } else {
                    container.insertBefore(draggedItem, target);
                }
            }
        });
        $('#saveOrderBtn').on('click', function() {
            const order = [];
            $('.dragDrop__item').each(function() {
                order.push($(this).text().trim());
            });

            $.ajax({
                url: '/functions/saveModulesToDB.php',
                type: 'POST',
                data: {
                    order: order
                },
                success: function(response) {
                    alert("Порядок успешно сохранен!");
                },
                error: function(xhr, status, error) {
                    alert("Произошла ошибка при сохранении!");
                }
            });
        });
        
    </script>
</body>

</html>