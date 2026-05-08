<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Stock";
$Page = "Add-Stock";
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
    if($id!=''){
    $sql = "UPDATE tbl_cust_prod_stock_2025 SET Qty='$Qty',ModifiedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',ModifiedDate='$CreatedDate',PurchasePrice='$PurchasePrice' WHERE id='$id' AND FrId='$BillSoftFrId'";
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


  <div class="form-group col-md-12">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ProdId" id="ProdId" required onchange="getAvailProdStock(this.value)">

<option selected="" value="">Select Product</option>
<?php 
  $sql12 = "SELECT * FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND delete_flag=0 AND ProdType=0";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
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

    

<div class="form-group col-md-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

 </div>



<div class="form-row">



    
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
 function getProdDetails(id){
      var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_shop_products.php",
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
      getProdDetails(id);
     var action = "getAvailProdStock";
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
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