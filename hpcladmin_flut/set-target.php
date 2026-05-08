<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Target-Complete";
$Page = "Set-Target";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Raw Stock
    </title>
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

            <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM  tbl_set_target WHERE id='$id'";
$row7 = getRecord($sql7);


if(isset($_POST['submit'])){
    $frid= addslashes(trim($_POST['frid']));
    $month = addslashes(trim($_POST['month']));
    $year = addslashes(trim($_POST['year']));
    $target = addslashes(trim($_POST['target']));
    $qsrkitchen_target = addslashes(trim($_POST['qsrkitchen_target']));
    $packfood_target = addslashes(trim($_POST['packfood_target']));
    $cross_sale_target = addslashes(trim($_POST['cross_sale_target']));
    $CreatedDate = date('Y-m-d H:i:s');
    
    if($_GET['id']==''){
        $sql = "SELECT * FROM tbl_set_target WHERE frid='$frid' AND month='$month' AND year='$year'";
        $rncnt = getRow($sql);
        if($rncnt > 0){
            echo "<script>alert('This Month Target Already Exists');window.location.href='set-target.php';</script>";
        }
        else{
$sql73 = "INSERT INTO tbl_set_target SET frid='$frid',month='$month',year='$year',target='$target',qsrkitchen_target='$qsrkitchen_target',packfood_target='$packfood_target',cross_sale_target='$cross_sale_target',createddate='$CreatedDate',createdby='$user_id'";
   $conn->query($sql73);

  echo "<script>alert('New Target SET Successfully!');window.location.href='view-set-target.php';</script>";
        }
    }
    else{
        $sql = "SELECT * FROM tbl_set_target WHERE frid='$frid' AND month='$month' AND year='$year' AND id!='$id'";
        $rncnt = getRow($sql);
        if($rncnt > 0){
            echo "<script>alert('This Month Target Already Exists');window.location.href='set-target.php?id=$id';</script>";
        }
        else{
        $sql73 = "UPDATE tbl_set_target SET frid='$frid',month='$month',year='$year',target='$target',qsrkitchen_target='$qsrkitchen_target',packfood_target='$packfood_target',cross_sale_target='$cross_sale_target',modifieddate='$CreatedDate',modifiedby='$user_id' WHERE id='$id'";
   $conn->query($sql73);
     echo "<script>alert('Target Updated Successfully!');window.location.href='view-set-target.php';</script>";
        }
    }
  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Set
                            <?php } ?> Target</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     

                                  <div class="form-group col-md-12">
<label class="form-label">Franchise <span class="text-danger">*</span></label>
  <select class="select2-demo form-control" id="frid" name="frid" required="">
      <option selected="" disabled="" value="">Select Franchise</option>
     
     <?php 
     $sql1 = "SELECT * FROM tbl_users_bill WHERE Roll=5";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
          
      ?>
    <option <?php if($row7['frid'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']." (".$result['Phone'].")"; ?></option>
<?php } ?>
 

</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Month <span class="text-danger">*</span></label>
<select class="form-control" style="width: 100%" name="month" id="month" required>
<option <?php if($row7['month'] == date('m')){?> selected <?php } ?> value="<?php echo date('m');?>"><?php echo date('M');?></option>
<!--<option <?php if($row7['month'] == '02'){?> selected <?php } ?> value="02">Feb</option>
<option <?php if($row7['month'] == '03'){?> selected <?php } ?> value="03">Mar</option>
<option <?php if($row7['month'] == '04'){?> selected <?php } ?> value="04">Apr</option>
<option <?php if($row7['month'] == '05'){?> selected <?php } ?> value="05">May</option>
<option <?php if($row7['month'] == '06'){?> selected <?php } ?> value="06">Jun</option>
<option <?php if($row7['month'] == '07'){?> selected <?php } ?> value="07">Jul</option>
<option <?php if($row7['month'] == '08'){?> selected <?php } ?> value="08">Aug</option>
<option <?php if($row7['month'] == '09'){?> selected <?php } ?> value="09">Sep</option>
<option <?php if($row7['month'] == '10'){?> selected <?php } ?> value="10">Oct</option>
<option <?php if($row7['month'] == '11'){?> selected <?php } ?> value="11">Nov</option>
<option <?php if($row7['month'] == '12'){?> selected <?php } ?> value="12">Dec</option>-->
  </select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Year <span class="text-danger">*</span></label>
<select class="form-control" style="width: 100%" name="year" id="year" required>
    <option <?php if($row7['year'] == '2025'){?> selected <?php } ?> value="2025">2025</option>
    <option <?php if($row7['year'] == '2024'){?> selected <?php } ?> value="2024">2024</option>
  </select>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-4">
   <label class="form-label">Target Amount <span class="text-danger">*</span></label>
     <input type="text" name="target" id="target" class="form-control"
                                                placeholder="" value="<?php echo $row7["target"]; ?>"
                                                autocomplete="off"  required>
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">QSR KITCHEN SALES (%) <span class="text-danger">*</span></label>
     <input type="text" name="qsrkitchen_target" id="qsrkitchen_target" class="form-control"
                                                placeholder="" value="<?php echo $row7["qsrkitchen_target"]; ?>"
                                                autocomplete="off"  required>
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-4">
   <label class="form-label">PACK FOOD SALES (%) <span class="text-danger">*</span></label>
     <input type="text" name="packfood_target" id="packfood_target" class="form-control"
                                                placeholder="" value="<?php echo $row7["packfood_target"]; ?>"
                                                autocomplete="off"  required>
    <div class="clearfix"></div>
 </div>
 
  <div class="form-group col-md-4">
   <label class="form-label">CROSS SALES (Qty) <span class="text-danger">*</span></label>
     <input type="text" name="cross_sale_target" id="cross_sale_target" class="form-control"
                                                placeholder="" value="<?php echo $row7["cross_sale_target"]; ?>"
                                                autocomplete="off"  required>
    <div class="clearfix"></div>
 </div>


</div>
                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                
                                    </div>
                               </div>


                                

 </div>
 </form>





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

 <script>
       $(document).ready(function() {
     $(document).on("change", "#UserId", function(event) {
            var val = this.value;
            var action = "getUserDetails";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    $('#EmailId').val(data.EmailId);
                    $('#Name').val(data.Fname);
                    $('#Phone').val(data.Phone);
                    
                }
            });

        });
       });
 </script>
</body>

</html>