<?php 
session_start();
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title>
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">


<link rel="stylesheet" href="assets/css/bootstrap-material.css">
<link rel="stylesheet" href="assets/css/shreerang-material.css">
<link rel="stylesheet" href="assets/css/uikit.css">




</head>

<body>

<style type="text/css">
    @media print {
  .printPageButton {
    display: none;
  }

}


tr {
     border:1px solid #000;
}

p {
  margin-top: 0;
  margin-bottom: 0px;
  font-size:16px; 
}
body {
    background: #fff;
}

</style>
<script>
    window.print();
</script>

<?php 
$id = $_GET['id'];
$query = "SELECT * FROM tbl_transfer_godown_raw_prod_stock WHERE id = '$id'";
$row = getRecord($query);
//$number = $row['Amount'];
//include_once 'convert_currancy.php';
  
$sql7 = "SELECT * FROM tbl_users_bill WHERE id='".$row['FranchiseId']."'";
$row7 = getRecord($sql7);
  
 ?>
 
     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            

            <div class="layout-container">

                
              
                    
  <div class="layout-content">
     <div class="container-fluid flex-grow-1 container-p-y">

<table width="100%">
<tbody>
<tr>
<td colspan="7" width="281" style="text-align:center;">
<img src="logo.jpg" width="200px" style="float:left;"><p style="font-size:30px;"><strong>MAHACHAI PRIVATE LIMITED</strong></p>

<p><strong>Plot No, 3, Jetwan Society Rd, Agne Layout, Shastri Layout, Khamla, Nagpur, Maharashtra 440025</strong></p>
<p><strong>Mobile. 9730445152</strong></p>
<p><strong>E-Mail : info@mahabuddy.com</strong></a></p>

</td>

</tr>
<tr>
<td colspan="8" width="281" style="text-align:center; background-color:#b3ffff;">
<p><strong>INVOICE</strong></p>
</td>

</tr>
<tr>
<td colspan="6"  >
<p><strong><?php echo $row7['ShopName'];?> </strong><strong></p>
<p><strong>Address : <?php echo $row7['Address'];?> </strong></p>
<p><strong>MOBILE NO.: <?php echo $row7['Phone'];?></strong></p>



</td>

<td colspan="3" >
    <p><strong>Invoice No. : <?php echo $row['InvoiceNo'];?> </strong></p>
     <p><strong>Invoice Date : <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></strong></p>


</td>


</tr>


<tr style="background-color:#b3ffff;">
<td width="40">
<p><strong>SR NO</strong></p>
</td>
<td width="107">
<p><strong>DESCRIPTION</strong></p>
</td>
<td width="26">
<p><strong>RATE</strong></p>
</td>
<td width="15">
<p><strong>QTY</strong></p>
</td>
<td width="23">
<p><strong></strong></p>
</td>
<td width="28">
<p><strong></strong></p>
</td>
<td width="28">
<p><strong></strong></p>
</td>
<td width="28">
<p><strong>Amount</strong></p>
</td>

</tr>


<?php 
$i=1;
$sql44 = "SELECT ttg.*,tgp.ProductName FROM tbl_transfer_godown_raw_stock_items ttg LEFT JOIN tbl_godown_products tgp ON ttg.ProdId=tgp.id WHERE ttg.TransferId='$id'";
$row44 = getList($sql44);
foreach($row44 as $result){?>
 <tr>
<td width="14">
<p style="text-align:center;"><?php echo $i;?></p>
</td>
<td width="107">
<p><?php echo $result['ProductName'];?></p>
</td>

<td width="26">
<p>₹<?php echo $result['Price'];?></p>
</td>
<td width="15">
<p><?php echo $result['Qty'];?></p>
</td>
<td width="23">
<p></p>
</td>
<td width="28">
<p></p>
</td>
<td width="40">
<p></p>
</td>
<td  width="28">
<p>₹<?php echo $result['TotalPrice'];?></p>
</td>

</tr>

<?php } ?>



<tr>
<td colspan="6"></td>    
<td>
<p> <strong>GRAND TOTAL </strong></p>
</td>
<td>
<p><strong>₹<?php echo number_format($row['TotalAmount'],2);?></strong></p>
</td>
</tr>



<tr>

<td colspan="6" width="121"></td>
<td colspan="4" width="161">
<p><strong>For MAHACHAI PRIVATE LIMITED</strong></p><br><br>
<p><strong>Authorised signatory</strong></p>
</td>

</tr>

</tbody>
</table>
         
                       
                        </div>

                    </div>
                    


<nav class="layout-footer footer footer-light">
<div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
<div class="pt-3">
<span class="float-md-right d-none d-lg-block">&copy; Mobile Software <i class="fas fa-heart text-danger mr-2"></i></span>
</div>

</div>
</nav>

             

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>

   </div>
  

     
</body>

</html>