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
  $sql11 = "DELETE FROM tbl_customer_invoice WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_customer_invoice_details WHERE InvId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND Flag='Customer-Invoice'";
  $conn->query($sql11);
  $sql = "DELETE FROM tbl_product_stocks WHERE InvId='$id'";
  $conn->query($sql);
  $sql = "DELETE FROM tbl_cust_prod_stock WHERE InvId='$id'";
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
<h4 class="font-weight-bold py-3 mb-0">Customer Invoice List

</h4>
<br>

<div class="card">
   
<div class="card-datatable table-responsive">
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
        <thead class="thead-light">
            <tr>
            
               
               <th>#</th>
             
                 <th>Item</th>
               <th>Order No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                
             
               <th>Franchise Name</th>
               
                <th>Amount</th>
                <th>Discount</th>
               <th>Total Amount</th>
                <th>Payment Mode</th>
                
                
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $invid = $_GET['invid'];
             $sql = "SELECT * FROM tbl_customer_invoice WHERE Unqid IN ($invid)";
             
           
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            if($_POST['PayType']){
                $PayType = $_POST['PayType'];
                if($PayType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND PayType='$PayType'";
                }
            }
            $sql.=" ORDER BY InvoiceDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $TotAmt+=$row['NetAmount'];
                $TotSubAmt+=$row['SubTotal'];
                $TotDisc+=$row['Discount'];
                $sql2 = "SELECT ShopName FROM tbl_users_bill WHERE id='".$row['FrId']."'";
                $row2 = getRecord($sql2);
             ?>
            <tr>
             <td><?php echo $i;?></td>
           <td class="align-middle"><a href="view-order-items.php?id=<?php echo $row['Unqid']; ?>" target="_blank">View</a></td>
             <td class="align-middle"><?php echo $row['OrderNo']; ?></td>
              <td class="align-middle"><?php echo $row['InvoiceNo']; ?></td>
              <td class="align-middle"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
           
             <!--   <td class="align-middle"><?php echo $row['CustName']; ?></td>-->
             <td class="align-middle"><?php echo $row2['ShopName']; ?></td>
                 <td class="align-middle">&#8377; <?php echo number_format($row['SubTotal'],2); ?></td>
                  <td class="align-middle" style="color:red;">&#8377; <?php echo number_format($row['Discount'],2); ?></td>
                  <td class="align-middle">&#8377; <?php echo number_format($row['NetAmount'],2); ?></td>
                 
                  <td class="align-middle"><?php echo $row['PayType']; ?></td>
                
            
         
              
            </tr>
           <?php $i++;} ?>
           
           <tr>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
                <th></th>
               <th>Total</th>
               <th>&#8377;<?php echo number_format($TotSubAmt,2);?></th>
               <th>&#8377;<?php echo number_format($TotDisc,2);?></th>
               <th>&#8377;<?php echo number_format($TotAmt,2);?></th>
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
        order: [[1, 'desc']],
      "scrollX": true
    });
});
</script>
</body>
</html>
