<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$AdminLoginId = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products-2025";
$Page = "View-Cust-Stock-2025";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Stock List</title>
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
<h4 class="font-weight-bold py-3 mb-0">View MRP Product GRN List
    
</h4>

<div class="card" style="padding: 10px;">
   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Product Name</th>
               <th>Date</th>
                <th>Qty</th>
                <th>Purchase Price</th>
                <th>Sell Price</th>
              
             
            </tr>
        </thead>
        <tbody>
             <?php 
                            $i=1;
                    $id = $_GET['id'];
                    $sql = "SELECT ts.*,p.ProductName,p.MinQty FROM tbl_cust_ved_prod_stock ts 
                    INNER JOIN tbl_cust_products_2025 p ON ts.ProdId=p.id WHERE ts.InvId='$id'";
                            $row = getList($sql);
                            foreach($row as $result){
                            ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo $result['ProductName']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['StockDate']))); ?></td>
               <td><?php echo $result['Qty']; ?></td>
              
              <td><?php echo $result['PurchasePrice'];?></td>
              <td><?php echo $result['SellPrice'];?></td>
            
            
       
              
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
        "scrollX": true
    });
});
</script>
</body>
</html>
