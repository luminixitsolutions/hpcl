<?php
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getMore'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-3 ">
<label class="form-label">Godown Product</label>
 <select class="select2-demo form-control GodownProdId" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>" data-allow-clear="true" data-placeholder="Search and select godown product..." onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_godown_products WHERE Status='1'";
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
<select class="select2-demo form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>" data-allow-clear="true" data-placeholder="Search and select product...">
<option selected="" value="">Select Product</option>
 <?php 
 if($FranchiseId!=''){
  $sql12 = "select * from tbl_cust_products WHERE CreatedBy = '$FranchiseId' AND Transfer=1";
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
	/*if($row['balqty'] > 0){
	echo $row['balqty'];
	}
	else{
	    echo 0;
	}*/
	echo $row['balqty'];
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
    $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='dr' then sum(Qty) else '0' end) as debitqty,(case when Status='cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_godown_raw_prod_stock` WHERE ProdId='$id' AND GodownId='$GodownId' GROUP by Status) as a";
    $row = getRecord($sql);
    if($row['balqty'] > 0){
        $balqty = $row['balqty'];
    }
    else{
        $balqty = 0;
    }
	echo $balqty;
}



if($_POST['action'] == 'getAvailProdStock2'){
	$id = $_POST['id'];
	$FrId = $_POST['FromFrId'];
$sql = "SELECT Unit,MinPrice FROM tbl_cust_products_2025 WHERE id='$id'";
$row = getRecord($sql);
$Unit = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' AND FrId='$FrId' AND ProdType=0 GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'balqty'=>$row2['balqty'],'Price'=>$row['MinPrice']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'balqty'=>0,'Price'=>$row['MinPrice']));
	}
}

if($_POST['action'] == 'getAvailRawProdStock'){
	$id = $_POST['id'];
	$FrId = $_POST['FromFrId'];
$sql = "SELECT Unit,MinPrice FROM tbl_cust_products_2025 WHERE id='$id'";
$row = getRecord($sql);
$Unit2 = $row['Unit'];

$sql2 = "SELECT Name2 FROM tbl_units WHERE Name='$Unit2'";
$row2 = getRecord($sql2);
$Unit = $row2['Name2'];

$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' AND FrId='$FrId' AND ProdType=0 GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'balqty'=>$row2['balqty'],'Price'=>$row['MinPrice']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'balqty'=>0,'Price'=>$row['MinPrice']));
	}
}

if($_POST['action'] == 'addMoreRaw'){
    $i = $_POST['id'];?>
  <div class="form-row" id="row<?php echo $i;?>">
 <div class="form-group col-md-3">
    <label class="form-label">Customer Product </label>
        <select class="form-control" style="width: 100%" data-allow-clear="true" name="CustProdId[]" id="CustProdId<?php echo $i;?>">
        <option selected value="">...</option>
            <?php
                echo $sql4 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy IN ($BillSoftFrId) AND ProdType=0 ORDER BY ProductName";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
        <option <?php if ($row7["CustProdId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (&#8377;".$result['MinPrice'].")"; ?></option>
       <?php } ?>
    </select>
</div>

 <div class="form-group col-lg-3">
<label class="form-label">Making Qty </label>
<input type="text" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $i;?>" placeholder="" value="<?php echo $row7["MakingQty"]; ?>">
<div class="clearfix"></div>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
<?php } 


/*if($_POST['action'] == 'getAvailRawProdStock'){
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
}*/

if($_POST['action'] == 'getFrRawProduct'){?>
    <option value="" selected="selected" disabled="">Select Product</option>
<?php 
    $CountryId = $_POST['id'];
        /*$q = "select id,ProductName,'Selling' AS Roll from tbl_cust_products_2025 WHERE CreatedBy = '$CountryId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN (1,3) UNION ALL SELECT id,ProductName,'Raw' AS Roll FROM tbl_cust_products2 WHERE id IN($AllocateRawProd) ORDER BY ProductName";*/
        
        $q = "select id,ProductName,ProdType2 from tbl_cust_products_2025 WHERE CreatedBy = '$CountryId' AND ProdType=0 AND ProdType2 IN (1,3) ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        if($rw['ProdType2'] == 1){
          $ProdType2 = "MRP";
      }
      else if($rw['ProdType2'] == 3){
          $ProdType2 = "Other";
      }
?>
                <option value="<?php echo $rw['id']; ?>"> <?php echo $rw['ProductName']." (".$ProdType2.")"; ?></option>
<?php } } 


if($_POST['action'] == 'getFrRawProduct2'){?>
    <option value="" selected="selected" disabled="">Select Product</option>
<?php 
    $FrId = $_POST['id'];
    $sql77 = "SELECT AllocateRawProd FROM tbl_users_bill WHERE id='$FrId'";
	$row77 = getRecord($sql77);
	$AllocateRawProd = $row77['AllocateRawProd'];
       
        $q = "select id,ProductName,ProdType2 from tbl_cust_products_2025 WHERE CreatedBy='$FrId' AND checkstatus=1 AND ProdType='1' AND Status=1 ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        $ProdType2 = "RAW";
?>
                <option value="<?php echo $rw['id']; ?>"> <?php echo $rw['ProductName']." (".$ProdType2.")"; ?></option>
<?php } } 

if($_POST['action'] == 'getAvailGodownProdStock'){
	$id = $_POST['id'];
	$GodownId = $_POST['GodownId'];
$sql = "SELECT Unit FROM tbl_godown_products WHERE id='$id'";
$row = getRecord($sql);
$Unit = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM 
(SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty 
FROM `tbl_godown_raw_prod_stock` WHERE ProdId='$id' AND GodownId='$GodownId' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'balqty'=>$row2['balqty']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'balqty'=>0));
	}
}


if($_POST['action'] == 'getMoreFr'){ 
     $i = $_POST['id']; 
     $FranchiseId = $_POST['FranchiseId'];?> 
   <div class="form-row" id="row<?php echo $i;?>"> 
 
 
  <div class="form-group col-md-2"> 
 <label class="form-label">From Franchise Product</label> 
  <select class="form-control GodownProdId" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)"> 
 <option selected="" value="">Select Product</option> 
  <?php 
   $sql12 = "SELECT id,ProductName,ProdType2 FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN (1,3) AND checkstatus=1 ORDER BY ProductName"; 
   $row12 = getList($sql12); 
   foreach($row12 as $result){ 
       if($result['ProdType2'] == 1){
          $ProdType2 = "MRP";
      }
      else if($result['ProdType2'] == 3){
          $ProdType2 = "Other";
      }
      ?> 
   <option value="<?php echo $result['id'];?>"> 
     <?php echo $result['ProductName']." (".$ProdType2.")"; ?></option> 
 <?php } ?> 
 </select> 
 </div> 
 
 <div class="form-group col-md-2"> 
 <label class="form-label">To Franchise Product</label> 
  <select class="form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>"> 
 <option selected="" value="">Select Product</option> 
  <?php 
  if($FranchiseId!=''){ 
   $sql12 = "select * from tbl_cust_products_2025 WHERE CreatedBy = '$FranchiseId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN (1,3) AND checkstatus=1 ORDER BY ProductName"; 
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
     <input type="text" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" required oninput="calculateTotal(<?php echo $i; ?>)"> 
         <div class="input-group-append"> 
             <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;"> 
         </div> 
     </div> 
 </div> 
 
 <div class="form-group col-md-1"> 
 <label class="form-label">Price </label> 
 <input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="calculateTotal(<?php echo $i; ?>)"> 
 </div> 
 
 <div class="form-group col-md-2"> 
 <label class="form-label">Total Price </label> 
 <input type="text" name="TotalPrice[]" id="TotalPrice<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly> 
 </div> 
 
 <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>"> 
 
 <div class="form-group col-md-1" style="padding-top: 30px;"> 
 <label class="form-label">&nbsp;</label> 
 <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button> 
 </div> 
 </div>
<?php } 


if($_POST['action'] == 'getMoreReqFr'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-4 ">
<label class="form-label">From Franchise Product</label>
 <select class="form-control" name="ProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN (1,3) AND checkstatus=1 ORDER BY ProductName";
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


if($_POST['action'] == 'getToFrSlectProd'){
$id = $_POST['id'];
$ToFrId = $_POST['ToFrId'];
$sql = "SELECT ProdId FROM tbl_cust_products_2025 WHERE id='$id'";
$row = getRecord($sql);
$ProdId = $row['ProdId'];
$sql = "SELECT id FROM tbl_cust_products_2025 WHERE ProdId='$ProdId' AND CreatedBy = '$ToFrId'";
$row = getRecord($sql);
?>
    <option value="" selected="selected" disabled="">Select Product</option>
<?php 
         $q = "select id,ProductName,ProdType2 from tbl_cust_products_2025 WHERE CreatedBy = '$ToFrId' AND ProdType=0 AND ProdType2 IN (1,3) AND checkstatus=1 ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        if($rw['ProdType2'] == 1){
          $ProdType2 = "MRP";
      }
      else if($rw['ProdType2'] == 3){
          $ProdType2 = "Other";
      }
?>
                <option value="<?php echo $rw['id']; ?>" <?php if($row['id'] == $rw['id']){?>selected<?php } ?>> <?php echo $rw['ProductName']." (".$ProdType2.")"; ?></option>
<?php } } 

if($_POST['action'] == 'getToFrSlectProd2'){
$id = $_POST['id'];
$ToFrId = $_POST['ToFrId'];


$sql77 = "SELECT AllocateRawProd FROM tbl_users_bill WHERE id='$ToFrId'";
	$row77 = getRecord($sql77);
	$AllocateRawProd = $row77['AllocateRawProd'];
?>
    <option value="" selected="selected" disabled="">Select Product</option>
<?php 
         $q = "select id,ProductName,ProdType2 from tbl_cust_products_2025 WHERE CreatedBy='$ToFrId' AND checkstatus=1 AND ProdType='1' AND Status=1 ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        $ProdType2 = "RAW";
?>
                <option value="<?php echo $rw['id']; ?>" <?php if($id == $rw['id']){?>selected<?php } ?>> <?php echo $rw['ProductName']." (".$ProdType2.")"; ?></option>
<?php } } 



if($_POST['action'] == 'getMoreFr2'){ 
     $i = $_POST['id']; 
     $FranchiseId = $_POST['FranchiseId'];
     $sql77 = "SELECT AllocateRawProd FROM tbl_users_bill WHERE id='$FranchiseId'";
	$row77 = getRecord($sql77);
	$AllocateRawProd2 = $row77['AllocateRawProd'];
     ?> 
   <div class="form-row" id="row<?php echo $i;?>"> 
 
 
  <div class="form-group col-md-2"> 
 <label class="form-label">From Franchise Product</label> 
  <select class="form-control GodownProdId" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)"> 
 <option selected="" value="">Select Product</option> 
  <?php 
   $sql12 = "SELECT id,ProductName,ProdType2 FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND checkstatus=1 AND ProdType='1' AND Status=1 ORDER BY ProductName"; 
   $row12 = getList($sql12); 
   foreach($row12 as $result){ 
       $ProdType2 = "RAW";
      ?> 
   <option value="<?php echo $result['id'];?>"> 
     <?php echo $result['ProductName']." (".$ProdType2.")"; ?></option> 
 <?php } ?> 
 </select> 
 </div> 
 
 <div class="form-group col-md-2"> 
 <label class="form-label">To Franchise Product</label> 
  <select class="form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>"> 
 <option selected="" value="">Select Product</option> 
  <?php 
  if($FranchiseId!=''){ 
   $sql12 = "select * from tbl_cust_products_2025 WHERE CreatedBy='$FranchiseId' AND checkstatus=1 AND ProdType='1' AND Status=1 ORDER BY ProductName"; 
   $row12 = getList($sql12); 
   foreach($row12 as $result){ 
       $ProdType2 = "RAW";
      ?> 
   <option value="<?php echo $result['id'];?>"> 
     <?php echo $result['ProductName']." (".$ProdType2.")"; ?></option> 
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
     <input type="text" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" required oninput="calculateTotal(<?php echo $i; ?>)"> 
         <div class="input-group-append"> 
             <input type="text" name="QtyUnit[]" id="QtyUnit<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;"> 
         </div> 
     </div> 
 </div> 
 
 <div class="form-group col-md-1"> 
 <label class="form-label">Price </label> 
 <input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="calculateTotal(<?php echo $i; ?>)"> 
 </div> 
 
 <div class="form-group col-md-2"> 
 <label class="form-label">Total Price </label> 
 <input type="text" name="TotalPrice[]" id="TotalPrice<?php echo $i; ?>" class="form-control" placeholder="" value="" autocomplete="off" readonly> 
 </div> 
 
 <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>"> 
 
 <div class="form-group col-md-1" style="padding-top: 30px;"> 
 <label class="form-label">&nbsp;</label> 
 <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button> 
 </div> 
 </div>
<?php } 
?>