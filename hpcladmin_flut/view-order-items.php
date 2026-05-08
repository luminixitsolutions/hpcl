<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Invoice";
$Page = "View-Today-Orders";
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
//   $sql11 = "DELETE FROM tbl_customer_invoice WHERE id = '$id'";
//   $conn->query($sql11);
//   $sql11 = "DELETE FROM tbl_customer_invoice_details WHERE InvId = '$id'";
//   $conn->query($sql11);
//   $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND Flag='Customer-Invoice'";
//   $conn->query($sql11);
//   $sql = "DELETE FROM tbl_product_stocks WHERE InvId='$id'";
//   $conn->query($sql);
//   $sql = "DELETE FROM tbl_cust_prod_stock WHERE InvId='$id'";
//   $conn->query($sql);
$modified_time = gmdate('Y-m-d H:i:s.').gettimeofday()['usec'];
    $sql11 = "UPDATE tbl_customer_invoice SET delete_flag=1,modified_time='$modified_time',push_flag=1 WHERE id = '$id'";
    $conn->query($sql11);
    $sql11 = "UPDATE tbl_customer_invoice_details SET delete_flag=1,modified_time='$modified_time',push_flag=1 WHERE InvId = '$id'";
    $conn->query($sql11);

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
  if($status == 0){
      $sql = "UPDATE tbl_customer_invoice SET Status=1 WHERE id='$id'";
      $conn->query($sql);
  }
  else{
      $sql = "UPDATE tbl_customer_invoice SET Status=0 WHERE id='$id'";
      $conn->query($sql);
  }
  ?>
    <script type="text/javascript">
      alert("Status Updated Successfully!");
      window.location.href="view-customer-invoices.php";
    </script>
<?php }
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Order Item List

</h4>
<br>

<div class="card">
   
<div class="card-datatable table-responsive">
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
        <thead class="thead-light">
            <tr>
              
               <th>Invoice Id</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Rate</th>
               
               
                <th>Total Amount</th>
               
              
   
            </tr>
        </thead>
        <tbody>
            <?php 
          
           
              $sql = "SELECT tcid.*,tcp.ProductName FROM tbl_customer_invoice_details tcid INNER JOIN tbl_cust_products tcp ON tcid.ProdId=tcp.id WHERE tcid.ServerInvId='".$_GET['id']."'";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT Discount,NetAmount FROM tbl_customer_invoice WHERE Unqid='".$_GET['id']."'";
                $row2 = getRecord($sql2);
                $TotQty+=$row['Qty'];
                $TotPrice+=$row['Price'];
                $TotAmt+=$row['Total'];
             ?>
            <tr>
            
            
          
             
         
             <td class="align-middle"><?php echo $row['InvId']; ?></td>
              <td class="align-middle"><?php echo $row['ProductName']; ?></td>
    
           
                <td class="align-middle"><?php echo $row['Qty']; ?></td>
             
                
                  <td class="align-middle">&#8377; <?php echo number_format($row['Price'],2); ?></td>
               <td class="align-middle">&#8377; <?php echo number_format($row['Total'],2); ?></td>
         
              
            </tr>
           <?php $i++;} ?>
            
           
        </tbody>
        <tbody>
            <tr>
               <th></th>
               
               
               <th>Total</th>
               <th><?php echo $TotQty;?></th>
               <th>&#8377;<?php echo number_format($TotPrice,2);?></th>
               <th>&#8377;<?php echo number_format($TotAmt,2);?></th>
              
           </tr>
           <tr style="color:red;">
               <th></th>
               <th>Discount</th>
               <th></th>
               <th></th>
               
               <th>&#8377;<?php echo number_format($row2['Discount'],2);?></th>
              
           </tr>
            <tr>
               <th></th>
               <th>Total Amount</th>
               <th></th>
               <th></th>
               
               <th>&#8377;<?php echo number_format($row2['NetAmount'],2);?></th>
              
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
        order: [[1, 'desc']],
      "scrollX": true
    });
});
</script>
</body>
</html>
