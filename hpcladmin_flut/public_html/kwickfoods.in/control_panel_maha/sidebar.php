<?php 
	$user_id = $_SESSION['fr_admin'];
    $sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
	$row77 = getRecord($sql77);
    $Roll = $row77['Roll'];
    //$BillSoftFrId = $row77['BillSoftFrId'];
	$Options = explode(',',$row77['Options']);
    if($Roll == 5){
        $BillSoftFrId = $_SESSION['fr_admin'];
    }
    else{
        $BillSoftFrId = $row77['BillSoftFrId'];
    }

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
  border-top-color: #f26921;
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

<div class="layout-wrapper layout-2">
<div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
<div class="app-brand demo">
<span class="app-brand-logo demo">
<a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2">	
<img src="logo.jpg" alt="<?php echo $Proj_Title; ?>" class="img-fluid" style="height: 60px;">
</a>
</span>
<a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2">	
</a>
<a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
<i class="ion ion-md-menu align-middle" style="color: #000;"></i>
</a>
</div>
<div class="sidenav-divider mt-0"></div>
<ul class="sidenav-inner py-1">
<li class="sidenav-item">
<a href="dashboard.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>
<div>Dashboard</div>
<?php if($Page=='Dashboard') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php  if(in_array("79", $Options)) {?>
<li class="sidenav-item">
<a href="invoice-print-setting.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>
<div>Print Setting</div>
<?php if($Page=='Print-Setting') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a> 
</li>
<?php } if(in_array("84", $Options)) {?>
<!--<li class="sidenav-item">
<a href="javascript:void(0)" onclick="goToOffline()" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Create/Tab Order</div>
<?php if($Page=='View-Customer-Invoice2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->
<?php } ?> 

<?php if(in_array("92", $Options)) {?>
<li class="sidenav-item">
<a href="view-cash-book.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Cash Book</div>
<?php if($Page=='View-Cash-Book') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } ?> 
<li class="sidenav-item <?php if($MainPage=='Orders') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Orders</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("85", $Options)) {?>
    <li class="sidenav-item">
<a href="view-today-orders.php" class="sidenav-link">
<div> Today Orders</div>
<?php if($Page=='View-Today-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-today-barcode-orders.php" class="sidenav-link">
<div> Today Barcode Orders</div>
<?php if($Page=='View-Today-Barcode-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-today-online-orders.php" class="sidenav-link">
<div> Today Online Orders</div>
<?php if($Page=='View-Today-Online-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("86", $Options)) {?> 
<li class="sidenav-item">
<a href="view-customer-invoices.php?Search=Search" class="sidenav-link">
<div> All Orders</div>
<?php if($Page=='View-Customer-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("96", $Options)) {?> 
<li class="sidenav-item">
<a href="view-pending-orders.php?Search=Search" class="sidenav-link">
<div> Today Pending Orders</div>
<?php if($Page=='View-Today-Pending-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
    </ul>
</li> 

<li class="sidenav-item <?php if($MainPage=='Report') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Reports</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("69", $Options)) {?> 
<li class="sidenav-item">
<a href="product-stock-report.php" class="sidenav-link">
<div> Product Stock Report</div>
<?php if($Page=='Product-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="stock-report-new.php" class="sidenav-link">
<div> Product Stock Report 2</div>
<?php if($Page=='Product-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("99", $Options)) {?> 
<li class="sidenav-item">
<a href="fr-raw-product-stock-report.php" class="sidenav-link">
<div> Raw Product Stock Report</div>
<?php if($Page=='Raw-Product-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("71", $Options)) {?> 
<!--<li class="sidenav-item">
<a href="expense-report.php" class="sidenav-link">
<div> Expense Report</div>
<?php if($Page=='Expense-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->
<?php } if(in_array("93", $Options)) {?> 
<li class="sidenav-item">
<a href="sell-by-category-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div> Category Wise Sell Report</div>
<?php if($Page=='Sell-category-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("94", $Options)) {?> 
<li class="sidenav-item">
<a href="sell-by-product-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div> Product Wise Sell Report</div>
<?php if($Page=='Sell-Product-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="discount-report.php" class="sidenav-link">
<div> Discount Report</div>
<?php if($Page=='Discount-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
</ul>
</li> 

<?php if(in_array("97", $Options)) {?>
<!--<li class="sidenav-item">
<a href="view-pending-orders.php?Search=Search" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Pending Orders</div>
<?php if($Page=='View-Pending-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } if(in_array("77", $Options)) {?>
<!--<li class="sidenav-item">
<a href="borrowing-orders.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Credit Orders </div> 
<?php if($Page=='Borrowing-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->

<?php } ?>

<li class="sidenav-item <?php if($MainPage=='Customer-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Customer Products</div>
</a>
<ul class="sidenav-menu">
    <?php if(in_array("56", $Options)) {?>    
<li class="sidenav-item">
<a href="customer-category.php" class="sidenav-link">
<div>Product Category </div> 
<?php if($Page=='Category') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>   

<li class="sidenav-item">
<a href="customer-sub-category.php" class="sidenav-link">
<div>Product Sub Category </div> 
<?php if($Page=='Sub-Category') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>  
<?php } if(in_array("57", $Options)) {?>    
<li class="sidenav-item">
<a href="view-customer-products.php" class="sidenav-link">
<div> Customer Products</div>
<?php if($Page=='View-Customer-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="download-customer-product-excel.php" class="sidenav-link">
<div> Download Customer Products Excel</div>
<?php if($Page=='Download-Customer-Products-Excel') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("78", $Options)) {?>    
<li class="sidenav-item">
<a href="view-cust-stocks.php" class="sidenav-link">
<div>Manage Stocks </div> 
<?php if($Page=='View-Cust-Stocks') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="add-cust-stock-by-barcode.php" class="sidenav-link">
<div>Add Stocks By Barcode </div> 
<?php if($Page=='Add-Cust-Stocks-By-Barcode') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

    </ul>
    </li>


<?php if(in_array("50", $Options) || in_array("98", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Raw-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Raw Products</div>
</a>
<ul class="sidenav-menu">

    <li class="sidenav-item">
<a href="raw-product-category.php" class="sidenav-link">
<div> Raw Product Category</div>
<?php if($Page=='Raw-Product-Catgeory') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="raw-product-sub-category.php" class="sidenav-link">
<div> Raw Product Sub Catgeory</div>
<?php if($Page=='Raw-Product-Sub-Catgeory') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="download-raw-product-sub-category.php" class="sidenav-link">
<div> Download Sub Catgeory Excel</div>
<?php if($Page=='Download-Raw-Product-Sub-Catgeory') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    <?php if(in_array("14", $Options)) {?> 
<li class="sidenav-item">
<a href="add-raw-products.php" class="sidenav-link">
<div> Add Raw Products</div>
<?php if($Page=='Add-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-raw-products.php" class="sidenav-link">
<div> View Raw Products</div>
<?php if($Page=='View-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="download-raw-products.php" class="sidenav-link">
<div> Download Raw Product Excel</div>
<?php if($Page=='Download-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <?php if(in_array("98", $Options)) {?> 
<li class="sidenav-item">
<a href="view-fr-raw-stock.php" class="sidenav-link">
<div>Manage Raw Stocks </div> 
<?php if($Page=='Manage-Raw-Stocks') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
    </ul>
    </li>
    <?php } ?>
    
    <li class="sidenav-item <?php if($MainPage=='Return-Products') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Return Products</div>
</a>
<ul class="sidenav-menu">

    <li class="sidenav-item">
<a href="return-mrp-products.php" class="sidenav-link">
<div> Return MRP Product</div>
<?php if($Page=='Return-MRP-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="return-raw-products.php" class="sidenav-link">
<div> Return Raw Product</div>
<?php if($Page=='Return-Raw-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>
    
<li class="sidenav-item">
<a href="view-check-transfer-stock.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Receive Go-Down Stocks </div> 
<?php if($Page=='Check-And-Receive-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<li class="sidenav-item">
<a href="view-transfer-franchise-to-franchise-stock.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>    
<div> Transfer Stock Coco To Coco</div>
<?php if($Page=='Transfer-Stock-Franchise-To-Franchise') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-receive-franchise-stock.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Receive Coco Franchise Stocks </div> 
<?php if($Page=='Receive-Franchise-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-request-product-stock.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Request For Product Stocks </div> 
<?php if($Page=='Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php if(in_array("49", $Options)) {?>  
<li class="sidenav-item">
<a href="view-employee.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Employees</div>
<?php if($Page=='View-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } ?>


<?php if(in_array("48", $Options)) {?>
<!--<li class="sidenav-item">
<a href="account-head.php" class="sidenav-link">
     <i class="sidenav-icon feather icon-check-circle"></i> 
<div>Expenses Account Head</div>
<?php if($Page=='Account-Head') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->
<?php } if(in_array("60", $Options)) {?>
<!--<li class="sidenav-item">
<a href="view-expenses.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Expenses</div>
<?php if($Page=='View-Expenses') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->

<?php } ?>




<?php  if(in_array("82", $Options)) {?>
<li class="sidenav-item">
<a href="table-shop-qr-code.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>
<div>Order QR Code</div>
<?php if($Page=='Order-QR-Code') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

<!--<li class="sidenav-item">
<a href="test-print.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>
<div>Test Print</div>
<?php if($Page=='Order-QR-Code') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->

 <li class="sidenav-item <?php if($MainPage=='Account2') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Account Settings</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item ">
<a href="change-password.php" class="sidenav-link">
<div><i class="feather icon-unlock text-muted"></i> Change Password</div>
<?php if($Page=='Change-Password') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="logout.php"  class="sidenav-link">
<div><i class="feather icon-power text-danger"></i> Log Out</div>
</a>
</li>
</ul>
</li> 

</ul>
</div>

<script>
    function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
  
   function goToOffline(){
      Android.goToOfflinePage();
   }
</script>