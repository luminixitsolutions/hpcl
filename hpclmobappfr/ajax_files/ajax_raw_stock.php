<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
//include_once 'incuserdetails.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'getMore'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-3 ">
<label class="form-label">Godown Product</label>
 <select class="form-control" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
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
 <select class="form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>">
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

	 $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' GROUP by Status) as a";
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
	$GodownId = $_POST['GodownId'];
$sql = "SELECT Unit FROM tbl_cust_products_2025 WHERE id='$id'";
$row = getRecord($sql);
$Unit = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'balqty'=>$row2['balqty']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'balqty'=>0));
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
                $sql4 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy IN ($BillSoftFrId) AND ProdType=0 ORDER BY ProductName";
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


if($_POST['action'] == 'getAvailRawProdStock'){
	$id = $_POST['id'];
$sql = "SELECT tu.Name2, tp.Unit
FROM tbl_cust_products2 AS tp
LEFT JOIN tbl_units AS tu ON tp.Unit = tu.Name
WHERE tp.ProdType = 1 AND tp.id = '$id'";
$row = getRecord($sql);
$Unit = $row['Name2'];
$Unit2 = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM 
(SELECT (case when Status='Dr' then sum(Qty2) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty2) else '0' end) as creditqty 
FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' AND ProdType=1 AND FrId='$user_id' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'Unit2'=>$Unit2,'balqty'=>$row2['balqty']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'Unit2'=>$Unit2,'balqty'=>0));
	}
}


if($_POST['action'] == 'getFrRawProduct'){?>
    <option value="" selected="selected" disabled="">Select Product</option>
<?php 
    $CountryId = $_POST['id'];
        $q = "select * from tbl_cust_products WHERE CreatedBy = '$CountryId' AND Transfer=1";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
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


 <div class="form-group col-md-3 ">
<label class="form-label">From Franchise Product</label>
 <select class="form-control" name="GodownProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-3">
<label class="form-label">To Franchise Product</label>
 <select class="form-control ProdId" name="ProdId[]" id="ProdId<?php echo $i; ?>">
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

if($_POST['action'] == 'getMoreReqFr'){
    $i = $_POST['id']; 
    $FranchiseId = $_POST['FranchiseId'];?>
  <div class="form-row" id="row<?php echo $i;?>">


 <div class="form-group col-md-4 ">
<label class="form-label">From Franchise Product</label>
 <select class="form-control" name="ProdId[]" id="GodownProdId<?php echo $i; ?>"  onchange="getAvailProdStock(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_products WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 ORDER BY ProductName";
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

if($_POST['action'] == 'addItem'){
    $ProdId = $_POST['ProdId'];
    $AvailStock = $_POST['AvailStock'];
    $AvailStockUnit = $_POST['AvailStockUnit'];
    $Qty = $_POST['Qty'];
    $QtyUnit = $_POST['QtyUnit'];
    $sql = "SELECT ProductName,GstAmt,CgstPer,SgstPer,IgstPer,CgstAmt,SgstAmt,IgstAmt,ProdPrice,MinPrice FROM tbl_cust_products_2025 WHERE id='$ProdId'";
    $row = getRecord($sql);
 if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT * FROM tbl_cust_products_2025 WHERE id='" . $_POST["ProdId"] . "'");
     $itemArray = array($productByCode[0]["code"]=>array('code'=>$productByCode[0]["code"],'ProdId'=>$_POST["ProdId"],'AvailStock'=>$_POST["AvailStock"],'AvailStockUnit'=>$_POST["AvailStockUnit"],'Qty'=>$_POST["Qty"],'QtyUnit'=>$_POST["QtyUnit"],'ProdName'=>$row['ProductName'],'CgstPer'=>$row['CgstPer'],'SgstPer'=>$row['SgstPer'],'IgstPer'=>$row['IgstPer'],'CgstAmt'=>$row['CgstAmt'],'IgstAmt'=>$row['IgstAmt'],'SgstAmt'=>$row['SgstAmt'],'ProdPrice'=>$row['ProdPrice'],'MinPrice'=>$row['MinPrice'],'GstAmt'=>$row['GstAmt']));
      if(!empty($_SESSION["cart_item"])) {
        if(in_array($productByCode[0]["code"],$_SESSION["cart_item"])) {
          foreach($_SESSION["cart_item"] as $k => $v) {
              if($productByCode[0]["code"] == $k)
                $_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
          }
        } else {
          $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
        }
      } else {
        $_SESSION["cart_item"] = $itemArray;
      }
    }

   echo "Product Added";
}

if($_POST['action'] == 'showData'){?>
    <table class="table table-striped table-bordered">
     <thead>
    <tr>
        <th>Sr.No</th>
        <th>Product Name</th>
        <th>Avail Stock</th>
        <th>Qty</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1;
    foreach ($_SESSION["cart_item"] as $product){?>
<tr>
    <td><?php echo $i;?></td>
    <td><?php echo $product['ProdName'];?></td>
    <td><?php echo $product['AvailStock']." ".$product['AvailStockUnit'];?></td>
    <td><?php echo $product['Qty']." ".$product['QtyUnit'];?></td>
    <td><a href="javascript:void(0)" onclick="deleteCart('<?php echo $product['code'];?>')"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $i++;} ?>
</tbody>
</table>
    <?php } 

if($_POST['action'] == 'deleteCart'){
    $output = "";
    if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_POST["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                        if(empty($_SESSION["cart_item"]))
                            unset($_SESSION["cart_item"]);
                           
                }
            } 
}

if($_POST['action'] == 'getProdDetails'){
$id = $_POST['id'];
$sql = "SELECT MinPrice,PurchasePrice FROM tbl_cust_products_2025 WHERE id='$id'";
$row = getRecord($sql);
echo json_encode(array('MinPrice'=>$row['MinPrice'],'PurchasePrice'=>$row['PurchasePrice']));
    }
?>