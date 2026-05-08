<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "Assign-Franchise-Zone";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="../ckeditor/ckeditor.js"></script>
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

    fieldset legend {
        background: inherit;
        font-family: "Lato", sans-serif;
        color: #650812;
        font-size: 15px;
        left: 10px;
        padding: 0 10px;
        position: absolute;
        top: -12px;
        font-weight: 400;
        width: auto !important;
        border: none !important;
    }

    fieldset {
        background: #ffffff;
        border: 1px solid #4FAFB8;
        border-radius: 5px;
        margin: 20px 0 1px 0;
        padding: 20px;
        position: relative;
    }
    </style>
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

 <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_assign_fr_to_zone WHERE id='$id'";
$row7 = getRecord($sql7);
$row7['frids'] = explode(',', $row7['frids']);

if(isset($_POST['submit'])){
  $zone = addslashes(trim($_POST['zone']));
  $frids = implode(",",$_POST['frids']);
  $CreatedDate = date('Y-m-d H:i:s');


  if($_GET['id'] == ''){
    $sql = "INSERT INTO tbl_assign_fr_to_zone SET zone='$zone',frids='$frids',createddate='$CreatedDate',createdby='$user_id'";
    $conn->query($sql);
    echo "<script>alert('Assign Franchise Successfully!');window.location.href='view-assign-franchise-to-zone.php';</script>";
  }
  else{
     $sql = "UPDATE tbl_assign_fr_to_zone SET zone='$zone',frids='$frids',modifieddate='$CreatedDate',modifiedby='$user_id' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Record Updated Successfully!');window.location.href='view-assign-franchise-to-zone.php';</script>";
  }

}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Dealer To Zone</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
<input type="hidden" name="action" value="Save" id="action">

<div class="form-group col-lg-12">
<label class="form-label">Zone <span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="zone" id="zone" required>
<option selected="" value="" disbaled>Select Zone</option>

 <?php 
  $sql12 = "SELECT * FROM tbl_zone";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["zone"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name'];?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
</div>
<fieldset>
 <legend>Dealer Access</legend>
<div class="form-row">               
                                    
                                    <?php  
                                        $sql33 = "SELECT * FROM  tbl_users_bill WHERE OwnFranchise=1 AND Roll=5";
                                        $row33 = getList($sql33);
                                        foreach($row33 as $result){
                                        ?>
                                        <div class="form-group col-md-6">
                                            <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="frids[]" value="<?php echo $result['id'];?>" <?php if(in_array($result["id"],$row7['frids'])) { ?>

                        checked="checked" <?php } ?>>
                                    <span class="custom-control-label"><?php echo $result['ShopName'];?></span>
                                </label>
                                        </div>
                                    <?php } ?>


 


                                    </div>  
                                     </fieldset>

<br>
<div class="form-row">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
        CKEDITOR.replace( 'editor1');
</script>
<script type="text/javascript">
function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Saved Successfully...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
   $(document).ready(function(){
  


$(document).on("click", "#delete_photo2", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Document?"))  
           {  
             var action = "deleteDoc";
             var id = $('#userid').val();
             var Photo = $('#OldFile').val();
             $.ajax({
    url:"ajax_files/ajax_news.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {

      $('#show_photo2').hide();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });


  
});
</script>
</body>
</html>