<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "All-Report";
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
<h4 class="font-weight-bold py-3 mb-0">All Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">




<div class="form-group col-md-4">
    <label class="form-label">Account <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="AccCode" id="AccCode">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll IN (3,5,55)";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["AccCode"] == $result['CustomerId']) { ?> selected <?php } ?> value="<?php echo $result['CustomerId']; ?>"><?php echo $result['Fname'] . " (" . $result['CustomerId'].")"; ?></option>
            <?php } ?>
            <?php 
  $sql12 = "SELECT * FROM tbl_account_head WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["AccCode"] == $result['Code']) {?> selected <?php } ?> value="<?php echo $result['Code'];?>">
    <?php echo $result['Name']." (".$result['Code'].")"; ?></option>
<?php } ?>
        </select>
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
               <th>Account Name</th> 
             
                <th>Payment Date</th>
                
                <th>Narration</th>
                <th>Payment Mode</th>
                <th>Debit</th>
                <th>Credit</th>
                
                
                
                
               
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_general_ledger WHERE 1";

            if($_POST['AccCode']){
                $AccCode = $_POST['AccCode'];
                if($AccCode == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND AccCode='$AccCode'";
                }
            }
            if($_POST['InvNo']){
                $InvNo = $_POST['InvNo'];
                if($InvNo == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND InvoiceNo='$InvNo'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND PaymentDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND PaymentDate<='$ToDate'";
            }
            $sql.=" ORDER BY id";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['CrDr'] == 'cr'){
                    $Credit = $row['Amount'];
                    $Debit = 0;
                    $TotCredit+=$row['Amount'];
                }
                else{
                    $Debit = $row['Amount'];
                    $Credit = 0;
                    $TotDebit+=$row['Amount'];
                }
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
            
              
               
                  <td><?php echo $row['AccountName']; ?></td>
                 
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaymentDate']))); ?></td>
              <td><?php echo $row['Narration']; ?></td>
              <td><?php echo $row['PayMode']; ?></td>
               
              <td>&#8377;<?php echo number_format($Credit,2); ?></td>
                 <td>&#8377;<?php echo number_format($Debit,2); ?></td>
                
               
     
           
          
        
              
            </tr>
           <?php $i++;} ?>

           <tr>
                <td><?php echo $i;?></td>
                
                <td></td>
                <td></td>
                <td></td>
                <th>Total</th>
                <th>&#8377;<?php echo number_format($TotCredit,2);?></th>
                <th>&#8377;<?php echo number_format($TotDebit,2);?></th>
                
                
                
                
           </tr>
           <tr>
                <td><?php echo $i+1;?></td>
                
                <td></td>
                <td></td>
                <td></td>
                <th>Balalnce</th>
                <th>&#8377;<?php echo number_format($TotCredit-$TotDebit,2);?></th>
                <th></th>
                
           </tr>
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
                var action = "getInvoiceNos";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
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
