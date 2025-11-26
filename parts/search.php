<form method="post" id="filterForm" class='spreadSearch'>
    <input type="text" name="search" placeholder="Фамилия">
    <div class="check">
        <label><input type="checkbox" name="status[]" value="ordered"> Новый</label>
        <label><input type="checkbox" name="status[]" value="confirmed"> Подтвержден</label>
        <label><input type="checkbox" name="status[]" value="declined"> Отменен</label>
    </div>

</form>

<div class="recomendation__cards marginFor" id="productsContainer">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadOrders() {
        $.ajax({
            url: '/functions/filterOrders.php',
            method: 'POST',
            data: $('#filterForm').serialize(),
            success: function(data) {
                $('#productsContainer').html(data);
            }
        });
    }

    $(document).ready(function() {
        loadOrders();
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            loadOrders();
        });
        $('#filterForm input[type="checkbox"], #filterForm input[name="search"]').on('input change', function() {
            loadOrders();
        });
    });
</script>