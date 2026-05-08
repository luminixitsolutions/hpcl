<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
include('../../libs/phpqrcode/qrlib.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Order-QR-Code";
$Page = "Order-QR-Code";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php';
$tempDir = '../../barcodes/'; 
$filename = $BillSoftFrId."_table.png";
$url = "https://kwickfoods.in/barcodeorder/order/".$BillSoftFrId;
$codeContents = $url;
QRcode::png($codeContents, $tempDir.''.$filename, QR_ECLEVEL_L, 5);
$Barcode = $filename;
$sql = "UPDATE tbl_users_bill SET TableQrCode='$Barcode' WHERE id='$BillSoftFrId'";
$conn->query($sql);
?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">


<div class="card" style="max-width: 22rem;">
                            <img class="card-img-top" src="../../barcodes/<?php echo $Barcode;?>" alt="Card image cap">
                            <div class="card-body">
                              
                               <!-- <p class="card-text" style="text-align: center;">https://mahabuddy.com/barcodeorder/order/<?php echo $BillSoftFrId;?></p>-->
                                <div style="text-align: center;">
                               <a href="../../barcodes/<?php echo $Barcode;?>" class="btn btn-primary" download>Download QR Code</a>
                                </div>
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


</body>
</html>
