<h3 class='titleOrderList'><b>Все категории: </b></h3>
<div class="categories__align">
    <div id='categories__container'></div>
    <div class="categories__new">
        <button id='openAdding'>Добавить категорию</button>
        <form action="" method="post" class='adding__category' id='adding__category' enctype="multipart/form-data">
            <label for="categoryNew"><b>Введите название новой категории: </b></label>
            <input required type="text" name="categoryNew" id="categoryNew">
            <label for="categoryNewPhoto"><b>Введите фото новой категории: </b></label>
            <input type="file" required accept="image/png, image/jpeg" name="categoryNewPhoto" id="categoryNewPhoto" value="Выбрать фото"><br>
            <img id="previewImage" />
            <input type="submit" name="submit" value="Добавить">
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    function loadCategories() {
        $.ajax({
            url: "/functions/printCategories.php",
            method: "GET",
            success: function(response) {
                $('#categories__container').html(response);
            },
            error: function(xhr) {
                console.error("Ошибка при загрузке категорий:", xhr.responseText);
            }
        });
    }

    $(document).ready(function() {
        loadCategories();

        $('#categories__container').on('dblclick', '.editable', function() {
            const div = $(this);
            div.attr('contenteditable', true).focus();
        });

        $('#categories__container').on('blur', '.editable', function() {
            const div = $(this);
            div.attr('contenteditable', false);

            const id = div.data('id');
            const newValue = div.text().trim();

            $.ajax({
                url: '/functions/updateField.php',
                method: 'POST',
                data: {
                    id: id,
                    value: newValue
                },
                success: function(response) {
                    console.log("Обновлено:", response);
                },
                error: function(xhr, status, error) {
                    console.error("Ошибка:", error);
                }
            });
        });


        const openAdding = document.getElementById('openAdding');
        const addingCategory = document.getElementById('adding__category');
        openAdding.addEventListener('click', () => {
            addingCategory.classList.add('adding__category__active');
        });

        document.getElementById('categoryNewPhoto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('previewImage');
                img.src = e.target.result;
                img.style.display = 'block';
            }
            reader.readAsDataURL(file);
        });

        $('#adding__category').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '/functions/categoryAdding.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    const img = document.getElementById('previewImage');
                    const input = document.getElementById('categoryNew');
                    img.src = "";
                    input.value = "";
                    loadCategories();
                },
                error: function(xhr, status, error) {
                    console.error("Ошибка:", error);
                }
            });
        });
    });

    function deleteValue(button) {
        const li = button.closest('.category__item');
        const categoryId = li.getAttribute('data-id');

        $.ajax({
            url: '/functions/deleteCategoryFromDB.php',
            type: 'POST',
            data: {
                category_id: categoryId
            },
            success: function(response) {
                li.remove();
            },
            error: function(xhr, status, error) {
                console.error("Ошибка при удалении:", error);
            }
        });
    }
</script>