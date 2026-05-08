<?php 
session_start();
include_once 'config.php';
//include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customers List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>
    
    <style>
        body {
            font-size:15px;
        }
    </style>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<?php 
$id = $_GET['id'];
$sql = "SELECT ti.* FROM tbl_customer_invoice ti LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE ti.id='$id'";
$row = getRecord($sql);
$number = $row['NetAmount'];
include_once 'convert_currancy.php';
?>

<div class="layout-container" style="background-color:#fff;">
 <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        
                        <div class="">
                            <div class="card-body p-2">
                                
                                <strong>TAX INVOICE ORIGINAL FOR RECIPIENT</strong>
                                <br><br>
                                
                                <div class="row">
                                    <div class="col-sm-2 " style="border-right: 1px dotted #000;">
                                        <div class="media align-items-center ">
                                            <div class="  demo py-0">
                                                <span class="app-brand-logo demo">
                                                    <img  width="200px" src="logo332.png" alt="Brand Logo" class="img-fluid">
                                                </span>
                                               
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-7 ">
<strong style="font-size: 30px;">MAHACHAI PRIVATE LIMITED</strong>
<div class="mb-1">201 SIDHIVINAYAK APARTMENT, IT park ,Srinagar, Nagpur 440022, Nagpur, 440022</div>
                                        <div class="mb-1">Mobile: 09975037482       GSTIN: 27AANCM5897K1ZH</div>
                                    </div>
                                    
                                </div>
                                <hr class="mb-4">
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-6" style="background-color:#d9d9d9;color: #000;">
                                       
                                        <div class="font-weight-bold mb-2" style="padding-top:15px;text-align: center; font-size:18px;">Invoice No.: <?php echo $row['InvoiceNo'];?></div>
        
                                    </div>
                                    <div class="col-sm-6 mb-6" style="background-color:#d9d9d9;color: #000;">
                                        <div class="font-weight-bold mb-2" style="padding-top:15px;text-align: center; font-size:18px;">Invoice Date: <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?>                                     </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-6">
                                        
                                        <div  style="padding-top:15px; font-size:15px;">BILL TO<br>
<strong><?php echo $row['CustName'];?></strong><br>
<?php echo $row['Address'];?><br>
Mobile: <?php echo $row['CellNo'];?><br>
State: Maharashtra</div>
                                    </div>
                                    <div class="col-sm-6 mb-6">
                                        <div  style="padding-top:15px; font-size:15px;">SHIP TO<br>
<strong><?php echo $row['CustName'];?></strong><br>
<?php echo $row['Address'];?></div>
                                    </div>
                                </div>
                                <div class="table-responsive mb-4">
                                    <table class="table m-0">
                                        <thead style="background-color:#d9d9d9;">
                                            <tr style="background-color:#d9d9d9;font-weight:bold;">
                                                <th class="py-3">
                                                    ITEMS
                                                </th>
                                                <th class="py-3">
                                                    QTY
                                                </th>
                                                <th class="py-3">
                                                    RATE
                                                </th>
                                                <th class="py-3">
                                                   TAX
                                                </th>
                                                 <th class="py-3">
                                                    AMOUNT
                                                </th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php 
            $i=1;
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='$id'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName FROM products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=$result12['GstAmt'];
        ?>                                <tr>
                                                
                                                <td class="py-3">
                                                    <strong><?php echo $row13['ProductName'];?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong><?php echo $result12['Qty'];?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $result12['Price'];?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $result12['GstAmt'];?></strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $result12['Total'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            <?php } ?>
                                             <tr>
                                                
                                                <td class="py-3">
                                                    <strong>SUBTOTAL</strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong><?php echo $TotQty;?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $TotPrice;?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $TotGst;?></strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['NetAmount'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>TAXABLE AMOUNT</strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['NetAmount'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>CGST @2.5%T</strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹0</strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>SGST @2.5%</strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹0</strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>Received Amount </strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['Advance'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>Balance</strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['Balance'];?></strong>
                                                </td>
                                                
                                            </tr>
                                                                                                                          
                                                                                   

                                           

                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <strong>BANK DETAILS</strong> <br>


Name: MAHACHAI PRIVATE LIMITED<br>
IFSC Code: CNRB0003124<br>
Account No: 125000412080<br>
                                </div>
                            </div>
                            <hr>
                            <div style="text-align:right;padding-right:100px;">Total Amount (in words)<br>
                            Thirteen Thousand Rupees</div><br><br>


 <div class="row">
                                    <div class="col-sm-6 "><br><br><br><br><br><br><br><br>
                                        
                                    </div>
                                    <div class="col-sm-5 " style="text-align:right;">
                                        <img width="180px" src="signature.png"><br><br>
                                        <div class="font-weight-bold ">AUTHORISED SIGNATORY FOR<br>
MAHACHAI PRIVATE LIMITED</div>
                                    </div>
                                    
                                    <div class="col-sm-1 " style="text-align:right;">
                                    </div>
                                    
                                </div>
<hr>





                            <div class="card-footer text-right">
                                <a href="javascript:void(0)" onclick="window.print()" class="btn btn-default"><i class="ion ion-md-print"></i>&nbsp; Print</a>
                            </div>
                        </div>

                    </div>
                    <!-- [ content ] End -->

                   

                </div>
                <!-- [ Layout content ] Start -->

            </div>
            <!-- [ Layout container ] End -->
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>


</body>
</html>
