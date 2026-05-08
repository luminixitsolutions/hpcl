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
$row7['AssignPincode'] = explode(',', $row7['AssignPincode']);
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Distributer Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="ajax_files/ajax_distributer.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                     <fieldset>
 <legend>Personal Detail</legend>
                                    <div class="form-row">
                                       
                                      <div class="form-group col-md-2">
                                            <label class="form-label">Zone <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" required="" onchange="getSubZone(this.value)">
                                                <option selected="" disabled="" value="">Select</option>
                                                <?php $sql = "SELECT * FROM tbl_zone WHERE Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($row7["ZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Vendor <span class="text-danger">*</span></label>
                                            <select class="select2-demo form-control" id="VedId" name="VedId" required="">
                                                <option selected="" disabled="" value="">Select</option>
                                                <?php $sql = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=3";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($row7["VedId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Fname'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                                     
                                       <div class="form-group col-md-6">
                                            <label class="form-label"> Distributer Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Country </label>
                                            <select class="form-control" name="CountryId" id="CountryId">
                                                <option selected="" disabled="">Select Country</option>
                                                <?php 
                                        $q = "select * from tbl_country";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if($row7['CountryId']==$rw['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                            <select class="form-control" id="StateId" name="StateId" required>
                                                <option selected="" disabled="">Select State</option>
                                                <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='$CountryId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                                                <option <?php if($row7['StateId']==$rw['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <select class="form-control" id="CityId" name="CityId" required>
                                                <option selected="" disabled="">Select City</option>
                                                <?php 
 $StateId = $row7['StateId'];
        $q = "select * from tbl_city WHERE StateId='$StateId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                                                <option <?php if($row7['CityId']==$rw['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Office Pincode No <span class="text-danger">*</span></label>
                                            <input type="text" name="Pincode" class="form-control"
                                                placeholder="Pincode No" value="<?php echo $row7["Pincode"]; ?>"
                                                autocomplete="off" required>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        

 <div class="form-group col-md-12">
                                            <label class="form-label"> Address </label>
                                            <textarea name="Address" class="form-control" placeholder="Address"
                                                autocomplete="off"><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Allocate Pincode No <span class="text-danger">*</span></label>
                                            <select class="select2-demo form-control" id="AssignPincode" name="AssignPincode[]" required multiple>
                                             
                                                <?php 
 $CityId = $row7['CityId'];
        $q = "select * from tbl_pincode WHERE Status=1 ORDER BY Pincode ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                                                <option <?php if(in_array($rw["Pincode"],$row7['AssignPincode'])) {?> selected <?php } ?>
                                                    value="<?php echo $rw['Pincode']; ?>"><?php echo $rw['Pincode']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        
                                   
                                        <input type="hidden" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="12345">
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control"
                                                placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                      
                                        
                                         <div class="form-group col-md-3">
                                            <label class="form-label">Email Id <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off" required>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                     

                                        <!--<div class="form-group col-md-8">
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
                                        </div>-->

  <div class="form-group col-md-3">
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
                                        <input type="hidden" Name="Roll" id="Roll" value="166">
 <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                     
                        </form>               

                                       
   

</div>
</fieldset>
        
                                   
          <div id="pageLoader">
    <div class="loaderCenter">
        <div class="loaderBox">
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>

            <div class="shadow"></div>
            <div class="shadow"></div>
            <div class="shadow"></div>
        </div>
    </div>
</div>


<style>
   /* Full-screen blur layer */
#pageLoader {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(6px);
    background: rgba(255, 255, 255, 0.3);
    z-index: 999999;
}

/* Center positioning: FLEXBOX → Perfect Center */
.loaderCenter {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;   /* center horizontally */
    align-items: center;        /* center vertically */
}

/* Loader balls */
.loaderBox {
    position: relative;
    width: 140px;
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.loaderBox .circle {
    width: 25px;
    height: 25px;
    background: #4e8cff;
    border-radius: 50%;
    animation: bounce 0.6s infinite alternate;
}

.loaderBox .circle:nth-child(2) {
    animation-delay: 0.2s;
}
.loaderBox .circle:nth-child(3) {
    animation-delay: 0.4s;
}

/* Shadows under bouncing balls */
.loaderBox .shadow {
    width: 25px;
    height: 5px;
    background: rgba(0,0,0,0.25);
    border-radius: 50%;
    position: absolute;
    bottom: -10px;
    animation: shadow 0.6s infinite alternate;
}

.loaderBox .shadow:nth-child(4) { left: 0; }
.loaderBox .shadow:nth-child(5) { left: 50px; animation-delay: 0.2s; }
.loaderBox .shadow:nth-child(6) { left: 100px; animation-delay: 0.4s; }

/* Keyframes */
@keyframes bounce {
    from { transform: translateY(0); }
    to   { transform: translateY(-35px); }
}

@keyframes shadow {
    from { transform: scale(1); opacity: 0.4; }
    to   { transform: scale(0.6); opacity: 0.1; }
}

</style>
                        

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
    
$(document).ready(function(){

    function calculateGST(prodId){
        let price = parseFloat($("input[name='AssignPrice["+prodId+"]']").val()) || 0;
        let sgst  = parseFloat($("input[name='SgstPer["+prodId+"]']").val()) || 0;
        let cgst  = parseFloat($("input[name='CgstPer["+prodId+"]']").val()) || 0;
        let igst  = parseFloat($("input[name='IgstPer["+prodId+"]']").val()) || 0;

        let gstRate = (sgst + cgst + igst) / 100;

        let withoutGST = (price / (1 + gstRate)).toFixed(2);
        let gstAmt     = (price - withoutGST).toFixed(2);

        $("#WithoutGSTAmt_"+prodId).val(withoutGST);
        $("#GSTAmt_"+prodId).val(gstAmt);
    }

    // Trigger on typing/changing Assign Price or GST%
    $(document).on("keyup change", ".assign-price, .gst-input", function(){
        let prodId = $(this).data("id");
        calculateGST(prodId);
    });

});

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
        
$(document).on("change", "#CityId", function(event) {

    var cityId = this.value;
    var action = "getPincode";

    $("#pageLoader").fadeIn(200);

    $.ajax({
        url: "ajax_files/ajax_dropdown.php",
        method: "POST",
        data: { action: action, id: cityId },

        success: function(data) {

            $('#AssignPincode').html(data);

            $('#AssignPincode option[selected]').prop("selected", true);

            $('#AssignPincode').trigger('change');
        },

        complete: function() {
            $("#pageLoader").fadeOut(200);
        }
    });

});




        
         // When vendor or pincodes change, trigger validation
    $(document).on("change", "#VedId, #AssignPincode", function() {
        validateVendorPincode();
    });

    function validateVendorPincode() {
    var vendorId = $("#VedId").val();
    var id = $('#userid').val();
    var pincodes = $("#AssignPincode").val(); // array of selected pincodes

    if (!vendorId || !pincodes || pincodes.length === 0) {
        return; // nothing to check yet
    }
    //alert(pincodes);
    $.ajax({
        url: "ajax_files/ajax_distributer.php",
        type: "POST",
        data: {
            action: "checkVendorPincode",
            VedId: vendorId,
            AssignPincode: pincodes,
            id:id
        },
        success: function(response) {
            console.log(response);
            response = response.trim();

            if (response.startsWith("exists:")) {
                // Example: exists:440009,440010
                let duplicates = response.split(":")[1].split(",");
                let current = $("#AssignPincode").val() || [];

                // Filter out only duplicates, keep valid pincodes
                let valid = current.filter(p => !duplicates.includes(p));

                $("#AssignPincode").val(valid).trigger('change');

                $.growl.error({
                    title: "Duplicate",
                    message: "Already assigned Pincode(s): " + duplicates.join(", "),
                    location: "tr"
                });
            }
        }
    });
}

    
    });
    </script>
    
</body>

</html>