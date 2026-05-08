<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Transfer-Stock-Godown-To-Franchise-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
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




<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Customer Redeem Points Outstanding
</h4>

<div class="card" style="padding: 10px;">
    
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    <div class="form-group col-md-4">
<label class="form-label">Customer</label>
<select class="select2-demo form-control" name="custid" id="custid">
    <option selected="" value="all">All</option>
    <?php 
    $sql12 = "
        SELECT DISTINCT u.id, u.Fname, u.Phone 
        FROM tbl_users u
        INNER JOIN tbl_customer_points c ON u.id = c.custid
        WHERE u.Roll = 55
        ORDER BY u.Fname
    ";
    $row12 = getList($sql12);
    foreach($row12 as $result){
    ?>
        <option <?php if($_REQUEST["custid"] == $result['id']) {?> selected <?php } ?> 
            value="<?php echo $result['id'];?>">
            <?php echo $result['Fname']." (".$result['Phone'].")"; ?>
        </option>
    <?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
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
   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Phone</th>
            <th>Total Credit Points</th>
            <th>Total Debit Points</th>
            <th>Balance Points</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        // Base query with join
        $sql = "
            SELECT 
                c.custid,
                u.Fname AS customer_name,
                c.phone,
                SUM(CASE WHEN c.status='cr' THEN c.points ELSE 0 END) AS total_credit,
                SUM(CASE WHEN c.status='dr' THEN c.points ELSE 0 END) AS total_debit,
                (SUM(CASE WHEN c.status='cr' THEN c.points ELSE 0 END) - 
                 SUM(CASE WHEN c.status='dr' THEN c.points ELSE 0 END)) AS balance
            FROM tbl_customer_points c
            LEFT JOIN tbl_users u ON c.custid = u.id
            WHERE 1=1
        ";

        // Filters
        if(!empty($_POST['custid'])){
            $custid = $_POST['custid'];
            if($custid != 'all'){
                $sql.= " AND c.custid='$custid'";
            }
        }
        if(!empty($_POST['FromDate'])){
            $FromDate = $_POST['FromDate'];
            $sql.= " AND c.StockDate >= '$FromDate'";
        }
        if(!empty($_POST['ToDate'])){
            $ToDate = $_POST['ToDate'];
            $sql.= " AND c.StockDate <= '$ToDate'";
        }

        // Group by
        $sql.=" GROUP BY c.custid, u.Fname, c.phone ORDER BY c.custid";

        $res = $conn->query($sql);
        while($row = $res->fetch_assoc())
        {
        ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $row['custid']; ?></td>
                <td><?= $row['customer_name']; ?></td>
                <td><?= $row['phone']; ?></td>
                <td class="text-success fw-bold"><?= number_format($row['total_credit'],2); ?></td>
                <td class="text-danger fw-bold"><?= number_format($row['total_debit'],2); ?></td>
                <td class="fw-bold"><?= number_format($row['balance'],2); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
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

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,   // remove default auto-title
                filename: 'customer_redeem_points_outstanding',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    var downrows = 6;
                    var clRow = $('row', sheet);

                    // shift all rows down
                    clRow.each(function() {
                        var attr = $(this).attr('r');
                        var ind = parseInt(attr);
                        ind = ind + downrows;
                        $(this).attr("r", ind);
                    });

                    // update row references in cells
                    $('row c', sheet).each(function() {
                        var attr = $(this).attr('r');
                        var pre = attr.substring(0, 1);
                        var ind = parseInt(attr.substring(1));
                        ind = ind + downrows;
                        $(this).attr("r", pre + ind);
                    });

                    // helper to insert new rows
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

                    // custom headers for Customer Redeem Points Outstanding
                    var title1   = AddRow(1, [{ cell: 'A', text: 'Kwick Bill' }]);
                    var title2   = AddRow(2, [{ cell: 'A', text: 'Customer Redeem Points Outstanding Report' }]);
                    var period   = AddRow(3, [{ cell: 'A', text: 'As on Date: <?php echo date("d/m/Y"); ?>' }]);
                    var user     = AddRow(4, [{ cell: 'A', text: 'Generated by: <?php echo $_SESSION["Admin"]["Fname"]; ?>' }]);
                    var datetime = AddRow(5, [{ cell: 'A', text: 'Generated on: <?php echo date("d/m/Y h:i A"); ?>' }]);
                    var blankRow = AddRow(6, [{ cell: 'A', text: '' }]);

                    // prepend our custom rows before actual data
                    sheet.childNodes[0].childNodes[1].innerHTML =
                        title1 + title2 + period + user + datetime + blankRow + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            }
        ]
    });
});
</script>

</body>
</html>
