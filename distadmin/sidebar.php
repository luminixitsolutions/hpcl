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

<div class="sidenav bg-dark">
  <div id="layout-sidenav" class="layout-sidenav-horizontal sidenav-horizontal flex-grow-0 bg-dark" style="padding:0 15px;">
    
    <!-- Logo -->
    <div class="app-brand demo">
      <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2">
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
         <!-- <li class="sidenav-item"><a href="dashboard.php" class="sidenav-link"><div>Dashboard</div></a></li>-->
          <li class="sidenav-item"><a href="dashboard-new.php" class="sidenav-link"><div>Dashboard</div></a></li>

        </ul>
      </li>

     
     <li class="sidenav-item <?php if($MainPage=='Main-Dashboard') echo 'open active'; ?>">
        <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Orders</div></a>
        <ul class="sidenav-menu">
      
          <li class="sidenav-item"><a href="dealer-product-request-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>&Search=Search" class="sidenav-link"><div>Today Requests</div></a></li>
          <li class="sidenav-item"><a href="pending-dealer-req-orders.php?Search=Search" class="sidenav-link"><div>Pending Orders</div></a></li>
          <li class="sidenav-item"><a href="partial-dealer-req-orders.php?Search=Search" class="sidenav-link"><div>Partial Delievered Orders</div></a></li>
          <li class="sidenav-item"><a href="delivered-dealer-req-orders.php?Search=Search" class="sidenav-link"><div>Delievered Orders</div></a></li>
         
        </ul>
      </li>
    

      <!-- ==================== 11. ACCOUNT SETTINGS ==================== -->
      <li class="sidenav-item">
        <a href="javascript:" class="sidenav-link sidenav-toggle"><div>Account Settings</div></a>
        <ul class="sidenav-menu">
          <li class="sidenav-item"><a href="change-password.php" class="sidenav-link"><div><i class="feather icon-unlock text-muted"></i> Change Password</div></a></li>
          <li class="sidenav-item"><a href="logout.php" class="sidenav-link"><div><i class="feather icon-power text-danger"></i> Log Out</div></a></li>
        </ul>
      </li>

    </ul>
  </div>
</div>
