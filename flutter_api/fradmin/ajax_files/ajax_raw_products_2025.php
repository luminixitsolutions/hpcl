<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
if($_POST['action'] == 'getProdDetails'){
$id = $_POST['id'];
$sql = "SELECT MinPrice,PurchasePrice,code FROM tbl_cust_products2 WHERE id='$id'";
$row = getRecord($sql);
echo json_encode(array('MinPrice'=>$row['MinPrice'],'PurchasePrice'=>$row['PurchasePrice'],'code'=>$row['code']));
    }
    
    if($_POST['action'] == 'addToCart'){
         if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT id,code,ProductName FROM tbl_cust_products2 WHERE id='" . $_POST["id"] . "'");
     $itemArray = array($productByCode[0]["code"]=>array('code'=>$productByCode[0]["code"],'id'=>$productByCode[0]["id"],'ProductName'=>$productByCode[0]["ProductName"],'PurchasePrice'=>$_POST["PurchasePrice"],'TotalPrice'=>$_POST["TotalPrice"],'Qty'=>$_POST["quantity"],'QtyUnit'=>$_POST["QtyUnit"],'Qty2'=>$_POST["Qty2"],'QtyUnit2'=>$_POST["QtyUnit2"]));
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
    
    //echo "<pre>";print_r($_SESSION["cart_item"]);
    echo 1;
    }
    
    if($_POST['action'] == 'displayCart'){
        ?>
        <table class="table table-striped table-bordered" width="100%">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Purchase Price</th>
            <th>Qty</th>
            
            <th>Total Price</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($_SESSION["cart_item"] as $product){?>
        <tr>
            <th><?php echo $product['ProductName'];?></th>
            <th><?php echo $product['PurchasePrice'];?></th>
            <th><?php echo $product['Qty2']." ".$product['QtyUnit2'];?></th>
            
            <th><?php echo $product['TotalPrice'];?></th>
            <th><a href="javascript:void(0)" onclick="delete_prod('<?php echo $product['code'];?>')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a></th>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php 
    }
    
    if($_POST['action'] == 'delete_cart_prod'){
        if (!empty($_SESSION["cart_item"])) {
        foreach ($_SESSION["cart_item"] as $k => $v) {
            if ($_POST["code"] == $k)
                unset($_SESSION["cart_item"][$k]);
            if (empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
        }
    }
        
    }
    
    if($_POST['action'] == 'calTotalQty'){
        $totqty = 0;
        foreach ($_SESSION["cart_item"] as $product){
            $totqty+=$product['Qty2'];
        }
        echo $totqty;
    }
    ?>