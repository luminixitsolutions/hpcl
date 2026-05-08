<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];

$MainPage = "Customer-Invoice";
$Page = "View-Customer-Invoice";
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

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



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

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Customer Invoice List <?php echo $uid = $_GET['uid'];?>
<?php if(in_array("14", $Options)) {?>
  <span style="float: right;">
<a href="orders.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add More</a></span><?php } ?>
</h4>
<br>

<div class="card">
    <div class="row">
                            <div class="col-md">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs nav-responsive-md">
                                             <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#navs-wc-order">Create Order</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#navs-wc-home">Today Orders</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#navs-wc-profile">All Orders</a>
                                            </li>
                                           
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
                                        <div class="tab-pane fade" id="navs-wc-profile">
                                            <div class="card-body" style="width: 100%;">
                                                <?php include 'inc-all-orders.php';?>
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
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script type="text/javascript">
function allOrdersLists(){
  var action = 'allOrders';
  var PayType = $('#PayType2').val();
  var ReportType = $('#ReportType').val();
  var FromDate = $('#FromDate').val();
  var ToDate = $('#ToDate').val();
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_orders.php",
   data:{action:action,PayType:PayType,ReportType:ReportType,FromDate:FromDate,ToDate:ToDate},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
    function todayOrderLists(){
  var action = 'todayOrders';
  var PayType = $('#PayType').val();
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_orders.php",
   data:{action:action,PayType:PayType},  
  success: function(data){
      $('#custresult2').html(data);
  }
  });
    }
function showdate(val){
    if(val == 'Custom'){
        $('.customfmdt').show();
        $('.customtodt').show();
    }
    else{
        $('.customfmdt').hide();
        $('.customtodt').hide();
        $('#FromDate').val('');
        $('ToDate').val('');
    }
}
    function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'SMS Sent...',
      location: isRtl ? 'tl' : 'tr'
    });
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
function printReceipt(invdata){
    console.log(invdata);
   // alert(invdata);
     Android.printReceipt(''+invdata+'');
}
 function approve(status,id){
     //alert(status);alert(id);
     window.location.href="view-invoices.php?status="+status+"&id="+id+"&action=changestatus";
 }
	$(document).ready(function() {
	    todayOrderLists();
	    allOrdersLists();
    $('#example').DataTable({
        order: [[1, 'desc']],
      "scrollX": true
    });
});
</script>

<script type="text/javascript">
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
    var AccCode = $('#AccCode').val();
    var PayType = $('#PayType').val();
    var CustId = $('#CustId').val();
    var PackageId = $('#PackageId').val();
    var PkgAmt = $('#PkgAmt').val();
    var PkgDiscount = $('#PkgDiscount').val();
    var PkgValidity = $('#PkgValidity').val();
    var PrimeDiscount = $('#PrimeDiscount').val();
    var PkgId = $('#PkgId').val();
    var Featured = $('#Featured').val();
    var Print = $('#Print').val();
    var action = "savePrint";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,CellNo:CellNo,CustName:CustName,PayType:PayType,CustId:CustId,PackageId:PackageId,PkgAmt:PkgAmt,PkgDiscount:PkgDiscount,PkgValidity:PkgValidity,PkgId:PkgId,PrimeDiscount:PrimeDiscount,Featured:Featured,Print:Print,AccCode:AccCode},
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
     showCart();
     $('.showform').hide();
      todayOrderLists();
	    allOrdersLists();
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
        showPendingOrders();
   

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
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action},
  success: function(data){
        $('#showcart').html(data);
        total();
        countCart();
        showPendingOrders();
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
