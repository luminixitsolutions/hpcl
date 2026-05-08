<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$Adminid = $_SESSION['Admin']['id'];
$MainPage = "Raw-Products-2025";
$Page = "Manage-Raw-Stocks-2025";
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



<?php
if($_REQUEST["action"]=="delete")
{
    if (!empty($id) && is_numeric($id)) {
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_cust_prod_stock_2025 WHERE id = '$id'";
  $conn->query($sql11);
  
  $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='raw product stock deleted',invid='$id',createddate='$createddate',roll='raw-product-stock'";
  $conn->query($sql);
  
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-fr-raw-stock-2025.php";
    </script>
<?php } } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Raw Product Stock List
    <?php if(in_array("115", $Options)) {?>   
<span style="float: right;">
<a href="add-raw-prod-stock-2025.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-2">
<label class="form-label"> Date </label>
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

<?php if(isset($_REQUEST['Search'])) {?>
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
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Id</th>
                <th>Product Name</th> 
                
                 <th>Stock Date</th>
                 <th>Price</th> 
              <th>Stock In Qty</th> 
              <th>Total Price</th> 
                <?php if(in_array("116", $Options) || in_array("117", $Options)) {?>
               <th>Action</th>
              <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.StockDate,ts.Qty2,ts.Unit2,ts.id, p.ProductName,ts.PurchasePrice,ts.SellPrice 
                          FROM tbl_cust_prod_stock_2025 ts 
                          INNER JOIN tbl_cust_products_2025 p ON ts.ProdId = p.id 
                          WHERE ts.FrId = '$BillSoftFrId' 
                          AND ts.Status = 'Cr' 
                          AND ts.ProdType = '1' 
                          AND p.delete_flag = 0  AND p.OldNew=1";
            
            
                           if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND ts.StockDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND ts.StockDate<='$ToDate'";
            }
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $row['id']; ?> </td>
              
                  <td><?php echo $row['ProductName']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
                     <td><?php echo $row['PurchasePrice']; ?></td>
                   <td><?php echo $row['Qty2']." ".$row['Unit2']; ?></td>
       <td><?php echo $row['SellPrice']; ?></td>
           <?php if(in_array("116", $Options) || in_array("117", $Options)) {?>
           <td>
               <!--<a onClick="return confirm('Are you sure you want delete this record?\nNote : Delete all record related this record (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>-->
               </td>
        <?php } else {?> 
        <td></td>
        <?php } ?>
              
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
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
