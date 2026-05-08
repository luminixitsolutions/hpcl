<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Daily-Sale-Report-2";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>Daily Sale Report - <?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">




<?php 
function calMonth($fromdate,$todate){
$date1 = $fromdate;
$date2 = $todate;
$ts1 = strtotime($date1);
$ts2 = strtotime($date2);
$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);
$month1 = date('m', $ts1);
$month2 = date('m', $ts2);
$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
if($diff > 0){
    $totmonth = $diff;
}
else{
    $totmonth = 0;
}
return $totmonth;
}

function getPayTypeCondition($paymode) {
    $conditions = [
        'cash'    => " AND PayType = 'Cash' ",
        'zomato'  => " AND PayType IN ('Swiggy','Zomato') ",
        'credit'  => " AND PayType = 'Borrowing' ",
        'default' => " AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI') "
    ];

    $paymode = strtolower(trim($paymode));
    return $conditions[$paymode] ?? $conditions['default'];
}

function ftdRevenue($frid, $fromdate, $todate, $paymode) {
    global $conn;
    $payTypeSQL = $paymode !== '' ? getPayTypeCondition($paymode) : '';

    $sql = "
        SELECT SUM(TotalInv) AS TotalInv FROM (
            SELECT COUNT(*) AS TotalInv FROM tbl_customer_invoice 
            WHERE FrId = '$frid' AND InvoiceDate BETWEEN '$fromdate' AND '$todate' $payTypeSQL
            UNION ALL
            SELECT COUNT(*) AS TotalInv FROM tbl_customer_invoice_2025 
            WHERE FrId = '$frid' AND InvoiceDate BETWEEN '$fromdate' AND '$todate' $payTypeSQL
        ) AS a
    ";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['TotalInv'] ?? 0;
}

function ftdRevenueAmt($frid, $fromdate, $todate, $paymode) {
    global $conn;
    $payTypeSQL = $paymode !== '' ? getPayTypeCondition($paymode) : '';

    $sql = "
        SELECT SUM(NetAmount) AS NetAmount FROM (
            SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice 
            WHERE FrId = '$frid' AND InvoiceDate BETWEEN '$fromdate' AND '$todate' $payTypeSQL
            UNION ALL
            SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 
            WHERE FrId = '$frid' AND InvoiceDate BETWEEN '$fromdate' AND '$todate' $payTypeSQL
        ) AS a
    ";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['NetAmount'] ?? 0;
}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Expense Via Sale Report
  
</h4>

<div class="card" style="padding: 10px;">
     <ul class="nav nav-tabs" id="reportTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="report-tab" data-toggle="tab" href="#report" role="tab">Report</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="graph-tab" data-toggle="tab" href="#graph" role="tab">Graph</a>
    </li>
  </ul>
  
  <div class="tab-content" id="reportTabContent">
    <!-- Report Tab -->
    <div class="tab-pane fade show active" id="report" role="tabpanel">
        <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

           
 

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off" required>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:25px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
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
   <?php if($_REQUEST['Search']=='Search') {?>
<div class="card-datatable table-responsive">
<?php
// Filter values
$FrId = $_REQUEST['FrId'] ?? '';
$ZoneId = $_REQUEST['ZoneId'] ?? '';
$SubZoneId = $_REQUEST['SubZoneId'] ?? '';
$FromDate = $_REQUEST['FromDate'] ?? '';
$ToDate = $_REQUEST['ToDate'] ?? '';


if (!empty($FromDate) && !empty($ToDate)) {
    $date1 = new DateTime($FromDate);
    $date2 = new DateTime($ToDate);

    // Add 1 day to include the end date fully
    $date2->modify('+1 day');

    $interval = $date1->diff($date2);

    // Total months = years*12 + months
    $months = ($interval->y * 12) + $interval->m;

    // If days > 0, then count it as an extra month
    if ($interval->d > 0) {
        $months++;
    }

    //echo "Total Months: " . $months;
}

// Date conditions
$dateCond = "";
if (!empty($FromDate)) $dateCond .= " AND ExpenseDate >= '$FromDate'";
if (!empty($ToDate)) $dateCond .= " AND ExpenseDate <= '$ToDate'";
$invoiceDateCond = str_replace("ExpenseDate", "InvoiceDate", $dateCond);

$walletDateCond = "";
if (!empty($FromDate)) $walletDateCond .= " AND w.CreatedDate >= '$FromDate'";
if (!empty($ToDate)) $walletDateCond .= " AND w.CreatedDate <= '$ToDate'";

// Main Franchise SQL
$sql = "SELECT * FROM tbl_users_bill WHERE Roll = 5 AND Status = 1";
if (!empty($FrId) && $FrId !== 'all') $sql .= " AND id = '$FrId'";
if (!empty($ZoneId) && $ZoneId !== 'all') $sql .= " AND ZoneId = '$ZoneId'";
if (!empty($SubZoneId) && $SubZoneId !== 'all') $sql .= " AND SubZoneId = '$SubZoneId'";
$franchises = getList($sql);
?>

<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Outlet Name</th>
            <th>Zone</th>
            <th>Sub Zone</th>
            <th>Total Sale</th>
            <th>Employee Expenses</th>
            <th>Vendor Expenses</th>
            <th>NSO Vendor Expenses</th>
           <!-- <th>Product Cost (40%)</th>-->
            <th>Salary</th>
            <th>Rent & Electricity</th>
            <th>Misc Expenses</th>
            <th>Investor Share Cost (12%)</th>
            <th>GST (5%)</th>
            <th>HO Cost (5%)</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($franchises as $result) {
    $FranchiseId = $result['id'];

    // Zone & Sub-Zone
    $zone = getRecord("SELECT Name FROM tbl_zone WHERE id = '{$result['ZoneId']}'");
    $subzone = getRecord("SELECT Name FROM tbl_sub_zone WHERE id = '{$result['SubZoneId']}'");

    // Expenses
     $emp = getRecord("SELECT SUM(Amount) AS TotalExp FROM tbl_expense_request WHERE FrId = '$FranchiseId' AND (IFNULL(ManagerStatus,0) != 2 AND IFNULL(AdminStatus,0) != 2) $dateCond");
    $vendor = getRecord("
    SELECT SUM(Amount) AS TotalVedExp 
    FROM tbl_vendor_expenses 
    WHERE Locations = '$FranchiseId' 
      AND (IFNULL(BdmStatus,0) != 2 AND IFNULL(PurchaseStatus,0) != 2 AND IFNULL(ManagerStatus,0) != 2 AND IFNULL(AdminStatus,0) != 2) 
      $dateCond
");

$nso = getRecord("
    SELECT SUM(Amount) AS TotalNsoVedExp 
    FROM tbl_nso_vendor_expenses 
    WHERE Locations = '$FranchiseId' 
      AND (IFNULL(BdmStatus,0) != 2 AND IFNULL(PurchaseStatus,0) != 2 AND IFNULL(ManagerStatus,0) != 2 AND IFNULL(AdminStatus,0) != 2) 
      $dateCond
");

    // Sales (2025 & old)
    $sql5 = "SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (
    SELECT SUM(NetAmount) AS NetAmount 
    FROM tbl_customer_invoice 
    WHERE FrId = '$FranchiseId' $invoiceDateCond

    UNION ALL

    SELECT SUM(NetAmount) AS NetAmount 
    FROM tbl_customer_invoice_2025 
    WHERE FrId = '$FranchiseId' $invoiceDateCond
) AS a";
    $row5 = getRecord($sql5);

    // Petty Cash
    $sql2 = "SELECT COALESCE(SUM(TotAmt), 0) AS TotPetty FROM (
    SELECT SUM(w.Amount) AS TotAmt
    FROM wallet w
    LEFT JOIN tbl_users tu ON tu.id = w.UserId
    LEFT JOIN tbl_users_bill tub ON tub.id = tu.UnderFrId
    WHERE tub.id = '$FranchiseId'
      AND w.Status = 'Cr'
      $walletDateCond
      AND (w.Narration LIKE '%Pretty Cash%' OR w.Narration LIKE '%Petty Cash%')
    GROUP BY w.UserId
) AS a";
    $row2 = getRecord($sql2);

    // Salary
    $sql3 = "SELECT COALESCE(SUM(MonthlySalary), 0) AS MonthlySalary FROM (
                SELECT tu.id, tu.PerDaySalary, tu.PerDaySalary * (DATEDIFF('$ToDate', '$FromDate') + 1) AS MonthlySalary
                FROM tbl_users tu WHERE tu.UnderFrId = '$FranchiseId' AND tu.SalaryType = 1 AND tu.Status = 1
                UNION ALL
                SELECT tu.id, tu.PerDaySalary, tu.PerDaySalary AS MonthlySalary
                FROM tbl_users tu WHERE tu.UnderFrId = '$FranchiseId' AND tu.SalaryType = 2 AND tu.Status = 1
            ) AS a";
    $row3 = getRecord($sql3);

    // Rent
    $sql4 = "SELECT COALESCE(SUM(MonthlyRent), 0) AS MonthlyRent FROM tbl_users WHERE id = '$FranchiseId'";
    $row4 = getRecord($sql4);

    // Final Calculations
    $netAmount = $row5['NetAmount'];
    $prodcost = ($netAmount * 40) / 100;
    $invsharecost = ($netAmount * 12) / 100;
    $gstcost = ($netAmount * 5) / 100;
    $hocost = ($netAmount * 5) / 100;

    $balance = $netAmount  - ($row3['MonthlySalary']*$months) - ($row4['MonthlyRent']*$months) - $row2['TotPetty'] - $invsharecost - $gstcost - $hocost;
    
    ?>
    <tr>
        <td><?= htmlspecialchars($result['ShopName']) ?></td>
        <td><?= $zone['Name'] ?? '' ?></td>
        <td><?= $subzone['Name'] ?? '' ?></td>
        <td><?= number_format($netAmount, 2) ?></td>
        <td><?= number_format($emp['TotalExp'] ?? 0, 2) ?></td>
        <td><?= number_format($vendor['TotalVedExp'] ?? 0, 2) ?></td>
        <td><?= number_format($nso['TotalNsoVedExp'] ?? 0, 2) ?></td>
        <!--<td><?= number_format($prodcost, 2) ?></td>-->
        <td><?= number_format($row3['MonthlySalary']*$months, 2) ?></td>
        <td><?= number_format($row4['MonthlyRent']*$months, 2) ?></td>
        <td><?= number_format($row2['TotPetty'], 2) ?></td>
        <td><?= number_format($invsharecost, 2) ?></td>
        <td><?= number_format($gstcost, 2) ?></td>
        <td><?= number_format($hocost, 2) ?></td>
        <td><?= number_format($balance, 2) ?></td>
    </tr>
<?php } ?>
    </tbody>
</table>

</div>
<?php } ?>
    </div>

    <!-- Graph Tab -->
    <div class="tab-pane fade" id="graph" role="tabpanel">
    <div class="row">
        <div class="col-lg-6">
            <canvas id="financeChart" width="100%" height="40" style="margin-top:20px;"></canvas>
        </div>
        <div class="col-lg-6">
            <canvas id="balanceChart" width="100%" height="40" style="margin-top:20px;"></canvas>
        </div>
    </div>

   <?php
$graphLabels = [
    'Total Sale', 'Employee Expenses', 'Vendor Expenses', 'NSO Vendor Expenses',
    'Salary', 'Rent & Electricity', 'Misc Expenses',
    'Investor Share Cost (12%)', 'GST (5%)', 'HO Cost (5%)', 'Balance'
];

$saleSum = $empSum = $vendorSum = $nsoSum = 0;
$prodcostSum = $salarySum = $rentSum = $pettySum = 0;
$invShareSum = $gstSum = $hoSum = 0;

foreach ($franchises as $result) {
    $FranchiseId = $result['id'];

    $emp = getRecord("SELECT SUM(Amount) AS TotalExp FROM tbl_expense_request WHERE FrId = '$FranchiseId' AND AdminStatus !=2 $dateCond");
    $vendor = getRecord("SELECT SUM(Amount) AS TotalVedExp FROM tbl_vendor_expenses WHERE Locations = '$FranchiseId' AND AdminStatus !=2 $dateCond");
    $nso = getRecord("SELECT SUM(Amount) AS TotalNsoVedExp FROM tbl_nso_vendor_expenses WHERE Locations = '$FranchiseId' AND AdminStatus !=2 $dateCond");

    $sale = getRecord("SELECT SUM(NetAmount) AS TotalSell FROM tbl_customer_invoice_2025 WHERE FrId = '$FranchiseId' $invoiceDateCond");

    $row5 = getRecord("SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (
        SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE FrId='$FranchiseId' $invoiceDateCond
        UNION ALL 
        SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId='$FranchiseId' $invoiceDateCond
    ) AS a");

    $row2 = getRecord("SELECT COALESCE(SUM(TotAmt), 0) AS TotPetty FROM (
        SELECT SUM(w.Amount) AS TotAmt
        FROM wallet w
        LEFT JOIN tbl_users tu ON tu.id = w.UserId
        LEFT JOIN tbl_users_bill tub ON tub.id = tu.UnderFrId
        WHERE tub.id = '$FranchiseId' AND w.Status = 'Cr' $walletDateCond
        AND (w.Narration LIKE '%Pretty Cash%' OR w.Narration LIKE '%Petty Cash%')
        GROUP BY w.UserId
    ) AS a");

    $row3 = getRecord("SELECT COALESCE(SUM(MonthlySalary), 0) AS MonthlySalary FROM (
        SELECT tu.PerDaySalary * (DATEDIFF('$ToDate', '$FromDate') + 1) AS MonthlySalary
        FROM tbl_users tu WHERE tu.UnderFrId = '$FranchiseId' AND tu.SalaryType = 1 AND tu.Status = 1
        UNION ALL
        SELECT tu.PerDaySalary AS MonthlySalary
        FROM tbl_users tu WHERE tu.UnderFrId = '$FranchiseId' AND tu.SalaryType = 2 AND tu.Status = 1
    ) AS a");

    $row4 = getRecord("SELECT COALESCE(SUM(MonthlyRent), 0) AS MonthlyRent FROM tbl_users WHERE id='$FranchiseId'");

    $netAmount = $row5['NetAmount'];
    $prodcost = ($netAmount * 40) / 100;
    $invShare = ($netAmount * 12) / 100;
    $gst = ($netAmount * 5) / 100;
    $ho = ($netAmount * 5) / 100;
    $balance = $netAmount  - $row3['MonthlySalary'] - $row4['MonthlyRent'] - $row2['TotPetty'] - $invShare - $gst - $ho;

    $saleSum += $sale['TotalSell'] ?? 0;
    $empSum += $emp['TotalExp'] ?? 0;
    $vendorSum += $vendor['TotalVedExp'] ?? 0;
    $nsoSum += $nso['TotalNsoVedExp'] ?? 0;

    $prodcostSum += $prodcost;
    $salarySum += $row3['MonthlySalary'];
    $rentSum += $row4['MonthlyRent'];
    $pettySum += $row2['TotPetty'];
    $invShareSum += $invShare;
    $gstSum += $gst;
    $hoSum += $ho;
}

$balanceSum = $saleSum - ($salarySum + $rentSum + $pettySum + $invShareSum + $gstSum + $hoSum);

$graphValues = [
    round($saleSum, 2),
    round($empSum, 2),
    round($vendorSum, 2),
    round($nsoSum, 2),
    round($salarySum, 2),
    round($rentSum, 2),
    round($pettySum, 2),
    round($invShareSum, 2),
    round($gstSum, 2),
    round($hoSum, 2),
    round($balanceSum, 2)
];
?>
</div>
  </div>



</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
<script>
const totalLabels = <?= json_encode($graphLabels); ?>;
const totalValues = <?= json_encode($graphValues); ?>;

// Bar Chart
const barCtx = document.getElementById('financeChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: totalLabels,
        datasets: [{
            label: '₹ Amount',
            data: totalValues,
            backgroundColor: totalLabels.map((_, i) => `hsl(${i * 30}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Overall Financial Summary (Bar Graph)'
            },
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ₹' + context.raw.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Amount in ₹'
                }
            }
        }
    }
});

// Doughnut Chart
const doughnutCtx = document.getElementById('balanceChart').getContext('2d');
new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: totalLabels,
        datasets: [{
            label: '₹ Amount',
            data: totalValues,
            backgroundColor: totalLabels.map((_, i) => `hsl(${i * 30}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Overall Financial Summary (Doughnut Graph)'
            },
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ₹' + context.raw.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
<script type="text/javascript">
 function changeStatus(id,val){
     window.location.href='expense-request.php?action=changestatus&id='+id+'&status='+val;
 }
    
    
     $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,
                filename: 'zone_expense_vs_sale_report',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var downrows = 5;
                    var clRow = $('row', sheet);

                    clRow.each(function() {
                        var attr = $(this).attr('r');
                        var ind = parseInt(attr);
                        ind = ind + downrows;
                        $(this).attr("r", ind);
                    });

                    $('row c', sheet).each(function() {
                        var attr = $(this).attr('r');
                        var pre = attr.substring(0, 1);
                        var ind = parseInt(attr.substring(1));
                        ind = ind + downrows;
                        $(this).attr("r", pre + ind);
                    });

                    function AddRow(index, data) {
                        var row = '<row r="' + index + '">';
                        for (var i = 0; i < data.length; i++) {
                            row += '<c t="inlineStr" r="' + data[i].cell + index + '" s="2">';
                            row += '<is><t>' + data[i].text + '</t></is>';
                            row += '</c>';
                        }
                        row += '</row>';
                        return row;
                    }

                    var title1 = AddRow(1, [{ cell: 'A', text: 'Maha Chai Pvt. Ltd.' }]);
                    var title2 = AddRow(2, [{ cell: 'A', text: 'Zone Expense vs Sale Report' }]);
                    var period = AddRow(3, [{ cell: 'A', text: 'Period: <?php echo !empty($_POST["FromDate"]) ? date("d/m/Y", strtotime($_POST["FromDate"])) : ""; ?> to <?php echo !empty($_POST["ToDate"]) ? date("d/m/Y", strtotime($_POST["ToDate"])) : ""; ?>' }]);
                    var generated = AddRow(4, [{ cell: 'A', text: 'Generated on: <?php echo date("d/m/Y h:i A"); ?>' }]);
                    var blankRow = AddRow(5, [{ cell: 'A', text: '' }]);

                    sheet.childNodes[0].childNodes[1].innerHTML =
                        title1 + title2 + period + generated + blankRow + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            }
        ]
    });
});

</script>
</body>
</html>
