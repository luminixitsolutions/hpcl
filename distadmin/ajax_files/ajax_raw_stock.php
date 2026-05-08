<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getMore'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-2 ">
<label class="form-label">Godown Product</label>
 <select class="form-control" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_godown_products WHERE Status='1' ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-2">
<label class="form-label">Franchise Product</label>
 <select class="form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>">
<option selected="" value="">Select Product</option>
 <?php 
 if($FranchiseId!=''){
  $sql12 = "select * from tbl_cust_products WHERE CreatedBy = '$FranchiseId' ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } } ?>
</select>
</div>


<div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" required oninput="calTotalPrice(document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('CgstPer<?php echo $i; ?>').value,document.getElementById('SgstPer<?php echo $i; ?>').value,document.getElementById('IgstPer<?php echo $i; ?>').value)">
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-1">
<label class="form-label">Price </label>
    <input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" required oninput="calTotalPrice(document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('CgstPer<?php echo $i; ?>').value,document.getElementById('SgstPer<?php echo $i; ?>').value,document.getElementById('IgstPer<?php echo $i; ?>').value)">
        
</div>

<div class="form-group col-md-2">
<label class="form-label">Total Price </label>
    <input type="text" name="TotalPrice[]" id="TotalPrice<?php echo $i; ?>" class="form-control txt2" placeholder="" value="" autocomplete="off" required>
        
</div>



<input type="hidden" class="form-control" name="CgstPer[]" id="CgstPer<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="SgstPer[]" id="SgstPer<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="IgstPer[]" id="IgstPer<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="CgstAmt[]" id="CgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="SgstAmt[]" id="SgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="IgstAmt[]" id="IgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control txtgstamt" name="GstAmt[]" id="GstAmt<?php echo $i; ?>" value="">

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
<?php } 

if($_POST['action'] == 'getAvailStock'){
	$id = $_POST['id'];
	$sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when crdr='dr' then sum(Qty) else '0' end) as debitqty,(case when crdr='cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_raw_stock` WHERE ProdId='$id' GROUP by CrDr) as a";

	$row = getRecord($sql);
	echo $row['balqty']; 
}

if($_POST['action'] == 'getAvailProdStock'){
	$id = $_POST['id'];

	 $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock` WHERE ProdId='$id' GROUP by Status) as a";
	$row = getRecord($sql);
	if($row['balqty'] > 0){
	echo $row['balqty'];
	}
	else{
	    echo 0;
	}
}

/*if($_POST['action'] == 'getAvailGodownProdStock'){
	$id = $_POST['id'];

	 $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_godown_raw_prod_stock` WHERE ProdId='$id' GROUP by Status) as a";
	$row = getRecord($sql);
	if($row['balqty'] > 0){
	echo $row['balqty'];
	}
	else{
	    echo 0;
	}
}*/

if($_POST['action'] == 'getAvailGodownStock'){
    $id = $_POST['id'];
	$GodownId = $_POST['GodownId'];
    $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='dr' then sum(Qty) else '0' end) as debitqty,(case when Status='cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_godown_raw_prod_stock_2025` WHERE ProdId='$id' AND GodownId='$GodownId' GROUP by Status) as a";
    $row = getRecord($sql);
    if($row['balqty'] > 0){
        $balqty = $row['balqty'];
    }
    else{
        $balqty = 0;
    }
	echo $balqty;
}


if($_POST['action'] == 'addMoreRaw'){
    $i = $_POST['id'];?>
  <div class="form-row" id="row<?php echo $i;?>">
 <div class="form-group col-md-3">
    <label class="form-label">Customer Product </label>
        <select class="form-control" style="width: 100%" data-allow-clear="true" name="CustProdId[]" id="CustProdId<?php echo $i;?>">
        <option selected value="">...</option>
            <?php
                $sql4 = "SELECT * FROM tbl_cust_products2 WHERE Status=1 AND ProdType=0 AND ProdType2=2 ORDER BY ProductName";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
        <option <?php if ($row7["CustProdId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (&#8377;".$result['MinPrice'].")"; ?></option>
       <?php } ?>
    </select>
</div>

 <div class="form-group col-lg-2">
<label class="form-label">Making Qty (As Per Unit)</label>
<input type="text" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $i; ?>" placeholder="" value="" oninput="calQty2(<?php echo $i; ?>,document.getElementById('MakingQty<?php echo $i; ?>').value)">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Unit</label>
 <input type="text" class="form-control unit" value="<?php echo $_POST['Unit'];?>" readonly>
<div class="clearfix"></div>
</div> 

<input type="hidden" class="form-control" name="MakingQty2[]" id="MakingQty2<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="MakingQtyUnit2[]" id="MakingQtyUnit2<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
<?php } 


if($_POST['action'] == 'getAvailRawProdStock'){
	$id = $_POST['id'];
$sql = "SELECT Unit FROM tbl_cust_products WHERE id='$id'";
$row = getRecord($sql);
$Unit = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM 
(SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty 
FROM `tbl_fr_raw_stock` WHERE ProdId='$id' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'balqty'=>$row2['balqty']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'balqty'=>0));
	}
}

if($_POST['action'] == 'getFrRawProduct'){?>
    <option value="" selected="selected" disabled="">Select Product</option>
<?php 
    $CountryId = $_POST['id'];
        $q = "select * from tbl_cust_products WHERE CreatedBy = '$CountryId' ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
<?php } } 

if($_POST['action'] == 'getFrCustProduct'){?>
    <option value="0" selected="selected">Select Product</option>
<?php 
    $BillSoftFrId = $_POST['id'];
        $q = "SELECT * FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 AND ProdType2 IN (1,3) ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
<?php } } 


if($_POST['action'] == 'getAvailGodownProdStock'){
	$id = $_POST['id'];
	$GodownId = $_POST['GodownId'];
$sql = "SELECT * FROM tbl_cust_products2 WHERE id='$id'";
$row = getRecord($sql);
$Unit = $row['Unit'];
$Price = $row['MinPrice'];
$CgstPer = $row['CgstPer'];
$SgstPer = $row['SgstPer'];
$IgstPer = $row['IgstPer'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM 
(SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty 
FROM `tbl_godown_raw_prod_stock_2025` WHERE ProdId='$id' AND GodownId='$GodownId' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'Price'=>$Price,'balqty'=>$row2['balqty'],'CgstPer'=>$CgstPer,'SgstPer'=>$SgstPer,'IgstPer'=>$IgstPer));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'Price'=>$Price,'balqty'=>0,'CgstPer'=>$CgstPer,'SgstPer'=>$SgstPer,'IgstPer'=>$IgstPer));
	}
}


if($_POST['action'] == 'getMoreFr'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-3 ">
<label class="form-label">Franchise Product</label>
 <select class="form-control" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products WHERE Status='1' AND CreatedBy=9";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-3">
<label class="form-label">Franchise Product</label>
 <select class="form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>">
<option selected="" value="">Select Product</option>
 <?php 
 if($FranchiseId!=''){
  $sql12 = "select * from tbl_cust_products WHERE CreatedBy = '$FranchiseId'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } } ?>
</select>
</div>


<div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" required>
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>







<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
<?php } 


if($_POST['action'] == 'getMoreRetailer'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-3 ">
<label class="form-label">Godown Product</label>
 <select class="form-control" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_godown_products WHERE Status='1' ORDER BY ProductName";
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
    <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" required oninput="calTotalPrice(document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('CgstPer<?php echo $i; ?>').value,document.getElementById('SgstPer<?php echo $i; ?>').value,document.getElementById('IgstPer<?php echo $i; ?>').value)">
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Price </label>
    <input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" required oninput="calTotalPrice(document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('CgstPer<?php echo $i; ?>').value,document.getElementById('SgstPer<?php echo $i; ?>').value,document.getElementById('IgstPer<?php echo $i; ?>').value)">
        
</div>

<div class="form-group col-md-2">
<label class="form-label">Total Price </label>
    <input type="text" name="TotalPrice[]" id="TotalPrice<?php echo $i; ?>" class="form-control txt2" placeholder="" value="" autocomplete="off" required>
        
</div>



<input type="hidden" class="form-control" name="CgstPer[]" id="CgstPer<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="SgstPer[]" id="SgstPer<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="IgstPer[]" id="IgstPer<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="CgstAmt[]" id="CgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="SgstAmt[]" id="SgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="IgstAmt[]" id="IgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control txtgstamt" name="GstAmt[]" id="GstAmt<?php echo $i; ?>" value="">

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
<?php }  

if($_POST['action'] == 'getMoreReqFr'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-4 ">
<label class="form-label">Franchise Product</label>
 <select class="form-control ProdId" name="ProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
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




<div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" required oninput="calTotal(document.getElementById('Rate<?php echo $i; ?>').value,document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">
<input type="hidden" class="form-control" name="CgstPer[]" id="CgstPer<?php echo $i; ?>" value="2.5">
<input type="hidden" class="form-control" name="SgstPer[]" id="SgstPer<?php echo $i; ?>" value="2.5">
<input type="hidden" class="form-control" name="IgstPer[]" id="IgstPer<?php echo $i; ?>" value="0">
<input type="hidden" class="form-control" name="CgstAmt[]" id="CgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="SgstAmt[]" id="SgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control" name="IgstAmt[]" id="IgstAmt<?php echo $i; ?>" value="">
<input type="hidden" class="form-control txtgstamt" name="GstAmt[]" id="GstAmt<?php echo $i; ?>" value="">

<div class="form-group col-md-1">
<label class="form-label">Rate </label>
    <input type="text" name="Rate[]" id="Rate<?php echo $i;?>" class="form-control" placeholder="" value="<?php echo $result['MinPrice'];?>" autocomplete="off" oninput="calTotal(document.getElementById('Rate<?php echo $i; ?>').value,document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</div>

<div class="form-group col-md-2">
<label class="form-label">Total </label>
    <input type="text" name="Total[]" id="Total<?php echo $i; ?>" class="form-control txt2" placeholder="" value="<?php echo $result['Total'];?>" autocomplete="off" >
</div>

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
<?php } 

if($_POST['action'] == 'getAvailProdStock2'){
    $id = $_POST['id'];
    $GodownId = $_POST['GodownId'];
    $FranchiseId = $_POST['FranchiseId'];
$sql = "SELECT Unit FROM tbl_cust_products_2025 WHERE id='$id'";
if($FranchiseId!=''){
            $sql.=" AND CreatedBy='$FranchiseId'";
        }
$row = getRecord($sql);
$Unit = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` 
        WHERE ProdId='$id' ";
        if($FranchiseId!=''){
            $sql2.=" AND FrId='$FranchiseId'";
        }
        $sql2.=" GROUP by Status) as a";
        //echo $sql2;
    $row2 = getRecord($sql2);
    if($row2['balqty'] > 0){
    echo json_encode(array('Unit'=>$Unit,'balqty'=>$row2['balqty']));
    }
    else{
        echo json_encode(array('Unit'=>$Unit,'balqty'=>0));
    }
}


if (isset($_POST['action']) && $_POST['action'] == 'UpdateOldNew') {
    $id = intval($_POST['id']);
    $oldnew = intval($_POST['oldnew']);
    function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string .= $domain[$index];
        }
        return $generated_string;
    }


    $sql = "UPDATE tbl_cust_products2 SET OldNew='$oldnew' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
       $sql2 = "SELECT id FROM `tbl_users` WHERE Roll=5 AND Status=1";
            $row2 = getList($sql2);
            foreach ($row2 as $result2) {
                $CreatedBy = $result2['id'];
                $checkstatus = 1;
                $n = 10;
                $Code2 = RandomStringGenerator($n);
                $sql = "INSERT INTO tbl_cust_products_2025 
                (`ProdId`, `ProductName`, `BrandId`, `CatId`, `SubCatId`, `CgstPer`, `SgstPer`, `IgstPer`, 
                `CgstAmt`, `SgstAmt`, `IgstAmt`, `GstAmt`, `ProdPrice`, `MinPrice`, `Status`, `SrNo`, `Photo`, 
                `code`, `CreatedDate`, `ModifiedDate`, `CreatedBy`, `ModifiedBy`, `BarcodeNo`, `StockQty`, 
                `TempPrdId`, `Display`, `push_flag`, `delete_flag`, `modified_time`, `ProdType`, `Qty`, `Unit`, 
                `Transfer`, `Assets`, `QrDisplay`, `MinQty`, `ProdType2`, `PurchasePrice`, `checkstatus`, 
                `tempstatus`, `clearprod`, `SubTotal`, `DiscPer`, `Discount`, `Division`, `Segment`, `Family`, 
                `ClassId`, `McDesc`, `BrandDesc`, `CrossSell`, `MatchMrpProdId`, `kitchen_type`, `OldNew`)
                SELECT 
                '$id' AS `ProdId`, `ProductName`, `BrandId`, `CatId`, `SubCatId`, `CgstPer`, `SgstPer`, `IgstPer`, 
                `CgstAmt`, `SgstAmt`, `IgstAmt`, `GstAmt`, `ProdPrice`, `MinPrice`, `Status`, `SrNo`, `Photo`, 
                '$Code2' AS `code`, `CreatedDate`, `ModifiedDate`, 
                '$CreatedBy' AS `CreatedBy`, `ModifiedBy`, `BarcodeNo`, `StockQty`, 
                `TempPrdId`, `Display`, `push_flag`, `delete_flag`, `modified_time`, `ProdType`, `Qty`, `Unit`, 
                `Transfer`, `Assets`, `QrDisplay`, `MinQty`, `ProdType2`, `PurchasePrice`, 
                '$checkstatus' AS `checkstatus`, 
                `tempstatus`, `clearprod`, `SubTotal`, `DiscPer`, `Discount`, `Division`, `Segment`, `Family`, 
                `ClassId`, `McDesc`, `BrandDesc`, `CrossSell`, `MatchMrpProdId`, `kitchen_type`, `OldNew`
                FROM tbl_cust_products2 WHERE id='$id'";
                $conn->query($sql);
            }
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
}



?>