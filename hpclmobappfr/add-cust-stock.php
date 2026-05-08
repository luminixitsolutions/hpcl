<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "My Expenses";
$Page = "Recharge";
$WallMsg = "NotShow"; 
$url = "home.php";
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <?php include 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       
<?php include 'top_header.php';?>

        <!-- page content start -->
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cust_prod_stock_2025 WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');
?>

<?php 
$FranchiseId = $_SESSION['FranchiseId'];

 ?>

        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
              
                <div class="card mb-4">
                    <form id="validation-form" method="post" enctype="multipart/form-data" action="save-selling-stock.php">
                        <div class="card-body">
<div class="form-row">

<div class="form-group col-md-12">
<label class="form-label"> Category<span class="text-danger">*</span></label>
 <select class="form-control" name="CatId" id="CatId"  onchange="getProduct(this.value)">

<option selected="" value="">Select Category</option>
<?php 
 $sql12 = "SELECT * 
FROM tbl_cust_category_2025 c
WHERE c.Status = 1 
  AND c.ProdType = 0
  AND EXISTS (
      SELECT 1 
      FROM tbl_cust_products_2025 p
      WHERE p.CatId = c.id 
        AND p.Status = 1
        AND p.ProdType = 0 
        AND p.ProdType2 IN (1,3)
        AND p.delete_flag = 0
        AND p.checkstatus = 1
        AND p.CreatedBy = '$FranchiseId'
  )
ORDER BY c.Name ASC";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>



<div class="form-group col-md-12">
  <label class="form-label">Product <span class="text-danger">*</span></label>
  <select class="form-control" name="ProdId" id="ProdId" onchange="getAvailProdStock(this.value)">
    <option value="">Select Product</option>
  </select>
</div>

 <!-- <div class="form-group col-md-12">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="selectpicker form-control" style="width: 100%;    padding-top: 12px;" name="ProdId" id="ProdId"  onchange="getAvailProdStock(this.value)" data-live-search="true">

<option selected="" value="">Select Product</option>

</select>
<div class="clearfix"></div>
</div>
-->

    <div class="form-group col-md-3 col-6">
<label class="form-label">Available Stock </label>
<input type="text" name="AvailStock" id="AvailStock" class="form-control" placeholder="" value="" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-2 col-6">
<label class="form-label">Stock In Qty </label>
<input type="text" name="Qty" id="Qty" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" >
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-3 col-6">
<label class="form-label">Purchase Price </label>
<input type="text" name="PurchasePrice" id="PurchasePrice" class="form-control" placeholder="" value="" autocomplete="off" >
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-2 col-6">
<label class="form-label">Sell Price </label>
<input type="text" name="SellPrice" id="SellPrice" class="form-control" placeholder="" value="<?php echo $row7["SellPrice"]; ?>" autocomplete="off" >
<div class="clearfix"></div>
    </div>


    <input type="hidden" name="code" id="code" class="form-control" placeholder="" value="" autocomplete="off" readonly>
    <div class="form-group col-md-2" style="padding-top: 20px;">
    <button type="button" id="add" class="btn btn-success btn-finish" onclick="addToCart()">Add</button>
</div>

<div class="form-row">

    <div class="col-lg-12" id="showcart">
    
        
</div>

</div>

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
 
 <div class="form-group col-md-6">
<label class="form-label">Upload Bill </label>
<input type="file" name="bill" id="bill" class="form-control" placeholder="" value="" autocomplete="off" style="padding: 3px;">
<div class="clearfix"></div>
    </div>

 </div>




<button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Submit</button>
<span id="error_msg" style="color:red;"></span>
</div>
</form>
                    
                </div>


            </div>
        </div>
    </main>

<?php include 'footer.php';?>
  
    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
 <script>
$(document).ready(function() {
    // Initialize Select2 with always-visible search
    $('#ProdId').select2({
        placeholder: "Select Product",
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0
    });
});

// Load products by category
function getProduct(catid){
    $.ajax({
        url: "ajax_files/ajax_dropdown.php",
        type: "POST",
        data: { action: "getProduct", catid: catid },
        success: function(data) {
            console.log(data);
            $('#ProdId').html(data).val(null).trigger('change');
        }
    });
}
 function addToCart(){
     var action = "addToCart";
     var code = $('#code').val();
     var ProdId = $('#ProdId').val();
     var quantity = $('#Qty').val();
     var PurchasePrice = $('#PurchasePrice').val();
     var SellPrice = $('#SellPrice').val();
     if(ProdId==''){
         alert("Please Select Product");
     }
     else if(quantity==''){
         alert("Please Enter Qty");
     }
     else{
            $.ajax({
                url: "ajax_files/ajax_shop_products_2025.php",
                method: "POST",
                data: {
                    action: action,
                    code: code,
                    quantity:quantity,
                    id:ProdId,
                    PurchasePrice:PurchasePrice,
                    SellPrice:SellPrice
                },
                beforeSend:function(){
     $('#add').attr('disabled','disabled');
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
                 $('#SellPrice').val('');
                 $('#AvailStock').val('');
                    
                }
            });
     }
 }
 
 function displayCart(){
     var action = "displayCart";
            $.ajax({
                url: "ajax_files/ajax_shop_products_2025.php",
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
                url: "ajax_files/ajax_shop_products_2025.php",
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
                    url: "ajax_files/ajax_shop_products_2025.php",
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
                    $('#code').val(res.code);
                    
                }
            });
 }
  function getAvailProdStock(id){
      getProdDetails(id);
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
    //   var ProdId = $('#ProdId').val();
    //   getAvailProdStock(ProdId);


  }); 
</script>
   
</body>

</html>
