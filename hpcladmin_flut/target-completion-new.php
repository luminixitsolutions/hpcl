<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Target-Complete";
$Page = "Target-Completion";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> | View Task List</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>

     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                

                <?php
                if ($_REQUEST["action"] == "delete") {
                    $id = $_REQUEST["id"];
                    $sql11 = "DELETE FROM tbl_assets WHERE id = '$id'";
                    $conn->query($sql11);
                ?>
                    <script type="text/javascript">
                        alert("Deleted Successfully!");
                        window.location.href = "view-assets.php";
                    </script>
                <?php } ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Target Completion
                        </h4>

                        <div class="card" style="padding: 10px;">

                            <div id="accordion2">
                                <div class="card mb-2">

                                    <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                        <div class="" style="padding:5px;">
                                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">



                                                    <div class="form-group col-md-4">
                                                        <label class="form-label"> Franchise<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="frid" id="frid" required>
                                                            <option selected value="all">All </option>
                                                            <?php
                                                            $sql1 = "SELECT * FROM tbl_users_bill WHERE Roll=5";
                                                            $row1 = getList($sql1);
                                                            foreach ($row1 as $result) {

                                                            ?>
                                                                <option <?php if ($_POST['frid'] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName'] . " (" . $result['Phone'] . ")"; ?></option>
                                                            <?php } ?>

                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>

<div class="form-group col-md-2">
                                            <label class="form-label">Zone </label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" onchange="getSubZone(this.value)">
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_zone WHERE Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["ZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                              
 <div class="form-group col-md-2">
                                            <label class="form-label">Sub Zone </label>
                                            <select class="form-control" id="SubZoneId" name="SubZoneId" >
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_sub_zone WHERE CatId='".$_POST["ZoneId"]."' AND Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["SubZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                                    <div class="form-group col-md-1">
                                                        <label class="form-label">Month</label>
                                                        <select class="form-control" style="width: 100%" name="month" id="month" required>
                                                            <option <?php if ($_POST['month'] == '01') { ?> selected <?php } ?> value="01">Jan</option>
                                                            <option <?php if ($_POST['month'] == '02') { ?> selected <?php } ?> value="02">Feb</option>
                                                            <option <?php if ($_POST['month'] == '03') { ?> selected <?php } ?> value="03">Mar</option>
                                                            <option <?php if ($_POST['month'] == '04') { ?> selected <?php } ?> value="04">Apr</option>
                                                            <option <?php if ($_POST['month'] == '05') { ?> selected <?php } ?> value="05">May</option>
                                                            <option <?php if ($_POST['month'] == '06') { ?> selected <?php } ?> value="06">Jun</option>
                                                            <option <?php if ($_POST['month'] == '07') { ?> selected <?php } ?> value="07">Jul</option>
                                                            <option <?php if ($_POST['month'] == '08') { ?> selected <?php } ?> value="08">Aug</option>
                                                            <option <?php if ($_POST['month'] == '09') { ?> selected <?php } ?> value="09">Sep</option>
                                                            <option <?php if ($_POST['month'] == '10') { ?> selected <?php } ?> value="10">Oct</option>
                                                            <option <?php if ($_POST['month'] == '11') { ?> selected <?php } ?> value="11">Nov</option>
                                                            <option <?php if ($_POST['month'] == '12') { ?> selected <?php } ?> value="12">Dec</option>
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                    <div class="form-group col-md-1">
                                                        <label class="form-label">Year</label>
                                                        <select class="form-control" style="width: 100%" name="year" id="year" required>
                                                            <option <?php if ($_POST['year'] == '2025') { ?> selected <?php } ?> value="2025">2025</option>
                                                            <option <?php if ($_POST['year'] == '2024') { ?> selected <?php } ?> value="2024">2024</option>
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <input type="hidden" name="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top: 30px;">
                                                        <label class="form-label">&nbsp;</label>
                                                        <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_POST['Search'])) { ?>
                                                        <div class="form-group col-md-1">
                                                            <label class="form-label">&nbsp;</label>
                                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($_POST['Search'])) { ?>
                                <?php
$month = $_POST['month'];
$year = $_POST['year'];
$zoneFilter = $_POST['ZoneId'] ?? '';
$subZoneFilter = $_POST['SubZoneId'] ?? '';
$fridFilter = $_POST['frid'] ?? '';

// Step 1: Fetch distinct dates
$sqlDates = "SELECT DISTINCT InvoiceDate 
             FROM (
                SELECT InvoiceDate FROM tbl_customer_invoice 
                INNER JOIN tbl_users_bill tu ON tbl_customer_invoice.frid = tu.id 
                WHERE MONTH(InvoiceDate) = '$month' AND YEAR(InvoiceDate) = '$year'
                
                UNION ALL
                
                SELECT InvoiceDate FROM tbl_customer_invoice_2025 
                INNER JOIN tbl_users_bill tu ON tbl_customer_invoice_2025.frid = tu.id 
                WHERE MONTH(InvoiceDate) = '$month' AND YEAR(InvoiceDate) = '$year'
             ) AS all_dates
             ORDER BY InvoiceDate ASC";
$dateResult = $conn->query($sqlDates);
$dates = [];
foreach ($dateResult as $row) {
    $dates[] = $row['InvoiceDate'];
}

// Step 2: Build WHERE conditions
$where = "WHERE 1 ";
if ($fridFilter && $fridFilter != 'all') {
    $where .= "AND tp.frid = '$fridFilter' ";
}
if ($zoneFilter && $zoneFilter != 'all') {
    $where .= "AND tu.ZoneId = '$zoneFilter' ";
}
if ($subZoneFilter && $subZoneFilter != 'all') {
    $where .= "AND tu.SubZoneId = '$subZoneFilter' ";
}
$where .= "AND MONTH(tp.InvoiceDate) = '$month' AND YEAR(tp.InvoiceDate) = '$year'";

// Step 3: Get amounts by franchise & date
$sqlData = "
    SELECT tp.FrId, tu.ShopName, tp.InvoiceDate, SUM(tp.NetAmount) as TotalAmount
    FROM tbl_customer_invoice tp
    INNER JOIN tbl_users_bill tu ON tp.frid = tu.id
    $where
    GROUP BY tp.FrId, tp.InvoiceDate

    UNION ALL

    SELECT tp.FrId, tu.ShopName, tp.InvoiceDate, SUM(tp.NetAmount) as TotalAmount
    FROM tbl_customer_invoice_2025 tp
    INNER JOIN tbl_users_bill tu ON tp.frid = tu.id
    $where
    GROUP BY tp.FrId, tp.InvoiceDate
";

$result = $conn->query($sqlData);
$data = []; // $data[FrId][InvoiceDate] = TotalAmount
$franchiseNames = [];

while ($row = $result->fetch_assoc()) {
    $fid = $row['FrId'];
    $invDate = $row['InvoiceDate'];
    $amt = $row['TotalAmount'];

    $data[$fid][$invDate] = ($data[$fid][$invDate] ?? 0) + $amt;
    $franchiseNames[$fid] = $row['ShopName'];
}

// Step 4: Print table
?>
<div class="card-datatable table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Franchise</th>
                <?php foreach ($dates as $d) { ?>
                    <th><?= date("d/m/Y", strtotime($d)); ?></th>
                <?php } ?>
                <th>Total</th>
                <th>Target</th>
                <th>Achieve %</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data as $frid => $franchiseData) {
                $sql3 = "SELECT * FROM tbl_set_target WHERE frid='$frid'";
                if($_POST['month']){
                $month = $_POST['month'];
                $sql3.= " AND month='$month'";
                }
                if($_POST['year']){
                    $year = $_POST['year'];
                    $sql3.= " AND year='$year'";
                }
                $row3 = getRecord($sql3);
                $target = $row3['target'];
                $total = 0;
                echo "<tr>";
                echo "<td><strong>{$franchiseNames[$frid]}</strong></td>";
                foreach ($dates as $d) {
                    $amt = isset($franchiseData[$d]) ? $franchiseData[$d] : 0;
                    echo "<td>" . number_format($amt, 2) . "</td>";
                    $total += $amt;
                }
                echo "<td><strong>" . number_format($total, 2) . "</strong></td>";
                 echo "<td><strong>" . number_format($target, 2) . "</strong></td>";
                 echo "<td>".round(($total/$target)*100,2)."%</td>"; 
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
                            <?php } ?>
                        </div>
                    </div>


                    <?php include_once 'footer.php'; ?>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5'
                ]
            });
        });
        
        function getSubZone(zoneid){

            var action = "getSubZone";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: zoneid
                },
                success: function(data) {
                    console.log(data);
                    $('#SubZoneId').html(data);
                }
            });
        }
    </script>
</body>

</html>