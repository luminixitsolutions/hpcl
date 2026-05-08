<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Franchise-Stock-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
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

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Franchise Stock Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">


<div class="form-group col-md-3">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="UserId" id="UserId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["UserId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname']. " - " . $result['Phone']; ?></option>
            <?php } ?>
        </select>
    </div>

<div class="form-group col-md-3">
    <label class="form-label">Products <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="ProdId" id="ProdId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM products WHERE Status=1 AND ProdFor IN (1,3)";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["ProdId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
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
                <th>Franchise Name</th>
                <th>Product Name</th>
                 <th>Particulars</th> 
                <th>Date</th>
                <th>Narration</th>
                <th>Credit</th>
              <th>Debit</th>
                
                
               
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT trs.*,trp.ProductName,tu.Fname FROM tbl_product_stocks trs 
                    INNER JOIN products trp ON trp.id=trs.ProdId 
                    INNER JOIN tbl_users tu ON tu.id=trs.UserId WHERE trs.Roll='Franchise-Stock'";
            if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND trs.UserId='$UserId'";
                }
            }        
            if($_POST['ProdId']){
                $ProdId = $_POST['ProdId'];
                if($ProdId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND trs.ProdId='$ProdId'";
                }
            }
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND trs.StockDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND trs.StockDate<='$ToDate'";
            }
            $sql.=" ORDER BY trs.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                if($row['CrDr'] == 'cr'){
                    $Credit = $row['Qty'];
                    $Debit = 0;
                    $TotCredit+=$row['Qty'];
                    $Particulars = "Stock Added";
                }
                else{
                    $Debit = $row['Qty'];
                    $Credit = 0;
                    $TotDebit+=$row['Qty'];
                    $Particulars = "Stock Used To ".$row22['Fname'];
                }

               
                
             ?>
            <tr>
               <td><?php echo $i;?></td>
              <td><?php echo $row['Fname']; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
               <td><?php echo $Particulars;?></td>
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
           
                <td><?php echo $row['Narration']; ?></td>
                <td><?php echo $Credit; ?></td>
                <td><?php echo $Debit; ?></td>
              
               
                
           
         
              
            </tr>
           <?php $i++;} ?>
            <tr>
           <td><?php echo $i;?></td>
           <td></td>
           <td></td>
            <td></td>
             <td></td>
           <th>Total</th>
           <th><?php echo $TotCredit; ?></th>
            <th><?php echo $TotDebit; ?></th>
          
           </tr> 
           <tr>
           <td><?php echo $i+1;?></td>
           <td></td>
            <td></td>
           <td></td>
            <td></td>
           <th>Balance</th>
           <th><?php echo $TotCredit-$TotDebit; ?></th>
          
           <td></td>
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
