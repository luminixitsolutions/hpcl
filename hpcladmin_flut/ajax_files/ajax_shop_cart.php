<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$db_handle = new DBController();
$user_id = $_SESSION['User']['id'];
$UserEmail = $_SESSION['User']['EmailId'];
if($_POST['action'] == 'shop_cart'){
 if(!empty($_POST["quantity"])) {
    $productByCode = $db_handle->runQuery("SELECT * FROM tbl_cust_products WHERE code='" . $_POST["code"] . "'");
      //$price = $productByCode[0]["MinPrice"];
      $price = $_POST["price"];
      $qty = $_POST["quantity"];
      $total_price = $price * $qty;
      
 
      $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["ProductName"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$_POST["price"],'totalprice'=>$total_price,'Type'=>'Cart'));
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


if($_POST['action'] == 'delete_shop_prod'){
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

if($_POST['action'] == 'show_cart'){
 $total_price = 0;
       foreach ($_SESSION["cart_item"] as $product){
           $Prod_code = $product["code"];
           $total_price2 = ($product["price"]*$product["quantity"]);
      $total_price3 += ($product["price"]*$product["quantity"]);
            $sql = "SELECT * FROM tbl_cust_products WHERE code = '$Prod_code'";
            $row = getRecord($sql);
            
?>
   <li class="list-group-item py-0" style="padding-right: 1px;padding-left: 1px;">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding-top:20px;"><a href="javascript:void(0)" id="<?php echo $product["code"]; ?>" onclick="delete_prod2(this.id)" class="text-muted text-h-danger  mb-1"><i style="font-size:17px;" class="lnr lnr-trash"></i></a></td>
    
    <td style="text-align:left;">
                                                                
                
             <input type="hidden" id="price<?php echo $row["id"]; ?>" value="<?php echo $product["price"]; ?>">
                     <input type="hidden" id="code<?php echo $row["id"]; ?>" value="<?php echo $product["code"]; ?>">
                                                               
                                                                <p class="m-0 d-inline-block align-middle">
                                                                    <a href="#!" class="text-body font-weight-semibold" style="font-size:12px;"><?php echo $row["ProductName"]; ?></a>
                                                                    <br>
        <small style="color:#f06721;font-weight:bold;font-size:14px;"><?php echo $product["quantity"]; ?> x &#8377; <?php echo number_format($product["price"],2); ?></small>
                                                                </p><br>
                <div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                                                                    <button type="button" id="decrease"  onclick="changeMinus(<?php echo $row["id"].",'".$Prod_code."'"; ?>);" class="btn btn-secondary" style="border-radius:20px;background-color:#f9f9f9;color:#000;">-</button>
                                                                    <input class="wid-65 text-center qntno" type="text" id="qntno<?php echo $Prod_code;?>" value="<?php echo $product["quantity"];?>" readonly>
                                                                    <button type="button" id="increase" onclick="changePlus(<?php echo $row["id"].",'".$Prod_code."'"; ?>);" class="btn btn-secondary" style="border-radius:20px;background-color:#f9f9f9;color:#000;">+</button>
                                                                </div>
                                                                
        
                                                            </td>
                                                            <td class="text-right" style="font-weight:bold;">
                                                                &#8377; <?php echo number_format($total_price2,2); ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li> 
<?php } } 


if($_POST['action'] == 'showtotal'){
$total_price = 0;
       foreach ($_SESSION["cart_item"] as $product){
      $total_price3 += ($product["price"]*$product["quantity"]);
       }
?>

                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Total Amount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($total_price3,2); ?>
                                                        </td>
                                                    </tr>

                                                    <input type="hidden" name="SubTotal" id="SubTotal" class="form-control" placeholder="" value="<?php echo $total_price3; ?>" readonly>


<?php } 

if($_POST['action'] == 'showdiscount'){
    $per = $_POST['per'];
$total_price = 0;
       foreach ($_SESSION["cart_item"] as $product){
      $total_price3 += ($product["price"]*$product["quantity"]);
       }
       $discount = $total_price3*($per/100);
?>

                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Discount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($discount,2); ?>
                                                        </td>
                                                    </tr>
                                                    
                                                     <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Payable Amount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($total_price3-$discount,2); ?>
                                                        </td>
                                                    </tr>

                                                    <input type="hidden" name="Discount" id="Discount" class="form-control" placeholder="" value="<?php echo $discount; ?>" readonly>


<?php } 

if($_POST['action'] == 'showpkgamt'){
?>

                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Pkg Amount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($_POST['pkgamt'],2); ?>
                                                        </td>
                                                    </tr>


                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Prime Discount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($_POST['primeamt'],2); ?>
                                                        </td>
                                                    </tr>
                                                      <input type="text" name="PrimeDiscount" id="PrimeDiscount" class="form-control" placeholder="" value="<?php echo $_POST['primeamt']; ?>" readonly>

                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Total Bill Amount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($_POST['SubTotal']+$_POST['pkgamt']-$_POST['primeamt'],2); ?>
                                                        </td>
                                                    </tr>

                                                    
<?php } 


if($_POST['action'] == 'showexistpkgamt'){
?>

                                                   

                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Prime Discount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($_POST['primeamt'],2); ?>
                                                               <input type="hidden" name="PrimeDiscount" id="PrimeDiscount" class="form-control" placeholder="" value="<?php echo $_POST['primeamt']; ?>" readonly>
                                                        </td>
                                                    </tr>


                                                    <tr class="border-top">
                                                        <td>
                                                            <h5 class="m-0">Total Bill Amount:</h5>
                                                        </td>
                                                        <td class="font-weight-semibold" style="font-size:18px;color:#3fb51b;">
                                                             &#8377; <?php echo number_format($_POST['SubTotal']-$_POST['primeamt'],2); ?>
                                                        </td>
                                                    </tr>

                                                    
<?php } 
?>