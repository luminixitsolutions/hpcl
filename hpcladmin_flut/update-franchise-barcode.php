<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "";
$Page = "";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> 
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
      <link rel="stylesheet" href="example/css/slim.min.css">
</head>

<body>
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
   <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
    
    <?php include 'top_header.php';?>
            

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">

                <?php 
                    if(isset($_POST['submit'])){
                        $ProductName = addslashes(trim($_POST["ProductName"]));
                        $BarcodeNo = addslashes(trim($_POST["BarcodeNo"]));
                        $MinQty = addslashes(trim($_POST["MinQty"]));
                        $PurchasePrice = addslashes(trim($_POST["PurchasePrice"]));
                        $sql2 = "SELECT id FROM `tbl_cust_products` WHERE ProductName='$ProductName'";
                            $row2 = getList($sql2);
                            foreach($row2 as $result2){
                                $id = $result2['id'];
                                $sql = "UPDATE tbl_cust_products SET BarcodeNo='$BarcodeNo',MinQty='$MinQty',PurchasePrice='$PurchasePrice',modified_time='2024-10-23 16:47:23.686' WHERE id='$id'";
                                $conn->query($sql);
                            }
                            echo "<script>alert('Barcode Updated Successfully');window.location.href='update-franchise-barcode.php';</script>";
                    }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Update Barcode No</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <div class="row">
                                    <div class="col-lg-6">
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="SavePaymentReceive" id="action">
                                    <div class="form-row">

                                        <div class="form-group col-md-12">
<label class="form-label"> Product<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ProductName" id="ProductName" required>
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT DISTINCT(ProductName) AS ProductName FROM `tbl_cust_products` WHERE ProdType=0 AND BarcodeNo=''";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['ProductName'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


 <div class="form-group col-md-4">
   <label class="form-label">Barcode No </label>
     <input type="text" name="BarcodeNo" id="BarcodeNo" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>



 <div class="form-group col-md-4">
   <label class="form-label">Min Qty </label>
     <input type="text" name="MinQty" id="MinQty" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-4">
   <label class="form-label">Purchase Price </label>
     <input type="text" name="PurchasePrice" id="PurchasePrice" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>



                                    </div> 
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
                            </div>

                           
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