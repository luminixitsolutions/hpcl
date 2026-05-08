<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
$user_id = $_SESSION['Admin']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}
//echo $sql11;
$Roll = $row['Roll'];
/*function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;


$sql = "SELECT * FROM tbl_cust_products WHERE code=''";
$row = getList($sql);
foreach($row as $result){
    $Code = RandomStringGenerator($n); 
    $Code2 = $Code."".$result['id'];
    $sql2 = "UPDATE tbl_cust_products SET code='$Code2' WHERE id='".$result['id']."'";
    $conn->query($sql2);
}*/
if($Roll == 63){
    echo "<script>window.location.href='emp-dashboard.php';</script>";
    exit();
}
if($Roll == 64){
    echo "<script>window.location.href='preparing-order.php';</script>";
    exit();
}
else{
    
}
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
<div class="container-fluid flex-grow-1 container-p-y">
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
</div>

<?php 
    function countval($val){
        global $conn; 
    if($val == 'raw'){
        $sql = "SELECT count(*) As result FROM tbl_raw_products";
    }
    if($val == 'vendor'){
        $sql = "SELECT count(*) As result FROM tbl_users WHERE Roll=3";
    }
    if($val == 'vendor_inv'){
        $sql = "SELECT count(*) As result FROM tbl_invoice";
    }
    if($val == 'use_raw'){
        $sql = "SELECT count(*) As result FROM tbl_use_raw_stock";
    }
    if($val == 'fr_prod'){
        $sql = "SELECT count(*) As result FROM products WHERE ProdFor IN(1,3)";
    }
    if($val == 'prod_stock'){
        $sql = "SELECT count(*) As result FROM tbl_product_stocks";
    }
    if($val == 'prod_cat'){
        $sql = "SELECT count(*) As result FROM tbl_cust_category";
    }
    if($val == 'prod_cust'){
        $sql = "SELECT count(*) As result FROM tbl_cust_products WHERE CreatedBy=0";
    }
    if($val == 'fr_inv'){
        $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=1";
    }
    if($val == 'cust_inv'){
        $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId=0";
    }
    if($val == 'today_cust_inv'){
        $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId=0 AND InvoiceDate='".date('Y-m-d')."'";
    }
    if($val == 'expenses'){
        $sql = "SELECT count(*) As result FROM tbl_expenses";
    }
    if($val == 'ved_payment'){
        $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PW' AND CrDr='dr' AND Flag='Invoice' AND AccRoll='Vendor'";
    }
    if($val == 'fr_payment'){
        $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PR' AND CrDr='cr' AND Flag='Franchise-Invoice' AND AccRoll='Franchise'";
    }
    if($val == 'cust_payment'){
        $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PR' AND CrDr='cr' AND Flag='Customer-Invoice' AND AccRoll='Customer'";
    }
    if($val == 'cash_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId=0 AND PayType='Cash'";
    }
    if($val == 'phonepay_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId=0 AND PayType='Phone Pay'";
    }
    if($val == 'paytm_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId=0 AND PayType='Paytm'";
    }
    if($val == 'googlepay_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId=0 AND PayType='Google Pay'";
    }
    if($val == 'otherupi_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId=0 AND PayType='Other UPI'";
    }
    if($val == 'borrow_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId=0 AND PayType='Borrowing'";
    }
     $res2 = $conn->query($sql);
	$row2 = $res2->fetch_assoc();
    return $row2['result'];
    }
    
    

?>

<div class="row">
    <?php
$bmi_range=$_REQUEST['uid'];
//echo "<h1> Uid =".$bmi_range."</h1>"
?>
    <?php if(in_array("59", $Options)) {?>
      <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?Search=Search">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Total Customer Invoice</div>
                                                <div class="text-large"><?php echo countval('cust_inv');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("59", $Options)) {?>  
                             <div class="col-sm-6 col-xl-3">
        <a href="view-today-orders.php">
                                <div class="card mb-4 bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Today Customer Invoice</div>
                                                <div class="text-large"><?php echo countval('today_cust_inv');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            <?php } ?>
                            
                              
                            <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?PayType=Cash">
                                <div class="card mb-4 bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Cash</div>
                                                <div class="text-large">₹<?php echo number_format(countval('cash_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                          
                             <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?PayType=Phone Pay">
                                <div class="card mb-4 bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Phone Pay</div>
                                                <div class="text-large">₹<?php echo number_format(countval('phonepay_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                         
                            
                            <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?PayType=Google Pay">
                                <div class="card mb-4 bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Google Pay</div>
                                                <div class="text-large">₹<?php echo number_format(countval('googlepay_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                          
                             <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?PayType=Paytm">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Paytm</div>
                                                <div class="text-large">₹<?php echo number_format(countval('paytm_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            
                            <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?PayType=Other UPI">
                                <div class="card mb-4 bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Other UPI</div>
                                                <div class="text-large">₹<?php echo number_format(countval('otherupi_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                           
                            <div class="col-sm-6 col-xl-3">
        <a href="view-customer-invoices.php?PayType=Borrowing">
                                <div class="card mb-4 bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Borrowing / उधार</div>
                                                <div class="text-large">₹<?php echo number_format(countval('borrow_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php if(in_array("56", $Options)) {?>  
                            
                            <div class="col-sm-6 col-xl-3">
        <a href="customer-category.php">
                                <div class="card mb-4 bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Product Category</div>
                                                <div class="text-large"><?php echo countval('prod_cat');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("57", $Options)) {?> 
                             <div class="col-sm-6 col-xl-3">
        <a href="view-customer-products.php">
                                <div class="card mb-4 bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Customer Products</div>
                                                <div class="text-large"><?php echo countval('prod_cust');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("54", $Options)) {?> 
                            
                            <div class="col-sm-6 col-xl-3">
        <a href="view-shop-products.php">
                                <div class="card mb-4 bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Franchise Product</div>
                                                <div class="text-large"><?php echo countval('fr_prod');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            <?php } if(in_array("55", $Options)) {?> 
                             <div class="col-sm-6 col-xl-3">
        <a href="view-stocks.php">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Franchise Product Stock</div>
                                                <div class="text-large"><?php echo countval('prod_stock');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            <?php } if(in_array("58", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="view-franchise-invoices.php">
                                <div class="card mb-4 bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Franchise Invoices</div>
                                                <div class="text-large"><?php echo countval('fr_inv');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("60", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="view-expenses.php">
                                <div class="card mb-4 bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Expenses</div>
                                                <div class="text-large"><?php echo countval('expenses');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("53", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="view-raw-products.php">
                                <div class="card mb-4 bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Raw Products</div>
                                                <div class="text-large"><?php echo countval('raw');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("51", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="view-vendors.php">
                                <div class="card mb-4 bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Vendors</div>
                                                <div class="text-large"><?php echo countval('vendor');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            <!--<div class="col-sm-6 col-xl-3">
        <a href="view-invoices.php">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Vendor Invoices</div>
                                                <div class="text-large"><?php echo countval('vendor_inv');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>-->
                            <?php } if(in_array("53", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="view-uses-raw-products.php">
                                <div class="card mb-4 bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Use Raw Products</div>
                                                <div class="text-large"><?php echo countval('use_raw');?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("61", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="receive-amount.php">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Vendor Payment</div>
                                                <div class="text-large">₹<?php echo number_format(countval('ved_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("62", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="franchise-amount.php">
                                <div class="card mb-4 bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Franchise Amount</div>
                                                <div class="text-large">₹<?php echo number_format(countval('fr_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("63", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="customer-amount.php">
                                <div class="card mb-4 bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Customer Amount</div>
                                                <div class="text-large">₹<?php echo number_format(countval('cust_payment'),2);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            <?php } ?> 
                            
    <div class="col-sm-6 col-xl-3">
        <a href="view-customers.php">
                                <div class="card mb-4 bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Total Customers</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE Roll=3";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                            <?php  if(in_array("52", $Options)) {?> 
                             <div class="col-sm-6 col-xl-3">
        <a href="view-invoices.php">
                                <div class="card mb-4 bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Total Vendor Invoice</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_invoice";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("52", $Options)) {?> 
                             <div class="col-sm-6 col-xl-3">
        <a href="view-invoices.php">
                                <div class="card mb-4 bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Today Vendor Invoice</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_invoice WHERE InvoiceDate='".date('Y-m-d')."'";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } if(in_array("60", $Options)) {?> 
                            
                             <div class="col-sm-6 col-xl-3">
        <a href="view-expenses.php">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Today Expenses</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT SUM(Amount) As Amount FROM tbl_expenses WHERE ExpenseDate='".date('Y-m-d')."'";
                                                            $row4 = getRecord($sql4);
                                                             echo "&#8377;".number_format($row4['Amount'],2);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>

                           <?php } if(in_array("60", $Options)) {?> 
                           <div class="col-sm-6 col-xl-3">
        <a href="view-expenses.php">
                                <div class="card mb-4 bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Total Expenses</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT SUM(Amount) As Amount FROM tbl_expenses";
                                                             $row4 = getRecord($sql4);
                                                             echo "&#8377;".number_format($row4['Amount'],2);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            <?php } ?>
                         
                           
                            
                           <!-- <div class="col-sm-6 col-xl-3">
                                <a href="view-balance-customers.php">
                               <div class="card bg-success text-white ui-hover-icon mb-4 bg-pattern-3" style="padding-top:15px;padding-bottom:15px;">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT sum(creditqty) AS TotAmt,sum(debitqty) AS PaidAmt,sum(creditqty)-sum(debitqty) AS balqty from (SELECT (case when CrDr='dr' then sum(Amount) else '0' end) as debitqty,(case when CrDr='cr' then sum(Amount) else '0' end) as creditqty FROM `tbl_general_ledger` WHERE Flag='Invoice' AND AccRoll='Customer' GROUP by CrDr) as a";
                                                             $row4 = getRecord($sql4);
                                                             echo "&#8377;".number_format($row4['balqty'],2);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Balance Amt</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>-->
                            
    
</div>
    
    <!--<div class="row">
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="orders.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/vendor-invoice.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Customer Invoice</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-customer-invoices.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/vendor-invoice.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Customer Invoice<br>(<?php echo countval('cust_inv');?>)</small></p>
        </div> 
        
          <div class="col-4 col-md-2 mb-3" style="text-align: center;">
                                <a href="add-raw-products.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           <img src="../mob_icons/raw-materials.jpg" alt="" style="width:70px;">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Raw Product</small></p>
                            </div>
                            
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-raw-products.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/raw-material.jpg" alt="" style="width:70px;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Raw Product<br>(<?php echo countval('raw');?>)</small></p>
        </div>  
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-vendor.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/provision.jpg" alt="" style="width:70px;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Vendor</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-vendors.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/provision.jpg" alt="" style="width:70px;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Vendors<br>(<?php echo countval('vendor');?>)</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-invoice.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/quotation.png" alt="" style="width:70px;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Create Vendor Invoice</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-invoices.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/vendor-invoice.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Vendor Invoice<br>(<?php echo countval('vendor_inv');?>)</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="use-raw-products.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/6008658.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Use Raw Products</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-uses-raw-products.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/6008658.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Use Raw Products<br>(<?php echo countval('use_raw');?>)</small></p>
        </div> 
        
         <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-shop-product.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/product.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Franchise Product</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-shop-products.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/products1.jpg" alt="" style="width:70px;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Franchise Product<br>(<?php echo countval('fr_prod');?>)</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-stocks.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/products.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Product Stock<br>(<?php echo countval('prod_stock');?>)</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="customer-category.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/inspection.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Category<br>(<?php echo countval('prod_cat');?>)</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-customer-product.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="../mob_icons/delivery-boy.jpg" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Customer Product</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-customer-products.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/product.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Customer Product<br>(<?php echo countval('prod_cust');?>)</small></p>
        </div> 
        
         <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-franchise-invoice.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/401182.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Franchise Invoice</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-franchise-invoices.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/401182.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Franchise Invoice<br>(<?php echo countval('fr_inv');?>)</small></p>
        </div> 
        
        
        
         <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-expenses.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/5501375.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Expenses</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="view-expenses.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/5501375.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Expenses<br>(<?php echo countval('expenses');?>)</small></p>
        </div> 
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-receive-amount.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/3515427.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Vendor Payment</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="receive-amount.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/3515427.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Vendor Payment<br>(₹<?php echo number_format(countval('ved_payment'),2);?>)</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-franchise-amount.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/2534217.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Franchise Amount</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="franchise-amount.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/2534217.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Franchise Amount<br>(₹<?php echo number_format(countval('fr_payment'),2);?>)</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="add-customer-amount.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/2867900.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Add Customer Amount</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="customer-amount.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/2867900.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Customer Amount<br>(₹<?php echo number_format(countval('cust_payment'),2);?>)</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="invoice-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/6785175.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Vendor Invoice Report</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="franchise-invoice-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/7176657.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Franchise Invoice Report</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="customer-invoice-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/4306907.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Customer Invoice Report</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="raw-product-stock-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/1533400.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Raw Product Stock Report</small></p>
        </div>
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="company-stock-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/1533400.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;"> Company Stock Report</small></p>
        </div>
        
        
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="franchise-stock-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/1533400.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;"> Franchise Stock Report</small></p>
        </div>
        
        <div class="col-4 col-md-2 mb-3" style="text-align: center;">
            <a href="expense-report.php">
                <div class="avatar avatar-70 mb-1 rounded">
                    <div class="background">
                        <img src="mob_icons/5501375.png" alt="" style="width:70px;border-radius: 50%;">
                    </div>
                </div>
            </a>
        <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;"> Expense Report</small></p>
        </div>
        
    </div>    -->

</div>




</div>



<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
function printReceipt(invdata){
    console.log(invdata);
     //Android.printReceipt(''+invdata+'');
}
    $(document).ready(function() {
    $('#example').DataTable( {
      "lengthMenu": [[5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"]]
    } );

} );
</script>
</body>
</html>
