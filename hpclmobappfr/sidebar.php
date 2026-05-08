<?php 
$UserId = $_SESSION['User']['id'];
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM tbl_users_bill WHERE id='$UserId'";
$row110 = getRecord($sql110);
$Name = $row110['Fname']." ".$row110['Lname'];
$Phone = $row110['Phone'];
$EmailId = $row110['EmailId'];
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = $row110['Member'];
$PkgDate = $row110['PkgDate'];
$Validity = $row110['Validity'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row110['Validity'])));

$CurrDate = date('Y-m-d');
$diff = strtotime($Validity) - strtotime($CurrDate);
$Days = ($diff / 86400);
$RemainDays2 = $Days + 1;
if($RemainDays2 == 1){
    $RemainDays = "Today";
}
else{
$RemainDays = $RemainDays2." days";
}

$sql11x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
                        $res11x = $conn->query($sql11x);
                        $row11x = $res11x->fetch_assoc();
$mybalance = $row11x['credit'] - $row11x['debit'];

$sql12x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when CrDr='cr' then sum(Points) else 0 end) as credit,(case when CrDr='dr' then sum(Points) else 0 end) as debit FROM tbl_points WHERE UserId='$user_id' group by CrDr) as a";
$res12x = $conn->query($sql12x);
$row12x = $res12x->fetch_assoc();
$mybalancepnts = $row12x['credit'] - $row12x['debit'];

function isActive($file){
  return basename($_SERVER['PHP_SELF']) === $file ? 'active' : '';
}
 ?>
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- menu main -->
    <div class="main-menu">
        <div class="row mb-4 no-gutters">
            <div class="col-auto"><button class="btn btn-link btn-40 btn-close "><span class="material-icons">chevron_left</span></button></div>
            <?php if(isset($_SESSION['User'])) {?>
            <div class="col-auto">
                <div class="avatar avatar-40 rounded-circle position-relative">
                    <figure class="background">
                       <?php 
                        if($row110['Photo'] == ''){
                     ?>
                    <img src="<?php echo $SiteUrl;?>/user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                <?php } else  {?>
                     <img src="<?php echo $Uploadurl;?>/uploads/<?php echo $row110['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                
                 <?php } ?>
                    </figure>
                </div>
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Name; ?></h6>
            </div>
        <?php } else{?>
             <div class="col-auto">
                
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Proj_Title; ?></h6>
                <p class="small text-default-secondary"></p>
            </div>
        <?php } ?>
        </div>
        <div class="menu-container">
              
            <ul class="nav nav-pills flex-column ">
                 <li class="nav-item">
        <a class="nav-link <?php echo isActive('home.php'); ?>" href="home.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">home</span></span>
            <span>Home</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('prod-category.php'); ?>" href="prod-category.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">ballot</span></span>
            <span>Category</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('product-lists.php'); ?>" href="product-lists.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">inventory_2</span></span>
            <span>Products</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('sell-by-category.php'); ?>" href="sell-by-category.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">fact_check</span></span>
            <span>Category Wise Sell</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('sell-by-product.php'); ?>" href="sell-by-product.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">sell</span></span>
            <span>Product Wise Sell</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('invoice-record.php'); ?>" href="invoice-record.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">receipt_long</span></span>
            <span>Invoice Records</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-cash-book.php'); ?>" href="view-cash-book.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">local_atm</span></span>
            <span>Cash Book</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-cust-stocks.php'); ?>" href="view-cust-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">inventory</span></span>
            <span>Manage Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-fr-raw-stock.php'); ?>" href="view-fr-raw-stock.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">inventory_2</span></span>
            <span>Manage Raw Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>
      
    
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-available-mrp-stocks.php'); ?>" href="view-available-mrp-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">inventory</span></span>
            <span>Manage Availble MRP Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-available-raw-stocks.php'); ?>" href="view-available-raw-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">inventory</span></span>
            <span>Manage Availble Raw Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>
    

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-wastage-stocks.php'); ?>" href="view-wastage-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">delete_sweep</span></span>
            <span>Manage Wastage Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-wastage-raw-stocks.php'); ?>" href="view-wastage-raw-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">delete_outline</span></span>
            <span>Manage Wastage Raw Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-req-mrp-product-stocks.php'); ?>" href="view-req-mrp-product-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">inventory_2</span></span>
            <span>Request MRP Product Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-req-raw-product-stocks.php'); ?>" href="view-req-raw-product-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">category</span></span>
            <span>Request Raw Product Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-req-godown-product-stocks.php'); ?>" href="view-req-godown-product-stocks.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">warehouse</span></span>
            <span>Request Godown Product Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-transfer-franchise-to-franchise-stock.php'); ?>" href="view-transfer-franchise-to-franchise-stock.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">call_made</span></span>
            <span>Transfer Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-check-transfer-stock.php'); ?>" href="view-check-transfer-stock.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">call_received</span></span>
            <span>Receive Godown Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo isActive('view-check-vendor-order-stock.php'); ?>" href="view-check-vendor-order-stock.php">
          <div class="left">
            <span class="menu-icon-wrap"><span class="material-icons-round menu-icon">local_shipping</span></span>
            <span>Receive Vendor Stock</span>
          </div>
          <span class="material-icons-round arrow">chevron_right</span>
        </a>
      </li>


     
                
               <!-- <li class="nav-item">
                    <a class="nav-link" href="view-req-raw-product-stocks.php">
                        <div>
                            <span class="material-icons icon">inventory</span>
                           Request Raw Product Stock
                        </div>
                      
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="view-req-other-product-stocks.php">
                        <div>
                            <span class="material-icons icon">inventory</span>
                           Request Godown Product Stock
                        </div>
                      
                    </a>
                </li>-->
                

                

                <!--<li class="nav-item">
                    <a class="nav-link" href="view-receive-franchise-stock.php">
                        <div>
                            <span class="material-icons icon">call_received</span>
                           Receive Franchise Stock
                        </div>
                      
                    </a>
                </li>

                -->
             <!--   <li class="nav-item">
                    <a class="nav-link" href="view-customers.php">
                        <div>
                            <span class="material-icons icon">person_book</span>
                            Customers
                        </div>
                      
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="view-employee.php">
                        <div>
                            <span class="material-icons icon">group</span>
                            Employees
                        </div>
                      
                    </a>
                </li>-->
               


          
                
                
                
               
             
            </ul>
            <?php if(isset($_SESSION['User'])){?>
            <div class="text-center">
                <a href="JavaScript:Void(0);" onclick="logout()" class="btn btn-outline-danger  rounded my-3 mx-auto">Sign out</a>
            </div>
             <?php } ?>
        </div>

    </div>
    <div class="backdrop"></div>
    
        
    <script>
        function shareApplication(msg){
            //alert(msg);
             Android.shareApplication(''+msg+'','Maha Connect');
             //Android.shareApplication('test','Daily Door Services');
        }
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>

<div class="container-fluid h-100 loader-display">
        <div class="row h-100">
            <div class="align-self-center col">
                <div class="logo-loading">
                    <div class="icon  ">
                        <img src="loog3.jpg" alt="">
                    </div><br>
                    <div class="loader-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    