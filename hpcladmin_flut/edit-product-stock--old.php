<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Request-Product-Stock";

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
$id = $_GET['id'];
$sql77 = "SELECT * FROM tbl_request_product_stock WHERE id='".$_GET['id']."'";
$row77 = getRecord($sql77);
if(isset($_POST['submit'])){
    $UpdatedDate = addslashes(trim($_POST['UpdatedDate']));
    $TotQty = addslashes(trim($_POST['TotQty']));
    $Remark = addslashes(trim($_POST['Remark']));
    $GstAmount = addslashes(trim($_POST['GstAmount']));
    $TotalAmount = addslashes(trim($_POST['TotalAmount']));
    $VedId = addslashes(trim($_POST['VedId']));
    $CreatedDate = date('Y-m-d H:i:s');
    $sql = "UPDATE tbl_request_product_stock_2025 SET TotQty='$TotQty',TotalAmount='$TotalAmount',GstAmount='$GstAmount',UpdatedDate='$UpdatedDate',Remark='$Remark',UpdatedBy='$user_id',UpdatedDateTime='$CreatedDate',UpdateStatus=1,Status=1,VedId='$VedId' WHERE id='$id'";
    $conn->query($sql);
    

    $number = count($_POST["ProdId"]);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["ProdId"][$i] != '')) {
                                $ProdId = addslashes(trim($_POST['ProdId'][$i]));
                                $Qty = addslashes(trim($_POST['Qty'][$i]));
                                $Unit = addslashes(trim($_POST['QtyUnit'][$i]));
                                $Rate = addslashes(trim($_POST['Rate'][$i]));
                                $Total = addslashes(trim($_POST['Total'][$i]));
                                $CgstPer = addslashes(trim($_POST['CgstPer'][$i]));
                                $SgstPer = addslashes(trim($_POST['SgstPer'][$i]));
                                $IgstPer = addslashes(trim($_POST['IgstPer'][$i]));
                                $CgstAmt = addslashes(trim($_POST['CgstAmt'][$i]));
                                $SgstAmt = addslashes(trim($_POST['SgstAmt'][$i]));
                                $IgstAmt = addslashes(trim($_POST['IgstAmt'][$i]));
                                $GstAmt = addslashes(trim($_POST['GstAmt'][$i]));
                                $id = addslashes(trim($_POST['id'][$i]));
                                $sql33 = "UPDATE tbl_request_product_stock_items_2025 SET MinPrice='$Rate',GstAmt='$GstAmt',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',Total='$Total' WHERE id='$id'";
                                $conn->query($sql33);
                                
                               
                            }
                        }
                    }

                    echo "<script>alert('Request Sent Successfully!');window.location.href='view-request-product-stock.php';</script>";
}


?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Edit Product Stock</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="FromFrId" id="FromFrId" value="<?php echo $BillSoftFrId;?>">
<div class="form-row">
<div class="form-group col-md-12">
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
</div>
 <div id="dynamic_field">
    <?php 
        $sql = "SELECT * FROM tbl_request_product_stock_items_2025 WHERE TransferId='".$_GET['id']."'";
        $row = getList($sql);
        foreach($row as $result){
    ?>
    <div class="form-row">
        
        <div class="form-group col-md-4 ">
<label class="form-label">Product</label>
 <select class="form-control" name="ProdId[]" id="ProdId<?php echo $result['id'];?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno1').value)">
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products_2025 WHERE id='".$result['ProdId']."'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?> 
  <option value="<?php echo $result12['id'];?>">
    <?php echo $result12['ProductName']; ?></option>
<?php } ?>
</select>
</div>





<!-- <div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="" value="<?php echo $result['AvailStock'];?>" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit1" class="form-control" placeholder="" value="<?php echo $result['AvailStockUnit'];?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div> -->

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $result['id'];?>" class="form-control txt" placeholder="" value="<?php echo $result['Qty'];?>" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $result['id'];?>" class="form-control" placeholder="" value="<?php echo $result["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Rate </label>
    <input type="text" name="Rate[]" id="Rate<?php echo $result['id'];?>" class="form-control" placeholder="" value="<?php echo $result['MinPrice'];?>" autocomplete="off" oninput="calTotal(document.getElementById('Rate<?php echo $result['id'];?>').value,document.getElementById('Qty<?php echo $result['id'];?>').value,document.getElementById('id<?php echo $result['id'];?>').value)">
</div>

<div class="form-group col-md-2">
<label class="form-label">Total </label>
    <input type="text" name="Total[]" id="Total<?php echo $result['id'];?>" class="form-control txt2" placeholder="" value="<?php echo $result['Qty']*$result['MinPrice'];?>" autocomplete="off" >
</div>

<input type="hidden" class="form-control" name="id[]" id="id<?php echo $result['id'];?>" value="<?php echo $result['id'];?>">

<input type="hidden" class="form-control" name="CgstPer[]" id="CgstPer<?php echo $result['id'];?>" value="2.5">
<input type="hidden" class="form-control" name="SgstPer[]" id="SgstPer<?php echo $result['id'];?>" value="2.5">
<input type="hidden" class="form-control" name="IgstPer[]" id="IgstPer<?php echo $result['id'];?>" value="0">
<input type="hidden" class="form-control" name="CgstAmt[]" id="CgstAmt<?php echo $result['id'];?>" value="<?php echo $result['CgstAmt'];?>">
<input type="hidden" class="form-control" name="SgstAmt[]" id="SgstAmt<?php echo $result['id'];?>" value="<?php echo $result['SgstAmt'];?>">
<input type="hidden" class="form-control" name="IgstAmt[]" id="IgstAmt<?php echo $result['id'];?>" value="<?php echo $result['IgstAmt'];?>">
<input type="hidden" class="form-control txtgstamt" name="GstAmt[]" id="GstAmt<?php echo $result['id'];?>" value="<?php echo $result['GstAmt'];?>">

</div>
<?php } ?>
</div>

<div class="form-row">

<div class="form-group col-md-2">
<label class="form-label">Total Qty <span class="text-danger">*</span></label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row77["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-2">
<label class="form-label">GST Amount <span class="text-danger">*</span></label>
<input type="text" name="GstAmount" id="GstAmount" class="form-control" placeholder="" value="<?php echo $row77["GstAmount"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-2">
<label class="form-label">Total Amount <span class="text-danger">*</span></label>
<input type="text" name="TotalAmount" id="TotalAmount" class="form-control" placeholder="" value="<?php echo $row77["TotalAmount"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>


<div class="form-group col-md-3">
<label class="form-label">Request Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control txt" placeholder="" value="<?php echo $row77['StockDate']; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

     <div class="form-group col-md-3">
<label class="form-label">Updated Date <span class="text-danger">*</span></label>
<input type="date" name="UpdatedDate" id="UpdatedDate" class="form-control txt" placeholder="" value="<?php echo $row77['UpdatedDate']; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<!--<div class="form-group col-md-6">
<label class="form-label">Total Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>-->

    
<div class="form-group col-md-6">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <input type="text" name="Narration" id="Narration" class="form-control" required value="<?php echo $row77['Narration']; ?>" readonly>
    <div class="clearfix"></div>
 </div>




    
<div class="form-group col-md-6">
   <label class="form-label">Remark <span class="text-danger">*</span></label>
     <input type="text" name="Remark" id="Remark" class="form-control" required value="<?php echo $row77['Remark']; ?>">
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
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
    function calTotal(rate,qty,id){
        var Total = Number(rate)*Number(qty);
        $('#Total'+id).val(parseFloat(Total).toFixed(2));
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
         getSubTotal();
        getGstAmt();
        getTotAmt();
  var i=1; 
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
      }); 

  }); 
</script>
</body>
</html>