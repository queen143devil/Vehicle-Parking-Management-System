<?php include 'db_connect.php'; ?>

<div class="container-fluid mt-4">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Check-In / Check-Out List</h4>
            </div>

            <div class="card-body">
                <table class="table table-hover table-striped" id="parkingTable">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Date</th>
                            <th>Reference No.</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM parked_list ORDER BY id DESC");
                        while ($row = $qry->fetch_assoc()):
                        ?>  
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>

                            <td><?php echo date('M d, Y', strtotime($row['date_created'])); ?></td>

                            <td class="font-weight-bold text-primary">
                                <?php echo $row['ref_no']; ?>
                            </td>

                            <td><?php echo $row['owner']; ?></td>

                            <td>
                                <?php if ($row['status'] == 1): ?>
                                    <span class="badge badge-warning px-3 py-2">Checked-In</span>
                                <?php else: ?>
                                    <span class="badge badge-success px-3 py-2">Checked-Out</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="index.php?page=view_parked_details&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i> View
                                </a>

                                <button class="btn btn-sm btn-outline-danger delete_park" 
                                        data-id="<?php echo $row['id']; ?>">
                                    <i class="fa fa-trash"></i> Delete
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

<script>
    $('#parkingTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "responsive": true
    });

    $('.delete_park').click(function() {
        _conf("Are you sure you want to delete this record?", "delete_park", [$(this).data('id')]);
    });

    function delete_park(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_vehicle',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Record deleted successfully", 'success');
                    setTimeout(() => location.reload(), 1200);
                }
            }
        });
    }
</script>
