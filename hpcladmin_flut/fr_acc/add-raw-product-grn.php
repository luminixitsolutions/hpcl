<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products-2025";
$Page = "View-Cust-Stocks-2025";
$sessionid = session_id();
//echo "<pre>";print_r($_SESSION["cart_item"]);
//unset($_SESSION["cart_item"]);
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

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Create Raw Product GRN</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-7">
<form id="validation-form" method="post" enctype="multipart/form-data" action="save-raw-product-grn.php">
<div class="form-row">

 <div class="form-group col-md-12">
<label class="form-label"> Vendor<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" style="width: 100%;padding-top: 12px;" name="VedId" id="VedId" data-live-search="true">

<option selected="" value="">Select Vendor</option>
<?php 
  $sql12 = "SELECT id,Fname FROM tbl_users WHERE Status='1' AND Roll='3' AND Fname!='' ORDER BY Fname";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["VedId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-12">
<label class="form-label"> Product</label>
 <select class="select2-demo form-control" name="ProdId" id="ProdId" onchange="getAvailProdStock(this.value)">

<option selected="" value="">Select Product</option>
<?php 
  $sql12 = "SELECT tp.ProductName,tp.id FROM tbl_cust_products2 tp WHERE tp.id IN ($AllocateRawProd) AND tp.ProdType='1' ORDER BY tp.ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


   <div class="form-group col-md-3">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock" id="AvailStock" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit" id="AvailStockUnit" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>                             
    


    
    <div class="form-group col-md-3">
<label class="form-label">Purchase Price </label>
<input type="text" name="PurchasePrice" id="PurchasePrice" class="form-control" placeholder="" value="" autocomplete="off" oninput="calTotalPrice()">
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-3">
<label class="form-label">Stock In Qty </label>
<div class="input-group">
    <input type="text" name="Qty2" id="Qty2" class="form-control" placeholder="" value="<?php echo $row7["Qty2"]; ?>" autocomplete="off"  oninput="covertIt(document.getElementById('Qty2').value,document.getElementById('QtyUnit2').value)">
        <div class="input-group-append">
            <input type="text" name="Unit2" id="QtyUnit2" class="form-control" placeholder="" value="<?php echo $row7["Unit2"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>


     <div class="form-group col-md-3">
<label class="form-label">Total Price </label>
<input type="text" name="TotalPrice" id="TotalPrice" class="form-control" placeholder="" value="" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

   <input type="hidden" class="form-control" name="Qty" id="Qty" value="<?php echo $row7["Qty"]; ?>">
   <input type="hidden" class="form-control" name="Unit" id="QtyUnit" value="<?php echo $row7["Unit"]; ?>">

    <input type="hidden" name="code" id="code" class="form-control" placeholder="" value="" autocomplete="off" readonly>
    <div class="form-group col-md-2" style="padding-top: 20px;">
    <button type="button" id="add" class="btn btn-success btn-finish" onclick="addToCart()">Add</button>
</div>
 </div>


 

<div class="form-row">

<div class="form-group col-md-6">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
    
<div class="form-group col-md-6">
<label class="form-label">Total Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div> 

    
<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"  
                                                ><?php echo $row7['Narration']; ?></textarea>
    <div class="clearfix"></div>
 </div>
  <div class="form-group col-md-12">
<label class="form-label">Upload Bill <span class="text-danger">*</span></label>
<input type="file" name="bill" id="bill" class="form-control" placeholder="" value="" autocomplete="off" style="padding: 3px;" required>
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
 function calTotalPrice(){
     var PurchasePrice = $('#PurchasePrice').val();
     var Qty2 = $('#Qty2').val();
     var TotalPrice = Number(PurchasePrice)*Number(Qty2);
     $('#TotalPrice').val(TotalPrice.toFixed(2));
 }
 function covertIt(Qty,Unit){
        if(Unit != 'Pieces'){
        let Qty2 = Number(Qty)*1000;
        $('#Qty').val(parseFloat(Qty2).toFixed(2));
        }
        else{
         $('#Qty').val(Qty);   
        }
        calTotalPrice();
    }

 function addToCart(){
     var action = "addToCart";
     var code = $('#code').val();
     var ProdId = $('#ProdId').val();
     var Qty2 = $('#Qty2').val();
     var QtyUnit2 = $('#QtyUnit2').val();
     var quantity = $('#Qty').val();
     var PurchasePrice = $('#PurchasePrice').val();
     var TotalPrice = $('#TotalPrice').val();
     var QtyUnit = $('#QtyUnit').val();
     if(ProdId==''){
         alert("Please Select Product");
     }
     else if(quantity==''){
         alert("Please Enter Qty");
     }
     else{
            $.ajax({
                url: "ajax_files/ajax_raw_products_2025.php",
                method: "POST",
                data: {
                    action: action,
                    code: code,
                    quantity:quantity,
                    id:ProdId,
                    PurchasePrice:PurchasePrice,
                    TotalPrice:TotalPrice,
                    QtyUnit:QtyUnit,
                    Qty2:Qty2,
                    QtyUnit2:QtyUnit2
                },
                beforeSend:function(){
     //$('#add').attr('disabled','disabled');
     $('#add').text('Please Wait...');
    },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                  displayCart();
                 $('#add').attr('disabled',false);
                 $('#add').text('Add');
                 $('#code').val('');
                 $('#ProdId').val(0).attr("selected",true);
                 $('#Qty').val('');
                 $('#PurchasePrice').val('');
                 $('#AvailStock').val('');
                 $('#Qty2').val('');
                 $('#QtyUnit2').val('');
                 $('#TotalPrice').val('');
                 $('#QtyUnit').val('');
                    
                }
            });
     }
 }
 
 function displayCart(){
     var action = "displayCart";
            $.ajax({
                url: "ajax_files/ajax_raw_products_2025.php",
                type: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    console.log(data);
                    $('#showcart').html(data);
                    calTotalQty();
                },

            });
 }
 
 function calTotalQty(){
      var action = "calTotalQty";
            $.ajax({
                url: "ajax_files/ajax_raw_products_2025.php",
                type: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    console.log(data);
                    $('#TotQty').val(data);
                },

            });
 }
 
  function delete_prod(code) {
            if (confirm("Are you sure you want to delete Record?")) {
                var action = "delete_cart_prod";
                $.ajax({
                    url: "ajax_files/ajax_raw_products_2025.php",
                    type: "POST",
                    data: {
                        action: action,
                        code: code
                    },
                    success: function(data) {
                        console.log(data);
                        displayCart();
                        
                    },

                });
            }
        }
 function getProdDetails(id){
      var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_raw_products_2025.php",
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
                    $('#code').val(res.code);
                    
                }
            });
 }
  function getAvailProdStock(id){
      getProdDetails(id);
     var action = "getAvailRawProdStock";
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
                  var res = JSON.parse(data);
                    $('#AvailStockUnit').val(res.Unit);
                    $('#QtyUnit2').val(res.Unit);
                    $('#AvailStock').val(res.balqty);
                    $('#QtyUnit').val(res.Unit2);

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


  }); 
</script>
</body>
</html>