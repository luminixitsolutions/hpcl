<style>
/* Sidebar Styling */
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

/* Submenu dropdown */
.sidenav-item .sidenav-menu {
  display: none;
  position: absolute;
  background-color: white;
  box-shadow: 0px 4px 6px rgba(0,0,0,0.1);
  min-width: 220px;
  padding: 10px;
  z-index: 100;
}
.sidenav-item:hover > .sidenav-menu {
  display: block;
}
</style>
 <script>
  const APP_PARAMS = {
    user_id: "<?php echo htmlspecialchars($displayUserId); ?>",
    lat: "<?php echo htmlspecialchars($lat); ?>",
    lng: "<?php echo htmlspecialchars($long); ?>"
  };

  function goPage(url) {
    const params = new URLSearchParams(APP_PARAMS).toString();
    window.location.href = url.includes('?')
      ? url + '&' + params
      : url + '?' + params;
  }
</script>
<div class="sidenav bg-dark">
  <div id="layout-sidenav" class="layout-sidenav-horizontal sidenav-horizontal flex-grow-0 bg-dark" style="padding:0 15px;">
    
    <!-- Logo -->
    <div class="app-brand demo">
      <a href="javascript:" onclick="goPage('dashboard.php')" class="app-brand-text demo sidenav-text font-weight-normal ml-2">
        <img src="logo.jpg" alt="<?php echo $Proj_Title; ?>" class="img-fluid" style="height:60px;">
      </a>
      <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
        <i class="ion ion-md-menu align-middle" style="color:#000;"></i>
      </a>
    </div>

    <div class="sidenav-divider mt-0"></div>

    <ul class="sidenav-inner">

  <!-- ==================== 1. MAIN DASHBOARD ==================== -->
  <li class="sidenav-item <?php if($MainPage=='Main-Dashboard') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Main Dashboard</div></a>
    <ul class="sidenav-menu">
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('dashboard.php')"><div>Dashboard</div></a>
      </li>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('dashboard-new.php')"><div>Dashboard V2</div></a>
      </li>
    </ul>
  </li>

  <!-- ==================== 2. TOP SELL DASHBOARD ==================== -->
  <?php if(array_intersect(["4","5","6","130"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Top-Sell-Dashboard') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Top Sell Dashboard</div></a>
    <ul class="sidenav-menu">
      <?php if(in_array("4", $Options)) { ?>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('expense-sale-dashboard.php?value=topsellzone')"><div>Zone Wise</div></a>
      </li>
      <?php } ?>

      <?php if(in_array("5", $Options)) { ?>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('expense-sale-dashboard.php?value=topsellsubzone')"><div>Region Wise</div></a>
      </li>
      <?php } ?>

      <?php if(in_array("130", $Options)) { ?>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('franchise-wise-top-sell-dashboard-new.php')"><div>Dealer Wise</div></a>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>

  <!-- ==================== 3. MASTERS ==================== -->
  <?php if(array_intersect(["1","2","3","143"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Masters') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Masters</div></a>
    <ul class="sidenav-menu">
      <?php if(in_array("1", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('country.php')"><div>Country</div></a></li>
      <?php } ?>

      <?php if(in_array("2", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('state.php?value=zone')"><div>State</div></a></li>
      <?php } ?>

      <?php if(in_array("3", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('city.php')"><div>City</div></a></li>
      <?php } ?>

      <?php if(in_array("143", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('pincode.php')"><div>PinCode</div></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>

  <!-- ==================== 4. UPLOAD BY EXCEL ==================== -->
  <?php if(array_intersect(["147","148","149","150","151","152","153","154","155","156","160"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Upload-Excel') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Upload By Excel</div></a>
    <ul class="sidenav-menu">

      <?php if(in_array("147", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('country-excel.php')"><div>Country</div></a></li><?php } ?>

      <?php if(in_array("148", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('state-excel.php')"><div>State</div></a></li><?php } ?>

      <?php if(in_array("149", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('city-excel.php')"><div>City</div></a></li><?php } ?>

      <?php if(in_array("150", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('pincode-excel.php')"><div>PinCode</div></a></li><?php } ?>

      <?php if(in_array("151", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('zone-excel.php')"><div>Zone</div></a></li><?php } ?>

      <?php if(in_array("152", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('region-excel.php')"><div>Region</div></a></li><?php } ?>

      <?php if(in_array("153", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('dealer-excel.php')"><div>Dealer</div></a></li><?php } ?>

      <?php if(in_array("155", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('vendor-excel.php')"><div>Vendors</div></a></li><?php } ?>

      <?php if(in_array("156", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('distributer-excel.php')"><div>Distributors</div></a></li><?php } ?>

      <?php if(in_array("160", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('mrp-product-excel.php')"><div>MRP Products</div></a></li><?php } ?>

    </ul>
  </li>
  <?php } ?>
  <!-- ==================== 5. USER ACCOUNTS ==================== -->
  <?php if(array_intersect(["8","133","134","135"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='User-Accounts') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>User Accounts</div></a>
    <ul class="sidenav-menu">

      <?php if(in_array("135", $Options)) { ?>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('user-type.php')"><div>Designation</div></a>
      </li>
      <?php } ?>

      <?php if(in_array("133", $Options)) { ?>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('view-vendors.php')"><div>Vendors</div></a>
      </li>
      <?php } ?>

      <?php if(in_array("134", $Options)) { ?>
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('view-distributers.php')"><div>Distributors</div></a>
      </li>
      <?php } ?>

    </ul>
  </li>
  <?php } ?>


  <!-- ==================== 6. DEALER/NFB ==================== -->
  <?php if(array_intersect(["7","131","132","157","158"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Franchise') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Dealer / NFB</div></a>
    <ul class="sidenav-menu">

      <?php if(in_array("131", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('zones.php')"><div>Zone</div></a></li>
      <?php } ?>

      <?php if(in_array("132", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('sub-zones.php')"><div>Region</div></a></li>
      <?php } ?>

      <?php if(in_array("157", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('franchise-type.php')"><div>Dealer Type</div></a></li>
      <?php } ?>

      <?php if(in_array("158", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-assign-franchise-to-zone.php')"><div>Assign Dealer To Zone</div></a></li>
      <?php } ?>

      <?php if(in_array("7", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-franchises.php')"><div>Dealers</div></a></li>
      <?php } ?>

    </ul>
  </li>
  <?php } ?>


  <!-- ==================== 7. SELLING PRODUCTS ==================== -->
  <?php if(array_intersect(["12","13","15","136","137","138","139"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Selling-Products') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Selling Products</div></a>
    <ul class="sidenav-menu">

      <?php if(in_array("136", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('customer-category.php')"><div>Category</div></a></li>
      <?php } ?>

      <?php if(in_array("137", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('customer-sub-category.php')"><div>Sub Category</div></a></li>
      <?php } ?>

      <?php if(in_array("12", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-customer-products.php')"><div>MRP Products</div></a></li>
      <?php } ?>

      <?php if(in_array("13", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-customer-making-products.php')"><div>Making Products</div></a></li>
      <?php } ?>

      <?php if(in_array("138", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-product-group.php')"><div>Product Groups</div></a></li>
      <?php } ?>

      <?php if(in_array("139", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-allocate-products-distributer.php')"><div>Allocate To Distributor</div></a></li>
      <?php } ?>

      <?php if(in_array("15", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-allocate-products.php')"><div>Allocate To Dealer</div></a></li>
      <?php } ?>

    </ul>
  </li>
  <?php } ?>


  <!-- ==================== 8. RAW PRODUCTS ==================== -->
  <?php if(array_intersect(["23","24","125","126","159"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Raw-Products') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Raw Products</div></a>
    <ul class="sidenav-menu">

      <?php if(in_array("23", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('raw-product-category.php')"><div>Category</div></a></li>
      <?php } ?>

      <?php if(in_array("24", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('raw-product-sub-category.php')"><div>Sub Category</div></a></li>
      <?php } ?>

      <?php if(in_array("159", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-raw-products.php')"><div>Products</div></a></li>
      <?php } ?>

      <?php if(in_array("125", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-allocate-new-raw-products.php')"><div>Allocate To Dealer</div></a></li>
      <?php } ?>

      <?php if(in_array("126", $Options)) { ?>
      <li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-allocate-raw-products-distributer.php')"><div>Allocate To Distributor</div></a></li>
      <?php } ?>

    </ul>
  </li>
  <?php } ?>
  <!-- ==================== 9. TARGET COMPLETE ==================== -->
  <?php if(array_intersect(["39","40","41","42","43"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Target') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Target Complete</div></a>
    <ul class="sidenav-menu">
      <?php if(in_array("39", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('view-set-target.php')"><div>Set Target</div></a></li><?php } ?>
      <?php if(in_array("40", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('target-completion.php')"><div>Report V1</div></a></li><?php } ?>
      <?php if(in_array("41", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('target-completion-new.php')"><div>Report V2</div></a></li><?php } ?>
      <?php if(in_array("42", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('target-completion-report.php')"><div>Report V3</div></a></li><?php } ?>
      <?php if(in_array("43", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('target-completion-report-date-wise.php')"><div>Report V4</div></a></li><?php } ?>
    </ul>
  </li>
  <?php } ?>


  <!-- ==================== DEALER STOCK REPORTS ==================== -->
  <?php if(array_intersect(["140","141","142"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Dealer-Stock-Reports') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Dealer Stock Request Reports</div></a>
    <ul class="sidenav-menu">
      <?php if(in_array("140", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('dealer-product-request-report.php')"><div>Dealer Product Request</div></a></li><?php } ?>
      <?php if(in_array("141", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('distributer-receive-product-report.php')"><div>Distributor Receive Product Request</div></a></li><?php } ?>
      <?php if(in_array("142", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('product-stock-report-2025.php')"><div>Dealer Product Stock</div></a></li><?php } ?>
    </ul>
  </li>
  <?php } ?>


  <!-- ==================== SELL REPORTS ==================== -->
  <?php if(array_intersect(["44","45","46","47","67","57","59","54","55","56","123"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Sell-Reports') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Sell Reports</div></a>
    <ul class="sidenav-menu">

      <?php if(in_array("44", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('daily-sale-report.php')"><div>Daily Sale Report V1</div></a></li><?php } ?>

      <?php if(in_array("45", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('daily-sale-report-2.php')"><div>Daily Sale Report V2</div></a></li><?php } ?>

      <?php if(in_array("46", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('weekly-sale-report.php')"><div>Weekly Sale Report V1</div></a></li><?php } ?>

      <?php if(in_array("47", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('weekly-sale-report-2.php')"><div>Weekly Sale Report V2</div></a></li><?php } ?>

      <?php if(in_array("123", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('date-wise-sale-report.php')"><div>Date Wise Sale Report</div></a></li><?php } ?>

      <?php if(in_array("54", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('sell-by-category-report-2025.php')"><div>Category Wise Sell Report</div></a></li><?php } ?>

      <?php if(in_array("55", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('sell-by-product-report-2025.php')"><div>Product Wise Sell Report</div></a></li><?php } ?>

      <?php if(in_array("56", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('sell-by-mrp-product-report-2025.php')"><div>MRP Product Wise Sell Report</div></a></li><?php } ?>

      <?php if(in_array("67", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('discount-report-2025.php')"><div>Discount Report</div></a></li><?php } ?>

      <?php if(in_array("59", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('cancelled-report.php')"><div>Cancelled Report</div></a></li><?php } ?>

    </ul>
  </li>
  <?php } ?>


  <!-- ==================== STOCK REPORTS ==================== -->
  <?php if(array_intersect(["51","52","53"], $Options)) { ?>
  <li class="sidenav-item <?php if($MainPage=='Stock-Reports') echo 'open active'; ?>">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Stock Reports</div></a>
    <ul class="sidenav-menu">
      <?php if(in_array("51", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('product-stock-report-2025.php')"><div>Product Stock Report</div></a></li><?php } ?>
      <?php if(in_array("52", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('stock-report-new-2025.php')"><div>Account Product Stock Report</div></a></li><?php } ?>
      <?php if(in_array("53", $Options)) { ?><li class="sidenav-item"><a href="javascript:" class="sidenav-link" onclick="goPage('fr-raw-product-stock-report-2025.php')"><div>Raw Product Stock Report</div></a></li><?php } ?>
    </ul>
  </li>
  <?php } ?>


  <!-- ==================== 11. ACCOUNT SETTINGS ==================== -->
  <li class="sidenav-item">
    <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Account Settings</div></a>
    <ul class="sidenav-menu">
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('change-password.php')">
          <div><i class="feather icon-unlock text-muted"></i> Change Password</div>
        </a>
      </li>

      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link" onclick="goPage('logout.php')">
          <div><i class="feather icon-power text-danger"></i> Log Out</div>
        </a>
      </li>
    </ul>
  </li>

</ul>


  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll("a[href]").forEach(a => {
    const url = a.getAttribute("href");

    // Skip anchors with javascript: or #
    if (!url || url.startsWith("#") || url.startsWith("javascript:")) return;

    a.addEventListener("click", function(e){
      e.preventDefault();
      window.location.href = url;
    });

    a.setAttribute("data-href", url);
    a.removeAttribute("href");
    a.style.cursor = "pointer";
  });
});
</script>
