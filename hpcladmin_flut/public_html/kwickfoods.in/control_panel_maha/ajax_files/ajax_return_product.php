<?php
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['fr_admin'];
$loginuserid = $_SESSION['Admin']['id'];

if($_POST['action'] == 'addMoreRow'){
    $i = $_POST['id']; ?>
  <div class="form-row" id="row<?php echo $i;?>"  style="border: 1px solid #cdcdcd;padding: 10px;">
      <div class="form-group col-md-3">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ProdId[]" id="ProdId<?php echo $i;?>" required onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">

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
    <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <!-- <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div> -->
    </div>
</div>                             
   

<div class="form-group col-md-3">
<label class="form-label">Return Stock In Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" required oninput="calTotalPrice(<?php echo $i;?>)">
        <!-- <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div>  -->
    </div>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">
<div class="form-group col-md-2" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
      </div>
 <?php } 

if($_POST['action'] == 'getAvailMrpProdStock'){
	$id = $_POST['id'];
	$sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock` WHERE ProdId='$id' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	$balqty = $row2['balqty'];
	echo json_encode(array('balqty'=>$balqty));

}

if($_POST['action'] == 'getAvailRawProdStock'){
	$id = $_POST['id'];
    $sql = "SELECT Unit FROM tbl_cust_products WHERE id='$id'";
    $row = getRecord($sql);
	$sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM 
                (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty 
                FROM `tbl_fr_raw_stock` WHERE ProdId='$id' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	$balqty = $row2['balqty'];
	echo json_encode(array('balqty'=>$balqty,'Unit'=>$row['Unit']));

}


if($_POST['action'] == 'addMoreRawRow'){
    $i = $_POST['id']; ?>
  <div class="form-row" id="row<?php echo $i;?>"  style="border: 1px solid #cdcdcd;padding: 10px;">
      <div class="form-group col-md-3">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ProdId[]" id="ProdId<?php echo $i;?>" required onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">

<option selected="" value="">Select Product</option>
<?php 
  $sql12 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy='$BillSoftFrId' AND ProdType=1 ORDER BY ProductName";
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
    <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
         <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div> 
    </div>
</div>                             
   

<div class="form-group col-md-3">
<label class="form-label">Return Stock In Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" required oninput="calTotalPrice(<?php echo $i;?>)">
       <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly>
        </div>  
    </div>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">
<div class="form-group col-md-2" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
      </div>
 <?php } 
?>