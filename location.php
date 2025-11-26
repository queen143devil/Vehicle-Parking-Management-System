<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        
        <!-- Header -->
        <div class="row mb-4 mt-4">
            <div class="col-md-12 text-right">
                <button id="new_location" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Location
                </button>
            </div>
        </div>

        <!-- Table Section -->
        <div class="row">
            <div class="col-md-12">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Vehicle Parking Locations</h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Area Capacity</th>
                                    <th width="20%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                $i = 1;
                                $locations = $conn->query("
                                    SELECT l.*, c.name AS cname 
                                    FROM parking_locations l 
                                    INNER JOIN category c ON c.id = l.category_id 
                                    ORDER BY l.id ASC");

                                while($row = $locations->fetch_assoc()):
                                ?>
                                
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>

                                    <td><b><?php echo $row['cname']; ?></b></td>

                                    <td><b><?php echo $row['location']; ?></b></td>

                                    <td><b><?php echo $row['capacity']; ?></b></td>

                                    <td class="text-center">
                                        <button 
                                            class="btn btn-sm btn-outline-primary edit_location" 
                                            data-id="<?php echo $row['id']; ?>">
                                            Edit
                                        </button>

                                        <button 
                                            class="btn btn-sm btn-outline-danger delete_location" 
                                            data-id="<?php echo $row['id']; ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                <?php endwhile; ?>
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: 0;
    }
    img {
        max-width: 100px;
        max-height: 150px;
    }
</style>

<script>
    // Open New Location Modal
    $('#new_location').click(function () {
        uni_modal("New Vehicle Location", "manage_location.php");
    });

    // Edit Location
    $('.edit_location').click(function () {
        let id = $(this).data('id');
        uni_modal("Edit Vehicle Location", "manage_location.php?id=" + id);
    });

    // Ask for Delete Confirmation
    $('.delete_location').click(function () {
        let id = $(this).data('id');
        _conf("Are you sure you want to delete this location?", "delete_location", [id]);
    });

    // Delete Location AJAX
    function delete_location(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_location',
            method: 'POST',
            data: { id: id },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Location deleted successfully", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1200);
                }
            }
        });
    }
</script>
