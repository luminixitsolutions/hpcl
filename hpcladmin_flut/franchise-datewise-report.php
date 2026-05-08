<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Franchise-Date-Wise-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
<h4 class="font-weight-bold py-3 mb-0">Franchise Date Wise Sell Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">


<div class="form-group col-md-5">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="UserId" id="UserId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["UserId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname']. " - " . $result['Phone']; ?></option>
            <?php } ?>
        </select>
    </div>

    

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off" required>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
   <?php if(isset($_POST['Search'])) {?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
             <th>Sr No</th>
                <th>Franchise Name</th>
                <?php 
                    $sql = "SELECT DISTINCT(InvoiceDate) AS InvoiceDate FROM tbl_customer_invoice WHERE InvoiceDate>'2024-01-15'";
                    if($_POST['FromDate']){
                        $FromDate = $_POST['FromDate'];
                        $sql.= " AND InvoiceDate>='$FromDate'";
                    }
                    if($_POST['ToDate']){
                        $ToDate = $_POST['ToDate'];
                        $sql.= " AND InvoiceDate<='$ToDate'";
                    }
                    $sql.=" ORDER BY InvoiceDate DESC";
                    $row = getLIst($sql);
                    foreach($row as $result){
                ?>
                <th><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['InvoiceDate'])));?></th>
               <?php } ?>
                
                
               
   
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
              <td>MAHACHAI PRIVATE LIMITED - Main Branch (Khamala)</td>
              <?php 
                    $sql2 = "SELECT DISTINCT(InvoiceDate) AS InvoiceDate FROM tbl_customer_invoice WHERE InvoiceDate>'2024-01-15'";
                    if($_POST['FromDate']){
                        $FromDate = $_POST['FromDate'];
                        $sql2.= " AND InvoiceDate>='$FromDate'";
                    }
                    if($_POST['ToDate']){
                        $ToDate = $_POST['ToDate'];
                        $sql2.= " AND InvoiceDate<='$ToDate'";
                    }
                    $sql2.=" ORDER BY InvoiceDate DESC";
                    $row2 = getLIst($sql2);
                    foreach($row2 as $result2){
                        $sql3 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE InvoiceDate='".$result2['InvoiceDate']."' AND FrId='0'";
                        $row3 = getRecord($sql3);
                ?>
               <td>&#8377;<?php echo $row3['NetAmount']; ?></td>
              <?php } ?>
              
            </tr>
            <?php 
            $i=2;
            $sql = "SELECT trs.*,tu.ShopName FROM tbl_customer_invoice trs INNER JOIN tbl_users_bill tu ON trs.FrId=tu.id WHERE trs.FrId!=0 AND tu.Roll=5";
            if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND trs.UserId='$UserId'";
                }
            }        
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND trs.InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND trs.InvoiceDate<='$ToDate'";
            }
            $sql.=" GROUP BY trs.FrId ORDER BY trs.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
               
                
             ?>
            <tr>
                <td><?php echo $i;?></td>
              <td><?php echo $row['ShopName']; ?></td>
              <?php 
                    $sql2 = "SELECT DISTINCT(InvoiceDate) AS InvoiceDate FROM tbl_customer_invoice WHERE InvoiceDate>'2024-01-15'";
                    if($_POST['FromDate']){
                        $FromDate = $_POST['FromDate'];
                        $sql2.= " AND InvoiceDate>='$FromDate'";
                    }
                    if($_POST['ToDate']){
                        $ToDate = $_POST['ToDate'];
                        $sql2.= " AND InvoiceDate<='$ToDate'";
                    }
                    $sql2.=" ORDER BY InvoiceDate DESC";
                    $row2 = getLIst($sql2);
                    foreach($row2 as $result2){
                        $sql3 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE InvoiceDate='".$result2['InvoiceDate']."' AND FrId='".$row['FrId']."'";
                        $row3 = getRecord($sql3);
                        $NetAmount = $row3['NetAmount'];
                        if($NetAmount > 0){
                            $NetAmount2 = $row3['NetAmount'];
                        }
                        else{
                            $NetAmount2 = 0;
                        }
                       
                ?>
               <td>&#8377;<?php echo $NetAmount2; ?></td>
              <?php } ?>
              
            </tr>
           <?php $i++;} ?>
            
            
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

    $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                var action = "getInvoice";
                $.ajax({
                    url: "ajax_files/ajax_fr_general_ledger.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#InvNo').html(data);
                       
                    }
                });

            });
});
</script>
</body>
</html>
