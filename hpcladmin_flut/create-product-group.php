<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Selling-Products";
$Page = "Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Add Products</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row7 = [];
$currentProdIds = [];

if ($id > 0) {
    $sql7 = "SELECT * FROM tbl_product_group WHERE id='$id'";
    $fetched = getRecord($sql7);
    if (is_array($fetched)) {
        $row7 = $fetched;
        $currentProdIds = !empty($row7['ProdId']) ? explode(',', $row7['ProdId']) : [];
    }
}

$row7Defaults = ['GroupName' => '', 'ProdId' => '', 'Status' => '1'];
$row7 = array_merge($row7Defaults, $row7);

// Get all product IDs already used in any group
$sql8 = "SELECT GROUP_CONCAT(DISTINCT ProdId) AS SaveProdId FROM tbl_product_group WHERE Status=1";
$row8 = getRecord($sql8);
$SaveProdId = $row8['SaveProdId'] ?? '';
$allUsedIds = !empty($SaveProdId) ? explode(',', $SaveProdId) : [];

// Exclude other group products if editing
if($id > 0){
    $excludeIds = array_diff($allUsedIds, $currentProdIds);
} else {
    $excludeIds = $allUsedIds;
}

// Convert array to comma-separated string for SQL
$excludeIdsStr = !empty($excludeIds) ? implode(',', $excludeIds) : '0';

if(isset($_POST['submit'])){
    $GroupName = addslashes(trim($_POST["GroupName"]));
    $Status = $_POST['Status'];
    if($_POST['ProdId']!=''){
$ProdId = implode(",", $_POST['ProdId']);
}
else{
   $ProdId = 0; 
}

if($id <= 0){
    $sql = "INSERT INTO tbl_product_group SET GroupName='$GroupName',ProdId='$ProdId',Status='$Status',CreatedBy='$user_id'";
    $conn->query($sql);
    echo "<script>alert('Group Created Successfully');window.location.href='view-product-group.php';</script>";
}
else{
  $sql = "UPDATE tbl_product_group SET GroupName='$GroupName',ProdId='$ProdId',Status='$Status',ModifiedBy='$user_id' WHERE id='$id'";
    $conn->query($sql);  
    echo "<script>alert('Group Updated Successfully');window.location.href='view-product-group.php';</script>";
}
}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"><?php echo $id > 0 ? 'Edit' : 'Create'; ?> Product Group</h4>


<form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

<div class="mb-3">
<div id="" class="card animated fadeIn">
<div class="card-body">
    <input type="hidden" name="action" value="Add">
    <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999);?>">
    <input type="hidden" name="id" id="id" value="<?php echo $id > 0 ? $id : ''; ?>"/>
     <div class="form-row">
<div class="form-group col-lg-12">
<label class="form-label">Group Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="GroupName" value="<?php echo $row7["GroupName"]; ?>" required="">
<div class="clearfix"></div>
</div>


<div class="form-group col-lg-12">
<label class="form-label">MRP Products <span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="ProdId[]" id="MrpProdId" multiple>


 <?php 
 $excludeIdsStr = rtrim($excludeIdsStr, ',');
  $sql12 = "SELECT id,ProductName,MinPrice FROM tbl_cust_products2 WHERE ProdType=0 AND ProdType2=1 AND Status=1 AND id NOT IN($excludeIdsStr)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if(in_array($result["id"],$currentProdIds)) { ?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']." (".$result['MinPrice'].")";?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Making Products <span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="ProdId[]" id="MakeProdId" multiple>


 <?php 
 $excludeIdsStr = rtrim($excludeIdsStr, ',');
  $sql12 = "SELECT id,ProductName,MinPrice FROM tbl_cust_products2 WHERE ProdType=0 AND ProdType2=2 AND Status=1 AND id NOT IN($excludeIdsStr)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if(in_array($result["id"],$currentProdIds)) { ?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']." (".$result['MinPrice'].")";?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>In-Active</option>
</select>
</div>



</div>






 <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>

                
                                    </div>
</div>
</div>


</div>
</form>


</div>

</div>

<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>


</body>
</html>
