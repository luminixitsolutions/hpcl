<?php 
session_start();
include_once 'config.php';
//include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
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
            font-size:10px;
        }
        .table td {
  padding:3px;
        }
    </style>

<div class="">
<div class="">



<?php 
$id = $_GET['id'];
$sql = "SELECT ti.* FROM tbl_customer_invoice ti LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE ti.id='$id'";
$row = getRecord($sql);
$number = $row['NetAmount'];
include_once 'convert_currancy.php';
?>

<div  style="background-color:#fff;width:300px;">
 <!-- [ content ] Start -->
                    <div class=" flex-grow-1 container-p-y">
                        
                        <div class="">
                            <div class="">
                            
                                
                                <div class="row">
                                    <div class="col-sm-12 " style="border-right: 1px dotted #000;" align="center">
                                        <div class="media align-items-center ">
                                            <div class="  demo py-0" align="center">
                                                <span class="app-brand-logo demo" align="center">
                                                    <img  width="100px" src="logo332.png" alt="Brand Logo" class="img-fluid">
                                                </span>
                                               
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-12 " align="center">
<strong >MAHACHAI PRIVATE LIMITED</strong>
<div class="mb-1">201 SIDHIVINAYAK APARTMENT,<br> IT park ,Srinagar, Nagpur 440022, Nagpur, 440022</div>
                                        <div class="mb-1">Mobile: 09975037482       GSTIN: 27AANCM5897K1ZH</div>
                                    </div>
                                    
                                </div>
                               
                                
                                <div class="row">
                                    <div class="col-sm-12 " style="background-color:#d9d9d9;color: #000;">
                                       
                                        <div class="font-weight-bold mb-2" style="text-align: center; ">Invoice No.: <?php echo $row['InvoiceNo'];?></div>
        
                                    </div>
                                    <div class="col-sm-12 " style="background-color:#d9d9d9;color: #000;">
                                        <div class="font-weight-bold " style="text-align: center; ">Invoice Date: <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?>                                     </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6 mb-6">
                                        
                                        <div  style="padding-top:15px; ">BILL TO<br>
<strong><?php echo $row['CustName'];?></strong><br>
<?php echo $row['Address'];?><br>
Mobile: <?php echo $row['CellNo'];?></div>
                                    </div>
                                  
                                </div>
                                <div   width="200px">
                                    <table class="table m-0"  width="200px">
                                        <thead style="background-color:#d9d9d9;"  width="200px">
                                            <tr style="background-color:#d9d9d9;font-weight:bold;">
                                                <th width="50px;">
                                                    ITEMS
                                                </th>
                                                <th>
                                                    QTY
                                                </th>
                                                <th>
                                                    RATE
                                                </th>
                                                <th>
                                                   TAX
                                                </th>
                                                 <th>
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
                                                
                                                <td style="width:100px;">
                                                    <strong><?php echo $row13['ProductName'];?></strong>
                                                </td>
                                                <td >
                                                    <strong><?php echo $result12['Qty'];?></strong>
                                                </td>
                                                <td >
                                                    <strong>₹<?php echo $result12['Price'];?></strong>
                                                </td>
                                                <td >
                                                    <strong>₹<?php echo $result12['GstAmt'];?></strong>
                                                </td>
                                                 <td >
                                                    <strong>₹<?php echo $result12['Total'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            <?php } ?>
                                             <tr>
                                                
                                                <td >
                                                    <strong>SUBTOTAL</strong>
                                                </td>
                                                <td >
                                                    <strong><?php echo $TotQty;?></strong>
                                                </td>
                                                <td >
                                                    <strong>₹<?php echo $TotPrice;?></strong>
                                                </td>
                                                <td >
                                                    <strong>₹<?php echo $TotGst;?></strong>
                                                </td>
                                                 <td >
                                                    <strong>₹<?php echo $row['NetAmount'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td  colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td >
                                                    <strong>TAXABLE<br>AMOUNT</strong>
                                                </td>
                                                 <td >
                                                    <strong>₹<?php echo $row['NetAmount'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td  colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td >
                                                    <strong>CGST @2.5%T</strong>
                                                </td>
                                                 <td >
                                                    <strong>₹0</strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td  colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td >
                                                    <strong>SGST @2.5%</strong>
                                                </td>
                                                 <td >
                                                    <strong>₹0</strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td  colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td >
                                                    <strong>Received Amount </strong>
                                                </td>
                                                 <td >
                                                    <strong>₹<?php echo $row['Advance'];?></strong>
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td  colspan="3">
                                                    <strong> </strong>
                                                </td>
                                                
                                                <td >
                                                    <strong>Balance</strong>
                                                </td>
                                                 <td >
                                                    <strong>₹<?php echo $row['Balance'];?></strong>
                                                </td>
                                                
                                            </tr>
                                                                                                                          
                                                                                   

                                           

                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    
                                </div>
                            </div>
                            <hr>
                            <div style="text-align:right;padding-right:100px;">Total Amount (in words)<br>
                            Thirteen Thousand Rupees</div><br><br>


 <div class="row">
                                    <div class="col-sm-6 "><br><br><br><br><br><br><br><br>
                                        
                                    </div>
                                    <div class="col-sm-5 " style="text-align:right;">
                                        <img width="100px" src="signature.png"><br><br>
                                        <div class="font-weight-bold ">AUTHORISED SIGNATORY FOR<br>
MAHACHAI PRIVATE LIMITED</div>
                                    </div>
                                    
                                    <div class="col-sm-1 " style="text-align:right;">
                                    </div>
                                    
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



<?php include_once 'footer_script.php'; ?>


</body>
</html>
