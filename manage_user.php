<?php 
include('db_connect.php');

$meta = [];
if (isset($_GET['id'])) {
    $user = $conn->query("SELECT * FROM users WHERE id =".$_GET['id']);
    foreach ($user->fetch_array() as $k => $v) {
        $meta[$k] = $v;
    }
}
?>

<div class="container-fluid mt-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <b><?= isset($meta['id']) ? "Edit User" : "Add New User" ?></b>
        </div>

        <div class="card-body">
            <div id="msg"></div>

            <form id="manage-user">
                <input type="hidden" name="id" value="<?= isset($meta['id']) ? $meta['id'] : '' ?>">

                <!-- Full Name -->
                <div class="form-group mb-3">
                    <label for="name" class="fw-bold">Full Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control" 
                           placeholder="Enter full name"
                           value="<?= isset($meta['name']) ? $meta['name'] : '' ?>" 
                           required>
                </div>

                <!-- Username -->
                <div class="form-group mb-3">
                    <label for="username" class="fw-bold">Username</label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           class="form-control"
                           placeholder="Unique username"
                           value="<?= isset($meta['username']) ? $meta['username'] : '' ?>" 
                           required autocomplete="off">
                </div>

                <!-- Password -->
                <div class="form-group mb-3">
                    <label for="password" class="fw-bold">Password</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control"
                           placeholder="<?= isset($meta['id']) ? 'Leave blank to keep existing password' : 'Enter password' ?>"
                           autocomplete="off">

                    <?php if (isset($meta['id'])): ?>
                        <small class="text-muted"><i>Leave blank if you do not want to change the password.</i></small>
                    <?php endif; ?>
                </div>

                <!-- User Type -->
                <div class="form-group mb-3">
                    <label for="type" class="fw-bold">User Type</label>
                    <select name="type" id="type" class="custom-select">
                        <option value="2" <?= isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Staff</option>
                        <option value="1" <?= isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <!-- Save Button -->
                <div class="form-group mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Select2 if needed
    $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
    });

    // Submit User Form
    $('#manage-user').submit(function(e) {
        e.preventDefault();
        start_load();

        $.ajax({
            url: 'ajax.php?action=save_user',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("User data saved successfully!", "success");
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    $('#msg').html('<div class="alert alert-danger">Username already exists.</div>');
                    end_load();
                }
            }
        });
    });
</script>
