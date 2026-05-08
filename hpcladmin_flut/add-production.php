<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Production";
$Page = "View-Production";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Production Account
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
$sql7 = "SELECT * FROM tbl_users_bill WHERE id='$id'";
$row7 = getRecord($sql7);
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Production Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="ajax_files/ajax_production.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                     <fieldset>
 <legend>Personal Detail</legend>
                                    <div class="form-row">
                                       
                                                   
                                       <div class="form-group col-md-12">
                                            <label class="form-label"> Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <!-- <div class="form-group col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="Mname" id="Mname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Mname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                         <div class="form-group col-md-4">
                                            <label class="form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Lname" id="Lname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Lname"]; ?>"
                                                autocomplete="off">
                                        </div>-->

 <div class="form-group col-md-12">
                                            <label class="form-label"> Address </label>
                                            <textarea name="Address" class="form-control" placeholder="Address"
                                                autocomplete="off"><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        
                                     <!-- <div class="form-group col-md-3">
                                            <label class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="<?php echo $row7["Password"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        <input type="hidden" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="12345">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control"
                                                placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                       <!-- <div class="form-group col-md-3">
                                            <label class="form-label"> Father/Mother Contact No</label>
                                            <input type="text" name="FatherPhone" class="form-control"
                                                placeholder="" value="<?php echo $row7["FatherPhone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                         <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <!--<div class="form-group col-md-3">
                                            <label class="form-label">Designation</label>
                                            <input type="text" name="Designation" class="form-control"
                                                placeholder="" value="<?php echo $row7["Designation"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                       <!-- <div class="form-group col-md-3">
                                            <label class="form-label">Date Of Birth</label>
                                            <input type="date" name="Dob" class="form-control"
                                                placeholder="" value="<?php echo $row7["Dob"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                       <!-- <div class="form-group col-md-3">
                                            <label class="form-label">Blood Group</label>
                                            <input type="text" name="BloodGroup" class="form-control"
                                                placeholder="" value="<?php echo $row7["BloodGroup"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                      <!--  <div class="form-group col-md-3">
                                            <label class="form-label">Aadhar Card No</label>
                                            <input type="text" name="AadharNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["AadharNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        

                                            <div class="form-group col-md-3">
                                            <label class="form-label">Date Of Joining</label>
                                            <input type="date" name="JoinDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["JoinDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        -->
                                       
                                        
                                        <!--<div class="form-group col-md-3">
                                            <label class="form-label">Company Email Id </label>
                                            <input type="email" name="EmailId2" id="EmailId2" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId2"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>-->

                                       <!-- <div class="form-group col-md-3">
                                            <label class="form-label">Per Day Salary <span class="text-danger">*</span></label>
                                            <input type="text" name="PerDaySalary" id="PerDaySalary" class="form-control"
                                                placeholder="" value="<?php echo $row7["PerDaySalary"]; ?>"
                                                autocomplete="off" required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-12">
<label class="form-label">Details</label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>-->


                                        <div class="form-group col-md-8">
                                            <label class="form-label">Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['Photo'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

  <div class="form-group col-md-4">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Status" name="Status" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["Status"]=='1') {?> selected
                                                    <?php } ?>>Active</option>
                                                <option value="0" <?php if($row7["Status"]=='0') {?> selected
                                                    <?php } ?>>Inctive</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        

                                     
                                       

                                       
   

</div>

                                   
                                       
<input type="hidden" Name="Roll" id="Roll" value="96">
<div class="row">

                                      
                                     
                                    
                                    
                                   

<!--<div class="form-group col-md-4">
                                            <label class="form-label">Resign </label>
                                            <select class="form-control" id="ResignStatus" name="ResignStatus" required="">
                                              
                                                <option value="0" <?php if($row7["ResignStatus"]=='0') {?> selected
                                                    <?php } ?>>No</option>
                                                <option value="1" <?php if($row7["ResignStatus"]=='1') {?> selected
                                                    <?php } ?>>Yes</option>
                                               
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                          <div class="form-group col-md-4">
                                            <label class="form-label">Resign Date</label>
                                            <input type="date" name="ResignDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ResignDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Resign Comment</label>
                                            <input type="text" name="ResignComment" class="form-control"
                                                placeholder="" value="<?php echo $row7["ResignComment"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->

</div>
                                    <br>
                                   <!-- <div class="row">
                                        <?php  
                                        $sql33 = "SELECT * FROM tbl_options WHERE id NOT IN(10,11,14) AND BillSoft=1";
                                        $row33 = getList($sql33);
                                        foreach($row33 as $result){
                                        ?>
                                        <div class="form-group col-md-4">
                                            <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id'];?>" <?php if(in_array($result["id"],$row7['Options'])) { ?>

                        checked="checked" <?php } ?>>
                                    <span class="custom-control-label"><?php echo $result['Name'];?></span>
                                </label>
                                        </div>
                                    <?php } ?>

                                    <?php  
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(10,11,14)";
                                        $row33 = getList($sql33);
                                        foreach($row33 as $result){
                                        ?>
                                        <div class="form-group col-md-4">
                                            <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id'];?>" <?php if(in_array($result["id"],$row7['Options'])) { ?>

                        checked="checked" <?php } ?>>
                                    <span class="custom-control-label"><?php echo $result['Name'];?></span>
                                </label>
                                        </div>
                                    <?php } ?>
                                    </div>-->
                                    
                                      </fieldset>

<!--<fieldset>
 <legend>Coco Franchise Access</legend>
<div class="form-row">               
                                    
                                    <?php  
                                        $sql33 = "SELECT * FROM  tbl_users_bill WHERE OwnFranchise=1 AND Roll=5";
                                        $row33 = getList($sql33);
                                        foreach($row33 as $result){
                                        ?>
                                        <div class="form-group col-md-6">
                                            <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="CocoFranchiseAccess[]" value="<?php echo $result['id'];?>" <?php if(in_array($result["id"],$row7['CocoFranchiseAccess'])) { ?>

                        checked="checked" <?php } ?>>
                                    <span class="custom-control-label"><?php echo $result['ShopName'];?></span>
                                </label>
                                        </div>
                                    <?php } ?>


 


                                    </div> 
                                     </fieldset>

<fieldset>
 <legend>Bank Account Detail</legend>
<div class="form-row">               
                                    
                                       <div class="form-group col-md-6">
<label class="form-label">Bank Holder Name </label>
<input type="text" name="AccountName" id="AccountName" class="form-control" placeholder="" value="<?php echo $row7["AccountName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Bank Name </label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Account No </label>
<input type="text" name="AccountNo" id="AccountNo" class="form-control" placeholder="" value="<?php echo $row7["AccountNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Branch </label>
<input type="text" name="Branch" id="Branch" class="form-control" placeholder="" value="<?php echo $row7["Branch"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">IFSC Code </label>
<input type="text" name="IfscCode" id="IfscCode" class="form-control" placeholder="" value="<?php echo $row7["IfscCode"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">UPI ID </label>
<input type="text" name="UpiNo" id="UpiNo" class="form-control" placeholder="" value="<?php echo $row7["UpiNo"]; ?>">
<div class="clearfix"></div>
</div>



 


                                    </div> 
                                     </fieldset>-->
                                     
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
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

    <script type="text/javascript">
    function myFunction2() {

        var x = document.getElementById("Password");
        if (x.type === "password") {
            x.type = "text";
            $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
        } else {
            x.type = "password";
            $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
        }
    }

    function error_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.error({
            title: 'Error',
            message: 'Email Id / Phone No Already Exists',
            location: isRtl ? 'tl' : 'tr'
        });
    }

    function success_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.success({
            title: 'Success',
            message: 'Saved Successfully...',
            location: isRtl ? 'tl' : 'tr'
        });
    }
    $(document).ready(function() {
        //$(document).on("click", ".btn-finish", function(event){
        $('#validation-form').on('submit', function(e) {
            exit();
            e.preventDefault();
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "ajax_files/ajax_employee.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {

                        if (data == 0) {
                            error_toast();

                        } else {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'view-employee.php';
                            }, 2000);
                        }
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                    }
                })



            } else {
                //$('#Fname').focus();
                return false;
            }
        });

        $(document).on("click", "#delete_photo", function(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to delete Profile Photo?")) {
                var action = "deletePhoto";
                var id = $('#userid').val();
                var Photo = $('#OldPhoto').val();
                $.ajax({
                    url: "ajax_files/ajax_employee.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: id,
                        Photo: Photo
                    },
                    success: function(data) {

                        $('#show_photo').hide();
                        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr(
                            'dir') === 'rtl';
                        $.growl.success({
                            title: 'Success',
                            message: data,
                            location: isRtl ? 'tl' : 'tr'
                        });

                    }
                });
            }

        });
        $(document).on("change", "#CountryId", function(event) {
            var val = this.value;
            var action = "getState";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#StateId').html(data);
                }
            });

        });

        $(document).on("change", "#StateId", function(event) {
            var val = this.value;
            var action = "getCity";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#CityId').html(data);
                }
            });

        });
    });
    </script>
     <script>
        CKEDITOR.replace( 'editor1');
</script>
</body>

</html>