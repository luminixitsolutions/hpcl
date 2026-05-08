<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$AdminLoginId = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Transfer-Raw-Stock-Godown-To-Franchise-COCO";
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
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_transfer_godown_raw_prod_stock_2025 WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_transfer_godown_raw_stock_items_2025 WHERE TransferId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_godown_raw_prod_stock_2025 WHERE TransferId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-transfer-godwon-raw-stock-coco.php";
    </script>
<?php } 

function franchiseBadge($code) {
  switch ((string)$code) {
    case '1': return '<span class="badge badge-pill" style="background:#28a745;color:#fff;">COCO</span>';
    case '2': return '<span class="badge badge-pill" style="background:#ff9800;color:#fff;">FOFO</span>';
    case '3': return '<span class="badge badge-pill" style="background:#17a2b8;color:#fff;">FOCO</span>';
    case '4': return '<span class="badge badge-pill" style="background:#dc3545;color:#fff;">COFO</span>';
    default:  return '<span class="badge badge-pill" style="background:#6c757d;color:#fff;">Not Assigned</span>';
  }
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Transfer Raw Stock Godown To Franchise
  <?php if(in_array("14", $Options)) {?> 
<span style="float: right;">
<a href="add-transfer-godown-raw-stock-to-franchise.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
    
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
     <div class="form-group col-md-4">
<label class="form-label">Franchise</label>
 <select class="select2-demo form-control" name="FrId" id="FrId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT id,ShopName,OwnFranchise FROM tbl_users WHERE Status='1' AND Roll=5 ORDER BY ShopName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["FrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?> (<?php echo franchiseBadge($result['OwnFranchise']); ?>)</option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
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
               <th>Sr No</th>
               <th>Go-Down</th> 
               <th>Franchise</th> 
               <th>Franchise Type</th> 
                <th>Transfer Date</th> 
                <th>Total Qty</th>
               <th>Total Amount</th>
                <th>Narration</th>
             
                <th>Created Date</th>
                <th>Invoice Print</th>
              <th>Print</th>
              
               <th>Action</th>
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS GodownName,tu2.ShopName,tu2.OwnFranchise FROM tbl_transfer_godown_raw_prod_stock_2025 tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.GodownId 
            LEFT JOIN tbl_users_bill tu2 ON tu2.id=tp.FranchiseId WHERE tp.ProdType=1";
            if($_REQUEST['FrId']){
                $FrId = $_REQUEST['FrId'];
                if($FrId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.FranchiseId='$FrId'";
                }
            }
            if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tp.StockDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tp.StockDate<='$ToDate'";
            }
            $sql.=" ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT COALESCE(SUM(Qty), 0) AS Qty FROM tbl_transfer_godown_raw_stock_items_2025 WHERE TransferId='".$row['id']."'";
               $row2 = getRecord($sql2);
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['GodownName']; ?></td>
               <td><?php echo $row['ShopName']; ?></td>
               <td><?php echo franchiseBadge($row['OwnFranchise']); ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               <td><?php echo $row2['Qty']; ?></td> 
              <td>&#8377;<?php echo $row['TotalAmount']; ?></td> 
                <td><?php echo $row['Narration']; ?></td>
             
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
             <td><a href="invoice-print.php?id=<?php echo $row['id'];?>" target="_new"><i class="lnr lnr-printer text-danger"></i></a></td>
            <td><a href="stock-tranfer-note.php?id=<?php echo $row['id'];?>" target="_new"><i class="lnr lnr-printer text-danger"></i></a></td>
            
       
            <td>
                 <?php if($AdminLoginId == 2091 || $AdminLoginId == 2003) {?>
                 <a onClick="return confirm('Are you sure you want delete this record?\nNote : Delete all record related this record (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
                 <?php } ?>
                
            </td>
           
        
              
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
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
