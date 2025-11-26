<?php include('db_connect.php');?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Vehicle Categories</h3>
        <button class="btn btn-primary" id="new_category">
            <i class="fa fa-plus"></i> Add Category
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="search_category" class="form-control" placeholder="Search categories...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Category</th>
                            <th>Rate per Hour</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="category_table">
                        <?php 
                        $i = 1;
                        $types = $conn->query("SELECT * FROM category ORDER BY id ASC");
                        while($row = $types->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++ ?></td>
                            <td><b><?php echo $row['name'] ?></b></td>
                            <td><b><?php echo number_format($row['rate'], 2) ?></b></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary edit_category" data-id="<?php echo $row['id'] ?>">Edit</button>
                                <button class="btn btn-sm btn-outline-danger delete_category" data-id="<?php echo $row['id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f1f3f5;
    }
    .card {
        border-radius: 12px;
    }
    #search_category {
        max-width: 300px;
    }
</style>

<script>
    // Modal functionality
    $('#new_category').click(function(){
        uni_modal("New Vehicle Category","manage_category.php")
    });

    $('.edit_category').click(function(){
        uni_modal("Edit Vehicle Category","manage_category.php?id="+$(this).attr('data-id'))
    });

    $('.delete_category').click(function(){
        _conf("Are you sure to delete this category?","delete_category",[$(this).attr('data-id')])
    });

    function delete_category($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_category',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully deleted",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }

    // Search filter
    $('#search_category').on('keyup', function(){
        let value = $(this).val().toLowerCase();
        $('#category_table tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
