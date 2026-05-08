<?php 
session_start();
include_once 'config.php';
//include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Retailer";
$Page = "Sell-To-Retailer";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
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

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>

<?php 
$id = $_GET['id'];
$query = "SELECT * FROM tbl_sell_retailer WHERE id = '$id'";
$row = getRecord($query);
$number = $row['TotalAmount'];
include_once 'convert_currancy.php';
  
$sql7 = "SELECT * FROM tbl_users_bill WHERE id='".$row['FranchiseId']."'";
$row7 = getRecord($sql7);

$sql77 = "SELECT * FROM tbl_users_bill WHERE id='".$row['GodownId']."'";
$row77 = getRecord($sql77);
  
 ?>

<div class="layout-container" style="background-color:#fff;">
 <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        
                        <div class="">
                            <div class="card-body p-2">
                                
                                <strong>RETAILER INVOICE</strong>
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
<div class="mb-1">Plot No. 3, Jetwan Society, Agne Layout, Khamla, Besides Anand Purti Super Bazaar Nagpur - 440025<br>
www.mahachai.in</div>
                                        <div class="mb-1">Mobile: 8007885000       GSTIN: 27AANCM5897K1ZH</div>
                                    </div>
                                    
                                </div>
                                <hr class="mb-4">
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-6" style="background-color:#d9d9d9;color: #000;">
                                       
                                        <div class="font-weight-bold mb-2" style="padding-top:15px;text-align: center; font-size:18px;">Sr. No.: <?php echo $row['InvoiceNo'];?></div>
        
                                    </div>
                                    <div class="col-sm-6 mb-6" style="background-color:#d9d9d9;color: #000;">
                                        <div class="font-weight-bold mb-2" style="padding-top:15px;text-align: center; font-size:18px;">Date: <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?>                                     </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-6">
                                        
                                        <div  style="padding-top:15px; font-size:15px;">TRANSFER FROM<br>
<strong><?php echo $row77['Fname'];?></strong><br>
<?php echo $row77['Address'];?><br>
Mobile: <?php echo $row77['Phone'];?><br>
State: Maharashtra</div>
                                    </div>
                                    <div class="col-sm-6 mb-6">
                                        <div  style="padding-top:15px; font-size:15px;">TRANSFER TO<br>
<strong><?php echo $row7['Fname'];?></strong><br>
<?php echo $row7['Address'];?><br>
Mobile: <?php echo $row7['Phone'];?><br></div>
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
                                                   GST
                                                </th>
                                                 <th class="py-3">
                                                    AMOUNT
                                                </th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
        <?php 
$i=1;
$sql44 = "SELECT ttg.*,tgp.ProductName FROM tbl_sell_retailer_items ttg LEFT JOIN tbl_godown_products tgp ON ttg.ProdId=tgp.id WHERE ttg.TransferId='$id'";
$row44 = getList($sql44);
foreach($row44 as $result){
$TotQty+=$result['Qty'];
$TotRate+=$result['Price'];
$TotGst+=$result['GstAmt'];
$TotSGst+=$result['SgstAmt'];
$TotCGst+=$result['CgstAmt'];
?>                            <tr>
                                                
                                                <td class="py-3">
                                                    <strong><?php echo $result['ProductName'];?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong><?php echo $result['Qty'];?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $result['Price'];?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $result['GstAmt'];?></strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $result['TotalPrice'];?></strong>
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
                                                    <strong>₹<?php echo $TotRate;?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <strong>₹<?php echo $TotGst;?></strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['TotalAmount'];?></strong>
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
                                                    <strong>₹<?php echo $row['TotalAmount']-$TotGst;?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>CGST @2.5%</strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $TotCGst;?></strong>
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
                                                    <strong>₹<?php echo $TotSGst;?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>Total Amount </strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['TotalAmount'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                          <!--  <tr>
                                                
                                                <td class="py-3" colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td class="py-3">
                                                    <strong>Balance</strong>
                                                </td>
                                                 <td class="py-3">
                                                    <strong>₹<?php echo $row['Balance'];?></strong>
                                                </td>
                                                
                                            </tr>-->
                                                                                                                          
                                                                                   

                                           

                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <strong>NOTE</strong> <br>

 If any queries u may call your purchase manager : +918007885000
                                </div>
                            </div>
                            <hr>
                            <div style="text-align:right;padding-right:100px;">Total Amount (in words)<br>
                            <?php echo $result22 . "Rupees  ";?></div><br><br>


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
