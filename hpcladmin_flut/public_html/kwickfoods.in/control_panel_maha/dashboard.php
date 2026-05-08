<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
if($_REQUEST['id']==''){
    $_SESSION['fr_admin'] = $_SESSION['fr_admin'];
}
else{
  $_SESSION['fr_admin'] = $_REQUEST['id'];  
}
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
</head>
<body>
<style type="text/css">
    .mr_5{
        margin-right: 3rem !important;
    }
    
 </style>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>


<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
</div>

<?php 
    function countval($val,$BillSoftFrId){
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
        $sql = "SELECT count(*) As result FROM tbl_cust_category WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0";
    }
    if($val == 'prod_cust'){
        $sql = "SELECT count(*) As result FROM tbl_cust_products WHERE CreatedBy='$BillSoftFrId'";
    }
    if($val == 'fr_inv'){
        $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=1";
    }
    if($val == 'cust_inv'){
         $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$BillSoftFrId'";
    }
    if($val == 'today_cust_inv'){
        $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId' AND delete_flag=0";
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
        $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PR' AND CrDr='cr' AND Flag='Customer-Invoice' AND AccRoll='Customer' AND BillSoftFrId='$BillSoftFrId'";
    }
    if($val == 'cash_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND FrId='$BillSoftFrId' AND PayType='Cash'";
    }
    if($val == 'phonepay_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND PayType='Phone Pay' AND FrId='$BillSoftFrId'";
    }
    if($val == 'paytm_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND PayType='Paytm' AND FrId='$BillSoftFrId'";
    }
    if($val == 'googlepay_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND PayType='Google Pay' AND FrId='$BillSoftFrId'";
    }
    if($val == 'otherupi_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND PayType='Other UPI' AND FrId='$BillSoftFrId'";
    }
    if($val == 'borrow_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE   Roll=2 AND PayType='Borrowing' AND FrId='$BillSoftFrId'";
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
                                                <div class="text-large"><?php echo countval('cust_inv',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('today_cust_inv',$BillSoftFrId);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('cash_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('phonepay_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('googlepay_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('paytm_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('otherupi_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('borrow_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large"><?php echo countval('prod_cat',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('prod_cust',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('fr_prod',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('prod_stock',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('fr_inv',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('expenses',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('raw',$BillSoftFrId);?></div>
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
                                                <div class="text-large"><?php echo countval('vendor',$BillSoftFrId);?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>
                            
                         
                            <?php } if(in_array("53", $Options)) {?> 
                            <div class="col-sm-6 col-xl-3">
        <a href="view-uses-raw-products.php">
                                <div class="card mb-4 bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Use Raw Products</div>
                                                <div class="text-large"><?php echo countval('use_raw',$BillSoftFrId);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('ved_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('fr_payment',$BillSoftFrId),2);?></div>
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
                                                <div class="text-large">₹<?php echo number_format(countval('cust_payment',$BillSoftFrId),2);?></div>
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
                            
                             <!--<div class="col-sm-6 col-xl-3">
        <a href="view-expenses.php">
                                <div class="card mb-4 bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Today Expenses</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT SUM(Amount) As Amount FROM tbl_expenses WHERE ExpenseDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId'";
                                                            $row4 = getRecord($sql4);
                                                             echo "&#8377;".number_format($row4['Amount'],2);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>-->

                           <?php } if(in_array("60", $Options)) {?> 
                           <!--<div class="col-sm-6 col-xl-3">
        <a href="view-expenses.php">
                                <div class="card mb-4 bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="ion ion-ios-card display-4"></div>
                                            <div class="ml-4">
                                                <div class="text-white small" style="font-weight: 700;">Total Expenses</div>
                                                <div class="text-large"><?php  
                                                            $sql4 = "SELECT SUM(Amount) As Amount FROM tbl_expenses WHERE FrId='$BillSoftFrId'";
                                                             $row4 = getRecord($sql4);
                                                             echo "&#8377;".number_format($row4['Amount'],2);

                                                        ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
                            </div>-->
                            <?php } ?>
                         
                           
                          
                            
    
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
