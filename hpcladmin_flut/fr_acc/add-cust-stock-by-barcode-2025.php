<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Stock";
$Page = "Add-Cust-Stocks-By-Barcode";
$sessionid = session_id();
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cust_prod_stock_2025 WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');
?>

<?php 
  if(isset($_POST['submit'])){
    $ProdId = $_POST['ProdId'];
    $Qty = addslashes(trim($_POST['Qty']));
    $StockDate = addslashes(trim($_POST['StockDate']));
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));
    $PurchasePrice = addslashes(trim($_POST['PurchasePrice']));
    $SellPrice = addslashes(trim($_POST['SellPrice']));

    if($_GET['id'] == ''){
     $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);
  
  $qx = "INSERT INTO tbl_cust_prod_stock_2025_backup SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice',orgstockid='$InvId'";
  $conn->query($qx);
  
   // Fetch the inserted records
$result = $conn->query("SELECT * FROM tbl_cust_prod_stock_2025 WHERE id = '$InvId'");
$row = $result->fetch_assoc();

// Create SQL Dump
$sqlDump = "INSERT INTO tbl_cust_prod_stock_2025 (ProdId, Qty, CreatedBy, StockDate, Narration, Status, UserId, CreatedDate, FrId, PurchasePrice, SellPrice) 
            VALUES ('" . implode("','", array_values($row)) . "');\n";

file_put_contents('stock_backup/'.$BillSoftFrId.'_backup.sql', $sqlDump, FILE_APPEND | LOCK_EX);

  
  $sql = "UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
  $conn->query($sql);
  
  $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new customer product stock added',invid='$InvId',createddate='$createddate',roll='customer-product-stock'";
  $conn->query($sql);
  echo "<script>alert('Product Stock Added Successfully!');window.location.href='view-cust-stocks-2025.php';</script>";
}
else{
    $sql = "UPDATE tbl_cust_prod_stock_2025 SET ProdId='$ProdId',Qty='$Qty',ModifiedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',ModifiedDate='$CreatedDate',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice' WHERE id='$id'";
    $conn->query($sql);
    
    $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='customer product stock updated',invid='$id',createddate='$createddate',roll='customer-product-stock'";
  $conn->query($sql);
    echo "<script>alert('Product Stock Updated Successfully!');window.location.href='view-cust-stocks-2025.php';</script>";
}
      
   

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product Stock</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-10">
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">


 <div class="form-group col-md-4">
<label class="form-label">Scan Barcode No </label>
<input type="text" name="BarcodeNo" id="BarcodeNo" class="form-control" placeholder="" value="" autocomplete="off" required onchange="checkBarcodeNo(this.value, <?php echo $BillSoftFrId; ?>)">
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-8">
<label class="form-label">Product Name </label>
<input type="text" name="ProductName" id="ProductName" class="form-control" placeholder="" value="" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<input type="hidden" name="ProdId" id="ProdId" value="">

    <div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<input type="text" name="AvailStock" id="AvailStock" class="form-control" placeholder="" value="" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-2">
<label class="form-label">Stock In Qty <span class="text-danger">*</span></label>
<input type="text" name="Qty" id="Qty" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-3">
<label class="form-label">Purchase Price <span class="text-danger">*</span></label>
<input type="text" name="PurchasePrice" id="PurchasePrice" class="form-control" placeholder="" value="" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-2">
<label class="form-label">Sell Price <span class="text-danger">*</span></label>
<input type="text" name="SellPrice" id="SellPrice" class="form-control" placeholder="" value="<?php echo $row7["SellPrice"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<div class="form-group col-md-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

 </div>


    <!-- <div id="dynamic_field">
    <div class="form-row">
<div class="form-group col-md-6 ">
<label class="form-label">Product Name</label>
 <select class="form-control" name="ProdId[]" id="ProdId1" >
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>



                                        <div class="form-group col-md-2">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty1" class="form-control txt" placeholder="e.g.,1" value="" autocomplete="off" min="1" oninput="getSubTotal()">
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>
</div>
</div> -->

<div class="form-row">



<!-- 
<div class="form-group col-md-12">
<label class="form-label">Total Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div> -->

    
<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"  
                                                ><?php echo $row7['Narration']; ?></textarea>
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Submit</button>
<span id="error_msg" style="color:red;"></span>

</form>
</div>
<div class="col-lg-4" id="showcart">
    
        
</div>
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
 let barcodeTimeout;

document.getElementById("BarcodeNo").addEventListener("input", function() {
    clearTimeout(barcodeTimeout);
    barcodeTimeout = setTimeout(() => {
        checkBarcodeNo2(this.value, <?php echo $BillSoftFrId; ?>);
    }, 200); // Adjust delay time as needed
});

 function checkBarcodeNo2(barcode,frid){
     var action = "checkBarcodeNo";
            $.ajax({
                url: "ajax_files/ajax_shop_products_2025.php",
                method: "POST",
                data: {
                    action: action,
                    barcode: barcode,
                    frid:frid
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                  var res = JSON.parse(data);
                    $('#ProductName').val(res.ProductName);
                    $('#ProdId').val(res.ProdId);
                    getProdDetails(res.ProdId);
                    getAvailProdStock(res.ProdId);
                          
                }
            });
 }
 function getProdDetails(id){
      var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_shop_products_2025.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                  var res = JSON.parse(data);
                 
                    $('#PurchasePrice').val(res.PurchasePrice);
                    $('#SellPrice').val(res.MinPrice);
       
                    
                }
            });
 }
  function getAvailProdStock(id){
     var action = "getAvailProdStock";
            $.ajax({
                url: "ajax_files/ajax_raw_stock_2025.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                    $('#AvailStock').val(data);
                    
                }
            });
  }
    function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#TotQty').val(sum);
   
    }

    $(document).ready(function(){
      var ProdId = $('#ProdId').val();
      getAvailProdStock(ProdId);
  var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "addMoreService";
    $.ajax({
    url:"ajax_files/ajax_stock.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {
      $('#dynamic_field').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row'+button_id+'').remove();  
            
           }
      }); 

  }); 
</script>
</body>
</html>