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
</style>
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">




<div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                       
                        <div class="row">
                            <div class="col-md-8">
                                 <div class="">
                        
                        <ul id="gallery-filter" class="nav nav-tabs tabs-alt mb-4">
                            <li class="nav-item">
                                <a class="nav-link active" href="#all">All</a>
                            </li>
                            <?php 
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#cat<?php echo $result['id'];?>"><?php echo $result['Name'];?></a>
                            </li>
                            <?php } ?>
                            
                        </ul>
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
                            <div class="gallery-sizer col-sm-6 col-md-6 col-xl-3 position-absolute"></div>
                            
                            <?php 
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                    $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' ORDER BY SrNo asc";
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
                            <div class="gallery-thumbnail col-sm-6 col-md-6 col-xl-3 mb-2" data-tag="cat<?php echo $result['id'];?>">
                                <a href="javascript:void(0)" onclick="addCart(<?php echo $result2['id'];?>)">
                                   <!-- <span class="img-thumbnail-overlay bg-dark opacity-25"></span>
                                    <span class="img-thumbnail-content display-4 text-white">
                                        <i class="ion ion-ios-search"></i>
                                    </span>-->
                                    <?php if($result2['Photo']==''){?>
                                     <img src="no_image.jpg" class="img-fluid" alt="images" style="width:163px;110px;">
                                    <?php } else { ?>
                                    <img src="../uploads/<?php echo $result2['Photo'];?>" class="img-fluid" alt="images" style="width:163px;110px;">
                                    <?php } ?>
                                </a>
                                <div align="center">
                                <strong style="text-align:center;font-size: 13px;"><?php echo $result2['ProductName'];?></strong><br>
                                <h4>&#8377; <?php echo number_format($result2['MinPrice'],2); ?></h4>
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

                            <?php } } ?>
                           
                        </div>

                    </div>
                    <!-- [ content ] End -->            
                                           
                                       
                                    </div>
                                
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
                                            </table>
                                            
                                            
                                        </div>
                                    </div>
                                    <a href="add-customer-invoice.php" class="btn btn-danger  mt-md-0 mt-2">
                                                                Continue to Shopping
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
    $(document).ready(function() {
    $('#example').DataTable( {
      "lengthMenu": [[5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"]]
    } );

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
       toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
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
             showCart();
         });
</script>
</body>
</html>
