<?php 
session_start();
include_once 'config.php';
//include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Customers";
$Page = "Add-Customers";

// GET parameters
$uid  = $_GET['user_id'] ?? '';
$lat  = $_GET['lat'] ?? '';
$long = $_GET['lng'] ?? '';
$test = $_GET['test'] ?? '';

/* ==============================
   USER FETCH LOGIC (DO NOT REMOVE)
============================== */

if ($uid == '') {
    // Use session user
    if ($user_id != '') {
        $sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
        $row   = getRecord($sql11);
        $_SESSION['Admin'] = $row;
    } else {
        // Safety fallback (should not happen normally)
        $row = [];
    }
} else {
    // Use GET user_id
    $sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
    $row   = getRecord($sql11);
    $_SESSION['Admin'] = $row;
}

//echo $sql11;

/* ==============================
   FINAL VARIABLES
============================== */

// Roll (safe)
$Roll = $row['Roll'] ?? '';

// Display user id (IMPORTANT)
$displayUserId = $uid != '' ? $uid : ($row['id'] ?? '');

// Optional: persist lat/lng in session if required later
if ($lat !== '') {
    $_SESSION['Admin']['lat'] = $lat;
}
if ($long !== '') {
    $_SESSION['Admin']['lng'] = $long;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Customer Account
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
$row7['ZomatoSwiggy'] = explode(',', $row7['ZomatoSwiggy']);

$sql71 = "SELECT menu_ids,submenuid FROM tbl_users_bill WHERE id='$id'";
$row71 = getRecord($sql71);
$row71['menu_ids'] = explode(',', $row71['menu_ids']);
$row71['submenuid'] = explode(',', $row71['submenuid']);
if($_GET['id'] == ''){
    $PrintCompName = "HAPPY SHOP";
    $PrintMobNo = "8007885000";
    $terms_condition = "Happy Journey Visit Again";
    $bottom_title = "Powered by KWICK BILL (For Bill Machine and Software please  call : 8149693719)";
}
else{
    $PrintCompName = $row7['PrintCompName'];
    $PrintMobNo = $row7['PrintMobNo'];
    $terms_condition = $row7['terms_condition'];
    $bottom_title = $row7['bottom_title'];
}

if($_REQUEST["action"]=="deletelink")
{
  $id = $_REQUEST["id"];
  $pid = $_REQUEST["leadid"];
  $sql11 = "DELETE FROM tbl_stud_docs WHERE id = '$id'";
  $conn->query($sql11);
?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
       window.location.href="add-customer.php?id=<?php echo $pid;?>";
    </script>
<?php }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Dealer Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                      <fieldset>
 <legend>Dealer Detail</legend>
 
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
                                        
                                          <div class="form-group col-md-2">
                                            <label class="form-label">Sub Zone <span class="text-danger">*</span></label>
                                            <select class="form-control" id="SubZoneId" name="SubZoneId" required="">
                                                <option selected="" disabled="" value="">Select</option>
                                                <?php $sql = "SELECT * FROM tbl_sub_zone WHERE Status=1 AND CatId='".$row7["ZoneId"]."'";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($row7["SubZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Dealer Code <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="CustomerId" id="CustomerId" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustomerId"]; ?>" required
                                                autocomplete="off">
                                        </div>
                                        
                                       <div class="form-group col-md-3">
                                            <label class="form-label">Dealer/NFB Partner Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Shop Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="ShopName" id="ShopName" class="form-control"
                                                placeholder="" value="<?php echo $row7["ShopName"]; ?>" required
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

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <!--<div class="form-group col-md-6">
                                            <label class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="<?php echo $row7["Password"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        <input type="hidden" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="12345">
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>"  >
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control"
                                                placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Shop Location </label>
                                            <input type="text" name="Location" class="form-control"
                                                placeholder="" value="<?php echo $row7["Location"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-lg-12">
<label class="form-label">Details </label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>


                                       <!-- <div class="form-group col-md-12">
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
    <label class="form-label">Country <span class="text-danger">*</span></label>
<select class="form-control" id="CountryId" name="CountryId" required="">
<!--<option selected="" disabled="">Select Country</option>-->
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_country WHERE id='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['CountryId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
  
 <div class="form-group col-md-3">
    <label class="form-label">State <span class="text-danger">*</span></label>
<select class="form-control" id="StateId" name="StateId" required="">
<option selected="" disabled="">Select State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
  
  
   <div class="form-group col-md-3">
    <label class="form-label">City <span class="text-danger">*</span></label>
<select class="form-control" id="CityId" name="CityId" required="">
<option selected="" disabled="">Select City</option>
 <?php 
        $StateId = $row7['StateId'];
        $q = "select * from tbl_city WHERE StateId='$StateId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['CityId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>
  
   <div class="form-group col-md-3">
                                            <label class="form-label"> Pincode <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Pincode" id="Pincode" class="form-control"
                                                placeholder="" value="<?php echo $row7["Pincode"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                     
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea name="Address" class="form-control" placeholder="Address"
                                                autocomplete="off" required><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                       
                                        <!--<div class="form-group col-md-3">
<label class="form-label"> Sell By<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ExeId" id="ExeId" required>
<option selected="" value="0">Own/Mahachai</option>

<optgroup label="Business Devlopment Manager">
<?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(9)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>

<optgroup label="Area Manager">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(22)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>

<optgroup label="Regional Manager">
<?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(23)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>
</select>
<div class="clearfix"></div>
</div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Sell Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="SellAmt" id="SellAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["SellAmt"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Sell Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="SellDate" id="SellDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SellDate"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>-->
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Dealer Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="OwnFranchise" name="OwnFranchise" required="">
                                                <!--<option selected="" disabled="" value="">Select</option>-->
                                                
                                                <?php 
  $sql12 = "SELECT * FROM tbl_fr_model WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["OwnFranchise"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>

                                                     
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <!--<div class="form-group col-md-3">
                                            <label class="form-label">Franchise Development Cost </label>
                                            <input type="text" name="FrDevCost" id="FrDevCost" class="form-control"
                                                placeholder="" value="<?php echo $row7["FrDevCost"]; ?>" >
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Monthly Rent</label>
                                            <input type="text" name="MonthlyRent" id="MonthlyRent" class="form-control"
                                                placeholder="" value="<?php echo $row7["MonthlyRent"]; ?>" >
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <!--<div class="form-group col-md-3">
                                            <label class="form-label">Pump name / Pump code / Pump dealer code </label>
                                            <input type="text" name="PumpName" id="PumpName" class="form-control"
                                                placeholder="" value="<?php echo $row7["PumpName"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                        <!--<div class="form-group col-md-3">
                                            <label class="form-label">Space Partner </label>
                                            <select class="form-control" id="SpacePartner" name="SpacePartner" >
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="IOCL" <?php if($row7["SpacePartner"]=='IOCL') {?> selected
                                                    <?php } ?>>IOCL</option>
                                                    <option value="BPCL" <?php if($row7["SpacePartner"]=='BPCL') {?> selected
                                                    <?php } ?>>BPCL</option>
                                                <option value="HPCL" <?php if($row7["SpacePartner"]=='HPCL') {?> selected
                                                    <?php } ?>>HPCL</option>
                                                    <option value="OTHER" <?php if($row7["SpacePartner"]=='OTHER') {?> selected
                                                    <?php } ?>>OTHER</option>
                                                     
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>-->
                                        

<div class="form-group col-md-3">
<label class="form-label">Lattitude</label>
<input type="text" name="Lattitude" id="Lattitude" class="form-control" placeholder="" value="<?php echo $row7["Lattitude"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="Longitude" id="Longitude" class="form-control" placeholder="" value="<?php echo $row7["Longitude"]; ?>">
<div class="clearfix"></div>
</div>

<!--<div class="form-group col-md-3">
    <label class="form-label">Menu <span class="text-danger">*</span></label>
<select class="form-control" id="MenuId" name="MenuId" required="">
<option selected="" disabled="">Select Menu</option>
 <?php 
      
        $q = "select * from tbl_mh_menu WHERE Status='1' ORDER BY name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['MenuId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['name']; ?></option>
              <?php } ?>
</select>
  </div>-->
  
  <!-- <div class="form-group col-md-3">
                                            <label class="form-label">New Dealer </label>
                                            <select class="form-control" id="NewFr" name="NewFr" >

                                                <option value="New" <?php if($row7["NewFr"]=='New') {?> selected
                                                    <?php } ?>>New</option>
                                                    <option value="Old" <?php if($row7["NewFr"]=='Old') {?> selected
                                                    <?php } ?>>Old</option>
                                              
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
  
  <!-- <div class="form-group col-md-3">
<label class="form-label">Aliance Partner Name</label>
<input type="text" name="AlianceName" id="AlianceName" class="form-control" placeholder="" value="<?php echo $row7["AlianceName"]; ?>">
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label">Aliance Partner Phone</label>
<input type="text" name="AliancePhone" id="AliancePhone" class="form-control" placeholder="" value="<?php echo $row7["AliancePhone"]; ?>">
<div class="clearfix"></div>
</div>
                                      <div class="form-group col-md-3">
<label class="form-label">Aliance Partner Email Id</label>
<input type="text" name="AlianceEmailId" id="AlianceEmailId" class="form-control" placeholder="" value="<?php echo $row7["AlianceEmailId"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Commision % </label>
<input type="text" name="AliancePer" id="AliancePer" class="form-control" placeholder="" value="<?php echo $row7["AliancePer"]; ?>">
<div class="clearfix"></div>
</div>-->

<div class="form-group col-md-3">
<label class="form-label">FSSAI number </label>
<input type="text" name="FssaiNo" id="FssaiNo" class="form-control" placeholder="" value="<?php echo $row7["FssaiNo"]; ?>">
<div class="clearfix"></div>
</div>

 <!--<div class="form-group col-md-3">
                                            <label class="form-label">Operational  </label>
                                            <select class="form-control" id="OperationalFr" name="OperationalFr" >
                                             
                                                <option value="Yes" <?php if($row7["OperationalFr"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                    <option value="No" <?php if($row7["OperationalFr"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                              
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>-->

<!--<div class="form-group col-lg-4">
<label class="form-label">Zomato/Swiggy </label>
<select class="select2-demo form-control" name="ZomatoSwiggy[]" id="ZomatoSwiggy[]" multiple>


 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Roll=9 AND Status=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if(in_array($result["id"],$row7['ZomatoSwiggy'])) { ?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name'];?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>-->

<!--<div class="form-group col-md-3">
<label class="form-label"> Model Type</label>
 <select class="select2-demo form-control" name="ModelType" id="ModelType">
<option selected="" value="0">Select Model Type</option>
 <?php 
      $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=2";
      $row12 = getList($sql12);
      foreach($row12 as $result){
      ?>
        <option <?php if($row7["ModelType"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
          <?php echo $result['Name']; ?>
        </option>
      <?php } ?>
</select>
<div class="clearfix"></div>
</div>-->

<!--<div class="form-group col-md-4">
<label class="form-label"> Reporting Manager</label>
 <select class="select2-demo form-control" name="UnderUser" id="UnderUser">
<option selected="" value="0">Select Reporting Manager</option>
 <?php 
      $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND ReportingMgr=1";
      $row12 = getList($sql12);
      foreach($row12 as $result){
      ?>
        <option <?php if($row7["UnderUser"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
          <?php echo $result['Fname']." (".$result['Phone'].")"; ?>
        </option>
      <?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label"> Under By BDM</label>
 <select class="select2-demo form-control" name="UnderByBdm" id="UnderByBdm">
<option selected="" value="0">No BDM</option>
<optgroup label="BDM">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN (134,145,181,183)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["UnderByBdm"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>
</select>
<div class="clearfix"></div>
</div>-->


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="form-group col-md-2">
  <label class="form-label">Open Time</label>
  <input type="text" name="OpenTime" id="OpenTime" class="form-control"
         value="<?php echo $row7['OpenTime']; ?>">
</div>

<div class="form-group col-md-2">
  <label class="form-label">Close Time</label>
  <input type="text" name="CloseTime" id="CloseTime" class="form-control"
         value="<?php echo $row7['CloseTime']; ?>">
</div>

<script>
flatpickr("#OpenTime", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
flatpickr("#CloseTime", { enableTime: true, noCalendar: true, dateFormat: "h:i K" });
</script>

                                    </div>
                                     </fieldset>

<!-- <fieldset>
 <legend>ID Proof Documents</legend>
<div class="form-row"> 
<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Front Aadhar Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="AadharCard" style="opacity: 1;">
<input type="hidden" name="AadharCardOld" value="<?php echo $row7['AadharCard'];?>" id="AadharCardOld">
<span class="custom-file-label"></span>
</label>
<?php if($row7['AadharCard']=='') {} else{?>
  <span id="show_photo3">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo3"></a><a href="../uploads/<?php echo $row7['AadharCard'];?>" target="_blank"><?php echo $row7['AadharCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Back Aadhar Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="AadharCard2" style="opacity: 1;">
<input type="hidden" name="AadharCardOld2" value="<?php echo $row7['AadharCard2'];?>" id="AadharCardOld2">
<span class="custom-file-label"></span>
</label>
<?php if($row7['AadharCard2']=='') {} else{?>
  <span id="show_photo4">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo4"></a><a href="../uploads/<?php echo $row7['AadharCard2'];?>" target="_blank"><?php echo $row7['AadharCard2'];?></a></div>
</span>
<?php } ?>
</div>

 <div class="form-group col-md-6 maindoc">
                                            <label class="form-label">Aadhar Card No  </label>
                                            <input type="text" name="AadharNo" id="AadharNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["AadharNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-6 maindoc">
                                            <label class="form-label">PAN Card No  </label>
                                            <input type="text" name="PanNo" id="PanNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PanNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Front Pan Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="PanCard" style="opacity: 1;">
<input type="hidden" name="PanCardOld" value="<?php echo $row7['PanCard'];?>" id="PanCardOld">
<span class="custom-file-label"></span>
</label>
<?php if($row7['PanCard']=='') {} else{?>
  <span id="show_photo5">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo5"></a><a href="../uploads/<?php echo $row7['PanCard'];?>" target="_blank"><?php echo $row7['PanCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Back Pan Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="PanCard2" style="opacity: 1;">
<input type="hidden" name="PanCardOld2" value="<?php echo $row7['PanCard2'];?>" id="PanCardOld2">
<span class="custom-file-label"></span>
</label>
<?php if($row7['PanCard2']=='') {} else{?>
  <span id="show_photo6">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo6"></a><a href="../uploads/<?php echo $row7['PanCard2'];?>" target="_blank"><?php echo $row7['PanCard2'];?></a></div>
</span>
<?php } ?>
</div>


<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload GST Certificate </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="GstCertificate" style="opacity: 1;">
<input type="hidden" name="OldGstCertificate" value="<?php echo $row7['GstCertificate'];?>" id="OldGstCertificate">
<span class="custom-file-label"></span>
</label>
<?php if($row7['GstCertificate']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['GstCertificate'];?>" target="_blank"><?php echo $row7['GstCertificate'];?></a></div>
</span>
<?php } ?>
</div>


<div class="form-group col-md-6 otherdoc">
<label class="form-label">Gumasta No </label>
<input type="text" name="GumastaNo" id="GumastaNo" class="form-control" placeholder="" value="<?php echo $row7["GumastaNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload Gumasta Certificate </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Gumasta" style="opacity: 1;">
<input type="hidden" name="OldGumasta" value="<?php echo $row7['Gumasta'];?>" id="OldGumasta">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Gumasta']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['Gumasta'];?>" target="_blank"><?php echo $row7['Gumasta'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 otherdoc">
<label class="form-label">MSME No </label>
<input type="text" name="MsmeNo" id="MsmeNo" class="form-control" placeholder="" value="<?php echo $row7["MsmeNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload MSME Certificate </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Msme" style="opacity: 1;">
<input type="hidden" name="OldMsme" value="<?php echo $row7['Msme'];?>" id="OldMsme">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Msme']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['Msme'];?>" target="_blank"><?php echo $row7['Msme'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload Food License </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="FoodLicence" style="opacity: 1;">
<input type="hidden" name="OldFoodLicence" value="<?php echo $row7['FoodLicence'];?>" id="OldMsme">
<span class="custom-file-label"></span>
</label>
<?php if($row7['FoodLicence']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['FoodLicence'];?>" target="_blank"><?php echo $row7['FoodLicence'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload Food License Receipt</label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="FoodLicenceReceipt" style="opacity: 1;">
<input type="hidden" name="OldFoodLicenceReceipt" value="<?php echo $row7['FoodLicenceReceipt'];?>" id="OldFoodLicenceReceipt">
<span class="custom-file-label"></span>
</label>
<?php if($row7['FoodLicenceReceipt']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['FoodLicenceReceipt'];?>" target="_blank"><?php echo $row7['FoodLicenceReceipt'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload Agreement Copy</label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="AgreementCopy" style="opacity: 1;">
<input type="hidden" name="OldAgreementCopy" value="<?php echo $row7['AgreementCopy'];?>" id="OldAgreementCopy">
<span class="custom-file-label"></span>
</label>
<?php if($row7['AgreementCopy']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['AgreementCopy'];?>" target="_blank"><?php echo $row7['AgreementCopy'];?></a></div>
</span>
<?php } ?>
</div>

</div>

                    </fieldset>-->
                    
                    <fieldset>
 <legend>Billing Details</legend>
<div class="form-row">            

<div class="form-group col-md-6">
   <label class="form-label">GST No <span class="text-danger">*</span></label>
     <input type="text" name="GstNo" id="GstNo" class="form-control"
                                                placeholder="" value="<?php echo $row7['GstNo'];?>" maxlength="15"
                                                autocomplete="off" oninput="checkGstNoLength(this.value)">
    <div class="clearfix"></div>
 </div>
 
 
 <div class="form-group col-md-6">
   <label class="form-label">Customer Care Number <span class="text-danger">*</span></label>
     <input type="text" name="PrintMobNo" id="PrintMobNo" class="form-control"
                                                placeholder="" value="<?php echo $PrintMobNo;?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
                                    
                                      <div class="form-group col-md-12">
   <label class="form-label">Comapany Name <span class="text-danger">*</span></label>
     <input type="text" name="PrintCompName" id="PrintCompName" class="form-control"
                                                placeholder="" value="<?php echo $PrintCompName;?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 

 
 
  
 
 <div class="form-group col-md-12">
   <label class="form-label">Terms & Condition <span class="text-danger">*</span></label>
     <textarea name="terms_condition" id="terms_condition" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $terms_condition;?></textarea>
    <div class="clearfix"></div>
 </div>
 
<div class="form-group col-md-12">
   <label class="form-label">Bottom Title<span class="text-danger">*</span></label>
     <textarea name="bottom_title" id="bottom_title" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $bottom_title;?></textarea>
    <div class="clearfix"></div>
 </div>

                                    </div> 
                                     </fieldset>
                                     
 <fieldset>
 <legend>Bank Account Detail</legend>
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

<div class="form-group col-md-6">
<label class="form-label">Bank Name </label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>">
<div class="clearfix"></div>
</div>



<div class="form-group col-md-6">
<label class="form-label">Branch </label>
<input type="text" name="Branch" id="Branch" class="form-control" placeholder="" value="<?php echo $row7["Branch"]; ?>">
<div class="clearfix"></div>
</div>



<div class="form-group col-md-6">
<label class="form-label">UPI ID </label>
<input type="text" name="UpiNo" id="UpiNo" class="form-control" placeholder="" value="<?php echo $row7["UpiNo"]; ?>">
<div class="clearfix"></div>
</div>



 

                                      <div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Status" name="Status" required="">
                                               
                                                <option value="1" <?php if($row7["Status"]=='1') {?> selected
                                                    <?php } ?>>Active</option>
                                                <option value="0" <?php if($row7["Status"]=='0') {?> selected
                                                    <?php } ?>>Inctive</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                    </div> 
                                     </fieldset>
                                     
                                     <fieldset>
    <legend>Billing/POS App Access</legend>
    <div class="form-row">
        <?php 
        $sql88 = "SELECT * FROM tbl_dynamic_menu WHERE status=1 AND id NOT IN(4,15,16)";
        $row88 = getList($sql88);
        foreach($row88 as $result){ 
            $menuId = $result['id'];
        ?>
        <!-- Parent Menu -->
        <div class="form-group col-md-12">
            <label class="custom-control custom-checkbox font-weight-bold" style="padding-left: 1.688rem;">
                <input type="checkbox" 
                       class="custom-control-input menu-checkbox" 
                       name="menu_ids[]" 
                       value="<?= $menuId; ?>" <?php if(in_array($menuId,$row71['menu_ids'])) { ?> checked <?php } ?>
                       data-menuid="<?= $menuId; ?>">
                <span class="custom-control-label"><?= $result['title']; ?></span>
            </label>
        </div>

        <!-- Sub Menus -->
        <?php 
            $sql89 = "SELECT * FROM tbl_sub_menu WHERE status=1 AND menuid='".$menuId."'";
            $row89 = getList($sql89);
            foreach($row89 as $result2){ ?>
        <div class="form-group col-md-4 submenu-item submenu-of-<?= $menuId; ?>">
            <label class="custom-control custom-checkbox ml-3">
                <input type="checkbox" 
                       class="custom-control-input submenu-checkbox" 
                       name="submenuid[]" 
                       value="<?= $result2['id']; ?>" <?php if(in_array($result2['id'],$row71['submenuid'])) { ?> checked <?php } ?>
                       data-parent="<?= $menuId; ?>">
                <span class="custom-control-label"><?= $result2['title']; ?></span>
            </label>
        </div>
        <?php } } ?>
    </div>
</fieldset>
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
    function checkGstNoLength(value) {
    if (value.length === 15) {
        showLoader();
        gstVerify();
    }
}


function gstVerify() {
    var GstNo = $('#GstNo').val();
    var action = "GstVerify";

    if (GstNo.length !== 15) {
        alert("GST number must be 15 digits");
        hideLoader();
        return;
    }

    $.ajax({
        url: "ajax_files/ajax_api.php",
        method: "POST",
        data: {
            action: action,
            GstNo: GstNo
        },
        dataType: "json",
        success: function(data) {
            console.log("Response:", data);
            hideLoader();
            
            if (data.type != 'validation_error') {
               
                    $('#PrintCompName').val(data.legal_name_of_business);
                    
                
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

    function getSubZone(zoneid){
        var action = "getSubZone";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:zoneid},
    success:function(data)
    {
      $('#SubZoneId').html(data);
    }
    });

    }
function showDoc(val){
    if(val == 31){
$('.maindoc').show();
$('.otherdoc').hide();
$('.compdata').hide();
    }
    else if(val == 34){
         $('.compdata').show();
         $('.maindoc').show();
        $('.otherdoc').show();
    }
    else{
        $('.compdata').hide();
        $('.maindoc').show();
        $('.otherdoc').show();
    }
}
        function getProdList(acdc,Surface,PumpCapacity,WaterSource,BoreDia,PumpHead){
  var action = 'view2';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_product_specification.php",
   data:{action:action,acdc:acdc,Surface:Surface,PumpCapacity:PumpCapacity,WaterSource:WaterSource,BoreDia:BoreDia,PumpHead:PumpHead},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
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
            message: 'Phone No Already Exists',
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
        var userid = $('#userid').val();
       
        var ProjectType = $('#ProjectType').val();
        var AcDc = $('#AcDc').val();
        var Surface = $('#Surface').val();
        var PumpCapacity = $('#PumpCapacity').val();
        var WaterSource = $('#WaterSource').val();
        var BoreDia = $('#BoreDia').val();
        var PumpHead = $('#PumpHead').val();
        var CustType = $('#CustType').val();
        if(userid!=''){
        showHide(ProjectType);
        getProdList(AcDc,Surface,PumpCapacity,WaterSource,BoreDia,PumpHead);
        showDoc(CustType);
    }
        //$(document).on("click", ".btn-finish", function(event){
        $('#validation-form').on('submit', function(e) {
            e.preventDefault();
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "ajax_files/ajax_customer_account.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {
console.log(data);$('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                        if (data == 0) {
                            error_toast();

                        } else {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'view-franchises.php';
                            }, 2000);
                        }
                        
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
                    url: "ajax_files/ajax_customer_account.php",
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
      
        
        $(document).on("change", "#CountryId", function(event){
  var val = this.value;
   var action = "getState";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#StateId').html(data);
    }
    });

 });

         $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId').html(data);
    }
    });

 });
    });


    function showHide(val){
        if(val == 1){
            $('.Rooftop').hide();
            $('.Pump').show();
        }
        else{
$('.Rooftop').show();
$('.Pump').hide();
        }
    }
    </script>
       <script>
    function handleImageRemoval(data) {
        var type = 1;
        $.ajax({  
                url :"example/async-remove.php",  
                method:"GET",  
                data:{type:type},
                success:function(data){ 
                    
                }
            });
    }
  
    </script>

<script src="example/js/slim.kickstart.min.js"></script>
</body>

</html>