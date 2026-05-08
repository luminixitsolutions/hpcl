<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Customer-Invoice-Report";
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
<h4 class="font-weight-bold py-3 mb-0">Customer Invoice Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-4">
    <label class="form-label">Customer <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="CustId" id="CustId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT tu.Fname,tu.Phone,tci.id,tci.CellNo FROM `tbl_customer_invoice` tci INNER JOIN tbl_users tu ON tu.Phone=tci.CellNo WHERE tci.CellNo!='' GROUP BY tci.CellNo ORDER BY tu.Fname DESC";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["CustId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname'] . " - " . $result['Phone']; ?></option>
            <?php } ?>
        </select>
    </div>

   

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
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

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
             
                <th>Customer Name</th>
               
                <th>Phone No</th>
               <th>Total Invoice</th>
               <th>Total Income</th>
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.Fname,tu.Phone,tci.CustId,tci.CellNo FROM `tbl_customer_invoice` tci INNER JOIN tbl_users tu ON tu.Phone=tci.CellNo WHERE tci.CellNo!=''";
            if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tci.CustId='$CustId'";
                }
            }
            if($_POST['InvNo']){
                $InvNo = $_POST['InvNo'];
                if($InvNo == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tci.InvoiceNo='$InvNo'";
                }
            }
            if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tci.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate']; 
                $sql.= " AND tci.InvoiceDate<='$ToDate'";
            }
            $sql.=" GROUP BY tci.CellNo ORDER BY tci.InvoiceDate DESC ";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                  $sql3 = "SELECT GROUP_CONCAT(Unqid) AS InvIds FROM `tbl_customer_invoice` WHERE CellNo='".$row['CellNo']."'";
                  if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql3.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate']; 
                $sql3.= " AND InvoiceDate<='$ToDate'";
            }
                $row3 = getRecord($sql3);
                $InvIds = $row3['InvIds'];
                if($InvIds!=''){
                 $sql2 = "SELECT SUM(NetAmount) AS TotAmount,count(id) AS TotInv FROM tbl_customer_invoice WHERE Unqid IN($InvIds)";
                $row2 = getRecord($sql2);
             ?>
            <tr>
               <td><?php echo $i;?></td> 
             
           
                <td><?php echo $row['Fname']; ?></td>
                <td><?php echo $row['Phone']; ?></td>
                <td><a href="total-invoice.php?invid=<?php echo $InvIds; ?>" target="blank"><?php echo $row2['TotInv']; ?></a></td>
                <td><?php echo $row2['TotAmount']; ?></td>
                
         
              
            </tr>
           <?php $i++;}} ?>
           
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
