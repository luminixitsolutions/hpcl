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

if($_POST['action'] == 'getPrice'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_raw_products WHERE id='$id'";
    $row = getRecord($sql);
    echo $row['Price'];
}

if($_POST['action'] == 'getMore'){
    $i = $_POST['id']; ?>
<div class="form-row" id="row<?php echo $i;?>">
                                           
                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Raw Product </label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="ProdId[]" id="ProdId<?php echo $i;?>" onchange="getPrice(this.value,document.getElementById('srno<?php echo $i;?>').value)">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM tbl_raw_products WHERE Status=1";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($row7["TravelId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                           

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Price </label>
                                                                <input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>
                                                           
                                                           <div class="form-group col-md-2">
                                                                <label class="form-label">Qty </label>
                                                                <input type="text" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="1" autocomplete="off"
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                          
                                                           <!--  <div class="form-group col-md-1">
                                                                <label class="form-label">CGST %</label>
                                                                <input type="text" name="CgstPer[]" id="CgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('ServiceAmt<?php echo $i;?>').value,
                                                                document.getElementById('GstAmt<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">SGST %</label>
                                                                <input type="text" name="SgstPer[]" id="SgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('ServiceAmt<?php echo $i;?>').value,
                                                                document.getElementById('GstAmt<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>
                                                            
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">GST Amt</label>
                                                                <input type="text" name="GstAmt[]" id="GstAmt<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                                            </div>      -->     
                                                            <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Total[]" id="Total<?php echo $i;?>" class="form-control txt" placeholder="e.g.,5000" value="" autocomplete="off" required  readonly>
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                    <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                       
                                        </div>
<?php }
?>