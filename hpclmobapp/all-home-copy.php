<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";
$url = "all-home.php";
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}
//echo $sql11;
$Roll = $row['Roll'];
if($Roll == 5){
    $BillSoftFrId = $row['id'];
}
else if($Roll != 5){
    $BillSoftFrId = $row['BillSoftFrId'];
}
else{
    $BillSoftFrId = 0;
}

if($Roll == 5){
    echo "<script>window.location.href='../billsoftmobappfr/home.php?frid=$BillSoftFrId';</script>";exit();
}
else if($BillSoftFrId!=0){
    echo "<script>window.location.href='../billsoftmobappfr/home.php?frid=$BillSoftFrId';</script>";exit();
}
if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Maha Buddy</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
 
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">

 <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
   
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    <!-- screen loader -->
   

    <!-- menu main -->
    <?php include_once 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       <?php include 'top_header.php';?>

<?php if($_GET['ownfr']==''){
    $ownfr = 1;
}
else{
     $ownfr = $_GET['ownfr'];
}
?>
     

        <!-- page content start -->

        <div class="main-container" style="padding-top: 80px;">

            <div class="container">
               
<div class="swiper-container categories2tab1 text-center mb-4">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <?php if($ownfr == 1){?>
                            <a href="all-home.php?ownfr=1&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">COCO</a>
                            <?php } else {?>
                            <a href="all-home.php?ownfr=1&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">COCO</a>
                            <?php } ?>
                        </div>
                        <div class="swiper-slide">
                            <?php if($ownfr == 2){?>
                            <a href="all-home.php?ownfr=2&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">FOFO</a>
                            <?php } else {?>
                            <a href="all-home.php?ownfr=2&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">FOFO</a>
                            <?php } ?>
                        </div>
                        <div class="swiper-slide">
                           <?php if($ownfr == 3){?>
                            <a href="all-home.php?ownfr=3&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">FOCO</a>
                            <?php } else {?>
                            <a href="all-home.php?ownfr=3&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">FOCO</a>
                            <?php } ?>
                        </div>
                        
                        <div class="swiper-slide">
                           <?php if($ownfr == 4){?>
                            <a href="all-home.php?ownfr=4&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">COFO</a>
                            <?php } else {?>
                            <a href="all-home.php?ownfr=4&zoneid=<?php echo $_REQUEST['zoneid'];?>&subzoneid=<?php echo $_REQUEST['subzoneid'];?>&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">COFO</a>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination white-pagination text-left mb-3"></div>
                </div>
     
<?php 

function countval($val,$frid,$Calendar){
        global $conn; 
   $sql = "SELECT SUM(result) As result FROM (";
    if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    
   if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        
         $sql.=" UNION ALL ";
        if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    
   if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        $sql.=") as a";
        //echo $sql;
     $res2 = $conn->query($sql);
    $row2 = $res2->fetch_assoc();
    return $row2['result'];
    }
    
$Calendar = $_REQUEST['calendar'];
 $sql = "SELECT SUM(NetAmount) AS NetAmount,SUM(TotInv) AS TotInv,FrId,ShopName,Address FROM (SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,tc.FrId,tu.ShopName,tu.Address FROM tbl_customer_invoice tc INNER JOIN tbl_users_bill tu ON tu.id=tc.FrId WHERE tu.Roll='5' AND tu.OwnFranchise='".$ownfr."'";
if($Roll != 1){
    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
    $sql.=" AND tu.id IN ($CocoFranchiseAccess)";
    }
}

if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND tc.InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tc.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tc.InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND tc.InvoiceDate='".date('Y-m-d')."'"; 
        }
        
$sql.=" GROUP BY tc.FrId ";
 $sql.= " UNION ALL SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,tc.FrId,tu.ShopName,tu.Address FROM tbl_customer_invoice_2025 tc INNER JOIN tbl_users_bill tu ON tu.id=tc.FrId WHERE tu.Roll='5' AND tu.OwnFranchise='".$ownfr."'";
if($Roll != 1){
    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
    $sql.=" AND tu.id IN ($CocoFranchiseAccess)";
    }
}

if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND tc.InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tc.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tc.InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND tc.InvoiceDate='".date('Y-m-d')."'"; 
        }
        
$sql.=" GROUP BY tc.FrId ";

$sql.=" ORDER BY NetAmount DESC) as a GROUP BY FrId";
//echo $sql;
$row = getList($sql);
 foreach($row as $result){
     
    $NetAmount2 = $result['NetAmount'];
   if($NetAmount2 > 0){
       $NetAmount = $result['NetAmount'];
   }
   else{
       $NetAmount = 0;
   }
   
   $rncnt224+=$result['TotInv'];
    $TotNetAmount+=$NetAmount;
   $TotCashAmount+=countval('cash_payment',$result['FrId'],$Calendar);
   $TotUpiAmount+=countval('upi_payment',$result['FrId'],$Calendar);
    if($NetAmount > 0){
        
        $sql = "SELECT MAX(LastTime) AS LastTime FROM (SELECT Max(modified_time) AS LastTime FROM `tbl_customer_invoice` WHERE FrId='".$result['FrId']."' UNION ALL SELECT Max(modified_time) AS LastTime FROM `tbl_customer_invoice_2025` WHERE FrId='".$result['FrId']."') as a";
$row = getRecord($sql);
$LastTime = $row['LastTime'];
$date = $LastTime;
$currtime = gmdate("Y-m-d H:i:s");
// Create two DateTime objects
$dateTime1 = new DateTime($currtime); // Current date and time
$dateTime2 = new DateTime($date); // 1 hour 45 minutes ago

// Calculate the difference between the two dates
$interval = $dateTime1->diff($dateTime2);

// Format the difference
$hours = $interval->h;
$minutes = $interval->i;

if ($hours > 0 && $minutes > 0) {
    $timeDifference = "$hours hour" . ($hours > 1 ? 's' : '') . " $minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
} elseif ($hours > 0) {
    $timeDifference = "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
} elseif ($minutes > 0) {
    $timeDifference = "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
} else {
    $timeDifference = "just now";
}


$sync_time = $timeDifference;
    ?>
                <div class="card mb-2">
                   <!-- <a href="product-lists.php?catid=<?php echo $result['id'];?>">-->
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                             <a href="home2.php?frid=<?php echo $result['FrId'];?>">
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"><?php echo $result['ShopName'];?></h6>
                                          <span class="small text-secondary" style=""><?php echo $result['Address'];?></span>
                                       <p class="small " style="text-transform:capitalize;color:#009eff;">Last Sync : <?php echo $sync_time;?></p>
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($NetAmount,2);?></strong>
                                         <br><span style="font-size:12px;">Cash : ₹<?php echo number_format(countval('cash_payment',$result['FrId'],$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">UPI : ₹<?php echo number_format(countval('upi_payment',$result['FrId'],$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">Avg : <?php echo number_format($NetAmount/$result['TotInv'],2);?></span></p>
                                    </div>
                                </div>
                            </li>
                            </a>
                        </ul>
                    </div>
               <!-- </a>-->
                </div>
            <?php } } ?>
                
                 <?php 
       $sql2 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE InvoiceDate='".date('Y-m-d')."' AND FrId='0'";
   $row2 = getRecord($sql2);
   $NetAmount22 = $row2['NetAmount'];
   
   $sql22 = "SELECT * FROM tbl_customer_invoice WHERE InvoiceDate='".date('Y-m-d')."' AND FrId='0'";
   $rncnt223 = getRow($sql22);
   
   if($NetAmount22 > 0){
       $NetAmount3 = $row2['NetAmount'];
   }
   else{
       $NetAmount3 = 0;
   }
   $rncnt225 = $rncnt224+$rncnt223;
   if($NetAmount3 > 0){
   ?>

<div class="card mb-2">
                  <a href="home2.php?frid=0">
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class="font-weight-normal mb-1" style="color:black;">MAHA CHAI PVT LTD - KHAMALA (Main)</h6>
                                        <p class="small text-secondary">Plot No, 3, Jetwan Society Rd, Agne Layout, Shastri Layout, Khamla, Nagpur, Maharashtra 440025</p>
                                       
                                       
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary"><strong>&#8377;<?php echo number_format($NetAmount3,2);?></strong><br><span style="font-size:12px;">Avg : <?php echo number_format($NetAmount/$rncnt22,2);?></span>
                                         </p>
                                         
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
              </a>
                </div>
                <?php } ?>
                
<div class="card mb-2">
                
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Cash</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotCashAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total UPI</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotUpiAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"> Total Income</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotNetAmount+$NetAmount3,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    
                     <?php if($TotNetAmount+$NetAmount3 == 0){?>
                     <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Avg</h6>
                                         
                                      
                                    </div> 
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>0</strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    <?php } else {?>
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Avg</h6>
                                         
                                      
                                    </div> 
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong><?php echo number_format(($TotNetAmount+$NetAmount3)/$rncnt225,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    <?php } ?>
                    
                  
                    
                </div>
                

            </div>
        </div>
    </main>


     <?php include_once 'footer.php';?>

    <!-- color settings style switcher -->
   <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>



    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
 <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
  
    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
 <script type="text/javascript" src="js/toastr.min.js"></script>
    <script type="text/javascript">
   function featured(){
        if($('#Featured').prop('checked') == true) {
            $('#Featured').val(1);
        }
        else{
           $('#Featured').val(0);
            }
        }
function category_lists(){
  var action = 'view';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_category.php",
   data:{action:action},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Category Name Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
        toastr.success('New Category Added Successfully!', 'Success', {timeOut: 2000});
   
  }
function update_toast(){
     toastr.success('Category Updated Successfully!', 'Success', {timeOut: 2000});
      
  }

  $(document).ready(function() {

   
      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Category</span>");  
           $('#action').val("Add");  
           $('#id').val('');
        $('#srno').val('');
      $('#Name').val('');
      $('#Icon').val('');
      $('#Photo').val('');
        $('#OldPhoto').val(''); 
        $('#show_photo').hide();
        
         $('#Photo2').val('');
        $('#OldPhoto2').val(''); 
        $('#show_photo2').hide();
        
      $('#Status').attr("selected","selected").val(null);
      $('#Roll').attr("selected","selected").val('3');
      
       $('#Featured').val(1).prop('checked',false);
       $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
         e.preventDefault();    
      var action = $('#action').val();
         $.ajax({  
                url :"ajax_files/ajax_customer_category.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
               
                    if(data == 1){
                      if(action == 'Edit'){
                        update_toast();
                      }
                      else{
                      success_toast();
                      }
                      $('.insert_frm').modal('hide'); 
                      setTimeout(function(){  
                      window.location.href="prod-category.php";
                    }, 1000);  
                    }
                    else{
                      error_toast();
                      $('.insert_frm').modal('show'); 
                      
                    }
                  //category_lists();
                      $('#submit').attr('disabled',false);
                       $('#submit').text('Submit');
                        $('#action').val("Add");  
                }  
           })  

 
  });


      $(document).on("click", ".update", function(event){
 event.preventDefault();
 event.stopPropagation();
 var id = $(this).attr("data-id");
 var action = "fetch_record";
 $.ajax({  
                url:"ajax_files/ajax_customer_category.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                    $('#srno').val(data.srno);  
                     $('#Name').val(data.Name);  
                      $('#Icon').val(data.Icon);
                      $('#OldPhoto').val(data.Photo); 
                      $('#Photo').val(''); 
                      
                       $('#OldPhoto2').val(data.Photo2); 
                      $('#Photo2').val(''); 
                      
                      $('#Roll').val(data.Roll).attr("selected",true);  
                    $('#Status').val(data.Status).attr("selected",true);  
                   if(data.Featured == 1){

        $('#Featured').val(data.Featured).prop('checked',true);
      }
      else{
       
        $('#Featured').val(data.Featured).prop('checked',false);
      }
                     $('#action').val('Edit'); 
                    if(data.Photo==''){
                       $('#show_photo').hide();
                    } else{
                       $('#show_photo').show();
                    $('#show_photo').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/'+data.Photo+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                  
                  if(data.Photo2==''){
                       $('#show_photo2').hide();
                    } else{
                       $('#show_photo2').show();
                    $('#show_photo2').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo2"></a><img src="../uploads/'+data.Photo2+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                       $('#id').val(id);  
                       $('#submit').text("Update");   
                       $('.insert_frm').modal('show');
                         $('.modal-title').html("Update <span class='font-weight-light'>Category</span>"); 
                     
                }  
           });
});



 $(document).on("click", ".delete", function(event){
 event.preventDefault();
 var id = $(this).attr("data-id");
 var action = "delete";
 //alert(id);
   swal({
            title: "Are you sure?",
            text: "Deleted All Records Related this Category & You will not be able to recover this Category!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                 $.ajax({  
                url:"ajax_files/ajax_customer_category.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Category has been deleted.", "success");
              window.location.href="prod-category.php";
              //category_lists();

                     }  
           });
                
            } else {
                swal("Cancelled", "Category is safe :)", "error");
            }
        });
        
/* bootbox.confirm({
            message: 'Are you sure?',
            className: 'bootbox-sm',
                callback: function(result) {
                 if(result == true){
                
                 }
                 else{
                    
                 }
            },
        }); */
           
           
 });

 $(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Category Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_customer_category.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
      category_lists();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });
   
   $(document).on("click", "#delete_photo2", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Category Icon?"))  
           {  
             var action = "deletePhoto2";
             var id = $('#id').val();
             var Photo = $('#OldPhoto2').val();
             $.ajax({
    url:"ajax_files/ajax_customer_category.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo2').hide();
      $('#OldPhoto2').val('');
      category_lists();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });

} );
</script>
</body>

</html>
