<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Preparing-Order";
$Page = "Preparing-Order";
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



<?php
if($_GET["action"]=="delete")
{
  $id = $_GET["id"];
  $sql11 = "DELETE FROM tbl_customer_invoice WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_customer_invoice_details WHERE InvId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND Flag='Customer-Invoice'";
  $conn->query($sql11);
  $sql = "DELETE FROM tbl_product_stocks WHERE InvId='$id'";
  $conn->query($sql);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-customer-invoices.php";
    </script>
<?php } 


if($_GET["action"]=="changestatus")
{
  $id = $_GET["id"];
  $status = $_GET['status'];
  
       $sql = "UPDATE tbl_customer_invoice SET KitchenStatus='$status' WHERE id='$id'";
      $conn->query($sql);
 
  ?>
    <script type="text/javascript">
      //alert("Status Updated Successfully!");
      window.location.href="preparing-order.php";
    </script>
<?php }
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Today Preparing Order Lists

</h4>
<br>

<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



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
   
   
<div class="row">
    <?php 
            $i=1;
            if(isset($_POST['Search'])) {
            $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND FrId=0";
            }
            else{
             $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='".date('Y-m-d')."' AND FrId=0";   
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            $sql.=" ORDER BY InvoiceDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
                            <div class="col-md-6 col-xl-4">

                                <div class="card  mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title" style="text-align: center;"> <span class="display-3 d-inline-block font-weight-bold align-middle"><?php echo $row['OrderNo']; ?></span></h4>
                                        <h5 class="m-0" style="text-align: center;"><?php echo $row['CustName']; ?></h5><br>
                                        <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            
                                                             <?php 
            $i=1;
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='".$row['id']."'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName,Photo FROM  tbl_cust_products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=$result12['GstAmt'];
        ?>
                                                            <td>
                                                                
                                                                                 <?php if($row13["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 50px;height: 50px;" class="rounded mr-2"> 
                 <?php } else if(file_exists('../uploads/'.$row13["Photo"])){?>
                 <img src="../uploads/<?php echo $row13["Photo"];?>" alt="" style="width: 50px;height: 50px;" class="rounded mr-2">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="rounded mr-2" style="width: 50px;height: 50px;"> 
             <?php } ?>
                                                               
                                                                <p class="m-0 d-inline-block align-middle">
                                                                    <a href="#!" class="text-body font-weight-semibold"><?php echo $row13['ProductName'];?></a>
                                                                   
                                                                </p>
                                                            </td>
                                                            <td class="text-right" style="font-weight:bold;padding-top: 23px;">
                                                               x                                                    </td>
                                                            <td class="text-right" style="font-weight:bold;padding-top: 23px;">
                                                               <?php echo $result12['Qty'];?>                                                     </td>
                                                        </tr>
                                                        
                                                         <?php } ?>
                                                        
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php if($row['KitchenStatus']=='3'){?>
                                             <div class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-success text-center">Delivered</a></div>
                                            <?php } else { ?>
                                            <div class="text-center">
                                        <a href="javascript:void(0)" onclick="changeStatus(3,<?php echo $row['id'];?>)" class="btn btn-primary text-center">Deliver</a></div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                            <?php $i++;} ?>
                        </div>
                        

<!--<div class="card">
    <div id="accordion2">

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
              <th>Kitchen Status</th>
              <th>Change Status</th>
               <th>Order No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
               
                <th>Phone No</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance Amount</th>
                <th>Payment Mode</th>
                
                
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            if(isset($_POST['Search'])) {
            $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 ";
            }
            else{
             $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='".date('Y-m-d')."'";   
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            $sql.=" ORDER BY InvoiceDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
            <tr>
               <td><?php echo $i;?></td>
           <td><?php if($row['KitchenStatus']=='0'){echo "<a href='javascript:void(0)' class='badge badge-pill badge-primary'>Pending</a>";} else if($row['KitchenStatus']=='1'){echo "<a href='javascript:void(0)' class='badge badge-pill badge-warning'>Preparing</a>";} else if($row['KitchenStatus']=='2'){echo "<a href='javascript:void(0)' class='badge badge-pill badge-info'>Ready</a>";} else { echo "<a href='javascript:void(0)' class='badge badge-pill badge-success'>Delivered</a>";} ?></td>
           <td><select class="form-control" onchange="changeStatus(this.value,<?php echo $row['id'];?>)">
               <option <?php if($row['KitchenStatus']=='0'){?> selected <?php } ?> value=0>Pending</option>
               <option <?php if($row['KitchenStatus']=='1'){?> selected <?php } ?> value=1>Preparing</option>
               <option <?php if($row['KitchenStatus']=='2'){?> selected <?php } ?> value=2>Ready</option>
               <option <?php if($row['KitchenStatus']=='3'){?> selected <?php } ?> value=3>Delivered</option>
           </select></td>
             <td><?php echo $row['OrderNo']; ?></td>
              <td><?php echo $row['InvoiceNo']; ?></td>
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
           
                <td><?php echo $row['CustName']; ?></td>
                <td><?php echo $row['CellNo']; ?></td>
                
                  <td>&#8377; <?php echo number_format($row['NetAmount'],2); ?></td>
                  <td>&#8377; <?php echo number_format($row['Advance'],2); ?></td>
                  <td>&#8377; <?php echo number_format($row['Balance'],2); ?></td>
                  <td><?php echo $row['PayType']; ?></td>
                
            
         
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
</div>
</div>-->

</div>
<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>


<script type="text/javascript">
function changeStatus(status,id){
    window.location.href="preparing-order.php?action=changestatus&id="+id+"&status="+status;
}
function printReceipt(){
     Android.printReceipt();
}
 function approve(status,id){
     //alert(status);alert(id);
     window.location.href="view-invoices.php?status="+status+"&id="+id+"&action=changestatus";
 }
	$(document).ready(function() {
    $('#example').DataTable({
        order: [[2, 'desc']],
      "scrollX": true
    });
});
</script>
</body>
</html>
