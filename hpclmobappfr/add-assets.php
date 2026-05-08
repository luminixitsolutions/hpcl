<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Assets";
$UserId = $_SESSION['User']['id'];
 ?>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include 'back-header.php'; ?>
       
         <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM  tbl_assets WHERE id='$id'";
$row7 = getRecord($sql7);


if(isset($_POST['submit'])){
    $UserId = addslashes(trim($_POST['UserId']));
    $ProductName = addslashes(trim($_POST['ProductName']));
    $Price = addslashes(trim($_POST['Price']));
    $Phone = addslashes(trim($_POST['Phone']));
    $EmailId = addslashes(trim($_POST['EmailId']));
    $Gst = addslashes(trim($_POST['Gst']));
    $ShopName = addslashes(trim($_POST['ShopName']));
    $Address = addslashes(trim($_POST['Address']));
    $PurchaseDate = addslashes(trim($_POST['PurchaseDate']));
    $WarrantyDate = addslashes(trim($_POST['WarrantyDate']));
    $CreatedDate = date('Y-m-d');
    $CreatedDate2 = date('d-M-Y');
    $CreatedBy = $_POST['CreatedBy'];
    $OtherName = addslashes(trim($_POST['OtherName']));
    $randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
	$Photo = $_POST['OldPhoto'];
}
    
    if($_GET['id']==''){
$sql73 = "INSERT INTO tbl_assets SET UserId='$UserId',ProductName='$ProductName',Phone='$Phone',EmailId='$EmailId',Price='$Price',Gst='$Gst',ShopName='$ShopName',Address='$Address',PurchaseDate='$PurchaseDate',CreatedDate='$CreatedDate',Photo='$Photo',CreatedBy='$UserId',WarrantyDate='$WarrantyDate',OtherName='$OtherName'";
   $conn->query($sql73);
?>
 <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Success",
  text: "New Asset Created Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-assets.php";
  }
}); });</script>
<?php
    }
    else{
        $sql73 = "UPDATE tbl_assets SET UserId='$UserId',ProductName='$ProductName',Phone='$Phone',EmailId='$EmailId',Price='$Price',Gst='$Gst',ShopName='$ShopName',Address='$Address',PurchaseDate='$PurchaseDate',ModifiedDate='$CreatedDate',Photo='$Photo',ModifiedBy='$user_id',CreatedBy='$UserId',WarrantyDate='$WarrantyDate',OtherName='$OtherName'";
   $conn->query($sql73);
    ?>
 <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Success",
  text: "Asset Updated Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-assets.php";
  }
}); });</script>
<?php
    }
  }
?>

        <div class="main-container">
            <div class="container">
                <div class="card mb-4">
                  
                  

                       <form id="validation-form" method="post" autocomplete="off">
                          
                    <div class="card-body">
                         <div id="alert_message"></div>
                          <div class="form-row">
                              
                                <div class="form-group col-md-12">
<label class="form-label">Account <span class="text-danger">*</span></label>
  <select class="select2-demo form-control" id="UserId" name="UserId" required="">
      <option selected="" disabled="" value="">Select Account</option>
     
     <?php 
     $sql1 = "SELECT tu.*,tut.Name As AccType FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tu.Roll=tut.id WHERE tu.Status=1 AND tu.Roll=5";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
          
      ?>
    <option <?php if($row7['UserId'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." (".$result['Phone'].") - ".$result['AccType']; ?></option>
<?php } ?>
 

</select>
<div class="clearfix"></div>
</div>

                          <div class="form-group col-md-12">
   <label class="form-label">Product Name </label>
     <input type="text" name="ProductName" id="ProductName" class="form-control"
                                                placeholder="" value="<?php echo $row7["ProductName"]; ?>"
                                                autocomplete="off"  required>
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-6">
                                            <label class="form-label">Product Price </label>
                                            <input type="text" name="Price" id="Price" class="form-control"
                                                placeholder="" value="<?php echo $row7["Price"]; ?>"
                                                autocomplete="off" required>
                                            <div class="clearfix"></div>
                                        </div>
 
<div class="form-group col-lg-6">
<label class="form-label">GST<span class="text-danger">*</span></label>
<select class="form-control" name="Gst" required="">
    <option value="" selected>Select GST</option>
<option value="Yes" <?php if($row7["Gst"]=='Yes') {?> selected <?php } ?>>Yes</option>
<option value="No" <?php if($row7["Gst"]=='No') {?> selected <?php } ?>>No </option>
</select>
</div>
   <div class="form-group col-md-12">
   <label class="form-label">Bill Upload </label>
    <label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto" value="<?php echo $row7["Photo"]; ?>">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
 </div> 
      
       <div class="form-group col-md-12">
                                            <label class="form-label">Shop Name</label>
                                            <input type="text" name="ShopName" class="form-control"
                                                placeholder="" value="<?php echo $row7["ShopName"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                 
                                  <div class="form-group col-md-6">
                                            <label class="form-label">Phone No / Toll Free No</label>
                                            <input type="text" name="Phone" class="form-control"
                                                placeholder="" value="<?php echo $row7["Phone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>       
                                        
                                         <div class="form-group col-md-6">
                                            <label class="form-label">Email Id</label>
                                            <input type="email" name="EmailId" class="form-control"
                                                placeholder="" value="<?php echo $row7["EmailId"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>       



<div class="form-group col-md-12">
<label class="form-label">Address <span class="text-danger">*</span></label>
<textarea name="Address" class="form-control" id="Address" placeholder=""  required><?php echo $row7['Address'];?></textarea>
<div class="clearfix"></div>
</div>



   <div class="form-group col-md-6">
                                            <label class="form-label">Purchase Date</label>
                                            <input type="date" name="PurchaseDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["PurchaseDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                            <label class="form-label">Warranty Date</label>
                                            <input type="date" name="WarrantyDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["WarrantyDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        </div>
                                        
                         
                        
                    </div>
                    
                      <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="SaveHelpSupport" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit">Submit</button>
                    </div>
                </form>
                </div>
                
            </div>

            
        </div>
    </main>

    <!-- footer-->
    

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
   
</body>

</html>
