<?php include 'db_connect.php'; ?>
<?php
$qry = $conn->query("
    SELECT p.*, c.name AS cname, l.location AS lname 
    FROM parked_list p 
    INNER JOIN category c ON c.id = p.category_id 
    INNER JOIN parking_locations l ON l.id = p.location_id 
    WHERE p.id = ".$_GET['id']
);

foreach($qry->fetch_assoc() as $k => $v){
    $$k = $v;
}

$in_qry = $conn->query("SELECT * FROM parking_movement WHERE pl_id = $id AND status = 1");
$in_timestamp = $in_qry->num_rows > 0 
    ? date("M d, Y h:i A", strtotime($in_qry->fetch_array()['created_timestamp'])) 
    : 'N/A';

$out_qry = $conn->query("SELECT * FROM parking_movement WHERE pl_id = $id AND status = 2");
$out_timestamp = $out_qry->num_rows > 0 
    ? date("M d, Y h:i A", strtotime($out_qry->fetch_array()['created_timestamp'])) 
    : 'N/A';
?>

<style>
    .ticket-card {
        max-width: 650px;
        margin: auto;
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #ddd;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .ticket-title {
        font-size: 26px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 15px;
        text-transform: uppercase;
    }
    .ticket-line {
        border-top: 2px dashed #999;
        margin: 15px 0;
    }
    .ticket-info p {
        font-size: 15px;
        margin: 6px 0;
    }
    .ticket-info b {
        color: #333;
    }
    .info-label {
        width: 230px;
        display: inline-block;
        color: #555;
    }
</style>

<div class="ticket-card">

    <div class="ticket-title">Parking Ticket</div>
    <div class="ticket-line"></div>

    <div class="ticket-info">

        <p><span class="info-label"><b>Parking Reference No.:</b></span> 
           <?php echo $ref_no; ?></p>

        <p><span class="info-label">Vehicle Parked Area:</span> 
           <b><?php echo $lname; ?></b></p>

        <p><span class="info-label">Vehicle Category:</span> 
           <b><?php echo $cname; ?></b></p>

        <p><span class="info-label">Owner:</span> 
           <b><?php echo $owner; ?></b></p>

        <p><span class="info-label">Registration No.:</span> 
           <b><?php echo $vehicle_registration; ?></b></p>

        <p><span class="info-label">Vehicle Brand:</span> 
           <b><?php echo $vehicle_brand; ?></b></p>

        <p><span class="info-label">Description:</span> 
           <b><?php echo !empty($vehicle_description) ? $vehicle_description : "No details entered"; ?></b></p>

        <p><span class="info-label">Parked-In Timestamp:</span> 
           <b><?php echo $in_timestamp; ?></b></p>

    </div>

</div>
