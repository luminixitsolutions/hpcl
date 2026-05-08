<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Account";
$Page = "Customer-Amount";
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
<h4 class="font-weight-bold py-3 mb-0">View Customer Amount
      <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-customer-amount.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
<?php } ?>
</h4>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_general_ledger WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="customer-amount.php";
    </script>
<?php } ?>

<div class="card" style="padding: 10px;">
   
   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
                 <th>Voucher No</th>
                 <th>Invoice No</th> 
                 <th>Payment Date</th>
                 
                <th>Customer Name</th>
              
                <th>Amount</th>
                
                <th>Payment Mode</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_general_ledger WHERE Type='PR' AND CrDr='cr' AND Flag='Customer-Invoice' AND AccRoll='Customer'";
                  
             
              
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND PaymentDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND PaymentDate<='$ToDate'";
            }
            $sql.= " ORDER BY id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
            ?>
            <tr style="<?php echo $bcolor;?>">
               
              <td><?php echo $i;?></td>
              <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
              <td> 
              <?php if(in_array("10", $Options)){?>
                   <a href="add-customer-amount.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
<!--<a href="print-sell-bill.php?id=<?php echo $row['id']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="feather icon-printer text-danger mr-2"></i></a>-->
<?php } if(in_array("11", $Options)){?>
<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a><?php } ?>
            
               </td><?php } ?>
               <td><?php echo $row['Code']; ?></td>
               <td><?php echo $row['InvoiceNo']; ?></td> 
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['PaymentDate']))); ?></td>
           
                 <td><?php echo $row['AccountName']; ?></td>
            
        <td>₹<?php echo number_format($row['Amount'],2); ?></td>
    
                 <td><?php echo $row['PayMode']; ?></td>
               
               
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

    $(document).ready(function() {
    $('#example').DataTable({
    });
});
</script>
</body>
</html>
