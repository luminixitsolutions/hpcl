<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Executive";
$Page = "Add-Executive";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Executive Account
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
    
     #loader {
    display: none;
    position: fixed;
    z-index: 9999;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.7);
    text-align: center;
    padding-top: 20%;
    font-size: 24px;
    font-weight: bold;
    color: #333;
}
    </style>
     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

             <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);
$row7['AssignFranchiseDelivery'] = explode(',', $row7['AssignFranchiseDelivery']);
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Executive Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="ajax_files/ajax_executive.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                       
                                        <div class="form-group col-md-4">
    <label class="form-label">Aadhar Card No</label>
    <input type="text" name="AadharNo" class="form-control" id="AadharNo"
        placeholder="Enter 12-digit Aadhar No" maxlength="12" value="<?php echo $row7["AadharNo"]; ?>"
        oninput="checkAadharLength(this.value)">
    <div class="clearfix"></div>
</div>

<input type="hidden" id="ref_id">

<div class="form-group col-md-4">
    <label class="form-label">Aadhar Card OTP</label>
    <input type="text" name="AadharOtp" class="form-control" id="AadharOtp"
        placeholder="Enter 6-digit OTP" maxlength="6"
        oninput="checkOtpLength(this.value)">
    <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
    <label class="form-label">PAN Card No</label>
    <input type="text" name="PanNo" class="form-control" id="PanCardNo"
        placeholder="Enter Pan Card No" value="<?php echo $row7["PanNo"]; ?>" oninput="checkPanCardLength(this.value)">
    <div class="clearfix"></div>
</div>


                                       <div class="form-group col-md-12">
                                            <label class="form-label">Executive Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                      
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                       <!-- <div class="form-group col-md-6">
                                            <label class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="<?php echo $row7["Password"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
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


                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date Of Birth</label>
                                            <input type="date" name="Dob" class="form-control" id="Dob"
                                                placeholder="" value="<?php echo $row7["Dob"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Marriage Anniversary</label>
                                            <input type="date" name="AnniversaryDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["AnniversaryDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                          <div class="form-group col-md-2">
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


                                        <div class="form-group col-md-6">
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


                                     
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea name="Address" id="Address" class="form-control" placeholder="Address"
                                                autocomplete="off" required><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                       


                                        <div class="form-group col-lg-12">
                                            <label class="form-label">Assign Franchise For Delivery <span class="text-danger">*</span></label>
                                     <select class="select2-demo form-control" name="AssignFranchiseDelivery[]" id="AssignFranchiseDelivery[]" multiple>
 <?php  
                                        $sql33 = "SELECT * FROM  tbl_users_bill WHERE Roll=5 AND Status=1";
                                        $row33 = getList($sql33);
                                        foreach($row33 as $result){
                                        ?>
  <option <?php if(in_array($result["id"],$row7['AssignFranchiseDelivery'])) { ?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</optgroup>
</select>
</div>


                                      

                                    </div>
                                    
                                    <div class="form-row">             
<div class="form-group col-md-3">
<label class="form-label">Account No </label>
<input type="text" name="AccountNo" id="AccountNo" class="form-control" placeholder="" value="<?php echo $row7["AccountNo"]; ?>" oninput="getBankDetails()">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">IFSC Code </label>
<input type="text" name="IfscCode" id="IfscCode" class="form-control" placeholder="" value="<?php echo $row7["IfscCode"]; ?>" oninput="getBankDetails()">
<div class="clearfix"></div>
</div>


                                    
                                       <div class="form-group col-md-6">
<label class="form-label">Bank Holder Name </label>
<input type="text" name="AccountName" id="AccountName" class="form-control" placeholder="" value="<?php echo $row7["AccountName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Bank Name </label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>">
<div class="clearfix"></div>
</div>



<div class="form-group col-md-4">
<label class="form-label">Branch </label>
<input type="text" name="Branch" id="Branch" class="form-control" placeholder="" value="<?php echo $row7["Branch"]; ?>">
<div class="clearfix"></div>
</div>



<div class="form-group col-md-4">
<label class="form-label">UPI ID </label>
<input type="text" name="UpiNo" id="UpiNo" class="form-control" placeholder="" value="<?php echo $row7["UpiNo"]; ?>">
<div class="clearfix"></div>
</div>


 


                                    </div> 
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
<div id="loader">Please wait...</div>

    <?php include_once 'footer_script.php'; ?>

 <script type="text/javascript">
    function showLoader() {
    document.getElementById('loader').style.display = 'block';
}

function hideLoader() {
    document.getElementById('loader').style.display = 'none';
}

    function checkAadharLength(value) {
    if (value.length === 12) {
        showLoader();
        sentOtp();
    }
}

function checkOtpLength(value) {
    if (value.length === 6) {
        showLoader();
        otpVerify();
    }
}

function checkPanCardLength(value) {
    if (value.length === 10) {
        showLoader();
        sentPanOtp();
    }
}

function getBankDetails(){
     showLoader();
  var AccountNo = $('#AccountNo').val();
  var IfscCode = $('#IfscCode').val();
   var action = "BankAccountVerify";
   $.ajax({
        url: "ajax_files/ajax_api.php",
        method: "POST",
        data: {
            action: action,
            AccountNo: AccountNo,
            IfscCode:IfscCode
        },
        dataType: "json",
        success: function(data) {
            console.log("Response:", data); 
           hideLoader();
            
            if (data.account_status === 'VALID') {
                 $('#BankName').val(data.bank_name);
                 $('#AccountName').val(data.name_at_bank);
                 $('#Branch').val(data.branch);
                
            } else {
                // alert("Failed to send OTP: " + (data.message || "Unknown error"));
            }
        },
        error: function(xhr, status, error) {
            hideLoader();
            console.error("AJAX Error:", error);
            alert("An error occurred while sending OTP. Please try again.");
        }
    });
}

function sentPanOtp() {
    var PanCardNo = $('#PanCardNo').val();
    var action = "panOtpVerify";

    if (PanCardNo.length !== 10) {
        alert("PAN number must be 10 digits");
        hideLoader();
        return;
    }

    $.ajax({
        url: "ajax_files/ajax_api.php",
        method: "POST",
        data: {
            action: action,
            PanCardNo: PanCardNo
        },
        dataType: "json",
        success: function(data) {
            console.log("Response:", data);
            hideLoader();
            
            if (data.type != 'validation_error') {
                if (data.registered_name) {
                    $('#Fname').val(data.registered_name);
                } else {
                    alert("PAN verified, but no registered name found.");
                }
            } else {
                alert("Failed to send OTP: " + (data.message || "Unknown error"));
            }
        },
        error: function(xhr, status, error) {
            hideLoader();
            console.error("AJAX Error:", error);
            alert("An error occurred while sending OTP. Please try again.");
        }
    });
}


    function sentOtp() {
    var AadharNo = $('#AadharNo').val();
    var action = "sentAadharOtp";
if (AadharNo.length !== 12) {
        alert("Aadhar number must be 12 digits");
        hideLoader();
        return;
    }
    else{
    $.ajax({
        url: "ajax_files/ajax_api.php",
        method: "POST",
        data: {
            action: action,
            AadharNo: AadharNo
        },
         beforeSend: function() {
                        $('#sent_aadhar_otp_verify').attr('disabled', 'disabled');
                        $('#sent_aadhar_otp_verify').text('Please Wait...');
                    },
        dataType: "json",  // ✅ Expect JSON
        success: function(data) {
            console.log(data);  // ✅ Shows the parsed object
            hideLoader();
 $('#sent_aadhar_otp_verify').attr('disabled', false);
                        $('#sent_aadhar_otp_verify').text('SENT OTP');
            if(data.status === 'SUCCESS') {
                $('#ref_id').val(data.ref_id);
                //alert("OTP sent! Ref ID: " + data.ref_id);
            } else {
                alert("Failed to send OTP: " + data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
    }
}

 function otpVerify() {
    var AadharOtp = $('#AadharOtp').val();
    var ref_id = $('#ref_id').val();
    var action = "aadharOtpVerify";
 if (AadharOtp.length !== 6) {
        alert("OTP must be 6 digits");
        hideLoader();
        return;
    }
    else{
    $.ajax({
        url: "ajax_files/ajax_api.php",
        method: "POST",
        data: {
            action: action,
            AadharOtp: AadharOtp,
            ref_id: ref_id
        },
         beforeSend: function() {
                        $('#aadhar_otp_verify').attr('disabled', 'disabled');
                        $('#aadhar_otp_verify').text('Please Wait...');
                    },
        dataType: "json",
        success: function(data) {
            console.log("Response:", data);
            hideLoader();
 $('#aadhar_otp_verify').attr('disabled', false);
                        $('#aadhar_otp_verify').text('OTP Verify');
            if (data.status === "SUCCESS") {
                alert("✅ OTP verified successfully!\nRef ID: " + data.ref_id);
                // You can redirect or update UI here if needed
            } else if (data.status === "ERROR") {
                alert("❌ Verification failed: " + data.message);
            } else {
                $('#Address').val(data.address)
                $('#Fname').val(data.name);
                $('#EmailId').val(data.email);
                if (data.dob) {
    var parts = data.dob.split('-');
    if (parts.length === 3) {
        var formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
        $('#Dob').val(formatted);
    }
}
                $('#NomineeName').val(data.care_of);
                //$('#').val(split_address.pincode);
                //alert("⚠ Unexpected response: " + JSON.stringify(data));
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("❌ AJAX error: " + error);
        }
    });
    }
}

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
                    url: "ajax_files/ajax_executive.php",
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
                                window.location.href = 'view-manufacture.php';
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
                    url: "ajax_files/ajax_executive.php",
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