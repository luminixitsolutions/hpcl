<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Godown-To-Coco-Franchise-Report";
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





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Godown To COCO Franchise Report
  
</h4>

<div class="card" style="padding: 10px;">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-3">
    <label class="form-label">Godown <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" name="GodownId" id="GodownId" required>

<option selected="" value="all">All</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=93";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["GodownId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
    </div>

<div class="form-group col-md-3">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
       <select class="select2-demo form-control" name="FranchiseId" id="FranchiseId" required>

<option selected="" value="all">All</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND ShowFrStatus=1 AND OwnFranchise=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["FranchiseId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
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
               <th>Sr No</th>
               <th>Go-Down</th> 
               <th>Franchise</th> 
                <th>Transfer Date</th> 
                <th>Total Qty</th>
               <th>Total Amount</th>
              
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS GodownName,tu2.ShopName FROM tbl_transfer_godown_raw_prod_stock tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.GodownId 
            LEFT JOIN tbl_users_bill tu2 ON tu2.id=tp.FranchiseId WHERE tp.OwnShop=1";
            if($_POST['GodownId']){
                $GodownId = $_POST['GodownId'];
                if($GodownId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.GodownId='$GodownId'";
                }
            }
            if($_POST['FranchiseId']){
                $FranchiseId = $_POST['FranchiseId'];
                if($FranchiseId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.FranchiseId='$FranchiseId'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tp.StockDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tp.StockDate<='$ToDate'";
            }
            $sql.=" ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $TotQty+=$row['TotQty'];
                $TotalAmount+=$row['TotalAmount'];
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['GodownName']; ?></td>
               <td><?php echo $row['ShopName']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               <td><?php echo $row['TotQty']; ?></td> 
              <td>&#8377;<?php echo $row['TotalAmount']; ?></td> 
            
        
              
            </tr>
           <?php $i++;} ?>
           
           
           <tr>
               <td><?php echo $i; ?> </td>
               <td></td>
               <td></td>
                 <th>Total</th>
               <th><?php echo $TotQty; ?></th> 
              <th>&#8377;<?php echo $TotalAmount; ?></th> 
            
        
              
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
    });
});
</script>
</body>
</html>
