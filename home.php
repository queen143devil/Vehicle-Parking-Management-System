<?php include 'db_connect.php'; ?>

<style>
    /* Dashboard Cards */
    .summary-card {
        border-radius: 15px;
        padding: 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-5px);
    }
    .summary-card .icon-bg {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 3.5rem;
        opacity: 0.15;
    }
    /* Table */
    .dashboard-table th {
        background: #f1f1f1;
        font-weight: 600;
        text-transform: uppercase;
    }
    .dashboard-table td {
        vertical-align: middle;
    }
    /* Welcome Section */
    .welcome-card {
        border-radius: 15px;
        padding: 2rem;
        background: linear-gradient(135deg, #4A90E2, #9013FE);
        color: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    /* Now Serving */
    .now-serving-card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .now-serving-card .card-header {
        font-size: 1.5rem;
    }
    .btn-serve {
        font-size: 1.2rem;
        padding: 0.75rem 1.5rem;
    }
</style>

<div class="container-fluid mt-4">

    <!-- ======= Summary Cards ======= -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="summary-card bg-warning">
                <div class="icon-bg"><i class="fa fa-car"></i></div>
                <h3 class="fw-bold">
                    <?php echo $conn->query("SELECT * FROM parked_list where status = 1")->num_rows; ?>
                </h3>
                <p class="fw-semibold">Total Parked Vehicles</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="summary-card bg-success">
                <div class="icon-bg"><i class="fa fa-car"></i></div>
                <h3 class="fw-bold">
                    <?php echo $conn->query("SELECT * FROM parked_list where status = 2")->num_rows; ?>
                </h3>
                <p class="fw-semibold">Total Checked-Out Vehicles</p>
            </div>
        </div>
    </div>

    <!-- ======= Welcome Section ======= -->
    <div class="welcome-card text-center">
        <h4>Welcome back, <b><?php echo $_SESSION['login_name']; ?>!</b></h4>
        <p>Here is the current parking overview:</p>
    </div>

    <!-- ======= Parking Table ======= -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <table class="table table-bordered dashboard-table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Parking Area</th>
                                <th>Available Slots</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cat = $conn->query("SELECT * FROM category order by name asc");
                            while($crow = $cat->fetch_assoc()):
                            ?>
                                <tr class="table-secondary text-center">
                                    <th colspan="2"><?php echo $crow['name']; ?></th>
                                </tr>
                                <?php
                                $location = $conn->query("SELECT * FROM parking_locations where category_id = '".$crow['id']."'  order by location asc");
                                while($lrow= $location->fetch_assoc()):
                                    $in = $conn->query("SELECT * FROM parked_list where status = 1 and location_id = ".$lrow['id'])->num_rows;
                                    $available = $lrow['capacity'] - $in;
                                ?>
                                <tr class="text-center">
                                    <td><?php echo $lrow['location'] ?></td>
                                    <td>
                                        <span class="badge rounded-pill <?php echo $available>0?'bg-success':'bg-danger'; ?> px-3 py-2">
                                            <?php echo $available ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= Now Serving (Staff Only) ======= -->
    <?php if($_SESSION['login_type'] == 2): ?>
    <div class="row justify-content-center mb-5">
        <div class="col-md-3 text-center mb-3">
            <button class="btn btn-primary btn-serve w-100" onclick="queueNow()">
                <i class="fa fa-forward"></i> Next Serve
            </button>
        </div>
        <div class="col-md-5">
            <div class="card now-serving-card">
                <div class="card-header bg-primary text-white text-center">
                    <b>Now Serving</b>
                </div>
                <div class="card-body text-center">
                    <h4 id="sname"></h4>
                    <hr>
                    <h3 id="squeue"></h3>
                    <hr>
                    <h5 id="window"></h5>
                </div>
            </div>
        </div>
    </div>

    <script>
        function queueNow(){
            $.ajax({
                url:'ajax.php?action=update_queue',
                success:function(resp){
                    resp = JSON.parse(resp)
                    $('#sname').html(resp.data.name)
                    $('#squeue').html(resp.data.queue_no)
                    $('#window').html(resp.data.wname)
                }
            })
        }
    </script>
    <?php endif; ?>

</div>
