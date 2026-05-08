<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Transfer Stock";
$UserId = $_SESSION['User']['id'];
$FranchiseId = $_SESSION['FranchiseId'];
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
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
 

    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
      
      <?php 
if(isset($_POST['submit'])){
    $StockDate = addslashes(trim($_POST['StockDate']));
    $TotQty = addslashes(trim($_POST['TotQty']));
    $Narration = addslashes(trim($_POST['Narration']));
    $FromFrId = addslashes(trim($_POST['FromFrId']));
    $ToFrId = addslashes(trim($_POST['ToFrId']));
    $CreatedDate = date('Y-m-d');
    $sql = "INSERT INTO tbl_transfer_franchise_prod_stock SET FromFrId='$FromFrId',ToFrId='$ToFrId',StockDate='$StockDate',TotQty='$TotQty',Narration='$Narration',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    $UseRawId = mysqli_insert_id($conn);
    
    $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='transfer franchise to franchise stock added',invid='$UseRawId',createddate='$createddate',roll='transfer-franchise-to-franchise-stock'";
  $conn->query($sql);

    $number = count($_POST["ProdId"]);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["ProdId"][$i] != '')) {
                                $ProdId = addslashes(trim($_POST['GodownProdId'][$i]));
                                $Qty = addslashes(trim($_POST['Qty'][$i]));
                                $Unit = addslashes(trim($_POST['QtyUnit'][$i]));
                                $FrProdId = addslashes(trim($_POST['ProdId'][$i]));
                                $sql33 = "INSERT INTO tbl_transfer_franchise_prod_stock_items SET FromFrId='$FromFrId',ToFrId='$ToFrId',ProdId='$ProdId',FrProdId='$FrProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',CreatedBy='$user_id',StockDate='$StockDate'";
                                $conn->query($sql33);
                                
                                $sql22 = "INSERT INTO tbl_cust_prod_stock SET UserId='$FromFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',Status='Dr',Narration='$Narration',StockDate='$StockDate',CreatedBy='$user_id',FrId='$FromFrId'";
                                $conn->query($sql22);
                            }
                        }
                    }

                    echo "<script>alert('Record Saved Successfully!');window.location.href='view-transfer-franchise-to-franchise-stock.php';</script>";
}


?>

        <div class="main-container">
            <div class="container">
                <h5>Transfer Stock Franchise To Franchise </h5>
                <div class="card" style="padding:10px;">
                 <form id="validation-form" method="post" enctype="multipart/form-data">

<div class="form-row">
<div class="form-group col-md-6">
<label class="form-label"> From Franchise <span class="text-danger">*</span></label>
 <select class="form-control" name="FromFrId" id="FromFrId" required>

<!--<option selected="" value="">Select Franchise</option>-->
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE id='$FranchiseId'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["FromFrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label"> To Franchise <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ToFrId" id="ToFrId" required onchange="getFrRawProduct(this.value)">

<option selected="" value="">Select Franchise</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND id!='$FranchiseId' AND ShowFrStatus=1 AND OwnFranchise=1 ";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ToFrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

</div>

 <div id="dynamic_field">
    <div class="form-row">
        
        <div class="form-group col-md-3 ">
<label class="form-label">From Franchise Product</label>
 <select class="form-control" name="GodownProdId[]" id="GodownProdId1"  onchange="getAvailProdStock(this.value,document.getElementById('srno1').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products WHERE Status='1' AND CreatedBy='$FranchiseId' AND Transfer=1 ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?> 
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-3 ">
<label class="form-label">To Franchise Product</label>
 <select class="form-control ProdId" name="ProdId[]" id="ProdId1">
<option selected="" value="">Select Product</option>
 
</select>
</div>



<div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit1" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty1" class="form-control" placeholder="" value="" autocomplete="off" required>
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>
</div>
</div>

<div class="form-row">



<div class="form-group col-md-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control txt" placeholder="" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<!--<div class="form-group col-md-6">
<label class="form-label">Total Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>-->

    
<div class="form-group col-md-9">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <input type="text" name="Narration" id="Narration" class="form-control" required value="Stock Used">
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
                </div>
            </div>
        </div>
    </main>
<br><br><br>
    <!-- footer-->
    
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
    function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#TotQty').val(sum);
   
    }
    
    
    function getAvailProdStock(id,srno){
     var action = "getAvailProdStock2";
     var FromFrId = $('#FromFrId').val();
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    FromFrId:FromFrId
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                   var res = JSON.parse(data);
                   $('#AvailStockUnit'+srno).val(res.Unit);
                    $('#QtyUnit'+srno).val(res.Unit);
                    $('#AvailStock'+srno).val(res.balqty);
                }
            });
  }
  
     
    
    
    function getFrRawProduct(val){
        var action = "getFrRawProduct";
        $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    console.log(data);
                   $('.ProdId').html(data);
                    
                }
            });
    }

    $(document).ready(function(){
  var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getMoreFr";
       var FranchiseId = $('#ToFrId').val();
    $.ajax({
    url:"ajax_files/ajax_raw_stock.php",
    method:"POST",
    data : {action:action,id:i,FranchiseId:FranchiseId},
    success:function(data)
    {
        //alert(data);
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
