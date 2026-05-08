<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getTrainName'){?>
    <option value="" selected="selected" disabled="">...</option>
    <?php 
        $TravelId = $_POST['id'];
            $q = "select * from tbl_service_details WHERE TravelId = '$TravelId' AND Status='1'";
            $r = $conn->query($q);
            while($rw = $r->fetch_assoc())
        {
    ?>
        <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getFareClass'){?>
    <option value="" selected="selected" disabled="">...</option>
    <?php 
        $ServiceId = $_POST['id'];
            $q = "select * from tbl_common_master WHERE ServiceId = '$ServiceId' AND Status='1' AND Roll=2";
            $r = $conn->query($q);
            while($rw = $r->fetch_assoc())
        {
    ?>
        <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getMore'){
    $i = $_POST['id']; ?>
<div class="form-row" id="row<?php echo $i;?>">
                                            <div class="form-group col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Service <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="TravelId[]" id="TravelId<?php echo $i;?>" onchange="getTrainName(this.value,document.getElementById('srno<?php echo $i;?>').value)">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=1 AND Status=1";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($row7["TravelId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <!-- <div class="form-group col-md-4">
                                                                <label class="form-label">Train/Flight Name <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="TrainNameId[]" id="TrainNameId<?php echo $i;?>">
                                                                    <option selected value="" disabled>...</option>

                                                                </select>
                                                            </div> -->

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Train/Flight Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="TrainName[]" class="form-control" placeholder="e.g.,Nagpur To Pune" value="" autocomplete="off" required>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Fare Class <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="FareClassId[]" id="FareClassId<?php echo $i;?>">
                                                                    <option selected value="" disabled>...</option>

                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="Name[]" class="form-control" placeholder="e.g.,Rohit Sharma" value="" autocomplete="off" required>
                                                            </div>

                                                            <!-- <div class="form-group col-md-2">
                                                                <label class="form-label">Date Of Birth </label>
                                                                <input type="date" name="Dob[]" class="form-control" placeholder="e.g.,Rohit Sharma" value="" autocomplete="off" onchange="calAge(this.value,<?php echo $i; ?>)">
                                                            </div> -->

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Age </label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Age[]" id="Age<?php echo $i; ?>" class="form-control" placeholder="e.g.,32" value="" autocomplete="off" >
                                                                  
                                                                </div>
                                                            </div>

                                                            <!-- <div class="form-group col-md-2">
                                                                <label class="form-label">Adult/Child <span class="text-danger">*</span></label>
                                                                <select class="form-control" id="AdultChild" name="AdultChild[]" required="" onchange="getPrice(this.value,<?php echo $row_cnt; ?>)">
                                                                    <option selected="" disabled="" value="">Select</option>
                                                                    <option value="Adult">Adult</option>
                                                                    <option value="Child">Child</option>
                                                                </select>
                                                            </div> -->

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                                                <select class="form-control" id="Gender" name="Gender[]" required="">
                                                                    <option selected="" disabled="" value="">...</option>
                                                                    <option value="M">M</option>
                                                                    <option value="F">F</option>
                                                                </select>
                                                            </div>

                                                            <!-- <div class="form-group col-md-4">
                                                                <label class="form-label">ID Proof </label>
                                                                <input type="text" name="IdProof[]" class="form-control" placeholder="e.g.,12345" value="" autocomplete="off">
                                                            </div> -->

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Travel Date </label>
                                                                <input type="date" name="TravelDate[]" class="form-control" placeholder="e.g.,12345" value="" autocomplete="off">
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">PNR No </label>
                                                                <input type="text" name="PnrNo[]" class="form-control" placeholder="e.g.,12345" value="" autocomplete="off">
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Seat No </label>
                                                                <input type="text" name="SeatNo[]" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off">
                                                            </div>

                                                            <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control txt" placeholder="e.g.,5000" value="" autocomplete="off" required  oninput="getSubTotal()">
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                    <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
<?php }
?>