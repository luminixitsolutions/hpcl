<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
if($_POST['action'] == 'getProdDetails'){
$id = $_POST['id'];
$sql = "SELECT MinPrice,PurchasePrice,code FROM tbl_cust_products_2025 WHERE id='$id'";
$row = getRecord($sql);
echo json_encode(array('MinPrice'=>$row['MinPrice'],'PurchasePrice'=>$row['PurchasePrice'],'code'=>$row['code']));
    }
    
    if($_POST['action'] == 'addToCart'){
         if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT id,code,ProductName FROM tbl_cust_products_2025 WHERE id='" . $_POST["id"] . "'");
    $cartKey = $productByCode[0]["code"] . '|' . trim((string)($_POST["BatchNo"] ?? ''));
     $itemArray = array($cartKey=>array(
        'code'=>$productByCode[0]["code"],
        'id'=>$productByCode[0]["id"],
        'ProductName'=>$productByCode[0]["ProductName"],
        'PurchasePrice'=>$_POST["PurchasePrice"] ?? '',
        'SellPrice'=>$_POST["SellPrice"] ?? '',
        'Qty'=>$_POST["quantity"],
        'BatchNo'=>$_POST["BatchNo"] ?? '',
        'MfgDate'=>$_POST["MfgDate"] ?? '',
        'ExpDate'=>$_POST["ExpDate"] ?? ''
     ));
      if(!empty($_SESSION["cart_item"])) {
        if(isset($_SESSION["cart_item"][$cartKey])) {
          $_SESSION["cart_item"][$cartKey]["Qty"] = $_POST["quantity"];
          $_SESSION["cart_item"][$cartKey]["PurchasePrice"] = $_POST["PurchasePrice"] ?? '';
          $_SESSION["cart_item"][$cartKey]["SellPrice"] = $_POST["SellPrice"] ?? '';
          $_SESSION["cart_item"][$cartKey]["BatchNo"] = $_POST["BatchNo"] ?? '';
          $_SESSION["cart_item"][$cartKey]["MfgDate"] = $_POST["MfgDate"] ?? '';
          $_SESSION["cart_item"][$cartKey]["ExpDate"] = $_POST["ExpDate"] ?? '';
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
        <div class="table-responsive" style="overflow-x:auto;width: 490px;">
    <table class="table table-striped table-bordered" style="min-width: 1000px;">
        <thead>
            <tr>
                <th>Action</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Batch No</th>
                <th>Purchase Price</th>
                <th>Sell Price</th>
                <th>Mfg Date</th>
                <th>Exp Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION["cart_item"])) { 
                foreach ($_SESSION["cart_item"] as $cartKey => $product) { ?>
                <tr>
                    <td>
                        <a href="javascript:void(0)" 
                           onclick="delete_prod('<?php echo htmlspecialchars($cartKey, ENT_QUOTES, 'UTF-8');?>')" 
                           data-toggle="tooltip" 
                           title="Delete">
                            <i class="lnr lnr-trash text-danger"></i>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($product['ProductName'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($product['Qty'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($product['BatchNo'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($product['PurchasePrice'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($product['SellPrice'] ?? ''); ?></td>
                    <td><?= !empty($product['MfgDate']) ? date("d/m/Y", strtotime($product['MfgDate'])) : ''; ?></td>
                    <td><?= !empty($product['ExpDate']) ? date("d/m/Y", strtotime($product['ExpDate'])) : ''; ?></td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="8" class="text-center">No products in cart</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

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
            $totqty+=$product['Qty'];
        }
        echo $totqty;
    }
    

    if($_POST['action'] == 'checkBarcodeNo'){
$barcode = $_POST['barcode'];
$frid = $_POST['frid'];
$sql = "SELECT id,ProductName FROM tbl_cust_products_2025 WHERE BarcodeNo='$barcode' AND CreatedBy='$frid'";
$row = getRecord($sql);
echo json_encode(array('ProdId'=>$row['id'],'ProductName'=>$row['ProductName']));
    }
    ?>