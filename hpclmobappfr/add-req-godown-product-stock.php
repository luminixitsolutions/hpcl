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


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
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
                    <form id="validation-form" method="post" enctype="multipart/form-data" action="save-req-godown-product-stock.php">
                        <div class="card-body">
<div class="form-row">

 
  <div class="form-group col-md-12">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="selectpicker form-control" style="width: 100%;    padding-top: 12px;" name="ProdId" id="ProdId"  onchange="getAvailProdStock(this.value)" data-live-search="true">

<option selected="" value="">Select Product</option>
<?php 
  $sql12 = "SELECT id,ProductName FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FranchiseId' AND ProdType=0 AND ProdType2 IN (3) AND delete_flag=0 AND checkstatus=1 ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


    <!--<div class="form-group col-md-3 col-6">
<label class="form-label">Available Stock </label>
<input type="text" name="AvailStock" id="AvailStock" class="form-control" placeholder="" value="" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>-->

   <input type="hidden" class="form-control" name="Qty" id="Qty" value="<?php echo $row7["Qty"]; ?>">
   <input type="hidden" class="form-control" name="Unit" id="QtyUnit" value="<?php echo $row7["Unit"]; ?>">


 <div class="form-group col-md-4 col-12">
<label class="form-label">Stock In Qty </label>
<div class="input-group">
    <input type="text" name="Qty2" id="Qty2" class="form-control" placeholder="" value="<?php echo $row7["Qty2"]; ?>" autocomplete="off"  oninput="covertIt(document.getElementById('Qty2').value,document.getElementById('QtyUnit2').value)" style="width: 70%;">
        <div class="input-group-append">
            <input type="text" name="Unit2" id="QtyUnit2" class="form-control" placeholder="" value="<?php echo $row7["Unit2"]; ?>" autocomplete="off" readonly style="width: 95px;">
        </div>
    </div>
</div>
    
   <!-- <div class="form-group col-md-3 col-6">
<label class="form-label">Purchase Price </label>
<input type="text" name="PurchasePrice" id="PurchasePrice" class="form-control" placeholder="" value="" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-2 col-6">
<label class="form-label">Sell Price </label>
<input type="text" name="SellPrice" id="SellPrice" class="form-control" placeholder="" value="<?php echo $row7["SellPrice"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>-->


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
<input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required readonly>
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
 $(function() {
  $('.selectpicker').selectpicker();
});
</script>

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
                url: "ajax_files/ajax_godown_products_2025.php",
                method: "POST",
                data: {
                    action: action,
                    code: code,
                    quantity:quantity,
                    id:ProdId,
                    PurchasePrice:PurchasePrice,
                     QtyUnit:QtyUnit,
                    Qty2:Qty2,
                    QtyUnit2:QtyUnit2
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
                url: "ajax_files/ajax_godown_products_2025.php",
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
                url: "ajax_files/ajax_godown_products_2025.php",
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
                    url: "ajax_files/ajax_godown_products_2025.php",
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
                url: "ajax_files/ajax_godown_products_2025.php",
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
     var action = "getAvailProdStock2";
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
    //   var ProdId = $('#ProdId').val();
    //   getAvailProdStock(ProdId);


  }); 
</script>
   
</body>

</html>
