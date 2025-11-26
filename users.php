<div class="container-fluid mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary"><i class="fa fa-users"></i> Manage Users</h3>
        <button class="btn btn-success btn-sm" id="new_user">
            <i class="fa fa-plus"></i> New User
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped" id="users_table">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'db_connect.php';
                        $users = $conn->query("SELECT * FROM users ORDER BY name ASC");
                        $i = 1;
                        while($row = $users->fetch_assoc()):
                        ?>
                        <tr class="align-middle text-center">
                            <td><?php echo $i++; ?></td>
                            <td class="text-start"><?php echo ucwords($row['name']); ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td>
                                <?php 
                                    echo $row['type'] == 1 ? '<span class="badge bg-primary">Admin</span>' : '<span class="badge bg-secondary">Staff</span>'; 
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary edit_user" data-id="<?php echo $row['id'] ?>">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete_user" data-id="<?php echo $row['id'] ?>">
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
$(document).ready(function(){
    // Initialize DataTable with search, pagination
    $('#users_table').DataTable({
        columnDefs: [
            { orderable: false, targets: [4] } // Disable sorting on actions
        ]
    });

    // Button actions
    $('#new_user').click(function(){
        uni_modal('New User','manage_user.php');
    });

    $('.edit_user').click(function(){
        uni_modal('Edit User','manage_user.php?id='+$(this).attr('data-id'));
    });

    $('.delete_user').click(function(){
        _conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')]);
    });
});

// Delete function
function delete_user(id){
    start_load();
    $.ajax({
        url:'ajax.php?action=delete_user',
        method:'POST',
        data:{id: id},
        success:function(resp){
            if(resp == 1){
                alert_toast("User successfully deleted",'success');
                setTimeout(function(){
                    location.reload();
                },1500);
            }
        }
    });
}
</script>
