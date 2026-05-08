<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Transfer-Raw-Stock-Godown-To-Franchise-FOFO";
//echo "<pre>";print_r($_SESSION["cart_item"]);
//unset($_SESSION["cart_item"]);

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
    $n = 10;
    $Code = RandomStringGenerator($n); 
    
$sql = "SELECT * FROM tbl_cust_products2 WHERE code is null";
    $row = getList($sql);
    foreach($row as $result){
        $Code = RandomStringGenerator($n); 
        $Code2 = $Code."".$result['id'];
        $sql2 = "UPDATE tbl_cust_products2 SET code='$Code2' WHERE id='".$result['id']."'";
        $conn->query($sql2);
    }
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - Customers</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="../ckeditor/ckeditor.js"></script>
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


table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 5px;
}
</style>
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
$sql = "SELECT MAX(id) AS MaxId FROM tbl_transfer_godown_raw_prod_stock_2025";
$row = getRecord($sql);
$InvoiceNo = $row['MaxId']+1;
if(isset($_POST['submit'])){

    $StockDate = addslashes(trim($_POST['StockDate']));
    $TotQty = addslashes(trim($_POST['TotQty']));
    $Narration = addslashes(trim($_POST['Narration']));
    $GodownId = addslashes(trim($_POST['GodownId']));
    $FranchiseId = addslashes(trim($_POST['FranchiseId']));
    $TotalAmount = addslashes(trim($_POST['TotalAmount']));
    $InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
    $GstAmount = addslashes(trim($_POST['GstAmount']));
    $CreatedDate = date('Y-m-d');
    $sql = "INSERT INTO tbl_transfer_godown_raw_prod_stock_2025 SET GodownId='$GodownId',FranchiseId='$FranchiseId',StockDate='$StockDate',TotQty='$TotQty',Narration='$Narration',CreatedBy='$user_id',CreatedDate='$CreatedDate',TotalAmount='$TotalAmount',InvoiceNo='$InvoiceNo',GstAmount='$GstAmount',OwnShop=2";
    $conn->query($sql);
    $UseRawId = mysqli_insert_id($conn);
  
 foreach ($_SESSION["cart_item"] as $product) {
     $ProdId = addslashes(trim($product['id']));
     $sql = "SELECT id FROM `tbl_cust_products_2025` WHERE ProdId='$ProdId' AND CreatedBy='$FranchiseId'";
     $row = getRecord($sql);
     
                                $Qty = addslashes(trim($product['Qty']));
                                $Unit = addslashes(trim($product['Unit']));
                                $FrProdId = $row['id'];
                                $Price = addslashes(trim($product['Price']));
                                $TotalPrice = addslashes(trim($product['TotalPrice']));
                                $CgstPer = 2.5;
                                $SgstPer = 2.5;
                                $IgstPer = 0;
                                $CgstAmt = addslashes(trim($product['CgstAmt']));
                                $SgstAmt = addslashes(trim($product['SgstAmt']));
                                $IgstAmt = 0;
                                $GstAmt = addslashes(trim($product['GstAmt']));
                                $sql33 = "INSERT INTO tbl_transfer_godown_raw_stock_items_2025 SET GodownId='$GodownId',FranchiseId='$FranchiseId',ProdId='$ProdId',FrProdId='$FrProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',CreatedBy='$user_id',StockDate='$StockDate',Price='$Price',TotalPrice='$TotalPrice',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',GstAmt='$GstAmt',OwnShop=1";
                                $conn->query($sql33);
                                
                                $sql22 = "INSERT INTO tbl_godown_raw_prod_stock_2025 SET GodownId='$GodownId',FranchiseId='$FranchiseId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',Status='Dr',Narration='$Narration',StockDate='$StockDate',CreatedBy='$user_id',Price='$Price',TotalPrice='$TotalPrice',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',GstAmt='$GstAmt',OwnShop=1";
                                $conn->query($sql22);
 }
    /*$number = count($_POST["ProdId"]);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["ProdId"][$i] != '')) {
                                $ProdId = addslashes(trim($_POST['GodownProdId'][$i]));
                                $Qty = addslashes(trim($_POST['Qty'][$i]));
                                $Unit = addslashes(trim($_POST['QtyUnit'][$i]));
                                $FrProdId = addslashes(trim($_POST['ProdId'][$i]));
                                $Price = addslashes(trim($_POST['Price'][$i]));
                                $TotalPrice = addslashes(trim($_POST['TotalPrice'][$i]));
                                $CgstPer = addslashes(trim($_POST['CgstPer'][$i]));
                                $SgstPer = addslashes(trim($_POST['SgstPer'][$i]));
                                $IgstPer = addslashes(trim($_POST['IgstPer'][$i]));
                                $CgstAmt = addslashes(trim($_POST['CgstAmt'][$i]));
                                $SgstAmt = addslashes(trim($_POST['SgstAmt'][$i]));
                                $IgstAmt = addslashes(trim($_POST['IgstAmt'][$i]));
                                $GstAmt = addslashes(trim($_POST['GstAmt'][$i]));
                                $sql33 = "INSERT INTO tbl_transfer_godown_raw_stock_items_2025 SET GodownId='$GodownId',FranchiseId='$FranchiseId',ProdId='$ProdId',FrProdId='$FrProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',CreatedBy='$user_id',StockDate='$StockDate',Price='$Price',TotalPrice='$TotalPrice',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',GstAmt='$GstAmt',OwnShop=1";
                                $conn->query($sql33);
                                
                                $sql22 = "INSERT INTO tbl_godown_raw_prod_stock_2025 SET GodownId='$GodownId',FranchiseId='$FranchiseId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',Status='Dr',Narration='$Narration',StockDate='$StockDate',CreatedBy='$user_id',Price='$Price',TotalPrice='$TotalPrice',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',GstAmt='$GstAmt',OwnShop=1";
                                $conn->query($sql22);
                            }
                        }
                    }*/

                    echo "<script>alert('Record Saved Successfully!');window.location.href='view-transfer-godwon-raw-stock-fofo.php';</script>";
}

unset($_SESSION["cart_item"]);
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Transfer Stock Godown To FOFO Franchise</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-8">
<form id="validation-form" method="post" enctype="multipart/form-data">

<div class="form-row">
<div class="form-group col-md-6">
<label class="form-label"> Godown <span class="text-danger">*</span></label>
 <select class="form-control" name="GodownId" id="GodownId" required>

<!--<option selected="" value="">Select Godown</option>-->
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=93";
  $row12 = getList($sql12);
  foreach($row12 as $result){ 
     ?>
  <option <?php if($row7["GodownId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label"> Franchise <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="FranchiseId" id="FranchiseId" required onchange="getFrRawProduct(this.value)">

<option selected="" value="">Select Franchise</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND OwnFranchise=2";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["FranchiseId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

</div>

 <div id="dynamic_field">
    <div class="form-row">
        
        <div class="form-group col-md-4 ">
<label class="form-label">Godown Product</label>
 <select class="select2-demo form-control" name="GodownProdId[]" id="GodownProdId1"  onchange="getAvailProdStock(this.value,document.getElementById('srno1').value)">
<option selected="" value="0">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products2 WHERE Status=1 AND ProdType2 IN (3) ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
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
    <input type="text" name="Qty[]" id="Qty1" class="form-control txt" placeholder="" value="" autocomplete="off" oninput="calTotalPrice(document.getElementById('srno1').value,document.getElementById('CgstPer1').value,document.getElementById('SgstPer1').value,document.getElementById('IgstPer1').value)">
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-1">
<label class="form-label">Price </label>
    <input type="text" name="Price[]" id="Price1" class="form-control" placeholder="" value="" autocomplete="off" oninput="calTotalPrice(document.getElementById('srno1').value,document.getElementById('CgstPer1').value,document.getElementById('SgstPer1').value,document.getElementById('IgstPer1').value)">
        
</div>

<div class="form-group col-md-2">
<label class="form-label">Total Price </label>
    <input type="text" name="TotalPrice[]" id="TotalPrice1" class="form-control txt2" placeholder="" value="" autocomplete="off" >
        
</div>

<input type="hidden" class="form-control" name="CgstPer[]" id="CgstPer1" value="2.5">
<input type="hidden" class="form-control" name="SgstPer[]" id="SgstPer1" value="2.5">
<input type="hidden" class="form-control" name="IgstPer[]" id="IgstPer1" value="0">
<input type="hidden" class="form-control" name="CgstAmt[]" id="CgstAmt1" value="">
<input type="hidden" class="form-control" name="SgstAmt[]" id="SgstAmt1" value="">
<input type="hidden" class="form-control" name="IgstAmt[]" id="IgstAmt1" value="">
<input type="hidden" class="form-control txtgstamt" name="GstAmt[]" id="GstAmt1" value="">

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more" onclick="addToCart()">+</button>
</div>
</div>
</div>

<div class="form-row">

<div class="form-group col-md-2">
<label class="form-label">Total Qty <span class="text-danger">*</span></label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-2">
<label class="form-label">GST Amount <span class="text-danger">*</span></label>
<input type="text" name="GstAmount" id="GstAmount" class="form-control" placeholder="" value="<?php echo $row7["GstAmount"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-2">
<label class="form-label">Total Amount <span class="text-danger">*</span></label>
<input type="text" name="TotalAmount" id="TotalAmount" class="form-control" placeholder="" value="<?php echo $row7["TotalAmount"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

<div class="form-group col-md-3">
<label class="form-label">Invoice No <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" id="InvoiceNo" class="form-control" placeholder="" value="<?php echo $InvoiceNo; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
    
<div class="form-group col-md-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control txt" placeholder="" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>



    
<div class="form-group col-md-12">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <input type="text" name="Narration" id="Narration" class="form-control" required value="Stock Used">
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div>
<div class="col-lg-5" id="showcart">
    
        
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
 
 function calGst(){
            
        }
        
  function addToCart(){
    var action = "addToCart";
    var code = $('#code').val();
    var ProdId = $('#GodownProdId1').val();
    var quantity = $('#Qty1').val();
    var TotalPrice = $('#TotalPrice1').val();
    var Price = $('#Price1').val();
    var Unit = $('#QtyUnit1').val();
     
    var MinPrice = Number(TotalPrice)/Number(1.05);
    var GstAmt = Number(TotalPrice) - Number(MinPrice);
    var CgstAmt = Number(GstAmt) / 2;
    var SgstAmt = Number(GstAmt) / 2;
            
     if(ProdId==0){
         alert("Please Select Product");
     }
     else if(quantity==''){
         alert("Please Enter Qty");
     }
     else{
            $.ajax({
                url: "ajax_files/ajax_godown_stock.php",
                method: "POST",
                data: {
                    action: action,
                    code: code,
                    quantity:quantity,
                    id:ProdId,
                    TotalPrice:TotalPrice,
                    Price:Price,
                    Unit:Unit,
                    MinPrice:parseFloat(MinPrice).toFixed(2),
                    GstAmt:parseFloat(GstAmt).toFixed(2),
                    CgstAmt:parseFloat(CgstAmt).toFixed(2),
                    SgstAmt:parseFloat(SgstAmt).toFixed(2)
                    
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
                 $('#GodownProdId1').val(0).attr("selected",true);
                 $('#Qty1').val('');
                 $('#TotalPrice1').val('');
                 $('#Price1').val('');
                 $('#AvailStock1').val('');
                 $('#AvailStockUnit1').val('');
                 $('#QtyUnit1').val('');
                    
                }
            });
     }
    }
    
    function displayCart(){
     var action = "displayCart";
            $.ajax({
                url: "ajax_files/ajax_godown_stock.php",
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
                url: "ajax_files/ajax_godown_stock.php",
                type: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#TotQty').val(res.Qty);
                    $('#TotalAmount').val(res.TotalPrice);
                    $('#GstAmount').val(res.GstAmt);
                },

            });
 }
 
  function delete_prod(code) {
            if (confirm("Are you sure you want to delete Record?")) {
                var action = "delete_cart_prod";
                $.ajax({
                    url: "ajax_files/ajax_godown_stock.php",
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
    
    
  function calTotalPrice(srno,CgstPer,SgstPer,IgstPer){
    var Qty = $('#Qty'+srno).val();
    var Price = $('#Price'+srno).val();
    var TotalPrice = Number(Qty) * Number(Price);
    $('#TotalPrice'+srno).val(parseFloat(TotalPrice).toFixed(2));
    var TotGst = Number(CgstPer)+Number(SgstPer)+Number(IgstPer);
    var CgstAmt = Number(TotalPrice) * (Number(CgstPer)/100);
    var SgstAmt = Number(TotalPrice) * (Number(SgstPer)/100);
    var IgstAmt = Number(TotalPrice) * (Number(IgstPer)/100);
    $('#CgstAmt'+srno).val(parseFloat(CgstAmt).toFixed(2)); 
    $('#SgstAmt'+srno).val(parseFloat(SgstAmt).toFixed(2));
    $('#IgstAmt'+srno).val(parseFloat(IgstAmt).toFixed(2));
    var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
    $('#GstAmt'+srno).val(parseFloat(GstAmt).toFixed(2));
    getSubTotal();
    getGstAmt();
    getTotAmt();
    
 }
 
 
 
  function getGstAmt(){
     var sum = 0;
      $(".txtgstamt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#GstAmount').val(sum);
   
    }
    
 function getTotAmt(){
     var sum = 0;
      $(".txt2").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#TotalAmount').val(sum);
   
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
     var action = "getAvailGodownProdStock";
     var GodownId = $('#GodownId').val();
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    GodownId:GodownId
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                   var res = JSON.parse(data);
                   $('#AvailStockUnit'+srno).val(res.Unit);
                    $('#QtyUnit'+srno).val(res.Unit);
                    $('#AvailStock'+srno).val(res.balqty);
                    $('#Price'+srno).val(res.Price);
                    $('#CgstPer'+srno).val(res.CgstPer);
                    $('#SgstPer'+srno).val(res.SgstPer);
                    $('#IgstPer'+srno).val(res.IgstPer); 
                }
            });
  }
  
     
    function getAvailStock(id,srno){
      var action = "getAvailGodownStock";
      var GodownId = $('#GodownId').val();
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    GodownId:GodownId
                },
                success: function(data) {
                    console.log(data);
                    $('#AvailStock' + srno).val(data);
                    
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
  /*var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getMore";
       var FranchiseId = $('#FranchiseId').val();
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
      });*/ 

  }); 
</script>
</body>
</html>