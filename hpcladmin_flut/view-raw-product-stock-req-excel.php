<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Products";

function RandomStringGenerator($n)
    {
        $generated_string = "";   
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++)
        {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        return $generated_string;
    } 
    
    
$sql = "SELECT * FROM tbl_cust_products_2025 WHERE code is null";
    $row = getList($sql);
    foreach($row as $result){
        $n = 10;
        $Code = RandomStringGenerator($n); 
        $Code2 = $Code."".$result['id'];
        $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
        $sql2 = "UPDATE tbl_cust_products_2025 SET code='$Code2',modified_time='$modified_time' WHERE id='".$result['id']."'";
        $conn->query($sql2);
    }
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Product List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Request Raw Product Stock List

</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
              <th>Sr.No</th>
    <th>Product Name</th>
    <th>Req Qty</th>
    <th>Purchase Price</th>
    <th>Sell Price</th>
    <th>Approve Qty</th>
    <th>Date</th>
    <th>Status</th>
    <th>Comment</th>
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
          $sql2 = "SELECT tve.*,tcp.ProductName FROM tbl_fr_req_prod_stock tve INNER JOIN tbl_cust_products_2025 tcp ON tcp.id=tve.ProdId WHERE tve.InvId='".$_GET['id']."'";
            $row2 = getList($sql2);
  foreach($row2 as $result){
  $total = $result['Qty2'] * $result['PurchasePrice'];
            $grandTotal += $result['Qty2'];
             
             ?>
            <tr>
              
  
    <td><?php echo $i;?></td>
   <td><?php echo $result['ProductName']; ?></td>
            <td><?php echo $result['Qty2']." ".$result['Unit2'] ; ?></td>
            <td><?php echo $result['PurchasePrice']; ?></td>
             <td><?php echo $result['SellPrice']; ?></td>
            <!--<td><?php echo round($total); ?></td>-->
          
  <td><?php echo $result['PurchaseQty']." ".$result['Unit2'];?></td>
  <td><?php echo $result['PurchaseDate'];?></td>
  <td><select class="form-control" id="PurchaseStatus<?php echo $i;?>" name="PurchaseStatus[]" disabled>
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($result["PurchaseStatus"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($result["PurchaseStatus"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($result["PurchaseStatus"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select></td>
  <td><?php echo $result['PurchaseComment'];?></td>
                 
             
            
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


<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
 
	$(document).ready(function() {
    $('#example').DataTable({
      "scrollX": true,
        dom: 'Bfrtip',
        order: [[0, 'desc']],
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>

</body>
</html>
