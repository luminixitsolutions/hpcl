<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Create-Invoice-Product-Stock";
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
    
$sql = "SELECT * FROM tbl_cust_products_2025 WHERE code is null";
    $row = getList($sql);
    foreach($row as $result){
        $Code = RandomStringGenerator($n); 
        $Code2 = $Code."".$result['id'];
        $sql2 = "UPDATE tbl_cust_products_2025 SET code='$Code2' WHERE id='".$result['id']."'";
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


</style>
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
if(isset($_POST['submit'])){
    $StockDate = addslashes(trim($_POST['StockDate']));
    $TotQty = addslashes(trim($_POST['TotQty']));
    $Narration = addslashes(trim($_POST['Narration']));
    $FromFrId = addslashes(trim($_POST['FromFrId']));
    $ToFrId = addslashes(trim($_POST['ToFrId']));
    $UpdatedDate = addslashes(trim($_POST['UpdatedDate']));
    $Remark = addslashes(trim($_POST['Remark']));
    $GstAmount = addslashes(trim($_POST['GstAmount']));
    $TotalAmount = addslashes(trim($_POST['TotalAmount']));
    $VedId = addslashes(trim($_POST['VedId']));
    $FranchiseId = addslashes(trim($_POST['FranchiseId']));
    $CreatedDate = date('Y-m-d H:i:s');

    $sql = "INSERT INTO tbl_request_product_stock_2025 SET FranchiseId='$FranchiseId',FromFrId='$FranchiseId',ToFrId='$ToFrId',StockDate='$StockDate',TotQty='$TotQty',Narration='$Narration',CreatedBy='$FranchiseId',CreatedDate='$CreatedDate',TotalAmount='$TotalAmount',GstAmount='$GstAmount',UpdatedDate='$UpdatedDate',Remark='$Remark',UpdatedBy='$user_id',UpdatedDateTime='$CreatedDate',UpdateStatus=1,Status=1,VedId='$VedId'";
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
                                $MinPrice = addslashes(trim($product['MinPrice']));
                                $TotalPrice = addslashes(trim($product['TotalPrice']));
                                $CgstPer = 2.5;
                                $SgstPer = 2.5;
                                $IgstPer = 0;
                                $CgstAmt = addslashes(trim($product['CgstAmt']));
                                $SgstAmt = addslashes(trim($product['SgstAmt']));
                                $IgstAmt = 0;
                                $GstAmt = addslashes(trim($product['GstAmt']));
                               $sql33 = "INSERT INTO tbl_request_product_stock_items_2025 SET FromFrId='$FromFrId',ToFrId='$FranchiseId',ProdId='$ProdId',FrProdId='$ProdId',Qty='$Qty',Unit='$Unit',
                                         CreatedDate='$CreatedDate',TransferId='$UseRawId',CreatedBy='$FranchiseId',StockDate='$StockDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',
                                         CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ProdPrice='$Price',MinPrice='$MinPrice',GstAmt='$GstAmt',Total='$TotalPrice'";
                                $conn->query($sql33);

 }
    

                    echo "<script>alert('Request Sent Successfully!');window.location.href='approve-request-product-stock.php';</script>";
}
unset($_SESSION["cart_item"]);

?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Create Product Stock Invoice</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-7">
<form id="validation-form" method="post" enctype="multipart/form-data">

<div class="form-row">
<div class="form-group col-md-6">
<label class="form-label"> Vendor <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="VedId" id="VedId" required>

<option selected="" value="">Select Vendor</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll=3";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["VedId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
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
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1";
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
<label class="form-label">Product</label>
 <select class="select2-demo form-control ProdId" name="ProdId[]" id="GodownProdId1"  onchange="getAvailProdStock(this.value,document.getElementById('srno1').value)">
<option selected="" value="0">Select Product</option>

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
    <input type="text" name="Qty[]" id="Qty1" class="form-control txt" placeholder="" value="" autocomplete="off" oninput="calTotal(document.getElementById('Price1').value,document.getElementById('Qty1').value,document.getElementById('srno1').value)">
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-1">
<label class="form-label">Rate </label>
    <input type="text" name="Price[]" id="Price1" class="form-control" placeholder="" value="" autocomplete="off" oninput="calTotal(document.getElementById('Price1').value,document.getElementById('Qty1').value,document.getElementById('srno1').value)">
        
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
<label class="form-label">Request Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo $row77['StockDate']; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

     <div class="form-group col-md-3">
<label class="form-label">Updated Date <span class="text-danger">*</span></label>
<input type="date" name="UpdatedDate" id="UpdatedDate" class="form-control" placeholder="" value="<?php echo $row77['UpdatedDate']; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>



    
<div class="form-group col-md-6">
   <label class="form-label">Narration </label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row77['Narration']; ?>">
    <div class="clearfix"></div>
 </div>




    
<div class="form-group col-md-6">
   <label class="form-label">Remark </label>
     <input type="text" name="Remark" id="Remark" class="form-control" value="<?php echo $row77['Remark']; ?>">
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
 
  function addToCart(){
    var action = "addToCart2";
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
        
   function calTotal(rate,qty,id){
        var Total = Number(rate)*Number(qty);
        $('#TotalPrice'+id).val(parseFloat(Total).toFixed(2));
        var CgstPer = $('#CgstPer'+id).val();
        var SgstPer = $('#SgstPer'+id).val();
        var IgstPer = $('#IgstPer'+id).val();
        var CgstAmt = Number(Total) * (Number(CgstPer)/100);
        var SgstAmt = Number(Total) * (Number(SgstPer)/100);
        var IgstAmt = Number(Total) * (Number(IgstPer)/100);
        $('#CgstAmt'+id).val(parseFloat(CgstAmt).toFixed(2)); 
        $('#SgstAmt'+id).val(parseFloat(SgstAmt).toFixed(2));
        $('#IgstAmt'+id).val(parseFloat(IgstAmt).toFixed(2));
        var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
        $('#GstAmt'+id).val(parseFloat(GstAmt).toFixed(2));
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
     var action = "getAvailProdStock2";
     var FromFrId = $('#FromFrId').val();
     var FranchiseId = $('#FranchiseId').val();
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    FromFrId:FromFrId,
                    FranchiseId:FranchiseId
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
        var action = "getFrCustProduct";
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

    /*$(document).ready(function(){
  var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getMoreReqFr";
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
      }); 

  }); */
</script>
</body>
</html>