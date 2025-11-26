<?php include 'db_connect.php'; ?>

<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch parked vehicle details
$qry = $conn->query("SELECT p.*, c.name as cname, c.rate, l.location as lname 
                     FROM parked_list p 
                     INNER JOIN category c ON c.id = p.category_id 
                     INNER JOIN parking_locations l ON l.id = p.location_id 
                     WHERE p.id = $id");

if($qry->num_rows == 0){
    echo "<div class='alert alert-danger'>Invalid Parking ID.</div>";
    return;
}

$data = $qry->fetch_assoc();
extract($data);

// Fetch check-in and check-out
$in_qry = $conn->query("SELECT * FROM parking_movement WHERE pl_id = $id AND status = 1 ORDER BY created_timestamp ASC LIMIT 1");
$out_qry = $conn->query("SELECT * FROM parking_movement WHERE pl_id = $id AND status = 2 ORDER BY created_timestamp ASC LIMIT 1");

$in_timestamp = $in_qry->num_rows ? date("M d, Y h:i A", strtotime($in_qry->fetch_assoc()['created_timestamp'])) : 'N/A';
$out_timestamp = $out_qry->num_rows ? date("M d, Y h:i A", strtotime($out_qry->fetch_assoc()['created_timestamp'])) : 'N/A';

// Calculate time difference if checked out
if($status == 2 && $in_timestamp != 'N/A' && $out_timestamp != 'N/A'){
    $seconds = strtotime($out_timestamp) - strtotime($in_timestamp);
    $hours_decimal = $seconds / 3600;
    $hours_int = floor($hours_decimal);
    $minutes = round(($hours_decimal - $hours_int) * 60);
    $calc = sprintf("%02d:%02d", $hours_int, $minutes);
} else {
    $hours_decimal = 0;
    $calc = "N/A";
}
?>

<div class="container-fluid py-3">
    <div class="card shadow rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Parking Ref No.: <span class="text-primary"><?= $ref_no ?></span></h5>
            <div>
                <a href="index.php?page=manage_park&id=<?= $id ?>" class="btn btn-sm btn-primary me-2">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <button id="btn_print" class="btn btn-sm btn-success">
                    <i class="fa fa-print"></i> Print Ticket
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Parked Area:</b> <?= $lname ?></li>
                        <li class="list-group-item"><b>Category:</b> <?= $cname ?></li>
                        <li class="list-group-item"><b>Owner:</b> <?= $owner ?></li>
                        <li class="list-group-item"><b>Registration No.:</b> <?= $vehicle_registration ?></li>
                        <li class="list-group-item"><b>Brand:</b> <?= $vehicle_brand ?></li>
                        <li class="list-group-item"><b>Description:</b> <?= !empty($vehicle_description) ? $vehicle_description : 'No details entered' ?></li>
                        <li class="list-group-item"><b>Parked-In:</b> <?= $in_timestamp ?></li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <?php if($status == 1): ?>
                        <button type="button" id="checkout_btn" class="btn btn-primary w-100 mb-3">
                            <i class="fa fa-calculator"></i> Compute Checkout
                        </button>
                        <div id="check_details"></div>
                    <?php else: ?>
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th colspan="2" class="text-center">
                                        Checkout Details
                                        <button id="btn_print_receipt" class="btn btn-sm btn-success float-end">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><th>Check-In</th><td class="text-end"><?= $in_timestamp ?></td></tr>
                                <tr><th>Check-Out</th><td class="text-end"><?= $out_timestamp ?></td></tr>
                                <tr><th>Time Parked (HH:MM)</th><td class="text-end"><?= $calc ?> (<?= number_format($hours_decimal, 2) ?> hrs)</td></tr>
                                <tr><th>Hourly Rate</th><td class="text-end">₹ <?= number_format($rate,2) ?></td></tr>
                                <tr><th>Amount Due</th><td class="text-end">₹ <?= number_format($rate * $hours_decimal,2) ?></td></tr>
                                <tr><th>Amount Tendered</th><td class="text-end">₹ <?= number_format($amount_tendered,2) ?></td></tr>
                                <tr><th>Change</th><td class="text-end">₹ <?= number_format($amount_change,2) ?></td></tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#btn_print').click(function(){
    let nw = window.open("print_receipt.php?id=<?= $id ?>","_blank","height=500,width=800");
    nw.print();
    setTimeout(() => nw.close(), 500);
});

$('#btn_print_receipt').click(function(){
    let nw = window.open("print_checkout_receipt.php?id=<?= $id ?>","_blank","height=500,width=800");
    nw.print();
    setTimeout(() => nw.close(), 500);
});

$('#checkout_btn').click(function(){
    start_load();
    $.ajax({
        url: 'get_check_out.php?id=<?= $id ?>',
        success: function(resp){
            if(resp){
                $('#check_details').html(resp);
                end_load();
            }
        }
    });
});
</script>
