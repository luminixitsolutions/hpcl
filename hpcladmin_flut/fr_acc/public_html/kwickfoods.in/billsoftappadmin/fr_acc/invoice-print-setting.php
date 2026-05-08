<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Print-Setting";
$Page = "Print-Setting"; 
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Add Products</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php 
$sql55 = "SELECT * FROM tbl_users_bill WHERE id='$BillSoftFrId'";
$row55 = getRecord($sql55);

if(isset($_POST['submit'])){
    $ShopName = addslashes(trim($_POST['ShopName']));
    $Address = addslashes(trim($_POST['Address']));
    $Phone = addslashes(trim($_POST['Phone']));
    $GstNo = addslashes(trim($_POST['GstNo']));
    $terms_condition = addslashes(trim($_POST['terms_condition']));
    $bottom_title = addslashes(trim($_POST['bottom_title']));
    $sql = "UPDATE tbl_users_bill SET PrintCompName='$ShopName',Address='$Address',PrintMobNo='$Phone',GstNo='$GstNo',terms_condition='$terms_condition',bottom_title='$bottom_title' WHERE id='$BillSoftFrId'";
    $conn->query($sql);
    $msg = "Setting Updated Successfully!";
    echo "<script>window.location.href='invoice-print-setting.php';</script>";
}
     ?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
    <h4 style="color: #62e262;"><?php echo $msg;?></h4>
<h4 class="font-weight-bold py-3 mb-0">Print Setting</h4>


<form action="" method="POST" enctype="multipart/form-data" autocomplete="off">

<div class="row">
    <div class="col-md">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs nav-responsive-md">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#navs-wc-home">Header</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#navs-wc-profile">Footer</a>
                                            </li>

                                           
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-wc-home">
                                            <div class="card-body">
                                               <div class="form-row">
    
   
<div class="form-group col-md-12">
   <label class="form-label">Comapany Name <span class="text-danger">*</span></label>
     <input type="text" name="ShopName" id="ShopName" class="form-control"
                                                placeholder="" value="<?php echo $row55['PrintCompName'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-12">
   <label class="form-label">Comapany Address <span class="text-danger">*</span></label>
     <textarea name="Address" id="Address" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $row55['Address'];?></textarea>
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-6">
   <label class="form-label">Mobile No <span class="text-danger">*</span></label>
     <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="" value="<?php echo $row55['PrintMobNo'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
  <div class="form-group col-md-6">
   <label class="form-label">GST No <span class="text-danger">*</span></label>
     <input type="text" name="GstNo" id="GstNo" class="form-control"
                                                placeholder="" value="<?php echo $row55['GstNo'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
 </div>

  <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>

                
                                    </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-wc-profile">
                                            <div class="card-body">
                                                <div class="form-row">
    
   

 
 <div class="form-group col-md-12">
   <label class="form-label">Terms & Condition <span class="text-danger">*</span></label>
     <textarea name="terms_condition" id="terms_condition" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $row55['terms_condition'];?></textarea>
    <div class="clearfix"></div>
 </div>
 
<div class="form-group col-md-12">
   <label class="form-label">Bottom Title<span class="text-danger">*</span></label>
     <textarea name="bottom_title" id="bottom_title" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $row55['bottom_title'];?></textarea>
    <div class="clearfix"></div>
 </div>
 
 </div>

  <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>

                
                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
 
                        </div>


</form>


</div>

</div>

<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>
<script>
function status(val){
    if(val == '1'){
        var value = "header";
    }
    if(val == '2'){
        var value = "footer";
    }
    if(val == '3'){
        var value = "logo";
    }
    
        if($('#'+value).prop('checked') == true) {
            $('#'+value).val(1);
        }
        else{
           $('#'+value).val(0);
            }
        }
        
    function print(id){
       var action = "printSetting";
        var title = $('#title').val();
        var address = $('#address').val();
        var mobile = $('#mobile').val();
        var gstin = $('#gstin').val();
        var terms_condition = $('#terms_condition').val();
        var bottom_title = $('#bottom_title').val();
          $.ajax({
                url: "ajax_files/ajax_customer_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id:id,
                    title: title,
                    address:address,
                    mobile:mobile,
                    gstin:gstin,
                    terms_condition:terms_condition,
                    bottom_title:bottom_title
                }, 
                success: function(data) {
                   console.log(data);
                   Android.printReceipt(''+data+'');
                }
            });
       
    }
</script>
</body>
</html>
