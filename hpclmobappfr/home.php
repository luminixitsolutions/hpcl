<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";
$url = "home.php";
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

if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
else{
    if(isset($_SESSION['FranchiseId'])){}
    else{
        $_SESSION['FranchiseId'] = 0;
    }
    
}
$FranchiseId = $_SESSION['FranchiseId'];
$Calendar = $_REQUEST['calendar'];
 function countval($val,$frid,$Calendar){
        global $conn; 
        $sql = "SELECT SUM(result) As result FROM (";
    if($val == 'cust_inv'){
        $sql.= "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid'";
    }
    if($val == 'today_cust_inv'){
        $sql.= "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND InvoiceDate='".date('Y-m-d')."'";
    }
   
   if($val == 'total_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid'";
    }
    if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    if($val == 'phonepay_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Phone Pay'";
    }
    if($val == 'paytm_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Paytm'";
    }
    if($val == 'googlepay_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='UPI'";
    }
    if($val == 'otherupi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Other UPI'";
    }
    if($val == 'borrow_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Borrowing'";
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
if($val == 'cust_inv'){
        $sql.= "SELECT count(*) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid'";
    }
    if($val == 'today_cust_inv'){
        $sql.= "SELECT count(*) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND InvoiceDate='".date('Y-m-d')."'";
    }
   
   if($val == 'total_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid'";
    }
    if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    if($val == 'phonepay_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Phone Pay'";
    }
    if($val == 'paytm_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Paytm'";
    }
    if($val == 'googlepay_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='UPI'";
    }
    if($val == 'otherupi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Other UPI'";
    }
    if($val == 'borrow_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Borrowing'";
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

    $sql55 = "SELECT * FROM tbl_users_bill WHERE id='$FranchiseId'";
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
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="analytics">
    <!-- screen loader -->
   

    <!-- menu main -->
    <?php include 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer" style="background-color:#009eff;">
        <!-- Fixed navbar -->
      <?php 
$sql = "SELECT Max(modified_time) AS LastTime FROM `tbl_customer_invoice_2025` WHERE FrId='$FranchiseId'";
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
$no_of_bills = countval('cust_inv',$FranchiseId,$Calendar);
?> 
<?php include 'top_header.php';?>
        <!-- page content start -->
        <div class="container mt-3 mb-2 text-center" style="padding-top: 45px;">
            <h2 class="text-white">₹<?php echo number_format(countval('total_payment',$FranchiseId,$Calendar),2);?></h2>
             <p class="text-white mb-2" style="text-transform:capitalize;">Last Sync : <?php echo $sync_time;?></p>
        </div>

        <div class="main-container">

            <div class="container">
            
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                <div class="row h6 font-weight-bold">
                    <div class="col">Total Sale</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('total_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Cash</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('cash_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">UPI</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('upi_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Credit</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('borrow_payment',$FranchiseId,$Calendar),2);?></div>
                </div>

                            </div>
                        </div>
                    </div>
</div></div>
                    

    <div class="container mb-2">
                <div class="row">
                    <div class="col">
                        <h6 class="subtitle mb-2">UPI Sale </h6>
                    </div>
                    <!-- <div class="col-auto"><a href="#" class="text-default">View all</a></div> -->
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 mb-2">
                            <div class="">
                                <div class="row align-items-center">
                                    
                                    <div class="col-6 align-self-center">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="col">Google Pay</div>
                    <div class=" text-right text-mute">₹<?php echo number_format(countval('googlepay_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Paytm</div>
                    <div class=" text-right text-mute">₹<?php echo number_format(countval('paytm_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
                

                            </div>
                        </div>
                                    </div>

                                    <div class="col-6 align-self-center" style="border-left: 1px solid #cccccc;">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="">Phone Pay</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('phonepay_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
                
                <div class="row my-3  font-weight-normal">
                    <div class="">Others</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('otherupi_payment',$FranchiseId,$Calendar),2);?></div>
                </div>

                            </div>
                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
</div></div>

    <div class="container mb-2">
                <div class="row">
                    <div class="col">
                        <h6 class="subtitle mb-2">Summary </h6>
                    </div>
                    <!-- <div class="col-auto"><a href="#" class="text-default">View all</a></div> -->
                </div>
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                <div class="row h6 font-weight-bold">
                    <div class="col">No of Biils</div>
                    <div class="col text-right text-mute"><?php echo $no_of_bills; ?></div>
                </div>
               

                            </div>
                        </div>
                    </div>
                    
                    </div>
                    
                   
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                <div class="row h6 font-weight-bold">
                    <div class="col">Avg Bill Total</div>
                    <div class="col text-right text-mute"><?php if($no_of_bills > 0){ echo number_format(countval('total_payment',$FranchiseId,$Calendar)/countval('cust_inv',$FranchiseId,$Calendar),2);} else {echo 0; }?></div>
                </div>
               

                            </div>
                        </div>
                    </div>
                    
                    </div>

<div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 mb-2">
                            <div class="">
                                <div class="row align-items-center">
                                    
                                    <div class="col-6 align-self-center">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="col">Total Tax</div>
                    <div class=" text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Total Dis.</div>
                    <div class=" text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">No. of Deleted Bills</div>
                    <div class=" text-right text-mute">0</div>
                </div>

                            </div>
                        </div>
                                    </div>

                                    <div class="col-6 align-self-center" style="border-left: 1px solid #cccccc;">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="col">Round off</div>
                    <div class="col text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Refund Amount</div>
                    <div class="col text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Amount of Deleted Bills</div>
                    <div class="col text-right text-mute">&#8377;0</div>
                </div>

                            </div>
                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>


                    
                </div>
            </div>


            <div class="container mb-2">
                
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 mb-2">
                            <div class="">
                                <div class="row align-items-center">
                                    
                                    <div class="col-6 align-self-center">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="col">Opening Bal.</div>
                    <div class=" text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Extra Rec.</div>
                    <div class=" text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Delivery C.</div>
                    <div class=" text-right text-mute">&#8377;0</div>
                </div>

                            </div>
                        </div>
                                    </div>

                                    <div class="col-6 align-self-center" style="border-left: 1px solid #cccccc;">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="">Rece. Cust.</div>
                    <div class="col text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="">Advance (-)</div>
                    <div class="col text-right text-mute">&#8377;0</div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="">Total Expenses</div>
                    <div class="col text-right text-mute"><?php 
                    $sql = "SELECT SUM(Amount) As Amount FROM tbl_bill_expenses WHERE UserId='$FranchiseId'";
                    if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND ExpenseDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND ExpenseDate>='$Week' AND ExpenseDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            $Week = date('Y-m-d',strtotime("-30 days"));
            $sql.=" AND ExpenseDate>='$Week' AND ExpenseDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND ExpenseDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND ExpenseDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND ExpenseDate='".date('Y-m-d')."'"; 
        }
                    $row = getRecord($sql);
                    $ExpAmt = $row['Amount'];
                    echo "&#8377;".number_format($row['Amount'],2);?></div>
                </div>

                            </div>
                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
</div></div>


<?php 
$FrId = $FranchiseId;
$Calendar = $_REQUEST['calendar'];
$FromDate = $_REQUEST['FromDate'] ?? '';
$ToDate = $_REQUEST['ToDate'] ?? '';

// Fetch Franchise Name
$sql = "SELECT ShopName FROM tbl_users_bill WHERE id='$FrId'";
$row = getRecord($sql);

// Common date filter
/*$dateFilter = "";
if ($FromDate) {
    $dateFilter .= " AND tc.CreatedDate >= '$FromDate'";
}
if ($ToDate) {
    $dateFilter .= " AND tc.CreatedDate <= '$ToDate'";
}*/

// MRP Product
$sql2 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
         FROM tbl_customer_invoice_details_2025 tc 
         INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
         WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 1 AND tp.ProdType = 0 AND tp.CrossSell!=1 ";
         
         if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql2.=" AND tc.CreatedDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql2.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql2.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND tc.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql2.= " AND tc.CreatedDate<='$ToDate'";
            }
        }
        else{
           $sql2.=" AND tc.CreatedDate='".date('Y-m-d')."'"; 
        }
$row2 = getRecord($sql2);

// Kitchen Product
$sql21 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 2 AND tp.ProdType = 0 AND tp.CrossSell!=1 ";
          if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql21.=" AND tc.CreatedDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql21.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql21.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql21.= " AND tc.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql21.= " AND tc.CreatedDate<='$ToDate'";
            }
        }
        else{
           $sql21.=" AND tc.CreatedDate='".date('Y-m-d')."'"; 
        }
$row21 = getRecord($sql21);

// Cross Product
$sql22 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId = '$FrId' AND tp.CrossSell=1 ";
          if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql22.=" AND tc.CreatedDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql22.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql22.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql22.= " AND tc.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql22.= " AND tc.CreatedDate<='$ToDate'";
            }
        }
        else{
           $sql22.=" AND tc.CreatedDate='".date('Y-m-d')."'"; 
        }
$row22 = getRecord($sql22);
?>

 <div class="container mb-2">
                
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 mb-2">
                            <div class="">
                                <div class="row align-items-center">
                                    
                                    <div class="col-12 align-self-center">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="col-6">QSR KITCHEN SALES</div>
                    <div class="col-2"><?= $row21['TotSell']; ?></div>
                     <div class="col-4">&#8377;<?= $row21['NetAmount']; ?></div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">PACK FOOD SALES</div>
                    <div class="col-2"><?= $row2['TotSell']; ?></div>
                     <div class="col-4">&#8377;<?= $row2['NetAmount']; ?></div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">CROSS SALES</div>
                     <div class="col-2"><?= $row22['TotSell']; ?></div>
                     <div class="col-4">&#8377;<?= $row22['NetAmount']; ?></div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col"><strong>TOTAL SALES</strong></div>
                     <div class="col-2"><strong><?= $row2['TotSell']+$row21['TotSell']+$row22['TotSell']; ?></strong></div>
                     <div class="col-4"><strong>&#8377;<?= $row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount']; ?></strong></div>
                </div>

                            </div>
                        </div>
                                    </div>

                                   
                                    
                                </div>
                            </div>
                        </div>
                    </div>
</div></div>

<?php 
$sql3 = "SELECT count(*) AS TotEmp, SUM(tu.MonthlySalary) AS MonthlySalary FROM tbl_users tu WHERE tu.UnderFrId = '$FranchiseId' AND tu.Status=1";
    $row3 = getRecord($sql3);
    
     $month = date('m');
    $year = date('Y');
    $sql = "SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE FrId='$FranchiseId' AND month(InvoiceDate)='$month' AND year(InvoiceDate)='$year' UNION ALL 
    SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId='$FranchiseId' AND month(InvoiceDate)='$month' AND year(InvoiceDate)='$year') as a";
    $row = getRecord($sql);
    ?>
<div class="container mb-2">
               
                <div class="row">
                    <div class="col-12 col-md-6">
                        
                        <div class="card border-0 mb-2">
                            <div class="">
                                <div class="row align-items-center">
                                    
                                    <div class="col-12 align-self-center">
                                        <div class="">
                            <div class="card-body">
                
                <div class="row my-3  font-weight-normal">
                    <div class="col-6">Total Employee</div>
                    <div class="col-2"></div>
                     <div class="col-4"><?= $row3['TotEmp']; ?></div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Current Month Income</div>
                    <div class="col-2"></div>
                     <div class="col-4">&#8377;<?= $row['NetAmount']; ?></div>
                </div>
                <div class="row my-3  font-weight-normal">
                    <div class="col">Total Salary</div>
                    <div class="col-2"></div>
                     <div class="col-4">&#8377;<?= $row3['MonthlySalary']; ?></div>
                </div>
                
                <div class="row my-3  font-weight-normal">
                    <div class="col-4"></div>
                    <div class="col">
                    <a href="view-employee.php" class="btn btn-sm btn-default rounded">View</a>
                    </div>
                    </div>
                

                            </div>
                        </div>
                                    </div>

                                   
                                    
                                </div>
                            </div>
                        </div>
                    </div>
</div></div>

<div class="container">
            
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                <div class="row h6 font-weight-bold">
                    <div class="col">Total Amount</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('total_payment',$FranchiseId,$Calendar) - $ExpAmt,2);?></div>
                </div>
                <div class="row h6 font-weight-bold">
                    <div class="col">Cash in Hand</div>
                    <div class="col text-right text-mute">₹<?php echo number_format(countval('cash_payment',$FranchiseId,$Calendar),2);?></div>
                </div>
               

                            </div>
                        </div>
                    </div>
</div></div>
                    
                </div>
            </div>
        </div>
    </main>

    <!-- footer-->
    <?php include_once 'footer.php';?>


    <!-- color settings style switcher -->
    <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>




    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- chart js-->
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/chartjs/utils.js"></script>
    <script src="vendor/chartjs/chart-js-data.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
</body>

</html>
