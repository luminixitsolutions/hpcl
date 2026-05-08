<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Production";
$Page = "Manage-Raw-Production-Stock";
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
$sql7 = "SELECT * FROM tbl_production_raw_stock WHERE id='$id'";
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
$GodownId = addslashes(trim($_POST['GodownId']));
$Unit = addslashes(trim($_POST['QtyUnit']));
$Price = addslashes(trim($_POST['Price']));
$TotalPrice = addslashes(trim($_POST['TotalPrice']));

$CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $CgstAmt = addslashes(trim($_POST['CgstAmt']));
    $SgstAmt = addslashes(trim($_POST['SgstAmt']));
    $IgstAmt = addslashes(trim($_POST['IgstAmt']));
    
    if($_GET['id'] == ''){
     $qx = "INSERT INTO tbl_production_raw_stock SET GodownId='$GodownId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='0',CreatedDate='$CreatedDate',Price='$Price',TotalPrice='$TotalPrice',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);
  echo "<script>alert('Product Stock Added Successfully!');window.location.href='view-raw-production-stock.php';</script>";
}
else{
    $sql = "UPDATE tbl_production_raw_stock SET GodownId='$GodownId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',ModifiedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='0',ModifiedDate='$CreatedDate',Price='$Price',TotalPrice='$TotalPrice',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Product Stock Updated Successfully!');window.location.href='view-raw-production-stock.php';</script>";
}
       
    

    //header('Location:courses.php'); 

  }
 ?> 

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Raw Production Product Stock</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-8">
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

  

  <div class="form-group col-md-12">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ProdId" id="ProdId" required onchange="getAvailProdStock(this.value)">

<option selected="" value="">Select Product</option>
<?php 
  $sql12 = "SELECT * FROM tbl_raw_production_products WHERE Status=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-4">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock" id="AvailStock" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit" id="AvailStockUnit" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div>
    </div>
</div>                             
   

<div class="form-group col-md-4">
<label class="form-label">Stock In Qty </label>
<div class="input-group">
    <input type="text" name="Qty" id="Qty" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" required oninput="calTotalPrice()">
        <div class="input-group-append">
            <input type="text" name="QtyUnit" id="QtyUnit" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div> 
    </div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Product Price </label>
<div class="input-group">
    <input type="text" name="Price" id="Price" class="form-control" placeholder="" value="<?php echo $row7["Price"]; ?>" autocomplete="off" required oninput="calTotalPrice()">
        <div class="input-group-append">
            <input type="text" name="TotalPrice" id="TotalPrice" class="form-control" placeholder="" value="<?php echo $row7["TotalPrice"]; ?>" autocomplete="off" required> 
        </div>
    </div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="<?php echo $row7["CgstPer"];?>" readonly onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="<?php echo $row7["SgstPer"];?>" readonly onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)" >

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="<?php echo $row7["IgstPer"];?>" readonly onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>

<input type="hidden" id="CgstAmt" name="CgstAmt" value="<?php echo $row7['CgstAmt'];?>">
<input type="hidden" id="SgstAmt" name="SgstAmt" value="<?php echo $row7['SgstAmt'];?>">
<input type="hidden" id="IgstAmt" name="IgstAmt" value="<?php echo $row7['IgstAmt'];?>">

 
<div class="form-group col-lg-3">
<label class="form-label">Total GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="GstAmt" name="GstAmt" class="form-control" value="<?php echo $row7['GstAmt'];?>" readonly onKeyPress="return isNumberKey(event)"  readonly>
<div class="clearfix"></div>
</div>
</div>

    
<div class="form-group col-md-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<div class="form-group col-md-9">
   <label class="form-label">Narration</label>
<input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
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
    var Qty = $('#Qty').val();
    var Price = $('#Price').val();
    var CgstPer = $('#CgstPer').val();
    var SgstPer = $('#SgstPer').val();
    var IgstPer = $('#IgstPer').val();
    var TotalPrice = Number(Qty) * Number(Price);
    $('#TotalPrice').val(parseFloat(TotalPrice).toFixed(2));
    var TotGst = Number(CgstPer)+Number(SgstPer)+Number(IgstPer);
    var CgstAmt = Number(TotalPrice) * (Number(CgstPer)/100);
    var SgstAmt = Number(TotalPrice) * (Number(SgstPer)/100);
    var IgstAmt = Number(TotalPrice) * (Number(IgstPer)/100);
    $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
    $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
    $('#IgstAmt').val(parseFloat(IgstAmt).toFixed(2));
    var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
    $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
     
 } 
  function getAvailProdStock(id){
     var action = "getAvailRawProductionStock";
     //var GodownId = $('#GodownId').val();
            $.ajax({
                url: "ajax_files/ajax_raw_production_stock.php",
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
                    $('#QtyUnit').val(res.Unit);
                    $('#AvailStock').val(res.balqty);
                    $('#Price').val(res.Price);
                    $('#CgstPer').val(res.CgstPer);
                    $('#SgstPer').val(res.SgstPer);
                    $('#IgstPer').val(res.IgstPer);
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