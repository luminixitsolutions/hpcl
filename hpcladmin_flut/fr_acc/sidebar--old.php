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
<!--
<div class="loader"></div>-->

 <div class="layout-wrapper layout-1 layout-without-sidenav">
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

<?php } if(in_array("59", $Options)) {?>
<?php if(in_array("14", $Options)) {?>  
<!--<li class="sidenav-item">
<a href="orders2.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Customer Invoice SMS </div>
<?php if($Page=='Add-Customer-Invoice-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->
<!--<li class="sidenav-item">
<a href="orders.php?userid=<?php echo $user_id;?>" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Create Order</div>
<?php if($Page=='Add-Customer-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->

<li class="sidenav-item">
<a href="view-customer-invoices2.php?userid=<?php echo $user_id;?>" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Create/Tab Order</div>
<?php if($Page=='View-Customer-Invoice2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<!--<li class="sidenav-item">
<a href="view-customer-invoices2.php?userid=<?php echo $user_id;?>" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> New Order</div>
<?php if($Page=='View-Customer-Invoice2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->
<?php } ?>
<li class="sidenav-item">
<a href="view-today-orders.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Today Orders</div>
<?php if($Page=='View-Today-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-customer-invoices.php?Search=Search" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> All Orders</div>
<?php if($Page=='View-Customer-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-pending-orders.php?Search=Search" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Today Pending Orders</div>
<?php if($Page=='View-Today-Pending-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-pending-orders.php?Search=Search" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i>
<div> Pending Orders</div>
<?php if($Page=='View-Pending-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("74", $Options)) {?>
<li class="sidenav-item">
<a href="preparing-order.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>
<div>Preparing Orders</div>
<?php if($Page=='Preparing-Order') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("77", $Options)) {?>    
<li class="sidenav-item">
<a href="borrowing-orders.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Credit Orders </div> 
<?php if($Page=='Borrowing-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>   

<?php } if(in_array("56", $Options)) {?>    
<li class="sidenav-item">
<a href="customer-category.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Product Category </div> 
<?php if($Page=='Category') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>    
<?php } if(in_array("57", $Options)) { 
if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="add-customer-product.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div> Add Customer Product</div>
<?php if($Page=='Add-Customer-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-customer-products.php" class="sidenav-link">
 <i class="sidenav-icon feather icon-check-circle"></i>   
<div> Customer Products</div>
<?php if($Page=='View-Customer-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("78", $Options)) {?>    
<li class="sidenav-item">
<a href="view-cust-stocks.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>    
<div>Manage Stocks </div> 
<?php if($Page=='View-Cust-Stocks') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 

<?php } if(in_array("72", $Options)) {?>
<li class="sidenav-item">
<a href="packages.php" class="sidenav-link">
     <i class="sidenav-icon feather icon-check-circle"></i> 
<div>Membership Packages</div>
<?php if($Page=='Packages') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("75", $Options)) {?>
<li class="sidenav-item">
<a href="view-franchises.php" class="sidenav-link">
<i class="sidenav-icon feather icon-check-circle"></i>
<div>Franchise</div>
<?php if($Page=='View-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php }  if(in_array("49", $Options)) {?>
   <?php if(in_array("14", $Options)) {?>  
<!-- <li class="sidenav-item">
<a href="add-employee.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Add Employee</div>
<?php if($Page=='Add-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
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


<?php } if(in_array("73", $Options)) {?>
  <?php if(in_array("14", $Options)) {?>  
<!-- <li class="sidenav-item">
<a href="add-kitchen-account.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Create Kitchen Account</div>
<?php if($Page=='Add-Kitchen-Account') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-kitchen-accounts.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Kitchen Account</div>
<?php if($Page=='View-Kitchen-Account') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<?php } if(in_array("50", $Options)) {?>
<?php if(in_array("14", $Options)) {?> 
<!-- <li class="sidenav-item">
<a href="add-raw-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Add Raw Products</div>
<?php if($Page=='Add-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-raw-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Raw Products</div>
<?php if($Page=='View-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("51", $Options)) {?>
 <?php if(in_array("14", $Options)) {?>    
<!-- <li class="sidenav-item">
<a href="add-vendor.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Add Vendor</div>
<?php if($Page=='Add-Vendors') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-vendors.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> View Vendors</div>
<?php if($Page=='View-Vendors') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("52", $Options)) {?>
   <?php if(in_array("14", $Options)) {?>      
<!-- <li class="sidenav-item">
<a href="add-invoice.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Create Vendor Invoice</div>
<?php if($Page=='Add-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-invoices.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> View Vendor Invoice</div>
<?php if($Page=='View-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("53", $Options)) {?>
 <?php if(in_array("14", $Options)) {?>        
<li class="sidenav-item">
<a href="use-raw-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Use Raw Products</div>
<?php if($Page=='Add-Use-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } ?>
<li class="sidenav-item">
<a href="view-uses-raw-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Uses Raw Products</div>
<?php if($Page=='View-Use-Raw-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("54", $Options) || in_array("55", $Options)) {?>
<?php if(in_array("14", $Options)) {?>    
<!-- <li class="sidenav-item">
<a href="add-shop-product.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Add Franchise Product</div>
<?php if($Page=='Add-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-shop-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Franchise Products</div>
<?php if($Page=='View-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php if(in_array("55", $Options)) {?>
<li class="sidenav-item">
<a href="view-stocks.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Franchise Products Stock</div>
<?php if($Page=='View-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

<?php } if(in_array("58", $Options)) {?>
<?php if(in_array("14", $Options)) {?>    
<!-- <li class="sidenav-item">
<a href="add-franchise-invoice.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Add Franchise Invoice</div>
<?php if($Page=='Add-Franchise-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="view-franchise-invoices.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Franchise Invoice</div>
<?php if($Page=='View-Franchise-Invoice') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<?php } if(in_array("60", $Options)) {?>
<?php if(in_array("14", $Options)) {?>    
<!-- <li class="sidenav-item">
<a href="add-expenses.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Add Expenses</div>
<?php if($Page=='Add-Expenses') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->

<?php } if(in_array("48", $Options)) {?>
<li class="sidenav-item">
<a href="account-head.php" class="sidenav-link">
     <i class="sidenav-icon feather icon-check-circle"></i> 
<div>Expenses Account Head</div>
<?php if($Page=='Account-Head') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-expenses.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Expenses</div>
<?php if($Page=='View-Expenses') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<?php } if(in_array("80", $Options)) {?>
<li class="sidenav-item">
<a href="vendor-expenses.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Pending Vendor Expenses</div>
<?php if($Page=='Vendor-Expenses') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("81", $Options)) {?>
<li class="sidenav-item">
<a href="approve-vendor-expenses.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Approve Vendor Expenses</div>
<?php if($Page=='Approve-Vendor-Expenses') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("61", $Options) || in_array("62", $Options) || in_array("63", $Options)) {?>
<?php if(in_array("61", $Options)) {
if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="add-receive-amount.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Vendor Payment</div> 
<?php if($Page=='Add-Receive-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="receive-amount.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Vendor Payments</div>
<?php if($Page=='Receive-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 

<?php } if(in_array("62", $Options)) {
if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="add-franchise-amount.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Franchise Payment</div> 
<?php if($Page=='Add-Franchise-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<li class="sidenav-item">
<a href="franchise-amount.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Franchise Payment</div>
<?php if($Page=='Franchise-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 
<?php } if(in_array("63", $Options)) {
if(in_array("14", $Options)) {?>
<!-- <li class="sidenav-item">
<a href="add-customer-amount.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Customer Payment</div> 
<?php if($Page=='Add-Customer-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>
<!--<li class="sidenav-item">
<a href="customer-amount.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-check-circle"></i> 
<div> Customer Payment</div>
<?php if($Page=='Customer-Amount') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } ?>

<?php } ?>
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

<?php  if(in_array("64", $Options) || in_array("65", $Options) || in_array("66", $Options) || in_array("67", $Options) || in_array("68", $Options) || in_array("69", $Options) || in_array("70", $Options) || in_array("71", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Report') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Reports</div>
</a>
<ul class="sidenav-menu">
<?php if(in_array("64", $Options)) {?>    
<li class="sidenav-item">
<a href="invoice-report.php" class="sidenav-link">
<div> Vendor Invoice Report</div>
<?php if($Page=='Invoice-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("65", $Options)) {?> 
<li class="sidenav-item">
<a href="franchise-invoice-report.php" class="sidenav-link">
<div> Franchise Invoice Report</div>
<?php if($Page=='Franchise-Invoice-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("66", $Options)) {?> 
<li class="sidenav-item">
<a href="customer-invoice-report.php" class="sidenav-link">
<div> Customer Invoice Report</div>
<?php if($Page=='Customer-Invoice-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("67", $Options)) {?> 
<li class="sidenav-item">
<a href="raw-product-stock-report.php" class="sidenav-link">
<div> Raw Product Stock Report</div>
<?php if($Page=='Raw-Product-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("68", $Options)) {?> 
<li class="sidenav-item">
<a href="company-stock-report.php" class="sidenav-link">
<div> Company Stock Report</div>
<?php if($Page=='Company-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("69", $Options)) {?> 
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
<?php } if(in_array("70", $Options)) {?> 
<li class="sidenav-item">
<a href="franchise-stock-report.php" class="sidenav-link">
<div> Franchise Stock Report</div>
<?php if($Page=='Franchise-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<!-- <li class="sidenav-item">
<a href="gst-report.php" class="sidenav-link">
<div> GST Report</div>
<?php if($Page=='GST-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> -->
<?php } if(in_array("71", $Options)) {?> 
<li class="sidenav-item">
<a href="expense-report.php" class="sidenav-link">
<div> Expense Report</div>
<?php if($Page=='Expense-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("71", $Options)) {?> 
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
<?php } if(in_array("71", $Options)) {?> 
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
<?php } ?>
</li> 

</ul>

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
 <li class="sidenav-item <?php if($MainPage=='Account2') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<i class="sidenav-icon feather icon-lock"></i>
<div>Account Settings</div>
</a>
<ul class="sidenav-menu">
<!-- <li class="sidenav-item <?php if($Page=='Company') {?> active <?php } ?>">
<a href="company-information.php" class="sidenav-link">
<div><i class="feather icon-user text-muted"></i> Profile</div>
</a>
</li>-->	
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
<a href="javascript:void(0)" onclick="logout()" class="sidenav-link">
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
</script>