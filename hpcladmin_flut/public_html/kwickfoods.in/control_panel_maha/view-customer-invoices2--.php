<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];

$MainPage = "Customer-Invoice";
$Page = "View-Customer-Invoice";
/*print_r($_SESSION["cart_item"]);
echo "<pre>";*/
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php 
	$user_id = $_SESSION['Admin']['id'];
    $sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
	$row77 = getRecord($sql77);
    $Roll = $row77['Roll'];
    //$BillSoftFrId = $row77['BillSoftFrId'];
	$Options = explode(',',$row77['Options']);
	
	$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}
 $Roll = $row['Roll'];
if($Roll == 5){
    $BillSoftFrId = $_SESSION['Admin']['id'];
}
else{
    $BillSoftFrId = $row77['BillSoftFrId'];
}

 ?>


<div class="layout-container">

<?php //include_once 'top_header.php'; ?>

<?php
if($_GET["action"]=="delete")
{
  $id = $_GET["id"];
  $sql11 = "DELETE FROM tbl_customer_invoice WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_customer_invoice_details WHERE InvId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND Flag='Customer-Invoice'";
  $conn->query($sql11);
  $sql = "DELETE FROM tbl_product_stocks WHERE InvId='$id'";
  $conn->query($sql);
  $sql = "DELETE FROM tbl_cust_prod_stock WHERE InvId='$id'";
  $conn->query($sql);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-customer-invoices.php";
    </script>
<?php } 


if($_GET["action"]=="changestatus")
{
  $id = $_GET["id"];
  $status = $_GET['status'];
  if($status == 0){
      $sql = "UPDATE tbl_customer_invoice SET Status=1 WHERE id='$id'";
      $conn->query($sql);
  }
  else{
      $sql = "UPDATE tbl_customer_invoice SET Status=0 WHERE id='$id'";
      $conn->query($sql);
  }
  ?>
    <script type="text/javascript">
      alert("Status Updated Successfully!");
      window.location.href="view-customer-invoices.php";
    </script>
<?php }
?>

<style>
    .loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.4);
 /* background-image: url("04de2e31234507.564a1d23645bf.gif");
  background-repeat: no-repeat;
  background-position: center center; */
  transition: opacity 0.75s, visibility 0.75s;
  z-index:9999;
}

.loader--hidden {
  opacity: 0;
  visibility: hidden;
}

.loader::after {
  content: "";
  width: 75px;
  height: 75px;
  border: 5px solid #dddddd;
  border-top-color: #405189;
  border-radius: 50%;
  animation: loading 0.75s ease infinite;
}

@keyframes loading {
  from {
    transform: rotate(0turn);
  }
  to {
    transform: rotate(1turn);
  }
}



</style>

<script>
    window.addEventListener("load", () => {
  const loader = document.querySelector(".loader");

  loader.classList.add("loader--hidden");

  loader.addEventListener("transitionend", () => {
    document.body.removeChild(loader);
  });
});

</script>

<div class="loader"></div>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y" style="padding-right: 5px;padding-left: 5px;padding-top: 1px !important;">


<input type="hidden" id="LoginUserId" value="<?php echo $_GET['userid'];?>">
<div class="card">
    <div class="row">
                            <div class="col-md">
                                <div class="card mb-3">
                                    <div class="card-header" style="padding: 11px 11px;">
                                        <ul class="nav nav-tabs card-header-tabs nav-responsive-md">
                                             <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#navs-wc-order">Create Order</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#navs-wc-home">Today Orders</a>
                                            </li>
                                            <?php 
$sql = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND InvoiceDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId'";
$row = getRecord($sql);
?>
                                            <li class="nav-item">
                                                <a class="nav-link" style="font-weight: bolder;" id="totalincome">Total Income : &#8377;<?php echo $row['NetAmount'];?></a>
                                            </li>
                                            
                                           
                                           <li class="nav-item">
                                                <a class="nav-link" href="view-customer-invoices2.php?userid=<?php  echo $user_id;?>" style="font-weight: bolder;padding-left: 100px;"><i class="feather icon-refresh-cw"></i></a>
                                            </li>
                                            
                                              <li class="nav-item">
                                                <a class="nav-link" href="dashboard.php" style="font-weight: bolder;"><i class="sidenav-icon feather icon-home"></i></a>
                                            </li>
                                            
                                            <!--<li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="javascript:void(0)" onclick="connectWiredPrinterAgain()">Connect Wired Printer</a>
                                            </li>-->
                                            
                                           
                                           
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-wc-order">
                                            <div class="card-body">
                                                <?php include 'inc-create-orders.php';?>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-wc-home">
                                            <div class="card-body" style="width: 100%;">
                                                <?php include 'inc-today-orders.php';?>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
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

  <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/blueimp-gallery/gallery.js"></script>
    <script src="assets/libs/blueimp-gallery/gallery-fullscreen.js"></script>
    <script src="assets/libs/blueimp-gallery/gallery-indicator.js"></script>
    <script src="assets/libs/masonry/masonry.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
    <script src="assets/js/pages/pages_gallery.js"></script>
<!--<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>-->
<script type="text/javascript">
function connectWiredPrinterAgain(){
    Android.connectWiredPrinterAgain();
}
function totalIncome(){
  var action = 'totalIncome';
  //var PayType = $('#PayType').val();
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_orders.php",
   data:{action:action},  
  success: function(data){
      $('#totalincome').html('TOTAL INCOME : &#8377;'+data);
  }
  });
    }
  function todayOrderLists(){
  var action = 'todayOrders';
  //var PayType = $('#PayType').val();
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_orders.php",
   data:{action:action},  
  success: function(data){
      $('#custresult2').html(data);
      totalIncome();
  }
  });
    }
function checkLogin(){
    var LoginUserId = $('#LoginUserId').val();
    var action = "checkLogin";
    $.ajax({
   url:"ajax_files/ajax_login.php",
   method:"POST",
   data:{action:action,LoginUserId:LoginUserId},
   success:function(data)
   {
       console.log(data);
   
    
   }
  });
}
function searchProduct(){
     var action = "searchProduct";
     var search = $('#SearchProdName').val();
    
     if(search != '')
  {
      var query = search;
  }
  else{
      var query = "";
  }
    $.ajax({
   url:"ajax_files/ajax_customer_products.php",
   method:"POST",
   data:{action:action,query:query},
   success:function(data)
   {
       console.log(data);
    $('#gallery-thumbnails').html(data);
    
   }
  });
}
function getProdDetails(){
    var action = "getProdDetails";
    var BarcodeNo = $('#BarcodeNo').val();
$.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,BarcodeNo:BarcodeNo},
   dataType:"json",  
   success: function(data){
       console.log(data);
        if(data == 0){
           $('#ProdName').val('');
           $('#Price').val('');
        $('#Qty').val(1);  
        $('#ProdId').val('');
           $('#ProdCode').val('');
        }
        else{
           $('#ProdName').val(data.ProductName);
           $('#Price').val(data.MinPrice);
           $('#ProdId').val(data.id);
           $('#ProdCode').val(data.code);
        }
   }
   });

}

function addCart3(){
var action = "shop_cart";
var code = $('#ProdCode').val();
var quantity = $('#Qty').val();
var pid = $('#ProdId').val();
var price = $('#Price').val();
$.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:pid,quantity:quantity,code:code,price:price},
   beforeSend:function(){
     $('#addtocart').attr('disabled','disabled');
     $('#addtocart').text('Adding To Cart...');
    },

  success: function(data){
     //alert(data);
    
     showCart();
      // toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#addtocart').attr('disabled',false);
                       $('#addtocart').text('Added..');
        $('#modals-default').modal('hide');
        $('#qntno'+code).val(quantity);
      
  }
});

        }
function addCart2(id,totqty){
var action = "shop_cart";
var code = $('#code'+id).val();
//var quantity = $('#qntno'+code).val();
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
        
function changePlus(id,code){
            var qntno = $('#qntno'+code).val();
            var result = Number(qntno) + 1;
            $('#qntno'+code).val(result);
       addCart2(id,result);
            
         }
        function changeMinus(id,code){
            var qntno = $('#qntno'+code).val();

                
                 var result = Number(qntno) - 1;
                 if(result == 0){
                     delete_prod2(code);
                 }
                 else{
                 $('#qntno'+code).val(result);
                  addCart2(id,result);
                 }
           
            }
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
        
        function showRecentOrders(){
            
            var action = "showRecentOrders";
           $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action},
   success: function(data){
       console.log(data);
        $('#showrecentorders').html(data);
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
      if(data > 0){
        $('#cnt').html(data);
        $('#saveprint').attr("disabled",false);
        $('#saveprint2').attr("disabled",false);
      }
      else{
          $('#saveprint').attr("disabled",true);
        $('#saveprint2').attr("disabled",true);
      }
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
    
    function showProducts(){
    var action = "searchProduct";
    var query = "";
    $.ajax({
   url:"ajax_files/ajax_customer_products.php",
   method:"POST",
   data:{action:action,query:query},
   success:function(data)
   {
       console.log(data);
    $('#gallery-thumbnails').html(data);
    
   }
  });
}
function savePrint(){
    var CellNo = $('#CellNo').val();
    var CustName = $('#CustName').val();
    var AccCode = $('#AccCode').val();
    var PayType = $('#PayType').val();
    var CustId = $('#CustId').val();
    var PackageId = $('#PackageId').val();
    var PkgAmt = $('#PkgAmt').val();
    var PkgDiscount = $('#PkgDiscount').val();
    var PkgValidity = $('#PkgValidity').val();
    var PrimeDiscount = $('#PrimeDiscount').val();
    var DiscPer = $('#DiscPer').val();
    var Discount = $('#Discount').val();
    var PkgId = $('#PkgId').val();
    var Featured = $('#Featured').val();
    var Print = $('#Print').val();
    var action = "savePrint";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,CellNo:CellNo,CustName:CustName,PayType:PayType,CustId:CustId,PackageId:PackageId,PkgAmt:PkgAmt,PkgDiscount:PkgDiscount,PkgValidity:PkgValidity,PkgId:PkgId,PrimeDiscount:PrimeDiscount,Featured:Featured,Print:Print,AccCode:AccCode,DiscPer:DiscPer,Discount:Discount},
  beforeSend:function(){
     $('#saveprint').attr('disabled','disabled');
     $('#saveprint').text('Please Wait...');
    },
   success: function(data){
       console.log(data); 
       console.log(Print);
      toastr.success('Order Placed Successfully', 'Success', {timeOut: 2000});
      showProducts();
      $('#saveprint').attr('disabled',false);
     $('#saveprint').text('Save');
      $('#CustName').val('');
      $('#CellNo').val('');
    $('#CustId').val(0);
    $('#AccCode').val(''); 
    $('#PayType').val('Cash').attr("selected",true); 
    $('#DiscPer').val(0).attr("selected",true); 
    $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
     $('#PkgAmt').val('');
                        $('#PkgDiscount').val('');
                        $('#PkgValidity').val('');
                      $('#PrimeDiscount').val(0);
                      $('#PackageId').val(0);
                        $('#showpkgamt').html('');
                        $('.qntno').val(0);
     showCart();
     todayOrderLists();
     showRecentOrders();
     $('.showform').hide();
     if(Print == 1){
      Android.printReceipt(''+data+'');
     }
      //window.location.href="orders.php";
        
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
      setInterval(function(){  
           checkLogin();
        }, 1000);
        
        showPendingOrders();
        showRecentOrders();
        todayOrderLists();
    $('#example').DataTable( {
      "lengthMenu": [[5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"]]
    } );


$('#add_button').click(function(){  
           $('#BarcodeNo').focus();
           $('#BarcodeNo').val('');
           $('#ProdName').val('');
           $('#Price').val('');
        $('#Qty').val(1);
          $('#ProdId').val('');
           $('#ProdCode').val('');
   
          
      }) 
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
    var DiscPer = $('#DiscPer').val();
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action},
  success: function(data){
        $('#showcart').html(data);
        total();
        countCart();
        showPendingOrders();
        calDiscount(DiscPer);
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

function calDiscount(val){
    var action = "showdiscount";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,per:val},
  success: function(data){
        $('#showdiscount').html(data);
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
if(data == 0){
        setTimeout(function () { 
swal({
  title: "Login Session Expired!!!",
  text: "Before Adding Item into Cart Please do Login First...",
  type: "error",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="index.php";
  }
}); });
    }
    else{
        $('#saveprint').attr("disabled",false);
        $('#saveprint2').attr("disabled",false);
     showCart();
      // toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart'+id).attr('disabled',false);
                       $('#add-cart'+id).text('Added..');
      }
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
<script>
    function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
  function printReceipt(invdata){
    console.log(invdata);
   // alert(invdata);
     Android.printReceipt(''+invdata+'');
}
function sendSms(id){
         var action = "sendSms";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,id:id},
  success: function(data){
         success_toast();
      }
});
    }
</script>
</body>
</html>
