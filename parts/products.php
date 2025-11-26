
<div>
<a href='?name=Новый%20товар' class='newProduct'>Новый товар</a>

</div>
<div class="recomendation__cards marginFor" id="productsContainer">
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

$(document).ready(function () {
    function loadProducts() {
        $.get("/functions/loadProducts.php", function (data) {
            $("#productsContainer").html(data);
        });
    }

    loadProducts();

    $("#productsContainer").on("click", ".deleteProduct", function () {
        const productId = $(this).data("id");

        if (confirm("Вы уверены, что хотите удалить товар?")) {
            $.post("/functions/deleteProduct.php", { id: productId }, function (response) {
                if (response.trim() === 'success') {
                    $(`.recomendation__card[data-id='${productId}']`).fadeOut(300, function () {
                        $(this).remove();
                    });
                } else {
                    alert("Ошибка удаления.");
                }
            });
        }
    });
});
</script>
