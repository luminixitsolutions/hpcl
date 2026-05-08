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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add Customer Making Product</h4>

<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cust_products2 WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();

$sql33 = "SELECT * FROM tbl_cust_prod_stock_2025 WHERE ProdId='$id'";
$rncnt33 = getRow($sql33);

$query22 = "SELECT count(*) as totrec FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;
?>

<?php 
if (isset($_POST['submit'])) {
    // Sanitize input
    function sanitize($conn, $value) {
        return mysqli_real_escape_string($conn, trim($value));
    }

    // Random String Generator
    function RandomStringGenerator($n) {
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $generated = "";
        for ($i = 0; $i < $n; $i++) {
            $generated .= $domain[rand(0, strlen($domain) - 1)];
        }
        return $generated;
    }

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // Prepare all fields
    $TempPrdId     = sanitize($conn, $_POST['TempPrdId']);
    $ProductName   = sanitize($conn, $_POST['ProductName']);
    $CatId         = sanitize($conn, $_POST['CatId']);
    $SubCatId      = sanitize($conn, $_POST['SubCatId']);
    $BrandId       = sanitize($conn, $_POST['BrandId']);
    $MinPrice      = sanitize($conn, $_POST['MinPrice']);
    $CgstPer       = sanitize($conn, $_POST['CgstPer']);
    $SgstPer       = sanitize($conn, $_POST['SgstPer']);
    $IgstPer       = sanitize($conn, $_POST['IgstPer']);
    $GstAmt        = sanitize($conn, $_POST['GstAmt']);
    $ProdPrice     = sanitize($conn, $_POST['ProdPrice']);
    $CgstAmt       = sanitize($conn, $_POST['CgstAmt']);
    $SgstAmt       = sanitize($conn, $_POST['SgstAmt']);
    $IgstAmt       = sanitize($conn, $_POST['IgstAmt']);
    $BarcodeNo     = sanitize($conn, $_POST['BarcodeNo']);
    $StockQty      = sanitize($conn, $_POST['StockQty']);
    $MinQty        = sanitize($conn, $_POST['MinQty']);
    $ProdType2     = sanitize($conn, $_POST['ProdType2']);
    $Transfer      = sanitize($conn, $_POST['Transfer']);
    $PurchasePrice = sanitize($conn, $_POST['PurchasePrice']);
    $Unit          = sanitize($conn, $_POST['Unit']);
    $SubTotal      = sanitize($conn, $_POST['SubTotal']);
    $DiscPer       = sanitize($conn, $_POST['DiscPer']);
    $Discount      = sanitize($conn, $_POST['Discount']);
    $Division      = sanitize($conn, $_POST['Division']);
    $Segment       = sanitize($conn, $_POST['Segment']);
    $Family        = sanitize($conn, $_POST['Family']);
    $ClassId       = sanitize($conn, $_POST['ClassId']);
    $McDesc        = sanitize($conn, $_POST['McDesc']);
    $BrandDesc     = sanitize($conn, $_POST['BrandDesc']);
    $Status        = sanitize($conn, $_POST['Status']);
    $SrNo          = sanitize($conn, $_POST['SrNo']);
    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $CreatedDate   = date('Y-m-d');

    // Image upload
    $Photo = $_POST['OldPhoto'] ?? '';
    if (!empty($_FILES['Photo']['name'])) {
        $randno = rand(1, 100);
        $filename = pathinfo($_FILES["Photo"]["name"], PATHINFO_FILENAME);
        $filename = str_replace(" ", "_", $filename);
        $ext = "." . pathinfo($_FILES["Photo"]["name"], PATHINFO_EXTENSION);
        $dest = '../../uploads/' . $randno . "_" . $filename . $ext;
        if (move_uploaded_file($_FILES['Photo']['tmp_name'], $dest)) {
            $Photo = $randno . "_" . $filename . $ext;
        }
    }

    $conn->begin_transaction();

    try {
        if (empty($id)) {
            // ADD PRODUCT
            $Code = RandomStringGenerator(10);
            $sql = "INSERT INTO tbl_cust_products2 
                    SET Division='$Division', Segment='$Segment', Family='$Family', ClassId='$ClassId', McDesc='$McDesc',
                        BrandDesc='$BrandDesc', SubTotal='$SubTotal', DiscPer='$DiscPer', Discount='$Discount', Unit='$Unit',
                        PurchasePrice='$PurchasePrice', Transfer='$Transfer', ProdType2='$ProdType2', BrandId='$BrandId',
                        SubCatId='$SubCatId', ProductName='$ProductName', CatId='$CatId', MinPrice='$MinPrice',
                        Status='$Status', SrNo='$SrNo', CreatedDate='$CreatedDate', CgstPer='$CgstPer', SgstPer='$SgstPer',
                        IgstPer='$IgstPer', GstAmt='$GstAmt', ProdPrice='$ProdPrice', CgstAmt='$CgstAmt', SgstAmt='$SgstAmt',
                        IgstAmt='$IgstAmt', BarcodeNo='$BarcodeNo', StockQty='$StockQty', TempPrdId='$TempPrdId',
                        MinQty='$MinQty', Photo='$Photo'";
            if (!$conn->query($sql)) throw new Exception("Error inserting product: " . $conn->error);

            $ProdId = $conn->insert_id;
            $Code2 = $Code . $ProdId;
            if (!$conn->query("UPDATE tbl_cust_products2 SET code='$Code2' WHERE id='$ProdId'"))
                throw new Exception("Error updating product code: " . $conn->error);
                
                /* $sql = "SELECT id FROM `tbl_users_bill` WHERE Roll=5";
    $row = getList($sql);
    foreach($row as $result){
        $CreatedBy = $result['id'];
       $sql = "SELECT * FROM tbl_cust_products_2025 WHERE CreatedBy='$CreatedBy' AND ProdType=0";
       $rncnt = getRow($sql);
       if($ProdType2 == 3){
           $checkstatus = 1;  
       }
       else{
       if($rncnt > 0){
            $checkstatus = 0;
       }
       else{
           $checkstatus = 1;  
       }
       }
        $Code = RandomStringGenerator($n); 
        $Code2 = $Code."".$result2['id'];
        $sql = "INSERT INTO tbl_cust_products_2025 SET Division='$Division',Segment='$Segment',Family='$Family',ClassId='$ClassId',McDesc='$McDesc',BrandDesc='$BrandDesc',SubTotal='$SubTotal',DiscPer='$DiscPer',Discount='$Discount',Unit='$Unit',modified_time='$modified_time',PurchasePrice='$PurchasePrice',Transfer='$Transfer',ProdType2='$ProdType2',code='$Code2',BrandId='$BrandId',SubCatId='$SubCatId',ProdId='$ProdId',checkstatus='$checkstatus',CreatedBy='$CreatedBy',ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',CreatedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',BarcodeNo='$BarcodeNo',StockQty='$StockQty',TempPrdId='$TempPrdId',MinQty='$MinQty'";
        $conn->query($sql);
    }*/

            // RAW MATERIAL INSERT
            if (!empty($_POST["CustProdId"])) {
                foreach ($_POST["CustProdId"] as $i => $CustProdId) {
                    $MakingQty = $_POST["MakingQty"][$i];
                    if (!empty($MakingQty) && $MakingQty > 0) {
                        $MakingQty2 = $_POST["MakingQty2"][$i];
                        $MakingQtyUnit2 = $_POST["MakingQtyUnit2"][$i];
                        $RawUnit = $_POST["Unit"][$i];

                        $sql22 = "INSERT INTO tbl_raw_prod_make_qty_2025 
                                  SET RawQty='$MakingQty', RawUnit='$RawUnit', RawProdId='$CustProdId',
                                      CustProdId='$ProdId', MakingQty='$MakingQty', MakingQty2='$MakingQty2',
                                      MakingQtyUnit2='$MakingQtyUnit2'";
                        if (!$conn->query($sql22)) throw new Exception("Error inserting raw product: " . $conn->error);
                    }
                }
            }
            $msg = "Making Product Added Successfully!";
        } 
        else {
            // UPDATE PRODUCT
            $sql = "UPDATE tbl_cust_products2 
                    SET Division='$Division', Segment='$Segment', Family='$Family', ClassId='$ClassId', McDesc='$McDesc',
                        BrandDesc='$BrandDesc', SubTotal='$SubTotal', DiscPer='$DiscPer', Discount='$Discount', Unit='$Unit',
                        PurchasePrice='$PurchasePrice', Transfer='$Transfer', ProdType2='$ProdType2', BrandId='$BrandId',
                        SubCatId='$SubCatId', ProductName='$ProductName', CatId='$CatId', MinPrice='$MinPrice',
                        Status='$Status', SrNo='$SrNo', ModifiedBy='$user_id', ModifiedDate='$CreatedDate', 
                        CgstPer='$CgstPer', SgstPer='$SgstPer', IgstPer='$IgstPer', GstAmt='$GstAmt', ProdPrice='$ProdPrice',
                        CgstAmt='$CgstAmt', SgstAmt='$SgstAmt', IgstAmt='$IgstAmt', BarcodeNo='$BarcodeNo', 
                        StockQty='$StockQty', TempPrdId='$TempPrdId', MinQty='$MinQty', Photo='$Photo'
                    WHERE id='$id'";
            if (!$conn->query($sql)) throw new Exception("Error updating product: " . $conn->error);
            
            $sql = "UPDATE tbl_cust_products_2025 
                    SET Division='$Division', Segment='$Segment', Family='$Family', ClassId='$ClassId', McDesc='$McDesc',
                        BrandDesc='$BrandDesc', SubTotal='$SubTotal', DiscPer='$DiscPer', Discount='$Discount', Unit='$Unit',
                        PurchasePrice='$PurchasePrice', Transfer='$Transfer', ProdType2='$ProdType2', BrandId='$BrandId',
                        SubCatId='$SubCatId', ProductName='$ProductName', CatId='$CatId', MinPrice='$MinPrice',
                        Status='$Status', SrNo='$SrNo', ModifiedBy='$user_id', ModifiedDate='$CreatedDate', 
                        CgstPer='$CgstPer', SgstPer='$SgstPer', IgstPer='$IgstPer', GstAmt='$GstAmt', ProdPrice='$ProdPrice',
                        CgstAmt='$CgstAmt', SgstAmt='$SgstAmt', IgstAmt='$IgstAmt', BarcodeNo='$BarcodeNo', 
                        StockQty='$StockQty', TempPrdId='$TempPrdId', MinQty='$MinQty', Photo='$Photo'
                    WHERE ProdId='$id'";
            if (!$conn->query($sql)) throw new Exception("Error updating product: " . $conn->error);

            $conn->query("DELETE FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='$id'");

            if (!empty($_POST["CustProdId"])) {
                foreach ($_POST["CustProdId"] as $i => $CustProdId) {
                    $MakingQty = $_POST["MakingQty"][$i];
                    if (!empty($MakingQty) && $MakingQty > 0) {
                        $MakingQty2 = $_POST["MakingQty2"][$i];
                        $MakingQtyUnit2 = $_POST["MakingQtyUnit2"][$i];
                        $RawUnit = $_POST["Unit"][$i];

                        $sql22 = "INSERT INTO tbl_raw_prod_make_qty_2025 
                                  SET RawQty='$MakingQty', RawUnit='$RawUnit', RawProdId='$CustProdId',
                                      CustProdId='$id', MakingQty='$MakingQty', MakingQty2='$MakingQty2',
                                      MakingQtyUnit2='$MakingQtyUnit2'";
                        if (!$conn->query($sql22)) throw new Exception("Error inserting raw product (update): " . $conn->error);
                    }
                }
            }
            $msg = "Making Product Updated Successfully!";
        }

        $conn->commit();
        echo "<script>alert('$msg'); window.location.href='add-customer-making-product.php';</script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Transaction Failed: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
}
  
 ?>
<form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
    
<!--<ul class="card px-4 pt-3 mb-3">
<li>
<a href="#smartwizard-6-step-1" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">1</span>
<div class="text-muted small">FIRST STEP</div>
Basic Info
</a>
</li>
<li>
<a href="#smartwizard-6-step-2" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">2</span>
<div class="text-muted small">SECOND STEP</div>
Description
</a>
</li>
<li>
<a href="#smartwizard-6-step-3" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">3</span>
<div class="text-muted small">THIRD STEP</div>
Product Images
</a>
</li>
 <li>
<a href="#smartwizard-6-step-4" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">4</span>
<div class="text-muted small">FOURTH STEP</div>
Other Products
</a>
</li>

</ul>-->
<div class="mb-3">
<div id="" class="card animated fadeIn">
<div class="card-body">
    <input type="hidden" name="action" value="Add">
    <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999);?>">
    <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"/> 
     <div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="<?php echo $row7["ProductName"]; ?>" required="">
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Brand </label>
  <select class="form-control" id="BrandId" name="BrandId">
<option selected="" disabled="" value="">Select Brand</option>
<?php 
        $q = "select * from tbl_brands WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['BrandId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

 
<div class="form-group col-lg-2">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from tbl_cust_category_2025 WHERE Status='1' AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['CatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Sub Category </label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" disabled="" value="">Select Sub Category</option>
<?php 
        $q = "select * from tbl_cust_sub_category_2025 WHERE Status='1' AND CatId='".$row7['CatId']."' AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['SubCatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!--<div class="form-group col-lg-2">
<label class="form-label">Division </label>
  <select class="form-control" id="Division" name="Division" >
<option selected="" disabled="" value="">Select Division</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['Division']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Segment </label>
  <select class="form-control" id="Segment" name="Segment" >
<option selected="" disabled="" value="">Select Segment</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['Segment']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Family </label>
  <select class="form-control" id="Family" name="Family" >
<option selected="" disabled="" value="">Select Family</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=5";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['Family']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Class </label>
  <select class="form-control" id="ClassId" name="ClassId" >
<option selected="" disabled="" value="">Select Class</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=6";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['ClassId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Mc. Desc </label>
  <select class="form-control" id="McDesc" name="McDesc" >
<option selected="" disabled="" value="">Select Mc. Desc</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['McDesc']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Brand Desc</label>
  <select class="form-control" id="BrandDesc" name="BrandDesc" >
<option selected="" disabled="" value="">Select Brand Desc</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=8";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['BrandDesc']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>-->

</div>



 <div class="form-row">
<div class="form-group col-lg-2">
<label class="form-label">Purchase Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="PurchasePrice" name="PurchasePrice" class="form-control" value="<?php echo $row7["PurchasePrice"]; ?>" required="" onKeyPress="return isNumberKey(event)" required>
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-1">
<label class="form-label">Total Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="SubTotal" name="SubTotal" class="form-control" value="<?php echo $row7["SubTotal"]; ?>" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)" required>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Discount %<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="DiscPer" name="DiscPer" class="form-control" value="<?php echo $row7["DiscPer"]; ?>" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)" required>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
<div class="clearfix"></div>
</div>
</div>

<input type="hidden" id="Discount" name="Discount" value="<?php echo $row7["Discount"];?>">

<div class="form-group col-lg-1">
<label class="form-label">Final Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="<?php echo $row7["MinPrice"]; ?>" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)" readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="2.5" readonly required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="2.5" readonly required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="0" readonly required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Total GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="GstAmt" name="GstAmt" class="form-control" value="<?php echo $row7["GstAmt"]; ?>" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>

<input type="hidden" id="CgstAmt" name="CgstAmt" value="<?php echo $row7["CgstAmt"];?>">
<input type="hidden" id="SgstAmt" name="SgstAmt" value="<?php echo $row7["SgstAmt"];?>">
<input type="hidden" id="IgstAmt" name="IgstAmt" value="<?php echo $row7["IgstAmt"];?>">



<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="<?php echo $row7["ProdPrice"]; ?>" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Unit</label>
 <select class="form-control" id="Unit" name="Unit" >
<option selected="" value="">...</option>
<?php
                $sql4 = "SELECT Name AS Unit,id FROM tbl_units_2025";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
<option <?php if($row7['Unit']==$result['Unit']){ ?> selected <?php } ?> value="<?php echo $result['Unit']; ?>"><?php echo $result['Unit']; ?></option>
              <?php } ?></select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Barcode No</label>
<input type="text" class="form-control" name="BarcodeNo" value="<?php echo $row7["BarcodeNo"]; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Min Stock Qty</label>
<input type="text" class="form-control" name="MinQty" value="<?php echo $row7["MinQty"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Publish</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Not Publish</option>
</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Product Type<span class="text-danger">*</span></label>
<select class="form-control" name="ProdType2" required="">
<!--<option value="1" <?php if($row7["ProdType2"]=='1') {?> selected <?php } ?>>MRP Product</option>-->
<option value="2" <?php if($row7["ProdType2"]=='2') {?> selected <?php } ?>>Making Product</option>
<!--<option value="3" <?php if($row7["ProdType2"]=='3') {?> selected <?php } ?>>Other Product</option>-->
</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Transfer Product<span class="text-danger">*</span></label>
<select class="form-control" name="Transfer" required="">
<option value="1" <?php if($row7["Transfer"]=='1') {?> selected <?php } ?>>Yes</option>
<option value="0" <?php if($row7["Transfer"]=='0') {?> selected <?php } ?>>No</option>
</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Sr No</label>
<input type="text" class="form-control" name="SrNo" value="<?php echo $row7["SrNo"]; ?>">
<div class="clearfix"></div>
</div>



<!--<div class="form-group col-lg-2">
<label class="form-label">Opening Stock Qty<span class="text-danger">*</span></label>
<input type="number" class="form-control" name="StockQty" value="<?php echo $row7["StockQty"]; ?>" required="" min="0">
<div class="clearfix"></div>

</div>-->

<div class="form-group col-md-6">
    <label class="form-label">Copy Making Product Receipe</label>
    <select class="select2-demo form-control" name="MakingProdRec" id="MakingProdRec" onchange="loadRecipe(this.value)">
        <option selected value="0">Select Product</option>
        <?php 
        $sql12 = "SELECT p.id, p.ProductName, p.MinPrice 
                  FROM tbl_cust_products2 p
                  INNER JOIN tbl_raw_prod_make_qty_2025 r ON p.id = r.CustProdId
                  WHERE p.ProdType=0 AND p.ProdType2=2 AND p.Status=1 
                  GROUP BY p.id ORDER BY p.ProductName ASC";   
        $row12 = getList($sql12);
        foreach($row12 as $result){
        ?>
            <option <?php if($row7["MakingProdRec"] == $result['id']) {?> selected <?php } ?> 
                value="<?php echo $result['id'];?>">
                <?php echo $result['ProductName']." / ".$result['MinPrice']; ?>
            </option>
        <?php } ?>
    </select>
</div>


<!--<div class="form-group col-lg-6">
  <label class="form-label">Product Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto" value="<?php echo $row7["Photo"]; ?>">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>-->

</div>

<!-- Container to show recipe rows -->
<div id="recipeContainer"></div>

<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
      <div class="form-row" id="row<?php echo $i;?>">
 <div class="form-group col-md-4">
    <label class="form-label">Raw Product </label>
        <select class="form-control select2-demo" style="width: 100%" name="CustProdId[]" id="CustProdId<?php echo $i; ?>" onchange="getRawProductDetails(this.value,<?php echo $i; ?>)" >
        <option selected value="">...</option>
            <?php
                $sql4 = "SELECT id, ProductName, 'RAW' AS ProdCategory FROM tbl_cust_products2 WHERE Status=1 AND ProdType=1 AND CreatedDate>='2025-08-22' UNION ALL SELECT id, ProductName, 'Other' AS ProdCategory FROM tbl_cust_products2 WHERE Status=1 AND ProdType=0 AND ProdType2=3";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
        <option <?php if ($result12["RawProdId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?= $result['ProductName']; ?> (<?= $result['ProdCategory']; ?>)</option>
       <?php } ?>
    </select>
</div>

 <div class="form-group col-lg-2">
<label class="form-label">Making Qty</label>
<input type="number" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $i; ?>" placeholder="" value="<?php echo $result12["MakingQty"]; ?>">
<div class="clearfix"></div>
</div> 

<div class="form-group col-lg-1">
<label class="form-label">Unit</label>
 <input type="text" class="form-control unit" name="Unit[]" id="Unit<?php echo $i; ?>" value="<?php echo $result12['RawUnit'];?>" readonly>
<div class="clearfix"></div>
</div> 


<input type="hidden" class="form-control" name="MakingQty2[]" id="MakingQty2<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="MakingQtyUnit2[]" id="MakingQtyUnit2<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
</div>
<?php $i++;} ?>

<div id="dynamic_field"> 
    <div class="form-row" data-row="<?php echo $row_cnt; ?>">
        <div class="form-group col-md-4">
            <label class="form-label">Raw Product</label>
            <select class="form-control product-select select2-demo" name="CustProdId[]" style="width: 100%" id="CustProdId<?php echo $row_cnt; ?>" onchange="getRawProductDetails(this.value,<?php echo $row_cnt; ?>)" >
                <option value="">Select a product</option>
                <?php
                $sql4 = "SELECT id, ProductName, 'RAW' AS ProdCategory FROM tbl_cust_products2 WHERE Status=1 AND ProdType=1 AND CreatedDate>='2025-08-22' UNION ALL SELECT id, ProductName, 'Other' AS ProdCategory FROM tbl_cust_products2 WHERE Status=1 AND ProdType=0 AND ProdType2=3";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
                ?>
                    <option value="<?= $result['id']; ?>">
                        <?= $result['ProductName']; ?> (<?= $result['ProdCategory']; ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group col-md-2"> 
            <label class="form-label">Making Qty</label>
            <input type="number" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $row_cnt; ?>">
        </div>
        
        <div class="form-group col-md-1"> 
            <label class="form-label">Unit</label>
            <input type="text" name="Unit[]" class="form-control" id="Unit<?php echo $row_cnt; ?>" readonly>
        </div>

        <div class="form-group col-md-1" style="padding-top: 30px;">
            <button type="button" class="btn btn-secondary add-more">+</button>
        </div>
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
<style>
/* Overlay background with blur */
#pageLoader {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(4px);   /* blur effect */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
#pageLoader .loader-content {
    text-align: center;
    color: #007bff;
    font-weight: bold;
}
</style>
<!-- Full Page Loader -->
<div id="pageLoader" style="display:none;">
    <div class="loader-content">
        <i class="fa fa-spinner fa-spin fa-3x"></i>
        <p>Loading Recipe...</p>
    </div>
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

<script>
let rowCount = <?php echo $row_cnt; ?>;

$(document).ready(function () {
    // Initialize first select2 if static
    $('.product-select').select2({
        placeholder: "Select a product",
        allowClear: true
    });

    // Listen on container for dynamically added buttons
    $('#dynamic_field').on('click', '.add-more', function () {
        rowCount++;

        $.ajax({
            url: 'get_new_row.php',
            type: 'GET',
            data: { row: rowCount },
            success: function (response) {
                $('#dynamic_field').append(response);

                // Re-initialize Select2 for new product-select
                $('#dynamic_field .product-select').last().select2({
                    placeholder: "Select a product",
                    allowClear: true
                });
            },
            error: function () {
                alert("Failed to fetch new row.");
            }
        });
    });
    
    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
          
           $('#row'+button_id+'').remove();  
           
      }); 
      
});
  
    function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function getRawProductDetails(id,srno){
    var action = "getRawProductDetails";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:id},
    success:function(data)
    {
      var res = JSON.parse(data);
      $('#Unit'+srno).val(res.Unit);
    }
    });
}
function getProdPrice(CgstPer,SgstPer,IgstPer,DiscPer,SubTotal){
            
            var DiscAmt = Number(SubTotal)*(Number(DiscPer)/100);
            $('#Discount').val(parseFloat(DiscAmt).toFixed(2));
            
            var FinalPrice = Number(SubTotal)-Number(DiscAmt);
            $('#MinPrice').val(parseFloat(FinalPrice).toFixed(2));
            //var MinPrice = Number(prodprice);
            var MinPrice = Number(FinalPrice)/Number(1.05);
            $('#ProdPrice').val(parseFloat(MinPrice).toFixed(2));
            var GstAmt = Number(FinalPrice) - Number(MinPrice);
            $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
            var CgstAmt = Number(GstAmt) / 2;
            var SgstAmt = Number(GstAmt) / 2;
            $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
            $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
            $('#IgstAmt').val(0)
        }


   $(document).ready(function() {


   $(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getSubCat";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#SubCatId').html(data);
    }
    });

    

 });

       
 
   });
   
  // Fetch recipe when product selected
function loadRecipe(productId){
    if(productId==0){ 
        $("#recipeContainer").html(""); 
        return; 
    }

    $.ajax({
        url: "fetch_recipe.php",
        type: "POST",
        data: { id: productId },
        beforeSend: function(){
            // Show page blur loader
            $("#pageLoader").fadeIn();
        },
        success: function(response){
            $("#recipeContainer").html(response);
            $('.select2-demo').select2(); // re-init select2
        },
        error: function(){
            $("#recipeContainer").html('<div class="text-danger p-3">Error loading recipe.</div>');
        },
        complete: function(){
            // Hide loader once done
            $("#pageLoader").fadeOut();
        }
    });
}

</script>
</body>
</html>
