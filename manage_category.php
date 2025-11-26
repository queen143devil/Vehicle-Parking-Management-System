<?php include 'db_connect.php'; ?>

<?php
// Load category data if editing
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM category WHERE id = " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>

<div class="container-fluid py-2">

    <!-- Validation Message -->
    <div id="msg"></div>

    <form id="manage-category">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">

        <div class="form-group mb-3">
            <label class="control-label">Vehicle Category</label>
            <input type="text" class="form-control" name="name" 
                   value="<?= isset($name) ? $name : '' ?>" required 
                   placeholder="Enter vehicle category (e.g. Car, Bike)">
        </div>

        <div class="form-group mb-3">
            <label class="control-label">Rate per Hour</label>
            <input type="number" class="form-control text-right" name="rate" 
                   step="any" value="<?= isset($rate) ? $rate : '' ?>" 
                   required placeholder="Enter hourly rate">
        </div>
    </form>
</div>

<script>
    $('#manage-category').submit(function (e) {
        e.preventDefault();
        start_load();
        $('#msg').html(''); // Clear previous messages

        $.ajax({
            url: 'ajax.php?action=save_category',
            method: 'POST',
            data: new FormData($(this)[0]),
            contentType: false,
            processData: false,
            cache: false,

            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Category saved successfully!", 'success');
                    setTimeout(() => location.reload(), 1500);
                }
                else if (resp == 2) {
                    $('#msg').html(`
                        <div class="alert alert-danger py-2">
                            Category name already exists.
                        </div>
                    `);
                    end_load();
                }
            }
        });
    });
</script>
