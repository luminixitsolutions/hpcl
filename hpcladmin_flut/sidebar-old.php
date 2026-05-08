<style>
div.scrollmenu {
  background-color: #333;
  overflow: auto;
  white-space: nowrap;
}

div.scrollmenu a {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 14px;
  text-decoration: none;
}

div.scrollmenu a:hover {
  background-color: #777;
}


/* Hide submenu by default */
.sidenav-item .sidenav-menu {
    display: none;
    position: absolute;
    background-color: white;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    padding: 10px;
    z-index: 100;
}

/* Show submenu when parent is hovered */
.sidenav-item:hover > .sidenav-menu {
    display: block;
}
</style>

 <div class="sidenav bg-dark">
<div id="layout-sidenav" class=" <!--container--> layout-sidenav-horizontal sidenav-horizontal flex-grow-0 bg-dark" style="padding-left:15px;padding-right:15px;">
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
<div class="sidenav-divider  mt-0"></div>
<ul class="sidenav-inner ">
    
    <?php if($user_id == 2091){?>
<li class="sidenav-item <?php if($MainPage=='Account2') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Kwick Delivery</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item ">
<a href="view-delivery-accounts.php" class="sidenav-link">
<div>Delivery Account</div>
<?php if($Page=='Change-Password') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li>
<?php } ?>

<li class="sidenav-item <?php if($MainPage=='Pretty-Cash-Ho') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Dashboard</div>
</a>
<ul class="sidenav-menu">
    
 <li class="sidenav-item">
            <a href="dashboard.php" class="sidenav-link">
                
                <div>Dashboard </div>
                <?php if($Page=='Dashboard') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
<?php if($Roll==1){?>
 <li class="sidenav-item">
            <a href="expense-sale-dashboard.php" class="sidenav-link">
                
                <div>Sub Zone Expense VS Sale </div>
                <?php if($Page=='') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        
         <li class="sidenav-item">
            <a href="expense-sale-dashboard.php?value=zone" class="sidenav-link">
                
                <div>Zone Expense Vs Sale  </div>
                <?php if($Page=='') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        
         <li class="sidenav-item">
<a href="expense-sale-report.php" class="sidenav-link">
<div> Franchise Expense Vs Sale Report </div>
<?php if($Page=='Weekly-Sale-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>  
 </ul>
</li>  

<?php if($Roll==1 || $user_id == 2648){?>
<li class="sidenav-item <?php if($MainPage=='Pretty-Cash-Ho') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Top Sell Dashboard</div>
</a>
<ul class="sidenav-menu">
    
 <li class="sidenav-item">
            <a href="expense-sale-dashboard.php?value=topsellzone" class="sidenav-link">
                
                <div>Zone Wise </div>
                <?php if($Page=='Dashboard') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>

 <li class="sidenav-item">
            <a href="expense-sale-dashboard.php?value=topsellsubzone" class="sidenav-link">
                
                <div>Sub Zone Wise </div>
                <?php if($Page=='') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li>
        
        
         <li class="sidenav-item">
<a href="franchise-wise-top-sell-dashboard.php" class="sidenav-link">
<div> Franchise Wise </div>
<?php if($Page=='Weekly-Sale-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 </ul>
</li> 
<?php } ?>

<!--<li class="sidenav-item">
<a href="update-franchise-barcode.php" class="sidenav-link">
<div>Update Barcode</div>
<?php if($Page=='') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->

<li class="sidenav-item">
<a href="view-franchises.php" class="sidenav-link">
<div>Franchise</div>
<?php if($Page=='View-Customers') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-employee.php" class="sidenav-link">
     
<div> Employees</div>
<?php if($Page=='View-Employee') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item <?php if($MainPage == 'Selling-Products'){?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <div>Selling Products</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item">
                    <a href="brands.php" class="sidenav-link">
                        <div>Brands</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="customer-category.php" class="sidenav-link">
                        <div>Category</div>
                        <?php if($Page=='Category') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="customer-sub-category.php" class="sidenav-link">
                        <div>Sub Category</div>
                        <?php if($Page=='SubCategory') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="view-customer-products.php" class="sidenav-link">
                        <div>MRP Products</div>
                        <?php if($Page=='Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="view-customer-making-products.php" class="sidenav-link">
                        <div>Making Products</div>
                        <?php if($Page=='Making-Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="view-other-products.php" class="sidenav-link">
                        <div>Other Products</div>
                        <?php if($Page=='Other-Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="view-allocate-products.php" class="sidenav-link">
                        <div> Allocate Products</div>
                        <?php if($Page=='Allocate-Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="view-allocate-products-vendor.php" class="sidenav-link">
                        <div> Allocate Products To Vendor</div>
                        <?php if($Page=='Allocate-Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="common-master.php?pageid=3" class="sidenav-link">
                        <div>Division</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="common-master.php?pageid=4" class="sidenav-link">
                        <div>Segment</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                 <li class="sidenav-item">
                    <a href="common-master.php?pageid=5" class="sidenav-link">
                        <div>Family</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="common-master.php?pageid=6" class="sidenav-link">
                        <div>Class</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="common-master.php?pageid=7" class="sidenav-link">
                        <div>Mc. Desc</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="common-master.php?pageid=8" class="sidenav-link">
                        <div>Brand Desc</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
               
                
            </ul>
        </li>

        <li class="sidenav-item <?php if($MainPage == 'Raw-Products'){?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <div>Raw Products</div>
            </a>
            <ul class="sidenav-menu">
                
                <li class="sidenav-item">
                    <a href="raw-product-category.php" class="sidenav-link">
                        <div>Category</div>
                        <?php if($Page=='Raw-Category') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="raw-product-sub-category.php" class="sidenav-link">
                        <div>Sub Category</div>
                        <?php if($Page=='Raw-Sub-Category') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                <li class="sidenav-item">
                    <a href="view-raw-products.php" class="sidenav-link">
                        <div> Products</div>
                        <?php if($Page=='View-Raw-Product') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>

                <li class="sidenav-item">
                    <a href="view-allocate-raw-products.php" class="sidenav-link">
                        <div> Allocate Products</div>
                        <?php if($Page=='Allocate-Raw-Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="sidenav-item">
                    <a href="view-allocate-raw-products-vendor.php" class="sidenav-link">
                        <div> Allocate Products To Vendor</div>
                        <?php if($Page=='Allocate-Products') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
            </ul>
        </li>





<?php if(in_array("106", $Options) || in_array("107", $Options) || in_array("108", $Options) || in_array("109", $Options) || in_array("110", $Options)) {?>
 <li class="sidenav-item <?php if($MainPage=='Godown') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Godown</div>
</a>
<ul class="sidenav-menu">
    <?php  if(in_array("106", $Options)) {?>
    <li class="sidenav-item">
<a href="view-godown.php" class="sidenav-link">
<div> Godown Account</div>
<?php if($Page=='View-Godown') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } if(in_array("14", $Options)) {?> 
<li class="sidenav-item">
<a href="add-godown-raw-prod-stock.php" class="sidenav-link">
<div> Add Godown Stock</div>
<?php if($Page=='Add-Godown-Stocks') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-godown-raw-prod-stock.php" class="sidenav-link">
<div> View Godown Stock</div>
<?php if($Page=='View-Godown-Stocks') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="upload-godown-stock-by-excel.php" class="sidenav-link">
<div> Upload Godown Stock</div>
<?php if($Page=='View-Godown-Stocks') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php if(in_array("110", $Options)) {?> 
<li class="sidenav-item">
<a href="view-transfer-godwon-raw-stock-coco.php" class="sidenav-link">
<div> Transfer Stock Godown To COCO Franchise</div>
<?php if($Page=='Transfer-Raw-Stock-Godown-To-Franchise-COCO') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-transfer-godwon-raw-stock-fofo.php" class="sidenav-link">
<div> Transfer Stock Godown To FOFO Franchise</div>
<?php if($Page=='Transfer-Raw-Stock-Godown-To-Franchise-FOFO') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<?php } if(in_array("109", $Options)) {?> 
<li class="sidenav-item">
<a href="view-transfer-godwon-raw-stock.php" class="sidenav-link">
<div> Transfer Stock Godown To Other Franchise</div>
<?php if($Page=='Transfer-Raw-Stock-Godown-To-Franchise') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

<li class="sidenav-item">
<a href="view-request-product-stock.php" class="sidenav-link">
<div>Pending Request For Product Stocks </div> 
<?php if($Page=='Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="approve-request-product-stock.php" class="sidenav-link">
<div>Approve Request For Product Stocks </div> 
<?php if($Page=='Approve-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="create-product-stock-invoice.php" class="sidenav-link">
<div>Create Invoice For Product Stocks </div> 
<?php if($Page=='Create-Invoice-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="return-godown-products.php" class="sidenav-link">
<div> Return Godown Product</div>
<?php if($Page=='Return-Godown-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>

<?php } ?>


 <li class="sidenav-item <?php if($MainPage=='Godown') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Franchise Stock Request</div>
</a>
<ul class="sidenav-menu">
   

<li class="sidenav-item">
<a href="view-mrp-product-stock-request.php" class="sidenav-link">
<div>MRP Product Stocks Request </div> 
<?php if($Page=='Approve-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-raw-product-stock-request.php" class="sidenav-link">
<div>Raw Product Stocks Request </div> 
<?php if($Page=='Create-Invoice-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="view-other-product-stock-request.php" class="sidenav-link">
<div>Other Product Stocks Request</div>
<?php if($Page=='Return-Godown-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>
 
    
<li class="sidenav-item <?php if($MainPage=='Target-Complete') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Target Complete</div>
</a>
<ul class="sidenav-menu">
    <li class="sidenav-item">
<a href="view-set-target.php" class="sidenav-link">
<div>Set Target</div>
<?php if($Page=='Set-Target') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="target-completion.php" class="sidenav-link">
<div>Target Completion</div>
<?php if($Page=='Target-Completion') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="target-completion-new.php" class="sidenav-link">
<div>Target Completion New</div>
<?php if($Page=='Target-Completion') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="target-completion-report.php" class="sidenav-link">
<div>Target Completion Report</div>
<?php if($Page=='Target-Completion-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="target-completion-report-date-wise.php" class="sidenav-link">
<div>Target Completion Report Date Wise</div>
<?php if($Page=='Target-Completion-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
</ul>
</li>

<?php if(in_array("64", $Options) || in_array("65", $Options) || in_array("66", $Options) || in_array("67", $Options) || in_array("68", $Options) || in_array("69", $Options) || in_array("70", $Options) || in_array("71", $Options)) {?>
<li class="sidenav-item <?php if($MainPage=='Report') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Reports</div>
</a>
<ul class="sidenav-menu">
 <li class="sidenav-item">
<a href="daily-sale-report.php" class="sidenav-link">
<div> Daily Sale Report</div>
<?php if($Page=='Daily-Sale-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="daily-sale-report-2.php" class="sidenav-link">
<div> Daily Sale Report 2</div>
<?php if($Page=='Daily-Sale-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="weekly-sale-report.php" class="sidenav-link">
<div> Weekly Sale Report</div>
<?php if($Page=='Weekly-Sale-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

 <li class="sidenav-item">
<a href="weekly-sale-report-2.php" class="sidenav-link">
<div> Weekly Sale Report 2</div>
<?php if($Page=='Weekly-Sale-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>




<!-- <li class="sidenav-item">
<a href="godown-stock-report.php" class="sidenav-link">
<div> Godown Stock Report</div>
<?php if($Page=='Godown-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>-->

 <li class="sidenav-item">
<a href="godown-product-stock-report.php" class="sidenav-link">
<div> Godown Product Stock report</div>
<?php if($Page=='Godown-Product-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="transfer-stock-godown-to-franchise-report.php" class="sidenav-link">
<div> Transfer Stock Godown To Franchise Report</div>
<?php if($Page=='Transfer-Stock-Godown-To-Franchise-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="transfer-stock-godown-to-franchise-report-2.php" class="sidenav-link">
<div> Transfer Stock Godown To Franchise Report 2</div>
<?php if($Page=='Transfer-Stock-Godown-To-Franchise-Report-2') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li> 
<?php } ?>


<li class="sidenav-item <?php if($MainPage=='Franchise-Report-2025') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Franchise Reports 2025</div>
</a>
<ul class="sidenav-menu">   
<li class="sidenav-item">
<a href="product-stock-report-2025.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>&Search=Search" class="sidenav-link">
<div> Product Stock Report</div>
<?php if($Page=='Product-Stock-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="stock-report-new-2025.php" class="sidenav-link">
<div> Account Product Stock Report</div>
<?php if($Page=='Product-Stock-Report-2-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<li class="sidenav-item">
<a href="fr-raw-product-stock-report-2025.php" class="sidenav-link">
<div> Raw Product Stock Report</div>
<?php if($Page=='Raw-Product-Stock-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="sell-by-category-report-2025.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div> Category Wise Sell Report</div>
<?php if($Page=='Sell-category-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="sell-by-product-report-2025.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div> Product Wise Sell Report</div>
<?php if($Page=='Sell-Product-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="sell-by-mrp-product-report-2025.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div>MRP Product Wise Sell Report</div>
<?php if($Page=='Sell-Product-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="category-sale-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" class="sidenav-link">
<div> Sell Report</div>
<?php if($Page=='Sell-Product-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="discount-report-2025.php" class="sidenav-link">
<div> Discount Report</div>
<?php if($Page=='Discount-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="cancelled-report.php" class="sidenav-link">
<div> Cancelled Report</div>
<?php if($Page=='Discount-Report-2025') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

</ul>
</li> 

<li class="sidenav-item">
<a href="discount-percentage.php" class="sidenav-link">
<div>Discount Percentage</div>
<?php if($Page=='Discount-Percentage') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item <?php if($MainPage=='Financer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Financer/Partner</div>
</a>
<ul class="sidenav-menu">
    <?php  if(in_array("111", $Options)) {?>
    <li class="sidenav-item">
<a href="view-financer.php" class="sidenav-link">
<div> Financer/Partner Account</div>
<?php if($Page=='View-Financer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
  
<?php if(in_array("112", $Options)) {?> 
<li class="sidenav-item">
<a href="view-commision-note.php" class="sidenav-link">
<div> Commision Note</div>
<?php if($Page=='Commision-Note') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

    </ul>
    </li>

<li class="sidenav-item <?php if($MainPage == 'Selling-Products'){?> open active <?php } ?>">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <div>Request Product Stock</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item">
                    <a href="view-request-sell-product-stock.php" class="sidenav-link">
                        <div>Request Selling Product Stock</div>
                        <?php if($Page=='Brand') {?>
                        <div class="pl-1 ml-auto">
                            <span class="badge badge-dot badge-primary"></span>
                        </div>
                        <?php } ?>
                    </a>
                </li>
               
            </ul>
        </li>
        


<!--<li class="sidenav-item <?php if($MainPage=='Vendor-Payments') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Vendor Payments</div>
</a>
<ul class="sidenav-menu">
<li class="sidenav-item">
<a href="add-vendor-payment.php" class="sidenav-link">
<div> Add Vendor Payments</div>
<?php if($Page=='Add-Vendor-Payment') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-vendor-payment.php" class="sidenav-link"> 
<div> View Vendor Payments</div>
<?php if($Page=='View-Vendor-Payment') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>-->
    
<?php if(in_array("113", $Options) || in_array("114", $Options) || in_array("115", $Options) || in_array("116", $Options)) {?>
 <!--<li class="sidenav-item <?php if($MainPage=='Production') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Production</div>
</a>
<ul class="sidenav-menu">
    <?php  if(in_array("113", $Options)) {?>
    <li class="sidenav-item">
<a href="view-production.php" class="sidenav-link">
<div> Production Account</div>
<?php if($Page=='View-Production') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
    <?php if(in_array("14", $Options)) {?> 
<li class="sidenav-item">
<a href="add-production-product.php" class="sidenav-link">
<div> Add Production Products</div>
<?php if($Page=='Add-Production-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-production-products.php" class="sidenav-link">
<div> View Production Products</div>
<?php if($Page=='View-Production-Products') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
 <?php if(in_array("14", $Options)) {?> 
<li class="sidenav-item">
<a href="add-production-raw-product.php" class="sidenav-link">
<div> Add Production Raw Product</div>
<?php if($Page=='Add-Production-Raw-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
<li class="sidenav-item">
<a href="view-production-raw-product.php" class="sidenav-link">
<div> View Production Raw Product</div>
<?php if($Page=='View-Production-Raw-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php if(in_array("110", $Options)) {?> 
<li class="sidenav-item">
<a href="view-raw-production-stock.php" class="sidenav-link">
<div> Manage Raw Production Stocks</div>
<?php if($Page=='Manage-Raw-Production-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

    </ul>
    </li>-->
<?php } ?>

<?php if(in_array("111", $Options) || in_array("112", $Options)) {?>
 <!--<li class="sidenav-item <?php if($MainPage=='Retailer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Retailer</div>
</a>
<ul class="sidenav-menu">
    <?php  if(in_array("111", $Options)) {?>
    <li class="sidenav-item">
<a href="view-retailer.php" class="sidenav-link">
<div> Retailer Account</div>
<?php if($Page=='View-Retailer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>
  
<?php if(in_array("112", $Options)) {?> 
<li class="sidenav-item">
<a href="view-sell-to-retailer.php" class="sidenav-link">
<div> Sell To Retailer</div>
<?php if($Page=='Sell-To-Retailer') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<?php } ?>

    </ul>
    </li>-->

   
<?php } if(in_array("75", $Options)) {?>







<!--<li class="sidenav-item">
<a href="view-vendors.php" class="sidenav-link">
     
<div> Vendors</div>
<?php if($Page=='View-Vendors') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
-->
 
<?php } ?>


    




<li class="sidenav-item <?php if($MainPage=='Franchise-Report') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Franchise Reports 2024</div>
</a>
<ul class="sidenav-menu">   
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
<a href="fr-raw-product-stock-report.php" class="sidenav-link">
<div> Raw Product Stock Report</div>
<?php if($Page=='Raw-Product-Stock-Report') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

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
</ul>
</li> 



<!--<li class="sidenav-item <?php if($MainPage=='Admin-Req-Prod-Stock') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Admin Approval Request Product Stock</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="admin-pending-ved-request-product-stock.php" class="sidenav-link">
<div> Admin Pending Request</div>
<?php if($Page=='Admin-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="admin-approve-ved-request-product-stock.php" class="sidenav-link">
<div> Admin Approval Request</div>
<?php if($Page=='Admin-Approval-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="admin-reject-ved-request-product-stock.php" class="sidenav-link">
<div> Admin Reject Request</div>
<?php if($Page=='Admin-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>
    
    
    <li class="sidenav-item <?php if($MainPage=='First-Financer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Accountant Approval Request Product Stock</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="accoutant-ved-pending-request-product-stock.php" class="sidenav-link">
<div> Accountant Pending Request</div>
<?php if($Page=='Financer-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="accoutant-ved-approve-request-product-stock.php" class="sidenav-link">
<div> Accountant Approval Request</div>
<?php if($Page=='Financer-Approve-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="accoutant-ved-reject-request-product-stock.php" class="sidenav-link">
<div> Accountant Reject Request</div>
<?php if($Page=='Financer-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>
    
    
    <li class="sidenav-item <?php if($MainPage=='Admin-Req-Prod-Stock') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Admin Approval Vendor Order Request</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="admin-pending-ved-order-request.php" class="sidenav-link">
<div> Admin Pending Request</div>
<?php if($Page=='Admin-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="admin-approve-ved-order-request.php" class="sidenav-link">
<div> Admin Approval Request</div>
<?php if($Page=='Admin-Approval-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="admin-reject-ved-order-request.php" class="sidenav-link">
<div> Admin Reject Request</div>
<?php if($Page=='Admin-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>
    
    
    <li class="sidenav-item <?php if($MainPage=='First-Financer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Accountant Approval Vendor Order Request</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="accountant-pending-ved-order-request.php" class="sidenav-link">
<div> Accountant Pending Request</div>
<?php if($Page=='Financer-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="accountant-approve-ved-order-request.php" class="sidenav-link">
<div> Accountant Approval Request</div>
<?php if($Page=='Financer-Approve-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="accountant-reject-ved-order-request.php" class="sidenav-link">
<div> Accountant Reject Request</div>
<?php if($Page=='Financer-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>-->

<!--<li class="sidenav-item <?php if($MainPage=='First-Financer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">
<div>Accountant 1st Approval Request Product Stock</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="first-financer-pending-request-product-stock.php" class="sidenav-link">
<div> Accountant Pending Request</div>
<?php if($Page=='Financer-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="first-financer-approve-request-product-stock.php" class="sidenav-link">
<div> Accountant Approval Request</div>
<?php if($Page=='Financer-Approve-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="first-financer-reject-request-product-stock.php" class="sidenav-link">
<div> Accountant Reject Request</div>
<?php if($Page=='Financer-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>-->

    <!--<li class="sidenav-item <?php if($MainPage=='Admin-Req-Prod-Stock') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Admin Approval Request Product Stock</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="admin-pending-request-product-stock.php" class="sidenav-link">
<div> Admin Pending Request</div>
<?php if($Page=='Admin-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="admin-approve-request-product-stock.php" class="sidenav-link">
<div> Admin Approval Request</div>
<?php if($Page=='Admin-Approval-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="admin-reject-request-product-stock.php" class="sidenav-link">
<div> Admin Reject Request</div>
<?php if($Page=='Admin-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>

   <li class="sidenav-item <?php if($MainPage=='Final-Financer') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

<div>Accountant Final Approval Request Product Stock</div>
</a>
<ul class="sidenav-menu">
   
    <li class="sidenav-item">
<a href="final-financer-pending-request-product-stock.php" class="sidenav-link">
<div> Accountant Pending Request</div>
<?php if($Page=='Final-Financer-Pending-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
  
<li class="sidenav-item">
<a href="final-financer-approve-request-product-stock.php" class="sidenav-link">
<div> Accountant Approval Request</div>
<?php if($Page=='Final-Financer-Approve-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="final-financer-reject-request-product-stock.php" class="sidenav-link">
<div> Accountant Reject Request</div>
<?php if($Page=='Final-Financer-Reject-Request-Product-Stock') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

    </ul>
    </li>-->

 <li class="sidenav-item <?php if($MainPage=='Account2') {?> open active <?php } ?>">
<a href="javascript:" class="sidenav-link sidenav-toggle">

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
<a href="logout.php" class="sidenav-link">
<div><i class="feather icon-power text-danger"></i> Log Out</div>
</a>
</li>
</ul>
</li> 





</ul>
</div>
</div>
