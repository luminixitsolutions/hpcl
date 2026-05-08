<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
//include_once 'incuserdetails.php';
$FranchiseId = $_SESSION['FranchiseId'];
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'addItem'){
    $ProdId = $_POST['FromProdId'];
    $AvailStock = $_POST['AvailStock'];
    $AvailStockUnit = $_POST['AvailStockUnit'];
    $Qty = $_POST['Qty'];
    $QtyUnit = $_POST['QtyUnit'];
    $sql = "SELECT ProductName,GstAmt,CgstPer,SgstPer,IgstPer,CgstAmt,SgstAmt,IgstAmt,ProdPrice,MinPrice FROM tbl_cust_products_2025 WHERE id='$ProdId'";
    $row = getRecord($sql);
 if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT * FROM tbl_cust_products_2025 WHERE id='" . $_POST["FromProdId"] . "'");
     $itemArray = array($productByCode[0]["code"]=>array('code'=>$productByCode[0]["code"],'FromProdId'=>$_POST["FromProdId"],'ToProdId'=>$_POST["ToProdId"],'AvailStock'=>$_POST["AvailStock"],'AvailStockUnit'=>$_POST["AvailStockUnit"],'Qty'=>$_POST["Qty"],'QtyUnit'=>$_POST["QtyUnit"],'ProdName'=>$row['ProductName'],'CgstPer'=>$row['CgstPer'],'SgstPer'=>$row['SgstPer'],'IgstPer'=>$row['IgstPer'],'CgstAmt'=>$row['CgstAmt'],'IgstAmt'=>$row['IgstAmt'],'SgstAmt'=>$row['SgstAmt'],'ProdPrice'=>$row['ProdPrice'],'MinPrice'=>$row['MinPrice'],'GstAmt'=>$row['GstAmt']));
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
?>