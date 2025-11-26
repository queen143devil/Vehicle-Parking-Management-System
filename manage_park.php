<?php include 'db_connect.php'; ?>

<?php
// Load existing data if editing
if (isset($_GET['id'])) {
    $qry = $conn->query("
        SELECT p.*, c.name AS cname, l.location AS lname
        FROM parked_list p 
        INNER JOIN category c ON c.id = p.category_id 
        INNER JOIN parking_locations l ON l.id = p.location_id 
        WHERE p.id = " . $_GET['id']
    );

    foreach ($qry->fetch_assoc() as $k => $v) {
        $$k = $v;
    }
}
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <b><?= isset($id) ? "Manage Vehicle" : "Add New Vehicle" ?></b>
        </div>

        <div class="card-body">
            <form id="manage-vehicle">
                <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">

                <!-- Category + Area -->
                <div class="row form-group">
                    <div class="col-md-5 mb-3">
                        <label class="control-label">Vehicle Category</label>
                        <select name="category_id" id="category_id" class="custom-select select2" required>
                            <option value="">Select category</option>
                            <?php
                                $category = $conn->query("SELECT * FROM category ORDER BY name ASC");
                                while ($row = $category->fetch_assoc()):
                            ?>
                                <option value="<?= $row['id'] ?>"
                                    <?= (isset($category_id) && $category_id == $row['id']) ? 'selected' : '' ?>
                                    data-rate="<?= $row['rate'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="control-label">Parking Area</label>
                        <select name="location_id" id="location_id" class="custom-select select2" required>
                            <option value="">Select area</option>
                            <?php
                                $areas = $conn->query("SELECT * FROM parking_locations ORDER BY location ASC");
                                while ($row = $areas->fetch_assoc()):
                            ?>
                                <option value="<?= $row['id'] ?>"
                                    data-cid="<?= $row['category_id'] ?>"
                                    <?= (isset($location_id) && $location_id == $row['id']) ? 'selected' : '' ?>>
                                    <?= $row['location'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Vehicle Name + Registration -->
                <div class="row form-group">
                    <div class="col-md-5 mb-3">
                        <label class="control-label">Vehicle Name</label>
                        <input type="text" class="form-control"
                               name="vehicle_brand"
                               placeholder="Example: Honda Activa, Maruti Swift"
                               value="<?= isset($vehicle_brand) ? $vehicle_brand : '' ?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="control-label">Vehicle Registration No.</label>
                        <input type="text" class="form-control"
                               name="vehicle_registration"
                               placeholder="Example: MH12 AB 3456"
                               value="<?= isset($vehicle_registration) ? $vehicle_registration : '' ?>">
                    </div>
                </div>

                <!-- Owner -->
                <div class="row form-group">
                    <div class="col-md-5 mb-3">
                        <label class="control-label">Owner Name</label>
                        <input type="text" class="form-control"
                               name="owner"
                               placeholder="Enter owner full name"
                               value="<?= isset($owner) ? $owner : '' ?>">
                    </div>
                </div>

                <!-- Description -->
                <div class="row form-group">
                    <div class="col-md-10 mb-3">
                        <label class="control-label">Vehicle Description</label>
                        <textarea class="form-control" name="vehicle_description" rows="2"
                                  placeholder="Additional notes (color, model, issues, etc)"><?= isset($vehicle_description) ? $vehicle_description : '' ?></textarea>
                    </div>
                </div>

                <hr>

                <!-- Submit -->
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-lg float-right col-md-3">
                            Save
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Auto Filter Parking Locations based on Category
    $('#category_id').change(function () {
        let id = $(this).val();
        let rate = $(this).find('option[value="' + id + '"]').attr('data-rate');
        let parent = $(this).parent();

        parent.find('small').remove();

        parent.append(
            `<small class="text-info"><b><i>Rate: ${rate}</i></b></small>`
        );

        $('#location_id option').attr('disabled', true);
        $('#location_id option[data-cid="' + id + '"]').attr('disabled', false);

        $('#location_id').val('').trigger('change');
    });

    // Form Submit
    $('#manage-vehicle').submit(function (e) {
        e.preventDefault();
        start_load();

        $.ajax({
            url: "ajax.php?action=save_vehicle",
            method: "POST",
            data: $(this).serialize(),
            success: function (resp) {
                resp = JSON.parse(resp);

                if (resp.status == 1) {
                    alert_toast("Data successfully saved.", "success");

                    // New entry → print
                    if ('<?= !isset($id) ?>' == 1) {
                        let popup = window.open("print_receipt.php?id=" + resp.id,
                            "_blank", "width=800,height=500");

                        popup.print();

                        setTimeout(() => {
                            popup.close();
                            location.href = "index.php?page=view_parked_details&id=" + resp.id;
                        }, 600);
                    }

                    // Update
                    else {
                        setTimeout(() => {
                            location.href = "index.php?page=view_parked_details&id=" + resp.id;
                        }, 1000);
                    }
                }

                else {
                    alert_toast("An error occurred.", "danger");
                    end_load();
                }
            }
        });
    });
</script>
