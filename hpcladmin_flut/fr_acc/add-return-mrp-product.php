<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$frid = $_SESSION['fr_admin'];
$MainPage = "Return-Products";
$Page = "Return-MRP-Product";
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
$sql8 = "SELECT MAX(srno) AS MaxId FROM tbl_return_prod_stock WHERE ProdType=1";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$Invoice_No = "00" . $MaxId;
?>

<?php 
  if(isset($_POST['submit'])){
    $InvNo = $_POST['InvNo'];
    $srno = $_POST['srno'];
    $ReturnDate = addslashes(trim($_POST['ReturnDate']));
    $VedId = addslashes(trim($_POST['VedId']));
    $CreatedDate = date('Y-m-d H:i:s');
    $Narration = addslashes(trim($_POST['Narration']));

    if($_GET['id'] == ''){
     $qx = "INSERT INTO tbl_return_prod_stock SET srno='$srno',InvNo='$InvNo',VedId='$VedId',CreatedBy='$user_id',ReturnDate='$ReturnDate',Narration='$Narration',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',ProdType=1";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);
  
  $number = count($_POST["ProdId"]);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProdId"][$i] != ''))  
              {
                $ProdId = $_POST['ProdId'][$i];
                $AvailStock = $_POST['AvailStock'][$i];
                $AvailStockUnit = $_POST['AvailStockUnit'][$i];
                $Qty = $_POST['Qty'][$i];
                $Unit = $_POST['QtyUnit'][$i];
                
                $sql22 = "INSERT INTO tbl_return_prod_items SET InvId='$InvId',FrId='$BillSoftFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',ReturnDate='$ReturnDate',ReturnStatus='0',CreatedDate='$CreatedDate',ProdType=1";
                $conn->query($sql22);
              }  

          }
      }
      
  echo "<script>alert('Product Stock Return Successfully!Please Wait For admin Approval');window.location.href='return-mrp-products.php';</script>";
}
  
    

    //header('Location:courses.php'); 

  }
 ?> 

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Return MRP Product</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-12">
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Invoice No <span class="text-danger">*</span></label>
<input type="text" name="InvNo" id="InvNo" class="form-control" placeholder="" value="<?php echo $Invoice_No; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
<input type="hidden" id="srno" name="srno" value="<?php echo $MaxId;?>">
<div class="form-group col-md-3">
<label class="form-label">Return Date <span class="text-danger">*</span></label>
<input type="date" name="ReturnDate" id="ReturnDate" class="form-control" placeholder="" value="<?php echo $row7["ReturnDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

  <div class="form-group col-md-6">
<label class="form-label"> Vendor <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="VedId" id="VedId" required>

<!--<option selected="" value="">Select Godown</option>-->
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
</div> 

<div id="dynamic_field">
    <div class="form-row" style="border: 1px solid #cdcdcd;padding: 10px;">
  <div class="form-group col-md-3">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="form-control" name="ProdId[]" id="ProdId1" required onchange="getAvailProdStock(this.value,1)">

<option selected="" value="">Select Product</option>
<?php 
  $sql12 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy='$BillSoftFrId' AND ProdType=0 ORDER BY ProductName";
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
    <input type="text" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <!-- <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div> -->
    </div>
</div>                             
   

<div class="form-group col-md-3">
<label class="form-label">Return Stock In Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty1" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" required oninput="calTotalPrice(1)">
        <!-- <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div>  -->
    </div>
</div>


<div class="form-group col-md-2" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>

</div>
</div>

    <div class="form-row">


<div class="form-group col-md-12">
   <label class="form-label">Reason <span class="text-danger">*</span></label>
<input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>" required>
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

 function calTotalPrice(srno){
    var Qty = $('#Qty'+srno).val();
    var Price = $('#Price'+srno).val();
    var CgstPer = $('#CgstPer'+srno).val();
    var SgstPer = $('#SgstPer'+srno).val();
    var IgstPer = $('#IgstPer'+srno).val();
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
     
 } 
  function getAvailProdStock(id,srno){
     var action = "getAvailMrpProdStock";
            $.ajax({
                url: "ajax_files/ajax_return_product.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                   var res = JSON.parse(data);
                    $('#AvailStockUnit'+srno).val(res.Unit);
                    $('#QtyUnit'+srno).val(res.Unit);
                    $('#AvailStock'+srno).val(res.balqty);
                    /*$('#Price'+srno).val(res.Price);
                    $('#CgstPer'+srno).val(res.CgstPer);
                    $('#SgstPer'+srno).val(res.SgstPer);
                    $('#IgstPer'+srno).val(res.IgstPer);*/
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
       var action = "addMoreRow";
    $.ajax({
    url:"ajax_files/ajax_return_product.php",
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