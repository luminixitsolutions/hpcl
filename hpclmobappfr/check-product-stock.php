<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
$PageName = "My Expenses";
$Page = "Recharge";
$WallMsg = "NotShow"; 
$url = "home.php";?>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>
<style>
    h5, .h5 {
     font-size:15px;   
    }
</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <?php include 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       
<?php include 'top_header.php';?>

        <!-- page content start -->
<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_cash_uses WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="view-cash-uses.php";
    </script>
<?php } ?>

  
        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
               
               
               
               <?php 
               $id = $_GET['id'];
$query = "SELECT * FROM tbl_request_product_stock WHERE id = '$id'";
$row = getRecord($query);

                             $sql77 = "SELECT ttg.*,tgp.ProductName FROM tbl_request_product_stock_items ttg LEFT JOIN tbl_cust_products tgp ON ttg.ProdId=tgp.id WHERE ttg.TransferId='$id'";
                            $row77 = getList($sql77);
                            foreach($row77 as $result){
                                $TotQty+=$result['Qty'];
$TotRate+=$result['Price'];
$TotGst+=$result['GstAmt'];
$TotSGst+=$result['SgstAmt'];
$TotCGst+=$result['CgstAmt'];
                            ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="mb-1"><?php echo $result['ProductName'];?> </h5>
                                        <p class="text-secondary">
                                           Qty : <?php echo $result['Qty']." ".$result['Unit'];?><br>
                                           Price : &#8377;<?php echo $result['Total'];?>
                                       </p>
                                     
                                       
                                        
                                        

                                    </div>
                                    <div class="col-auto pl-0">
                                       
                                          <!--  <a href="add-cash-book.php?id=<?php echo $result['id']; ?>" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-edit"></i></a>-->
                                        <!--<a href="javascript:void(0)" onclick="getExpId(<?php echo $result['id'];?>)"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>-->
                                        <!--<a href="view-cash-uses.php?action=delete&id=<?php echo $result['id'];?>"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>-->
                     
                                      
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                         <?php } ?>

                      <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                <div class="row h6 font-weight-bold">
                    <div class="col">Total Qty</div>
                    <div class="col text-right text-mute"><?php echo $TotQty;?></div>
                </div>
                   <div class="row h6 font-weight-bold">
                    <div class="col">Taxable Amount</div>
                    <div class="col text-right text-mute">₹<?php echo $row['TotalAmount']-$TotGst;?></div>
                </div>
                <div class="row h6 font-weight-bold">
                    <div class="col">CGST @2.5%</div>
                    <div class="col text-right text-mute">₹<?php echo $TotCGst;?></div>
                </div>
                <div class="row h6 font-weight-bold">
                    <div class="col">SGST @2.5%</div>
                    <div class="col text-right text-mute">₹<?php echo $TotSGst;?></div>
                </div>
                <div class="row h6 font-weight-bold">
                    <div class="col">Total Amount</div>
                    <div class="col text-right text-mute">₹<?php echo $row['TotalAmount'];?></div>
                </div>
               

                            </div>
                        </div>
                    </div>
 </div>
                   <?php 
                    if(isset($_POST['submit'])){
                        $CreatedDate = date('Y-m-d H:i:s');
                        $ApproveDate = $_POST['ApproveDate'];
                        $ApproveTime = date('h:i a');

                        $randno = rand(1,100);
                        $src = $_FILES['Photo1']['tmp_name'];
                        $fnm = substr($_FILES["Photo1"]["name"], 0,strrpos($_FILES["Photo1"]["name"],'.')); 
                        $fnm = str_replace(" ","_",$fnm);
                        $ext = substr($_FILES["Photo1"]["name"],strpos($_FILES["Photo1"]["name"],"."));
                        $dest = '../uploads/'. $randno . "_".$fnm . $ext;
                        $imagepath =  $randno . "_".$fnm . $ext;
                        if(move_uploaded_file($src, $dest))
                        {
                        $Photo1 = $imagepath ;
                        } 
                        else{
                          $Photo1 = $_POST['OldPhoto1'];
                        }

                        $randno2 = rand(1,100);
                        $src2 = $_FILES['Photo2']['tmp_name'];
                        $fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
                        $fnm2 = str_replace(" ","_",$fnm2);
                        $ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
                        $dest2 = '../uploads/'. $randno2 . "_".$fnm2. $ext2;
                        $imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
                        if(move_uploaded_file($src2, $dest2))
                        {
                        $Photo2 = $imagepath2 ;
                        } 
                        else{
                          $Photo2 = $_POST['OldPhoto2'];
                        }

                        $randno3 = rand(1,100);
                        $src3 = $_FILES['Photo3']['tmp_name'];
                        $fnm3 = substr($_FILES["Photo3"]["name"], 0,strrpos($_FILES["Photo3"]["name"],'.')); 
                        $fnm3 = str_replace(" ","_",$fnm3);
                        $ext3 = substr($_FILES["Photo3"]["name"],strpos($_FILES["Photo3"]["name"],"."));
                        $dest3 = '../uploads/'. $randno3 . "_".$fnm3 . $ext3;
                        $imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
                        if(move_uploaded_file($src3, $dest3))
                        {
                        $Photo3 = $imagepath3 ;
                        } 
                        else{
                          $Photo3 = $_POST['OldPhoto3'];
                        }

                        $sql = "UPDATE tbl_request_product_stock SET ApproveBy='$FranchiseId',ApproveDate='$ApproveDate',ApproveTime='$ApproveTime',ApproveStatus=1,Photo1='$Photo1',Photo2='$Photo2',Photo3='$Photo3' WHERE id='$id'";
                        $conn->query($sql);

                        $sql = "UPDATE tbl_request_product_stock_items SET ApproveBy='$FranchiseId',ApproveDate='$CreatedDate',Receive=1 WHERE TransferId='$id'";
                        $conn->query($sql);

                        $sql = "SELECT * FROM tbl_request_product_stock_items WHERE TransferId='$id'";
                        $row = getList($sql);
                        foreach($row as $result){
                             $sql22 = "INSERT INTO tbl_cust_prod_stock SET UserId='".$result['FromFrId']."',ProdId='".$result['ProdId']."',Qty='".$result['Qty']."',Unit='".$result['Unit']."',CreatedDate='$ApproveDate',TransferId='$id',Status='Cr',Narration='Stock Added',StockDate='$ApproveDate',CreatedBy='$FranchiseId',FrId='".$result['FromFrId']."'";
                                $conn->query($sql22);
                        }
                       

echo "<script>alert('Stock Approved Successfully!');window.location.href='view-request-product-stock.php';</script>";
                    }
                   ?>
                   
                    <div class="card mb-4">
            <form method="post" enctype="multipart/form-data">   
            <div class="card-body">
<div class="form-row">
<div class="form-group col-md-12">
<label class="form-label">Approve Date <span class="text-danger">*</span></label>
<input type="date" name="ApproveDate" id="ApproveDate" class="form-control" placeholder="" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-12">
<label class="form-label">Upload Receipt 1 </label>
<input type="file" name="Photo1" class="form-control">
<div class="clearfix"></div>
    </div>

     <div class="form-group col-md-12">
<label class="form-label">Upload Receipt 2 </label>
<input type="file" name="Photo2" class="form-control">
<div class="clearfix"></div>
    </div>

     <div class="form-group col-md-12">
<label class="form-label">Upload Receipt 3 </label>
<input type="file" name="Photo3" class="form-control">
<div class="clearfix"></div>
    </div>

    </div>

<div align="center">
                     <button type="submit" name="submit" class="btn btn-sm btn-default rounded"> Approve</button>
                     </div>
                 </div>
                    </form>
                    </div>

                        
                     
                 

            </div>
        </div>
    </main>

<?php include 'footer.php';?>
  <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>
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
<script>
    function getExpId(id){
        $('#myModal').modal('show');
        $('.Exp_Id').val(id);
    }
</script>
   
</body>

</html>
