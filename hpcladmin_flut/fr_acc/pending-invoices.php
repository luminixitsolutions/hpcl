<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Invoice";
$Page = "Pending-Invoice";
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
  $sql11 = "DELETE FROM tbl_invoice WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_invoice_details WHERE InvId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND Flag='Invoice'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="pending-invoices.php";
    </script>
<?php } 


if($_GET["action"]=="changestatus")
{
  $id = $_GET["id"];
  $status = $_GET['status'];
  if($status == 0){
      $sql = "UPDATE tbl_invoice SET Status=1 WHERE id='$id'";
      $conn->query($sql);
  }
  else{
      $sql = "UPDATE tbl_invoice SET Status=0 WHERE id='$id'";
      $conn->query($sql);
  }
  ?>
    <script type="text/javascript">
      alert("Status Updated Successfully!");
      window.location.href="pending-invoices.php";
    </script>
<?php }
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pending Invoices
  
</h4>
<br>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Action</th>
                <th>QR Code</th>
                <th>Status</th>
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
            $sql = "SELECT * FROM tbl_invoice WHERE Status=0";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
            <tr>
               <td><?php echo $i;?></td>
             
             <td>
              <a href="edit-invoice.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
              <a onClick="return confirm('Are you sure you want delete this record. delete all record related this invoice.');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>
              &nbsp;&nbsp;
              <a href="invoice.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-printer text-danger mr-2"></i></a>
            </td>
            <td> <?php if($row["Barcode"] == '') {?>
                  -
                 <?php } else if(file_exists('../barcodes/'.$row["Barcode"])){?>
                 <a href="../barcodes/<?php echo $row["Barcode"];?>" target="_new"><img src="../barcodes/<?php echo $row["Barcode"];?>?nocache=<?php echo time(); ?>" class="d-block ui-w-40" alt="" style="width: 40px;height: 40px;"></a>
                  <?php }  else{?>
                 -
             <?php } ?></td>
             <td>
             <label class="switcher switcher-success">
                                        <input type="checkbox" class="switcher-input" <?php if($row['Status']=='1'){?>checked=""<?php } ?> onclick="approve('<?php echo $row['Status'];?>','<?php echo $row['id']; ?>')">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label"><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></span>
                                    </label>
             </td>
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
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>


<script type="text/javascript">
 function approve(status,id){
     //alert(status);alert(id);
     window.location.href="view-invoices.php?status="+status+"&id="+id+"&action=changestatus";
 }
	$(document).ready(function() {
    $('#example').DataTable({
      "scrollX": true
    });
});
</script>
</body>
</html>
