<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once("dbcontroller.php");
$db_handle = new DBController();
$MainPage = "Customer-Invoice";
$Page = "Add-Customer-Invoice";
//print_r($_SESSION["cart_item"]);
//echo $_SESSION["cart_item"]['9UuoAoFORY1']['quantity'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - Dashboard</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/blueimp-gallery/gallery.css">
    <link rel="stylesheet" href="assets/libs/blueimp-gallery/gallery-indicator.css">
</head>
<body>
<style type="text/css">
    .mr_5{
        margin-right: 3rem !important;
    }
    .tabbable .nav-tabs {
   overflow-x: auto;
   overflow-y:hidden;
   flex-wrap: nowrap;
}
.tabbable .nav-tabs .nav-link {
  white-space: nowrap;
}

</style>


 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>

<?php 
$id = $_GET['id'];
                $sql8 = "SELECT tci.*,tu.CustomerId FROM tbl_customer_invoice tci LEFT JOIN  tbl_users tu ON tci.CustId=tu.id WHERE tci.id='$id'";
                $row7 = getRecord($sql8);
                
                
                $i=1;
  $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
      $productByCode = $db_handle->runQuery("SELECT * FROM tbl_cust_products WHERE id='" . $result12["ProdId"] . "'");
      //$price = $productByCode[0]["MinPrice"];
      $price = $result12["ActPrice"];
      $qty = $result12["Qty"];
      $code = $productByCode[0]["code"];
      $total_price = $price * $qty;
      
 
      $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["ProductName"], 'code'=>$productByCode[0]["code"], 'quantity'=>$qty, 'price'=>$price,'totalprice'=>$total_price,'Type'=>'Cart'));
      if(!empty($_SESSION["cart_item"])) {
        if(in_array($productByCode[0]["code"],$_SESSION["cart_item"])) {
          foreach($_SESSION["cart_item"] as $k => $v) {
              if($productByCode[0]["code"] == $k)
                $_SESSION["cart_item"][$k]["quantity"] = $qty;
          }
        } else {
          $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
        }
      } else {
        $_SESSION["cart_item"] = $itemArray;
      }
  }
                ?>
<div class="layout-container">


<div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 " style="padding-top:10px;">
                       
                        <div class="row">
                            <div class="col-md-8">
                                 <div class="">
                        <div class="tabbable">
                        <ul id="gallery-filter" class="nav nav-tabs tabs-alt mb-2">
                            <li class="nav-item">
                                <a class="nav-link active" href="#all">All</a>
                            </li> 
                            <?php 
                                $i=1;
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                     $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' AND CreatedBy=0";
                                    $rncnt2 = getRow($sql2);
                                    if($rncnt2 > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#cat<?php echo $result['id'];?>" style="color:#fff;"><?php echo $result['Name'];?></a>
                            </li>
                            <?php }$i++;} ?>
                            
                        </ul>
                        </div>
                        <!-- Lightbox template -->
                        <div id="gallery-lightbox" class="blueimp-gallery blueimp-gallery-controls">
                            <div class="slides"></div>
                            <h3 class="title"></h3>
                            <a class="prev">‹</a>
                            <a class="next">›</a>
                            <a class="close">×</a>
                            <a class="play-pause"></a>
                            <ol class="indicator"></ol>
                        </div>
                        <div id="gallery-thumbnails" class="row form-row">
                        <div class="gallery-sizer col-sm-6 col-md-6 col-4 col-xl-3 position-absolute"></div>
                          
                            <?php 
                                $i=1;
                                /*$sql = "SELECT id FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc LIMIT 1";
                                $row = getRecord($sql);*/
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                    $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' AND CreatedBy=0 ORDER BY SrNo asc ";
                                    $row2 = getList($sql2);
                                    foreach($row2 as $result2){
                                        $code = $result2['code'];
                                        $sessqty2 =  $_SESSION["cart_item"][$code]['quantity'];
                                        if($sessqty2 == ''){
                                            $sessqty = 0;
                                        }
                                        else{
                                            $sessqty = $sessqty2;
                                        }
                            ?>
                            <div align="center" class="gallery-thumbnail col-sm-4 col-md-6  col-4 col-xl-3 mb-2" data-tag="cat<?php echo $result['id'];?>" style="">
                                <a href="javascript:void(0)" onclick="addCart(<?php echo $result2['id'];?>)">
                                   <!-- <span class="img-thumbnail-overlay bg-dark opacity-25"></span>
                                    <span class="img-thumbnail-content display-4 text-white">
                                        <i class="ion ion-ios-search"></i>
                                    </span>-->
                                  
                                </a>
                                <div align="center" style="padding-top:2px;">
                                <strong style="text-align:center;font-size: 12px;letter-spacing: 0.5px;"><?php echo $result2['ProductName'];?></strong><br>
                                <strong style="font-size:17px;color:#f06721;">&#8377; <?php echo number_format($result2['MinPrice'],2); ?></strong>
                                <!--<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm" style="padding-bottom: 10px;">
                                                                    <button style="background-color:#3d8b28;" type="button" id="decrease" onclick="decreaseValue(<?php echo $result2["id"];?>)" class="btn btn-secondary">-</button>
                                                                    <input class="wid-35 text-center" type="text" id="qntno<?php echo $result2["id"];?>" value="1">
                                                                    <button style="background-color:#3d8b28;"  type="button" id="increase" onclick="increaseValue(<?php echo $result2["id"];?>)" class="btn btn-secondary">+</button>
                                                                </div><br>-->
                                                                
                                  <input class="wid-35 text-center" type="hidden" id="qntno<?php echo $result2["code"];?>" value="<?php echo $sessqty;?>">                               
                               <!--  <button type="button" id="add-cart<?php echo $result2['id'];?>" class="btn btn-primary btn-finish" onclick="addCart(<?php echo $result2['id'];?>)">Add Cart</button>-->
                                </div>
                            </div>

                            
                            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">

   <input type="hidden" id="pid<?php echo $result2["id"];?>" value="<?php echo $result2["id"];?>">
  
    <input type="hidden" id="code<?php echo $result2["id"];?>" value="<?php echo $result2['code'];?>">
     <input type="hidden" id="prd_price<?php echo $result2["id"];?>" value="<?php echo $result2['MinPrice'];?>"> 

                            <?php } $i++;} ?>
                          
                        </div>

                    </div>
                    <!-- [ content ] End -->            
                                           
                                       
                                    </div>
                                
                            <div class="col-md-4  d-none d-md-block">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Order Summary</h5>
                                    </div>
                                    <ul class="list-group list-group-flush" id="showcart">
                                        
                                       
                                    </ul>
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0 w-auto table-sm float-right text-right">
                                                <tbody id="showtotal">
                                                    
                                                </tbody>
                                                <tbody id="showpkgamt">
                                                    
                                                </tbody>
                                            </table>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    
                                
                                    <div class="form-group col-md-12">
                                            <label class="form-label">Contact No </label>
                                            <input type="number" name="CellNo" id="CellNo" class="form-control" placeholder="" value="<?php echo $row7["CellNo"]; ?>" autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" id="CustName" class="form-control" placeholder="" value="<?php echo $row7["CustName"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <input type="hidden" name="CustId" id="CustId" value="<?php echo $row7['CustId'];?>">     
                                        <input type="hidden" name="AccCode" id="AccCode" value="<?php echo $row7['CustomerId'];?>">
                                        <div class="form-group col-lg-12">
                                            <label class="form-label">Payment Type</label>
                                            <select class="form-control" id="PayType" name="PayType">
                                                <option selected="" disabled="" value="">Select Payment Type</option>
                                                <option selected="" value="Cash">Cash</option>
                                                <option <?php if ($row7['PayType2'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($row7['PayType2'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($row7['PayType2'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($row7['PayType2'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-6" style="padding-top: 10px;">
                                 <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="Featured" value="1" onclick="featured()">
                                    <span class="custom-control-label">Pending</span>
                                </label>
                                </div>
                                
                                 <div class="form-group col-md-6" style="padding-top: 10px;">
                                 <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="Print" value="1" onclick="Print()" checked>
                                    <span class="custom-control-label">Print</span>
                                </label>
                                </div>
                                        
                                       <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Package </label>
                                            <select class="form-control" style="width: 100%" data-allow-clear="true" name="PkgId" id="PkgId">
                                               <option value="0">No Package</option>
                                                <?php
                                                $sql4 = "SELECT * FROM tbl_packages WHERE Status=1";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                    if($result['Period'] == 1){
                                                        $Duration = $result['Duration']." Month";
                                                    }
                                                    else{
                                                      $Duration = $result['Duration']." Year";  
                                                    }
                                                ?>
                                                    <option <?php if ($row7["PkgId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']." ( ₹".$result['Amount']." ) - ".$Duration; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <input type="hidden" value="" id="PackageId" name="PackageId">
                                          <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Amount </label>
                                            <input type="text" name="PkgAmt" id="PkgAmt" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Prime Discount % </label>
                                            <input type="text" name="PkgDiscount" id="PkgDiscount" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Valid Upto  </label>
                                            <input type="date" name="PkgValidity" id="PkgValidity" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                      
                                      <div style="padding-top:10px;"></div>   
                        <a href="javascript:void(0)" id="saveprint" class="btn btn-success  mt-md-0 mt-2" onclick="savePrint()">
                                                                Save & Print
                                                            </a>
                                       <div style="padding-top:10px;"></div>   
                                    <a  href="add-customer-invoice.php" class="btn btn-danger  mt-md-0 mt-2">
                                                                Continue to Order
                                                            </a>
                                                            
                                                            
                                                             </div>
                                                             
    <input type="hidden" id="InvoiceNo" value="<?php echo $row7['InvoiceNo'];?>">
    <input type="hidden" id="BillNo" value="<?php echo $row7['BillNo'];?>">
    <input type="hidden" id="OrderNo" value="<?php echo $row7['OrderNo'];?>">
    <input type="hidden" id="InvId" value="<?php echo $row7['id'];?>">
     <input type="hidden" id="SrNo" value="<?php echo $row7['SrNo'];?>">
    
                               <!--<div class="card">
                                    <div class="card-header">
                                        <h5>Pending Orders</h5>  
                                    </div>
                                    <div class="row" id="showpendingorders">
                                       
                                    </div>
                                     
                                </div>  -->  
                                    
                                
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
<!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/blueimp-gallery/gallery.js"></script>
    <script src="assets/libs/blueimp-gallery/gallery-fullscreen.js"></script>
    <script src="assets/libs/blueimp-gallery/gallery-indicator.js"></script>
    <script src="assets/libs/masonry/masonry.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
    <script src="assets/js/pages/pages_gallery.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
 function featured(){
        if($('#Featured').prop('checked') == true) {
            $('#Featured').val(0);
        }
        else{
           $('#Featured').val(1);
            }
        }
        
        function Print(){
        if($('#Print').prop('checked') == true) {
            $('#Print').val(1);
        }
        else{
           $('#Print').val(0);
            }
        }
        
        function showPendingOrders(){
            var action = "showPendingOrders";
           $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action},
   success: function(data){
        $('#showpendingorders').html(data);
   }
   });
        }
        
        function editOrder(id){
            window.location.href="edit-order.php?id="+id;
        }
    function countCart(){
         var action = "countCart";
           $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action},
   success: function(data){
        $('#cnt').html(data);
   }
   });
    }
    function showProduct(catid){
          var action = "showProduct";
           $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,catid:catid},
   success: function(data){
    //console.log(data);
    $('#showproduct').html(data);
   }
   });
    }
function savePrint(){
    var CellNo = $('#CellNo').val();
    var CustName = $('#CustName').val();
    var PayType = $('#PayType').val();
    var AccCode = $('#AccCode').val();
    var CustId = $('#CustId').val();
    var PackageId = $('#PackageId').val();
    var PkgAmt = $('#PkgAmt').val();
    var PkgDiscount = $('#PkgDiscount').val();
    var PkgValidity = $('#PkgValidity').val();
    var PrimeDiscount = $('#PrimeDiscount').val();
    var PkgId = $('#PkgId').val();
    var Featured = $('#Featured').val();
    var Print = $('#Print').val();
    var InvoiceNo = $('#InvoiceNo').val();
    var BillNo = $('#BillNo').val();
    var OrderNo = $('#OrderNo').val();
    var InvId = $('#InvId').val();
    var SrNo = $('#SrNo').val();
    var action = "updatePrint";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,CellNo:CellNo,CustName:CustName,PayType:PayType,CustId:CustId,PackageId:PackageId,PkgAmt:PkgAmt,PkgDiscount:PkgDiscount,PkgValidity:PkgValidity,PkgId:PkgId,PrimeDiscount:PrimeDiscount,Featured:Featured,Print:Print,InvoiceNo:InvoiceNo,BillNo:BillNo,AccCode:AccCode,OrderNo:OrderNo,InvId:InvId,SrNo:SrNo},
  beforeSend:function(){
     $('#saveprint').attr('disabled','disabled');
     $('#saveprint').text('Please Wait...');
    },
   success: function(data){
       console.log(data); 
      toastr.success('Order Placed Successfully', 'Success', {timeOut: 2000});
      $('#saveprint').attr('disabled',false);
     $('#saveprint').text('Save');
      $('#CustName').val('');
      $('#CellNo').val('');
    $('#CustId').val(0);
    $('#AccCode').val(''); 
    $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
     $('#PkgAmt').val('');
                        $('#PkgDiscount').val('');
                        $('#PkgValidity').val('');
                      $('#PrimeDiscount').val(0);
                      $('#PackageId').val(0);
                        $('#showpkgamt').html('');
    //  showCart();
    //  $('.showform').hide();
     if(Print == 1){
      Android.printReceipt(''+data+'');
     }
      window.location.href="orders.php";
        
   }
  });
}
function getUserDetails(){
    var CellNo = $('#CellNo').val();
    var action = "getUserDetails2";
     var CurrDate = $('#CurrDate').val();
  $.ajax({
  type: "POST",
  url: "ajax_files/ajax_dropdown.php",
  data: {action:action,CellNo:CellNo},
  dataType:"json",  
   success: function(data){
console.log(data);
if(data == 0){
     $('.showform').show();
      $('#CustName').val('');
    $('#CustId').val(0);
    $('#AccCode').val(''); 
     $('#PackageId').val(0);
                        $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
                        $('#PkgAmt').val(''); 
                        $('#PkgDiscount').val(''); 
                        $('#PkgValidity').val(''); 
                         $('#PrimeDiscount').val('');
 }
 else{
    $('.showform').show();
 $('#CustId').val(data.id);
                           $('#Address').val(data.Address);
                        $('#CustName').val(data.Fname);
                       // $('#CellNo').val(data.Phone);
                        $('#EmailId').val(data.EmailId);
                        $('#AccCode').val(data.CustomerId); 
                        if(data.Prime == 0){
                             $('#PackageId').val(0);
                        $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
                        $('#PkgAmt').val(''); 
                        $('#PkgDiscount').val(''); 
                        $('#PkgValidity').val(''); 
                         $('#PrimeDiscount').val('');
                        }
                        else{
                            $('#PackageId').val(data.PkgId);
                        $('#PkgId').val(data.PkgId).attr("disabled",true).attr("selected",true); 
                        $('#PkgAmt').val(data.PkgAmt); 
                        $('#PkgDiscount').val(data.PkgDiscount); 
                        $('#PkgValidity').val(data.PkgValidity); 
                         var SubTotal = $('#SubTotal').val();
                        var PrimeAmt = Number(SubTotal)*(Number(data.PkgDiscount)/100);
                        $('#PrimeDiscount').val(parseFloat(PrimeAmt).toFixed(2));
                        showexistpkgamt(data.PkgAmt,PrimeAmt,SubTotal);
                        }
                         //getSubTotal();
                         
   }
}
  });
}
    $(document).ready(function() {
       
    $('#example').DataTable( {
      "lengthMenu": [[5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"]]
    } );

     $(document).on("change", "#PkgId", function(event) {
                var val = this.value;
                var action = "getPkgDetails";
                var SubTotal = $('#SubTotal').val();
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        var res = JSON.parse(data);
                        if(val == 0){
                            $('#PkgAmt').val('');
                        $('#PkgDiscount').val('');
                        $('#PkgValidity').val('');
                      $('#PrimeDiscount').val(0);
                      $('#PackageId').val(0);
                        $('#showpkgamt').html('');
                        }
                        else{
                            $('#PackageId').val(0);
                           $('#PkgAmt').val(res.PkgAmt);
                        $('#PkgDiscount').val(res.PkgDiscount);
                        $('#PkgValidity').val(res.PkgValidity);
                        $('#Pkg_Amount').val(res.PkgAmt);
                        var PrimeAmt = Number(SubTotal)*(Number(res.PkgDiscount)/100);
                        $('#PrimeDiscount').val(parseFloat(PrimeAmt).toFixed(2));
                        showpkgamt(res.PkgAmt,PrimeAmt,SubTotal);
                        }
                        //getSubTotal();
                    }
                });

            });

} );

function delete_prod2(code){
          var action = "delete_shop_prod";

  $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,code:code},

   success: function(data){
     $('#qntno'+code).val(0);   
   showCart();
        
   }
  });

}

function showCart(){
    var action = "show_cart";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action},
  success: function(data){
        $('#showcart').html(data);
        total();
        countCart();
        //showPendingOrders();
      }
});
}

function total(){
    var action = "showtotal";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action},
  success: function(data){
        $('#showtotal').html(data);
      }
});
}

function showpkgamt(pkgamt,primeamt,SubTotal){
    var action = "showpkgamt";
    console.log(pkgamt,primeamt,SubTotal);
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pkgamt:pkgamt,primeamt:primeamt,SubTotal:SubTotal},
  success: function(data){
        $('#showpkgamt').html(data);
      }
});
}

function showexistpkgamt(pkgamt,primeamt,SubTotal){
    var action = "showexistpkgamt";
    console.log(pkgamt,primeamt,SubTotal);
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pkgamt:pkgamt,primeamt:primeamt,SubTotal:SubTotal},
  success: function(data){
        $('#showpkgamt').html(data);
      }
});
}
function addCart(id){
var action = "shop_cart";
var code = $('#code'+id).val();
var quantity = $('#qntno'+code).val();
if(quantity == 0){
    $('#qntno'+code).val(1);
    var totqty = 1;
}
else{
    var totqty = Number(quantity)+1;
    $('#qntno'+code).val(totqty);
}

var pid = $('#pid'+id).val();
var price = $('#prd_price'+id).val();
$.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:pid,quantity:totqty,code:code,price:price},
   beforeSend:function(){
     $('#add-cart'+id).attr('disabled','disabled');
     $('#add-cart'+id).text('Adding To Cart...');
    },

  success: function(data){
     //alert(data);

     showCart();
      // toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart'+id).attr('disabled',false);
                       $('#add-cart'+id).text('Added..');
      }
});

        }
        
        function increaseValue(id){
            var qntno = $('#qntno'+id).val();
            var result = Number(qntno) + 1;
            $('#qntno'+id).val(result);
         }
        function decreaseValue(id){
            var qntno = $('#qntno'+id).val();
            if(qntno == 1){}
             else{    
                 var result = Number(qntno) - 1;
                 $('#qntno'+id).val(result);
                 }
            }
            
         $(document).ready(function() {
             $('.showform').hide();
             showCart();
         });
</script>
</body>
</html>
