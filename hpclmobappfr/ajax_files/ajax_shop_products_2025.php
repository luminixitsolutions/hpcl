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
    
    if ($_POST['action'] == 'addToCart') {
    if (!empty($_POST["quantity"])) {
        $photoName = '';

        // Check if file is uploaded
        if (!empty($_FILES['Photo']['name'])) {
            $targetDir = "../../wastage_photos/mrp/"; // create folder if not exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $photoName = time() . "_" . basename($_FILES['Photo']['name']);
            $targetFile = $targetDir . $photoName;

            if (move_uploaded_file($_FILES['Photo']['tmp_name'], $targetFile)) {
                // file uploaded successfully
            } else {
                $photoName = ''; // if failed, keep it blank
            }
        }

        $productByCode = $db_handle->runQuery("SELECT id, code, ProductName FROM tbl_cust_products_2025 WHERE id='" . $_POST["id"] . "'");
        $itemArray = array(
            $productByCode[0]["code"] => array(
                'code' => $productByCode[0]["code"],
                'id' => $productByCode[0]["id"],
                'ProductName' => $productByCode[0]["ProductName"],
                'PurchasePrice' => $_POST["PurchasePrice"],
                'SellPrice' => $_POST["SellPrice"],
                'Qty' => $_POST["quantity"],
                'MfgDate' => $_POST["MfgDate"] ?? '',
                'ExpDate' => $_POST["ExpDate"] ?? '',
                'Photo' => $photoName // ✅ save filename in session
            )
        );

        if (!empty($_SESSION["cart_item"])) {
            if (array_key_exists($productByCode[0]["code"], $_SESSION["cart_item"])) {
                $_SESSION["cart_item"][$productByCode[0]["code"]]["Qty"] = $_POST["quantity"];
                $_SESSION["cart_item"][$productByCode[0]["code"]]["Photo"] = $photoName;
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }
    }

    echo 1;
}

    
    if($_POST['action'] == 'displayCart'){
        ?>
<div class="row">
    <?php foreach ($_SESSION["cart_item"] as $index => $product) { ?>
        <div class="col-md-6 col-12 mb-3">
            <div class="product-card">
                <!-- Product Image -->
                <img src="<?php echo !empty($product['Photo']) ? '../../wastage_photos/mrp/'.$product['Photo'] : 'no_image.jpg'; ?>" 
                     alt="Product Image"
                     class="product-image"
                     >

                <!-- Product Details -->
                <div class="product-details">
                    <div class="product-title"><?php echo $product['ProductName']; ?></div>
                    <div class="product-info">Qty: <strong><?php echo $product['Qty']; ?></strong></div>
                    <div class="product-info">Purchase: <strong><?php echo number_format($product['PurchasePrice'], 2); ?></strong></div>
                    <div class="product-info">Sell: <strong><?php echo number_format($product['SellPrice'], 2); ?></strong></div>
                </div>

                <!-- Delete Button -->
                <button class="delete-btn" type="button" onclick="delete_prod('<?php echo $product['code']; ?>')" title="Delete">
                    <i class="fa fa-trash text-danger"></i>
                </button>
            </div>
        </div>
    <?php } ?>
</div>


<style>
.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: flex;
    align-items: center;
}
.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.product-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    margin-right: 12px;
    border: 1px solid #ddd;
    cursor: pointer;
}
.product-details { flex: 1; }
.product-title { font-size: 15px; font-weight: 600; margin-bottom: 4px; color: #333; }
.product-info { font-size: 13px; color: #555; margin-bottom: 2px; }
.product-info strong { color: #000; }
.delete-btn {
    background: #ffe6e6;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
    margin-left: 10px;
}
.delete-btn:hover { background: #ffcccc; }
</style>
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