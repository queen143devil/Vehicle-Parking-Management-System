<?php include 'db_connect.php'; ?>

<?php
// Load existing data when editing
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM parking_locations WHERE id = " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>

<div class="container-fluid py-2">

    <!-- Validation Message Placeholder -->
    <div id="msg"></div>

    <form id="manage-location">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">

        <!-- Category -->
        <div class="form-group mb-3">
            <label class="control-label">Vehicle Category</label>
            <select name="category_id" id="category_id" class="custom-select select2" required>
                <option value="">Select category</option>

                <?php
                $category = $conn->query("SELECT * FROM category ORDER BY name ASC");
                while ($row = $category->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>"
                        <?= (isset($category_id) && $category_id == $row['id']) ? 'selected' : '' ?>>
                        <?= $row['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Area Location -->
        <div class="form-group mb-3">
            <label class="control-label">Area Location</label>
            <input type="text" class="form-control" name="location"
                value="<?= isset($location) ? $location : '' ?>"
                placeholder="Enter area name (Example: Zone A, Slot C2)" required>
        </div>

        <!-- Capacity -->
        <div class="form-group mb-3">
            <label class="control-label">Area Capacity</label>
            <input type="number" class="form-control text-right" name="capacity"
                step="1" min="1"
                value="<?= isset($capacity) ? $capacity : '' ?>"
                placeholder="Enter total capacity (Example: 40)" required>
        </div>

    </form>
</div>

<script>
    // Submit Form AJAX
    $('#manage-location').submit(function (e) {
        e.preventDefault();
        start_load();
        $('#msg').html("");

        $.ajax({
            url: 'ajax.php?action=save_location',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,

            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Location saved successfully!", 'success');
                    setTimeout(() => location.reload(), 1500);
                }
                else if (resp == 2) {
                    $('#msg').html(`
                        <div class="alert alert-danger py-2">
                            Location name already exists.
                        </div>
                    `);
                    end_load();
                }
            }
        });
    });
</script>
