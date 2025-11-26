<?php include 'db_connect.php' ?>
<?php
date_default_timezone_set('Asia/Manila');

$qry = $conn->query("SELECT p.*,c.name as cname,c.rate,l.location as lname 
    FROM parked_list p 
    INNER JOIN category c ON c.id = p.category_id 
    INNER JOIN parking_locations l ON l.id = p.location_id 
    WHERE p.id = ".$_GET['id']);

foreach($qry->fetch_assoc() as $k => $v){
    $$k = $v;
}

$in_qry = $conn->query("SELECT * FROM parking_movement 
        WHERE pl_id='".$_GET['id']."' AND status=1");

$in_timstamp = $in_qry->num_rows > 0 
    ? date("Y-m-d H:i", strtotime($in_qry->fetch_array()['created_timestamp'])) 
    : 'N/A';

$now = date('Y-m-d H:i');

$ocalc = abs(strtotime($now) - strtotime($in_timstamp));
$ocalc = ($ocalc / (60*60));

$c = explode('.', $ocalc);
$calc = $c[0];

if(isset($c[1])){
    $c[1] = floor(60 * ('.'.$c[1]));
    $calc = $c[1] >= 60 ? ($calc + $c[1]).':00' : $calc.':'.$c[1]; 
}
?>

<style>
    .receipt-box {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        border: 1px solid #e8e8e8;
    }
    .receipt-table th {
        width: 50%;
        background: #f8f9fa;
        font-weight: 600;
        padding: 12px;
    }
    .receipt-table td {
        padding: 12px;
        font-size: 15px;
    }
    .highlight {
        background: #fff3cd !important;
        font-weight: bold;
        color: #b37a00;
    }
</style>

<form action="" id="checkout_frm">
    <div class="receipt-box mt-3">

        <h4 class="text-center mb-4"><b>Vehicle Checkout Summary</b></h4>

        <table class="table receipt-table">
            <tr>
                <th>Check-In Time</th>
                <td class="text-right"><?php echo $in_timstamp ?></td>
            </tr>

            <tr>
                <th>Check-Out Time</th>
                <td class="text-right"><?php echo $now ?></td>
            </tr>

            <tr>
                <th>Total Time</th>
                <td class="text-right">
                    <b><?php echo $calc ?> hrs</b> (<?php echo number_format($ocalc,2) ?>)
                </td>
            </tr>

            <tr>
                <th>Rate Per Hour</th>
                <td class="text-right"><?php echo number_format($rate,2) ?></td>
            </tr>

            <tr class="highlight">
                <th>Amount Due</th>
                <td class="text-right"><?php echo number_format($rate * $ocalc,2) ?></td>
            </tr>

            <tr>
                <th>Amount Tendered</th>
                <td>
                    <input type="hidden" name="pl_id" value="<?php echo $id ?>">
                    <input type="hidden" name="created_timestamp" value="<?php echo $now ?>">
                    <input type="hidden" name="amount_due" value="<?php echo ($rate * $ocalc) ?>">

                    <input type="number" name="amount_tendered" step="any" class="form-control text-right"
                           placeholder="Enter tendered amount">
                </td>
            </tr>

            <tr class="highlight">
                <th>Change</th>
                <td>
                    <input type="number" name="amount_change" readonly step="any" 
                           class="form-control text-right bg-light">
                </td>
            </tr>
        </table>

        <div class="text-right mt-3">
            <button class="btn btn-primary btn-lg px-4">
                <i class="fa fa-arrow-alt-circle-right"></i> Proceed to Checkout
            </button>
        </div>
    </div>
</form>

<script>
    // Auto-calc change
    $('[name="amount_tendered"]').on('input', function(){
        var tendered = parseFloat($(this).val());
        var due = parseFloat($('[name="amount_due"]').val());
        var change = tendered - due;

        if(!isNaN(change)){
            $('[name="amount_change"]').val(change.toFixed(2));
        }
    });

    // Checkout request
    $('#checkout_frm').submit(function(e){
        e.preventDefault();
        start_load();

        $.ajax({
            url:'ajax.php?action=checkout_vehicle',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){
                if(resp == 1){
                    alert_toast("Checkout Successful","success");
                    var nw = window.open("print_checkout_receipt.php?id=<?php echo $_GET['id'] ?>",
                                         "_blank","height=500,width=800");
                    nw.print();
                    setTimeout(function(){
                        nw.close();
                        location.reload();
                    },500);
                }
            }
        })
    });
</script>
