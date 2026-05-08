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


/*--- Remove Bootstrap's styling for Nav Class if needed ---*/
#ProductNav .nav, #ProductNav2 .nav {
  display: inherit;
  flex-wrap: inherit;
  padding-left: inherit;
  margin-bottom: inherit;
  list-style: inherit;
}

/*--- Wrap Up ---*/
.ProductNav_Wrapper {
  position: relative;
  padding: 0 11px;
  box-sizing: border-box;
  height: 90px;
}

.ProductNav {
    /* Make this scrollable when needed */
    overflow-x: auto;
    /* We don't want vertical scrolling */
    overflow-y: hidden;
     -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
    /* For WebKit implementations, provide inertia scrolling */
    -webkit-overflow-scrolling: touch;
    /* We don't want internal inline elements to wrap */
    white-space: nowrap;
    /* If JS present, let's hide the default scrollbar */
    .js & {
        /* Make an auto-hiding scroller for the 3 people using a IE */
        -ms-overflow-style: -ms-autohiding-scrollbar;
        /* Remove the default scrollbar for WebKit implementations */
        &::-webkit-scrollbar {
            display: none;
        }
    }
  /* positioning context for advancers */
  position: relative;
  // Crush the whitespace here
  font-size: 0;
  
}

.ProductNav_Contents {
  float: left;
  transition: transform .2s ease-in-out;
  position: relative;
  display: none;  /* Safari and Chrome */
}

.ProductNav_Contents-no-transition {
  transition: none;
}

.ProductNav_Link {
  text-decoration: none;
  color: #cccccc;
  // Reset the font Size
  font-size: 11px;
  font-weight:500;
  display: table-cell;
  vertical-align:middle;
  padding: 8px 12px;
  line-height:1.35;
  // & + & {
  //  border-left-color: #eee;
  color: #6a2c79;
    font-size:15px;
    font-weight:bold;
  // }
  &[aria-selected="true"] {
    color: #6a2c79;
    font-size:15px;
    font-weight:bold;
  }
}

.Advancer {
  /* Reset the button */
  appearance: none;
  background: transparent;
  padding: 0;
  border: 0;
  &:focus {
    outline: 0;
  }
  &:hover {
    cursor: pointer;
  }
  /* Now style it as needed */
  position: absolute;
  top: 0;
  bottom: 0;
  /* Set the buttons invisible by default */
  opacity: 0;
  transition: opacity .3s;
}

.Advancer_Left {
  left: 0;
  [data-overflowing="both"] ~ &,
  [data-overflowing="left"] ~ & {
    opacity: 1;
  }
}

.Advancer_Right {
  right: 0;
  [data-overflowing="both"]  ~ &,
  [data-overflowing="right"] ~ & {
    opacity: 1;
  }
}

.Advancer_Icon {
  width: 12px;
  height: 44px;
  fill: #bbb;
}

.ProductNav_Indicator {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 100px;
  background-color: transparent;
  transform-origin: 0 0;
  transition: transform .2s ease-in-out, background-color .2s ease-in-out;
}
div#journey {
    background-image: url(uploads/16_journey14.jpg);
    background-repeat-x: no-repeat;
    background-size: cover;
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
                            <div class="col-md-8">
                                 <div class="">
                        
                        <div class=" ">
  <div class="ProductNav_Wrapper">
  <h4>Short Biographys</h4>
  <nav id="ProductNav2" class="ProductNav dragscroll mouse-scroll" role="tablist">
    <div id="ProductNavContents2" class="nav ProductNav_Contents">
    <?php 
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                     $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."'";
                                    $rncnt2 = getRow($sql2);
                                    if($rncnt2 > 0){
                            ?>
      <a class="ProductNav_Link <?php if($i<2){?>active<?php } ?>" id="home-tab<?php echo $result['id'];?>" data-toggle="tab" href="#home<?php echo $result['id'];?>" role="tab" aria-controls="home<?php echo $result['id'];?>" aria-selected="true"><?php echo $result['Name'];?></a>
      <?php }} ?>
      
     
      
    <span id="Indicator2" class="ProductNav_Indicator"></span>
    </div>
</nav>
  
  <button id="AdvancerLeft2" class="Advancer Advancer_Left" type="button">
    <svg class="Advancer_Icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 551 1024"><path d="M445.44 38.183L-2.53 512l447.97 473.817 85.857-81.173-409.6-433.23v81.172l409.6-433.23L445.44 38.18z"/></svg>
  </button>
  <button id="AdvancerRight2" class="Advancer Advancer_Right" type="button">
    <svg class="Advancer_Icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 551 1024"><path d="M105.56 985.817L553.53 512 105.56 38.183l-85.857 81.173 409.6 433.23v-81.172l-409.6 433.23 85.856 81.174z"/></svg>
  </button>

</div>
  <div class="card">
    <div class="">
      <div class="tab-content" id="myTabContent2">
          <?php 
/*    $i=1;
$sql3 = "SELECT * FROM tbl_biography WHERE Status=1 ORDER BY Year DESC";
$row3 = getList($sql3);
foreach($row3 as $result)*/{
?>
        <div class="tab-pane fade <?php if($i<2){?>show active<?php } ?>" id="home<?php echo $result['id'];?>" role="tabpanel" aria-labelledby="home-tab<?php echo $result['id'];?>">
           <a href="biography-details?id=<?php echo $result['id'];?>">
         <div data-bg-image="uploads/<?php echo $result['Photo'];?>" style="height: 200px; width:100%;"></div>
         <div class="card">
<div data-bg-image="">
<div class="bg-overlay" data-style="9"></div>
<div class="row">
<div class="col">
<div class="col-lg-8 text-light  text-left"  style="padding-left: 20px;padding-right: 20px; padding-top: 10px;padding-bottom: 10px;">
<div><strong style="font-size: 13px; line-height: 15px;"><?php echo $result['Title'];?></strong></div>
<h3 style="font-size: 10px;line-height: 15px;text-align:justify;"><?php echo $result['ShortDetails'];?></h3>
</div>
</div>
</div>
</div>
<div align="center" style="background-color:#203966;padding:15px 0;">
<a class="btn" href="https://mieknathshinde.in/timeline">View More</a>
</div>
</div>

</a> 

        </div>
        <?php $i++;} ?>
      
      </div>
    </div>
  </div>
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

                                        
                                        
                                      
                                      <div style="padding-top:10px;"></div>   
                        <a href="javascript:void(0)" class="btn btn-success  mt-md-0 mt-2" onclick="savePrint()">
                                                                Save and Print
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
function savePrint(){
    var CellNo = $('#CellNo').val();
    var CustName = $('#CustName').val();
    var PayType = $('#PayType').val();
    var action = "savePrint";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,CellNo:CellNo,CustName:CustName,PayType:PayType},
   success: function(data){
      toastr.success('Order Placed Successfully', 'Success', {timeOut: 2000});
      Android.printReceipt();
      window.location.href="orders.php";
        
   }
  });
}
function getUserDetails(){
    var CellNo = $('#CellNo').val();
    var action = "getUserDetails2";
  $.ajax({
  type: "POST",
  url: "ajax_files/ajax_dropdown.php",
  data: {action:action,CellNo:CellNo},
  dataType:"json",  
   success: function(data){
       $('#CustName').val(data.Fname);
    $('#CustId').val(data.id);
    $('#AccCode').val(data.CustomerId); 
   }
  });
}
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
             showCart();
         });
</script>

<script>
  var SETTINGS = {
    navBarTravelling: false,
    navBarTravelDirection: "",
 navBarTravelDistance: 150
}

var colours = {
    0: "#fead00"
/*
Add Numbers And Colors if you want to make each tab's indicator in different color for eg:
1: "#FF0000",
2: "#00FF00", and so on...
*/
}

document.documentElement.classList.remove("no-js");
document.documentElement.classList.add("js");

// Out advancer buttons
var AdvancerLeft = document.getElementById("AdvancerLeft");
var AdvancerRight = document.getElementById("AdvancerRight");

var AdvancerLeft2 = document.getElementById("AdvancerLeft2");
var AdvancerRight2 = document.getElementById("AdvancerRight2");

// the indicator
var Indicator = document.getElementById("Indicator");
var ProductNav = document.getElementById("ProductNav");
var ProductNavContents = document.getElementById("ProductNavContents");

var Indicator2 = document.getElementById("Indicator2");
var ProductNav2 = document.getElementById("ProductNav2");
var ProductNavContents2 = document.getElementById("ProductNavContents2");

ProductNav.setAttribute("data-overflowing", determineOverflow(ProductNavContents, ProductNav));

ProductNav2.setAttribute("data-overflowing", determineOverflow(ProductNavContents2, ProductNav2));

// Set the indicator
moveIndicator(ProductNav.querySelector("[aria-selected=\"true\"]"), colours[0]);

moveIndicator2(ProductNav2.querySelector("[aria-selected=\"true\"]"), colours[0]);

// Handle the scroll of the horizontal container
var last_known_scroll_position = 0;
var ticking = false;

function doSomething(scroll_pos) {
    ProductNav.setAttribute("data-overflowing", determineOverflow(ProductNavContents, ProductNav));
    ProductNav2.setAttribute("data-overflowing", determineOverflow(ProductNavContents2, ProductNav2));
}

ProductNav.addEventListener("scroll", function() {
    last_known_scroll_position = window.scrollY;
    if (!ticking) {
        window.requestAnimationFrame(function() {
            doSomething(last_known_scroll_position);
            ticking = false;
        });
    }
    ticking = true;
});

ProductNav2.addEventListener("scroll", function() {
    last_known_scroll_position = window.scrollY;
    if (!ticking) {
        window.requestAnimationFrame(function() {
            doSomething(last_known_scroll_position);
            ticking = false;
        });
    }
    ticking = true;
});


AdvancerLeft.addEventListener("click", function() {
// If in the middle of a move return
    if (SETTINGS.navBarTravelling === true) {
        return;
    }
    // If we have content overflowing both sides or on the left
    if (determineOverflow(ProductNavContents, ProductNav) === "left" || determineOverflow(ProductNavContents, ProductNav) === "both") {
        // Find how far this panel has been scrolled
        var availableScrollLeft = ProductNav.scrollLeft;
        // If the space available is less than two lots of our desired distance, just move the whole amount
        // otherwise, move by the amount in the settings
        if (availableScrollLeft < SETTINGS.navBarTravelDistance * 2) {
            ProductNavContents.style.transform = "translateX(" + availableScrollLeft + "px)";
        } else {
            ProductNavContents.style.transform = "translateX(" + SETTINGS.navBarTravelDistance + "px)";
        }
        // We do want a transition (this is set in CSS) when moving so remove the class that would prevent that
        ProductNavContents.classList.remove("ProductNav_Contents-no-transition");
        // Update our settings
        SETTINGS.navBarTravelDirection = "left";
        SETTINGS.navBarTravelling = true;
    }
    // Now update the attribute in the DOM
    ProductNav.setAttribute("data-overflowing", determineOverflow(ProductNavContents, ProductNav));
});
AdvancerLeft2.addEventListener("click", function() {
// If in the middle of a move return
    if (SETTINGS.navBarTravelling === true) {
        return;
    }
    // If we have content overflowing both sides or on the left
    if (determineOverflow(ProductNavContents2, ProductNav2) === "left" || determineOverflow(ProductNavContents2, ProductNav2) === "both") {
        // Find how far this panel has been scrolled
        var availableScrollLeft = ProductNav2.scrollLeft;
        // If the space available is less than two lots of our desired distance, just move the whole amount
        // otherwise, move by the amount in the settings
        if (availableScrollLeft < SETTINGS.navBarTravelDistance * 2) {
            ProductNavContents2.style.transform = "translateX(" + availableScrollLeft + "px)";
        } else {
            ProductNavContents2.style.transform = "translateX(" + SETTINGS.navBarTravelDistance + "px)";
        }
        // We do want a transition (this is set in CSS) when moving so remove the class that would prevent that
        ProductNavContents2.classList.remove("ProductNav_Contents-no-transition");
        // Update our settings
        SETTINGS.navBarTravelDirection = "left";
        SETTINGS.navBarTravelling = true;
    }
    // Now update the attribute in the DOM
    ProductNav2.setAttribute("data-overflowing", determineOverflow(ProductNavContents2, ProductNav2));
});

AdvancerRight.addEventListener("click", function() {
    // If in the middle of a move return
    if (SETTINGS.navBarTravelling === true) {
        return;
    }
    // If we have content overflowing both sides or on the right
    if (determineOverflow(ProductNavContents, ProductNav) === "right" || determineOverflow(ProductNavContents, ProductNav) === "both") {
        // Get the right edge of the container and content
        var navBarRightEdge = ProductNavContents.getBoundingClientRect().right;
        var navBarScrollerRightEdge = ProductNav.getBoundingClientRect().right;
        // Now we know how much space we have available to scroll
        var availableScrollRight = Math.floor(navBarRightEdge - navBarScrollerRightEdge);
        // If the space available is less than two lots of our desired distance, just move the whole amount
        // otherwise, move by the amount in the settings
        if (availableScrollRight < SETTINGS.navBarTravelDistance * 2) {
            ProductNavContents.style.transform = "translateX(-" + availableScrollRight + "px)";
        } else {
            ProductNavContents.style.transform = "translateX(-" + SETTINGS.navBarTravelDistance + "px)";
        }
        // We do want a transition (this is set in CSS) when moving so remove the class that would prevent that
        ProductNavContents.classList.remove("ProductNav_Contents-no-transition");
        // Update our settings
        SETTINGS.navBarTravelDirection = "right";
        SETTINGS.navBarTravelling = true;
    }
    // Now update the attribute in the DOM
    ProductNav.setAttribute("data-overflowing", determineOverflow(ProductNavContents, ProductNav));
});
AdvancerRight2.addEventListener("click", function() {
    // If in the middle of a move return
    if (SETTINGS.navBarTravelling === true) {
        return;
    }
    // If we have content overflowing both sides or on the right
    if (determineOverflow(ProductNavContents2, ProductNav2) === "right" || determineOverflow(ProductNavContents2, ProductNav2) === "both") {
        // Get the right edge of the container and content
        var navBarRightEdge = ProductNavContents2.getBoundingClientRect().right;
        var navBarScrollerRightEdge = ProductNav2.getBoundingClientRect().right;
        // Now we know how much space we have available to scroll
        var availableScrollRight = Math.floor(navBarRightEdge - navBarScrollerRightEdge);
        // If the space available is less than two lots of our desired distance, just move the whole amount
        // otherwise, move by the amount in the settings
        if (availableScrollRight < SETTINGS.navBarTravelDistance * 2) {
            ProductNavContents2.style.transform = "translateX(-" + availableScrollRight + "px)";
        } else {
            ProductNavContents2.style.transform = "translateX(-" + SETTINGS.navBarTravelDistance + "px)";
        }
        // We do want a transition (this is set in CSS) when moving so remove the class that would prevent that
        ProductNavContents2.classList.remove("ProductNav_Contents-no-transition");
        // Update our settings
        SETTINGS.navBarTravelDirection = "right";
        SETTINGS.navBarTravelling = true;
    }
    // Now update the attribute in the DOM
    ProductNav2.setAttribute("data-overflowing", determineOverflow(ProductNavContents2, ProductNav2));
});

ProductNavContents.addEventListener(
    "transitionend",
    function() {
        // get the value of the transform, apply that to the current scroll position (so get the scroll pos first) and then remove the transform
        var styleOfTransform = window.getComputedStyle(ProductNavContents, null);
        var tr = styleOfTransform.getPropertyValue("-webkit-transform") || styleOfTransform.getPropertyValue("transform");
        // If there is no transition we want to default to 0 and not null
        var amount = Math.abs(parseInt(tr.split(",")[4]) || 0);
        ProductNavContents.style.transform = "none";
        ProductNavContents.classList.add("ProductNav_Contents-no-transition");
        // Now lets set the scroll position
        if (SETTINGS.navBarTravelDirection === "left") {
            ProductNav.scrollLeft = ProductNav.scrollLeft - amount;
        } else {
            ProductNav.scrollLeft = ProductNav.scrollLeft + amount;
        }
        SETTINGS.navBarTravelling = false;
    },
    false
);
ProductNavContents2.addEventListener(
    "transitionend",
    function() {
        // get the value of the transform, apply that to the current scroll position (so get the scroll pos first) and then remove the transform
        var styleOfTransform = window.getComputedStyle(ProductNavContents2, null);
        var tr = styleOfTransform.getPropertyValue("-webkit-transform") || styleOfTransform.getPropertyValue("transform");
        // If there is no transition we want to default to 0 and not null
        var amount = Math.abs(parseInt(tr.split(",")[4]) || 0);
        ProductNavContents2.style.transform = "none";
        ProductNavContents2.classList.add("ProductNav_Contents-no-transition");
        // Now lets set the scroll position
        if (SETTINGS.navBarTravelDirection === "left") {
            ProductNav2.scrollLeft = ProductNav2.scrollLeft - amount;
        } else {
            ProductNav2.scrollLeft = ProductNav2.scrollLeft + amount;
        }
        SETTINGS.navBarTravelling = false;
    },
    false
);

// Handle setting the currently active link
ProductNavContents.addEventListener("click", function(e) {
var links = [].slice.call(document.querySelectorAll("#ProductNavContents .ProductNav_Link"));
links.forEach(function(item) {
item.setAttribute("aria-selected", "false");
})
e.target.setAttribute("aria-selected", "true");
// Pass the clicked item and it's colour to the move indicator function
moveIndicator(e.target, colours[links.indexOf(e.target)]);
});
ProductNavContents2.addEventListener("click", function(e) {
var links = [].slice.call(document.querySelectorAll("#ProductNavContents2 .ProductNav_Link"));
links.forEach(function(item) {
item.setAttribute("aria-selected", "false");
})
e.target.setAttribute("aria-selected", "true");
// Pass the clicked item and it's colour to the move indicator function
moveIndicator2(e.target, colours[links.indexOf(e.target)]);
});

// var count = 0;
function moveIndicator(item, color) {
    var textPosition = item.getBoundingClientRect();
    var container = ProductNavContents.getBoundingClientRect().left;
    var distance = textPosition.left - container;
 var scroll = ProductNavContents.scrollLeft;
    Indicator.style.transform = "translateX(" + (distance + scroll) + "px) scaleX(" + textPosition.width * 0.01 + ")";
// count = count += 100;
// Indicator.style.transform = "translateX(" + count + "px)";

    if (color) {
        Indicator.style.backgroundColor = color;
    }
}

// var count = 0;
function moveIndicator2(item, color) {
    var textPosition = item.getBoundingClientRect();
    var container = ProductNavContents2.getBoundingClientRect().left;
    var distance = textPosition.left - container;
 var scroll = ProductNavContents2.scrollLeft;
    Indicator2.style.transform = "translateX(" + (distance + scroll) + "px) scaleX(" + textPosition.width * 0.01 + ")";
// count = count += 100;
// Indicator.style.transform = "translateX(" + count + "px)";

    if (color) {
        Indicator2.style.backgroundColor = color;
    }
}

function determineOverflow(content, container) {
    var containerMetrics = container.getBoundingClientRect();
    var containerMetricsRight = Math.floor(containerMetrics.right);
    var containerMetricsLeft = Math.floor(containerMetrics.left);
    var contentMetrics = content.getBoundingClientRect();
    var contentMetricsRight = Math.floor(contentMetrics.right);
    var contentMetricsLeft = Math.floor(contentMetrics.left);
 if (containerMetricsLeft > contentMetricsLeft && containerMetricsRight < contentMetricsRight) {
        return "both";
    } else if (contentMetricsLeft < containerMetricsLeft) {
        return "left";
    } else if (contentMetricsRight > containerMetricsRight) {
        return "right";
    } else {
        return "none";
    }
}

/*------------------- ACTIVE LINK POSITION ------------------------*/
$("#ProductNav .ProductNav_Link").click(function() {
   
   centerLI(this, '#ProductNav');

 });

$("#ProductNav2 .ProductNav_Link").click(function() {
   
   centerLI(this, '#ProductNav2');

 });

 //http://stackoverflow.com/a/33296765/350421
 function centerLI(target, outer) {
   var out = $(outer);
   var tar = $(target);
   var x = out.width() - 50;
   var y = tar.outerWidth(true);
   var z = tar.index();
   var q = 0;
   var m = out.find('.ProductNav_Link');
   for (var i = 0; i < z; i++) {
     q += $(m[i]).outerWidth(true);
   }
   
 //out.scrollLeft(Math.max(0, q - (x - y)/2));
 var xy = x - y;
 if(q > xy){
out.animate({
  scrollLeft: Math.max(0, q - (x - y) + 100)
}, 500);
 } else {
 out.animate({
  scrollLeft: Math.max(0, q/2 - 50)
}, 500);    
 }

 }


$(document).ready(function() {
$('.mouse-scroll').mousewheel(function(e, delta) {
this.scrollLeft -= (delta * 50);
e.preventDefault();
});
});
</script>
</body>
</html>
