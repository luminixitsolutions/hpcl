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

function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 


$sql = "SELECT * FROM tbl_cust_products_2025 WHERE code=''";
$row = getList($sql);
foreach($row as $result){
    $n = 10;
    $Code2 = RandomStringGenerator($n); 
    $Code = $Code2."".$result['id'];
    $sql2 = "UPDATE tbl_cust_products_2025 SET code='$Code' WHERE id='".$result['id']."'";
    $conn->query($sql2);
}

//echo "<pre>";
//print_r($_SESSION["cart_item"]);
//unset($_SESSION["cart_item"]);
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

     <link rel="stylesheet" href="admin_css/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="admin_css/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="admin_css/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="admin_css/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="admin_css/assets/libs/datatables/datatables.css">

   
 
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
  if(isset($_POST['submit'])){
    $ProdId = $_POST['ProdId'];
    $FromFrId = addslashes(trim($_POST['FromFrId']));
    $StockDate = addslashes(trim($_POST['StockDate']));
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));

     $sql = "INSERT INTO tbl_request_product_stock_2025 SET FromFrId='$FromFrId',ToFrId='$ToFrId',StockDate='$StockDate',TotQty='$TotQty',Narration='$Narration',CreatedBy='$FranchiseId',CreatedDate='$CreatedDate',Status=0";
    $conn->query($sql);
    $UseRawId = mysqli_insert_id($conn);
      foreach ($_SESSION["cart_item"] as $product){
        $ProdId = $product['ProdId'];
        $Qty = $product['Qty'];
        $Unit = $product['QtyUnit'];
        $CgstPer = $product['CgstPer'];
        $SgstPer = $product['SgstPer'];
        $IgstPer = $product['IgstPer'];
        $CgstAmt = $product['CgstAmt'];
        $SgstAmt = $product['SgstAmt'];
        $IgstAmt = $product['IgstAmt'];
        $ProdPrice = $product['ProdPrice'];
        $MinPrice = $product['MinPrice'];
        $GstAmt = $product['GstAmt'];
        $sql33 = "INSERT INTO tbl_request_product_stock_items_2025 SET FromFrId='$FromFrId',ToFrId='$ToFrId',ProdId='$ProdId',FrProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',CreatedBy='$FranchiseId',StockDate='$StockDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ProdPrice='$ProdPrice',MinPrice='$MinPrice',GstAmt='$GstAmt'";
        $conn->query($sql33);
      }
   
echo "<script>alert('Request Sent Successfully!');window.location.href='view-request-product-stock.php';</script>";
    unset($_SESSION["cart_item"]);

  }

  unset($_SESSION["cart_item"]);
 ?>

        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
              
                <div class="card mb-4" style="padding:10px;">
                    <form id="validation-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="FromFrId" id="FromFrId" value="<?php echo $FranchiseId;?>">


<div id="dynamic_field">
    <div class="form-row"> 
        <div class="form-group col-md-4 ">
<label class="form-label">Franchise Product</label>
 <select class="selectpicker form-control" id="ProdId1"  onchange="getAvailProdStock(this.value,document.getElementById('srno1').value)" data-live-search="true">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FranchiseId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN(1,3) AND checkstatus=1 ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?> 
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-2 col-6">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" id="AvailStock1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" id="AvailStockUnit1" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;border-radius: 1px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2 col-6">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text"id="Qty1" class="form-control" placeholder="" value="" autocomplete="off" >
        <div class="input-group-append">
            <input type="text" id="QtyUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;border-radius: 1px;">
        </div>
    </div>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more" onclick="addItem(document.getElementById('srno1').value)">Add</button>
</div>
</div>
</div>

<div class="form-row">
    <div class="card-datatable table-responsive" id="custresult">

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
     <input type="text" name="Narration" id="Narration" class="form-control" required value="">
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

    <script src="admin_css/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="admin_css/assets/libs/datatables/datatables.js"></script>

    <!-- Demo -->
    
    <script src="admin_css/assets/js/pages/tables_datatables.js"></script>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
 <script>
     $(function() {
  $('.selectpicker').selectpicker();
});
function showData(){
     var action = "showData";
      $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                  $('#custresult').html(data);
                }
            });
}

function deleteCart(code){
    var action = "deleteCart";
      $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    code:code
                },
                success: function(data) {
                  showData();
                }
            });
}
 function addItem(srno){
    var action = "addItem";
    var ProdId = $('#ProdId'+srno).val();
    var AvailStock = $('#AvailStock'+srno).val();
    var AvailStockUnit = $('#AvailStockUnit'+srno).val();
    var Qty = $('#Qty'+srno).val();
    var QtyUnit = $('#QtyUnit'+srno).val();
    var quantity = 1;
        $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    ProdId: ProdId,
                    AvailStock:AvailStock,
                    AvailStockUnit:AvailStockUnit,
                    Qty:Qty,
                    QtyUnit:QtyUnit,
                    quantity:quantity
                },
                success: function(data) {
                    showData();
                    $('#ProdId'+srno).val('').attr("selected",true);
                    $('#AvailStock'+srno).val('');
                    $('#AvailStockUnit'+srno).val('');
                    $('#Qty'+srno).val('');
                    $('#QtyUnit'+srno).val('');
                    
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
showData();
  /*var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getMoreReqFr";
       var FranchiseId = $('#ToFrId').val();
    $.ajax({
    url:"ajax_files/ajax_raw_stock.php",
    method:"POST",
    data : {action:action,id:i,FranchiseId:FranchiseId},
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
      }); */

  }); 
</script>
   
</body>

</html>
