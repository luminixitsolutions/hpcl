<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Receive Franchise Stock ";
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   
<?php

if(isset($_POST['submit'])){
   $CreatedDate = date('Y-m-d');
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
                 if($ProdType == 0){
                     $qx = "INSERT INTO tbl_cust_prod_stock SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$CreatedDate',Narration='Stock Received',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId'";
                    $conn->query($qx);
                    $InvId = mysqli_insert_id($conn);
                    $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='receive franchise stock',invid='$InvId',createddate='$createddate',roll='receive-franchise-stock'";
  $conn->query($sql);
                    
                 }
                 else{
                      $qx = "INSERT INTO tbl_fr_raw_stock SET FrId='$BillSoftFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',StockDate='$CreatedDate',Narration='Stock Received',Status='Cr',CreatedDate='$CreatedDate'";
                    $conn->query($qx);
                    $InvId = mysqli_insert_id($conn);
                    $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='receive franchise raw stock',invid='$InvId',createddate='$createddate',roll='receive-franchise-stock'";
  $conn->query($sql);
                 }
                
                $sql = "UPDATE tbl_transfer_franchise_prod_stock_items SET Receive=1 WHERE id='$id'";
                $conn->query($sql);
                
                }
              }
            } 
        }


       
         echo "<script>alert('Stock Added Successfully!');window.location.href='view-receive-franchise-stock.php';</script>";
}
?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
      
        <div class="main-container">
            <div class="container">
                <h5>Receive Franchise Stock </h5>
                <div class="card">
                 <form id="validation-form" method="post" enctype="multipart/form-data" action="">   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Product Name</th> 
               <th>Qty</th> 
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $id = $_GET['id'];
            $sql = "SELECT ttg.*,tcp.ProductName,tcp.ProdType FROM tbl_transfer_franchise_prod_stock_items ttg 
                    LEFT JOIN tbl_cust_products tcp ON tcp.id=ttg.FrProdId WHERE ttg.TransferId='$id'";
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
                   <input type="hidden" value="<?php echo $row['FrProdId']; ?>" name="ProdId[]">
                   <input type="hidden" value="<?php echo $row['ProdType']; ?>" name="ProdType[]">
                   <input type="hidden" value="<?php echo $row['Qty']; ?>" name="Qty[]">
                   <input type="hidden" value="<?php echo $row['Unit']; ?>" name="Unit[]">
                   <input type="hidden" value="<?php echo $row['id']; ?>" name="id[]">
               <td><?php echo $row['ProductName']; ?></td>
               <td><?php echo $row['Qty']." ".$row['Unit']; ?></td>
             
             
           
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</div>
</form>

                </div>
            </div>
        </div>
    </main>
<br><br><br><br>
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
