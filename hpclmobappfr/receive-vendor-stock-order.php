<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Receive Product Stock ";
$UserId = $_SESSION['User']['id']; ?>
<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

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
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>
<style>
    h5, .h5 {
     font-size:15px;   
    }
</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   
<?php

if(isset($_POST['submit'])){
   $CreatedDate = date('Y-m-d');
   $TransferId = $_POST['TransferId'];
      $number = count($_POST['CheckId']);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                 $ProdId = addslashes(trim($_POST['ProdId'][$i]));
                 $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                 $Qty = addslashes(trim($_POST['Qty'][$i]));
                 $Unit = addslashes(trim($_POST['Unit'][$i]));
                 $id = addslashes(trim($_POST['id'][$i]));
                 $PurchasePrice = addslashes(trim($_POST['Price'][$i]));
                 if($ProdType == 0){
                     $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET vedorder='Yes',PurchasePrice='$PurchasePrice',VedInvId='$TransferId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$UserId',StockDate='$CreatedDate',Narration='$Narration',Status='Cr',UserId='$UserId',CreatedDate='$CreatedDate',FrId='$UserId'";
                    $conn->query($qx);
                    $InvId = mysqli_insert_id($conn);
                    $url = $_SERVER['REQUEST_URI'];
                  $createddate = date('Y-m-d H:i:s');
                  $sql = "INSERT INTO tbl_user_logs SET userid='$UserId',frid='$UserId',url='$url',action='receive godown stock',invid='$InvId',createddate='$createddate',roll='receive-godown-stock'";
                  $conn->query($sql);
                    
                 }
                 else{
                     
                     if($Unit!='Pieces'){
                                $Qty2 = ($Qty*1000);
                              
                            }
                            else{
                                $Qty2 = $Qty;
                               
                            }
                            
                            $sql3 = "SELECT * FROM tbl_units WHERE Name2='$Unit'";
                            $row3 = getRecord($sql3);
                            $Unit2 = $row3['Name'];
                            
                      $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET vedorder='Yes',PurchasePrice='$PurchasePrice',VedInvId='$TransferId',ProdId='$ProdId',Qty='$Qty2',Unit='$Unit2',Qty2='$Qty',Unit2='$Unit',CreatedBy='$UserId',StockDate='$CreatedDate',Narration='$Narration',Status='Cr',UserId='$UserId',CreatedDate='$CreatedDate',FrId='$UserId',ProdType=1";
                    $conn->query($qx);
                    $InvId = mysqli_insert_id($conn);
                    $url = $_SERVER['REQUEST_URI'];
                  $createddate = date('Y-m-d H:i:s');
                  $sql = "INSERT INTO tbl_user_logs SET userid='$UserId',frid='$UserId',url='$url',action='receive godown raw stock',invid='$InvId',createddate='$createddate',roll='receive-godown-stock'";
                  $conn->query($sql);
                 }
                
                $sql = "UPDATE tbl_vendor_stock_invoice_items SET Receive=1 WHERE id='$id'";
                $conn->query($sql);
                
                }
              }
            } 
        }


       
         echo "<script>alert('Stock Added Successfully!');window.location.href='view-check-vendor-order-stock.php';</script>";
}
?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
      
        <div class="main-container">
            <div class="container">
                <h5>Receive Product Stock </h5>
                <div class="card">
                 <form id="validation-form" method="post" enctype="multipart/form-data" action="">  
                 <input type="hidden" value="<?php echo $_GET['id']; ?>" name="TransferId">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Product Name</th> 
               <th>Qty</th> 
               <th>Unit</th> 
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $id = $_GET['id'];
             $sql = "SELECT tv.*,tp.ProductName FROM tbl_vendor_stock_invoice_items tv INNER JOIN tbl_cust_products_2025 tp ON tv.prod_id=tp.id WHERE tv.invid='$id' AND tv.prod_type=0";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['Receive']==0){
                    $bgcolor="background-color: #ffff;";
                }
                else{
                    $bgcolor="background-color: #e6fae6;";
                }
                
               
             ?>
            <tr style="<?php echo $bgcolor;?>"> 
                
               <td><?php if($row['Receive']==0){?>
                   <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label> <?php } ?> </td>
                  <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                   <input type="hidden" value="<?php echo $row['prod_id']; ?>" name="ProdId[]">
                   <input type="hidden" value="<?php echo $row['prod_type']; ?>" name="ProdType[]">
                   <input type="hidden" value="<?php echo $row['qty']; ?>" name="Qty[]">
                   <input type="hidden" value="" name="Unit[]">
                   <input type="hidden" value="<?php echo $row['id']; ?>" name="id[]">
                  <input type="hidden" value="0" name="Price[]">
                   
               <td><?php echo $row['ProductName']; ?></td>
               <td><?php echo $row['qty']; ?></td>
             <td></td>
            </tr>
           <?php $i++;} ?>
           
           
            <?php 
            $i=1;
            $id = $_GET['id'];
             $sql = "SELECT tv.*,tp.ProductName FROM tbl_vendor_stock_invoice_items tv INNER JOIN tbl_cust_products2 tp ON tv.prod_id=tp.id WHERE tv.invid='$id' AND tv.prod_type=1";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['Receive']==0){
                    $bgcolor="background-color: #ffff;";
                }
                else{
                    $bgcolor="background-color: #e6fae6;";
                }
                
               
             ?>
            <tr style="<?php echo $bgcolor;?>"> 
                
               <td><?php if($row['Receive']==0){?>
                   <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label> <?php } ?> </td>
                  <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                   <input type="hidden" value="<?php echo $row['prod_id']; ?>" name="ProdId[]">
                   <input type="hidden" value="<?php echo $row['prod_type']; ?>" name="ProdType[]">
                   <input type="hidden" value="<?php echo $row['qty']; ?>" name="Qty[]">
                   <input type="hidden" value="<?php echo $row['unit']; ?>" name="Unit[]">
                   <input type="hidden" value="<?php echo $row['id']; ?>" name="id[]">
                  <input type="hidden" value="0" name="Price[]">
                   
               <td><?php echo $row['ProductName']; ?></td>
               <td><?php echo $row['qty']; ?></td>
             <td><?php echo $row['unit']; ?></td>
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
<div class="form-group col-md-1" style="padding-bottom:100px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</div>
</form>
                </div>
            </div>
        </div>
    </main>

    <!-- footer-->
    
<?php include 'footer.php';?>

    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
    
   <script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
    });
});

  function featured(id,code,frombranch){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }
</script>
</body>

</html>
