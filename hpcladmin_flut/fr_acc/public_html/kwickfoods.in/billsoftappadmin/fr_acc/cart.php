<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage = "Customer-Invoice";
$Page = "Add-Customer-Invoice";
//print_r($_SESSION["cart_item"]);
//echo $_SESSION["cart_item"]['9UuoAoFORY1']['quantity'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
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


<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">


<div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 " style="padding-top:10px;">
                       
                        <div class="row">
                           
                                
                            <div class="col-md-4">
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
                                        <input type="hidden" name="CustId" id="CustId">
                                        <input type="hidden" name="AccCode" id="AccCode">
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
                                                                Save
                                                            </a>
                                       <div style="padding-top:10px;"></div>   
                                    <a  href="add-customer-invoice.php" class="btn btn-danger  mt-md-0 mt-2">
                                                                Continue to Order
                                                            </a>
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
    var CustId = $('#CustId').val();
    var PackageId = $('#PackageId').val();
    var PkgAmt = $('#PkgAmt').val();
    var PkgDiscount = $('#PkgDiscount').val();
    var PkgValidity = $('#PkgValidity').val();
    var PrimeDiscount = $('#PrimeDiscount').val();
    var PkgId = $('#PkgId').val();
    var action = "savePrint";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,CellNo:CellNo,CustName:CustName,PayType:PayType,CustId:CustId,PackageId:PackageId,PkgAmt:PkgAmt,PkgDiscount:PkgDiscount,PkgValidity:PkgValidity,PkgId:PkgId,PrimeDiscount:PrimeDiscount},
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
      //Android.printReceipt(''+data+'');
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
