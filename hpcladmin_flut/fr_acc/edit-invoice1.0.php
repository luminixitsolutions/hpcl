<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Invoice";
$Page = "Add-Invoice";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> </title>
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
    </style>
     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                
                <?php

if($_GET["action"]=="deleteprd")
{
  $id = $_GET["id"];
  $bid = $_GET["oid"];
  $sql11 = "DELETE FROM tbl_invoice_details WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="edit-invoice.php?id=<?php echo $bid;?>";
    </script>
<?php }

                $id = $_GET['id'];
                $sql8 = "SELECT * FROM tbl_invoice WHERE id='$id'";
                $row7 = getRecord($sql8);

                $query22 = "SELECT count(*) as totrec FROM tbl_invoice_details WHERE InvId = '$id'";
                $data22 = getRecord($query22);
                $row_cnt = $data22['totrec'] + 1;

                

                if (isset($_POST['submit'])) {
                    $CustId = $_POST['CustId'];
                    $AccCode = addslashes(trim($_POST['AccCode']));
                    $CustName = addslashes(trim($_POST['CustName']));
                    $CellNo = addslashes(trim($_POST['CellNo']));
                    $Address = addslashes(trim($_POST['Address']));
                    $EmailId = addslashes(trim($_POST['EmailId']));
                    $InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
                    $InvoiceDate = addslashes(trim($_POST['InvoiceDate']));
                    $PayType = addslashes(trim($_POST['PayType']));
                    $Narration = addslashes(trim($_POST['Narration']));
                    

                    $SubTotal = addslashes(trim($_POST['SubTotal']));
                    $SgstPer = addslashes(trim($_POST['SgstPer']));
                    $CgstPer = addslashes(trim($_POST['CgstPer']));
                    $IgstPer = addslashes(trim($_POST['IgstPer']));
                    $GstAmt = addslashes(trim($_POST['GstAmt']));
                    $ExtraAmount = addslashes(trim($_POST['ExtraAmount']));
                    $TotalAmount = addslashes(trim($_POST['TotalAmount']));
                    $Discount = addslashes(trim($_POST['Discount']));
                    $NetAmount = addslashes(trim($_POST['NetAmount']));
                    $Advance = addslashes(trim($_POST['Advance']));
                    $Balance = addslashes(trim($_POST['Balance']));
                    $ExtraReason = addslashes(trim($_POST['ExtraReason']));
                    $PayType = addslashes(trim($_POST['PayType']));
                    $ChequeNo = addslashes(trim($_POST['ChequeNo']));
                    $ChqDate = addslashes(trim($_POST['ChqDate']));
                    $BankName = addslashes(trim($_POST['BankName']));
                    $UpiNo = addslashes(trim($_POST['UpiNo']));
                    $CreatedDate = date('Y-m-d');
                    $CreatedTime = date('h:i a');

                    $sql = "UPDATE tbl_invoice SET AccCode='$AccCode',CustId='$CustId',CustName='$CustName',
                            CellNo='$CellNo',Address='$Address',EmailId='$EmailId',InvoiceNo='$InvoiceNo',
                            InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',
                            CgstPer='$CgstPer',SgstPer='$SgstPer',
                            IgstPer='$IgstPer',SubTotal='$SubTotal',
                            GstAmt='$GstAmt',Status=1,ModifiedBy='$user_id',ModifiedDate='$CreatedDate',
                            ExtraAmount='$ExtraAmount',TotalAmount='$TotalAmount',Discount='$Discount',
                            NetAmount='$NetAmount',Advance='$Advance',Balance='$Balance',
                            ExtraReason='$ExtraReason',ChequeNo='$ChequeNo',ChqDate='$ChqDate',
                            BankName='$BankName',UpiNo='$UpiNo' WHERE id='$id'";
                    $conn->query($sql);
                    $SellId = $id;

                    $sql = "DELETE FROM tbl_general_ledger WHERE SellId='$SellId' AND Flag='Invoice'";
                    $conn->query($sql);

                    $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
                    $row2 = getRecord($sql2);
                    if($row2['maxid'] == ''){
                        $SrNo = 1;
                        $Code = "PR".$SrNo;
                    }
                    else{
                        $SrNo = $row2['maxid']+1;
                        $Code = "PR".$SrNo;
                    }

                    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='OB',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$NetAmount',CrDr='cr',Roll=3,
                            Type='OB',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Invoice',AccRoll='Customer'";
                    $conn->query($sql4);

                    // $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='OB',UserId='0',
                    //         AccCode='AC1',
                    //         AccountName='Comapny Account',
                    //         InvoiceNo='$InvoiceNo',Amount='$NetAmount',CrDr='cr',Roll=3,
                    //         Type='OB',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                    //         PayMode='$PayType',Narration='$Narration',
                    //         SellId='$SellId',Flag='Invoice',AccRoll='Company'";
                    // $conn->query($sql4);

                    if($Advance > 0){
                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='dr',Roll=3,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Invoice',AccRoll='Customer'";
                    $conn->query($sql4);

                    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='0',
                            AccCode='AC1',
                            AccountName='Comapny Account',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='cr',Roll=3,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Invoice',AccRoll='Company'";
                    $conn->query($sql4);
                    }

                    $sql = "DELETE FROM tbl_invoice_details WHERE InvId='$SellId'";
                    $conn->query($sql);

                    $number = count($_POST["TravelId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["TravelId"][$i] != ''))  
              {
                $TravelId = addslashes(trim($_POST['TravelId'][$i]));
                $TrainNameId = addslashes(trim($_POST['TrainNameId'][$i]));
                $FareClassId = addslashes(trim($_POST['FareClassId'][$i]));
                $Name = addslashes(trim($_POST['Name'][$i]));
                $Dob = addslashes(trim($_POST['Dob'][$i]));
                $Age = addslashes(trim($_POST['Age'][$i]));
                $AdultChild = addslashes(trim($_POST['AdultChild'][$i]));
                $Gender = addslashes(trim($_POST['Gender'][$i]));
                $IdProof = addslashes(trim($_POST['IdProof'][$i]));
                $TravelDate = addslashes(trim($_POST['TravelDate'][$i]));
                $PnrNo = addslashes(trim($_POST['PnrNo'][$i]));
                $SeatNo = addslashes(trim($_POST['SeatNo'][$i]));
                $Price = addslashes(trim($_POST['Price'][$i]));
                
                $sql22 = "INSERT INTO tbl_invoice_details SET InvId='$SellId',TravelId='$TravelId',
                TrainNameId='$TrainNameId',FareClassId='$FareClassId',Name='$Name',Dob='$Dob',
                Age='$Age',AdultChild='$AdultChild',Gender='$Gender',IdProof='$IdProof',TravelDate='$TravelDate'
                ,PnrNo='$PnrNo',SeatNo='$SeatNo',Price='$Price',CreatedDate='$InvoiceDate'";
                $conn->query($sql22);
              }  

          }
      }

      echo "<script>alert('Invoice Updated Successfully!');window.location.href='view-invoices.php';</script>";
                }
                ?>
                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Edit Invoice</h4>


                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" enctype="multipart/form-data">

                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                                            <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="CustId" id="CustId">
                                                <option>Search Customer</option>
                                                <?php
                                                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=3";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                ?>
                                                    <option <?php if ($row7["CustId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name'] . " - " . $result['Phone']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
<input type="hidden" name="AccCode" id="AccCode" value="<?php echo $row7["AccCode"]; ?>">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control" placeholder="" value="<?php echo $row7["CellNo"]; ?>" autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" id="CustName" class="form-control" placeholder="" value="<?php echo $row7["CustName"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control" placeholder="" value="<?php echo $row7["EmailId"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address</label>
                                            <textarea name="Address" id="Address" class="form-control"><?php echo $row7['Address']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label class="form-label">Invoice No </label>
                                            <input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $row7['InvoiceNo']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Invoice Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control" placeholder="" value="<?php echo $row7['InvoiceDate']; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                    </div>

                                    <?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_invoice_details WHERE InvId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>

<div class="form-row">
                                            <div class="form-group col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Service <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="TravelId[]" id="TravelId<?php echo $i; ?>" onchange="getTrainName(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=1 AND Status=1";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($result12["TravelId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Train/Flight Name <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="TrainNameId[]" id="TrainNameId<?php echo $i; ?>">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM tbl_service_details WHERE TravelId='".$result12["TravelId"]."' AND Status=1";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($result12["TrainNameId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
                                                                    <?php } ?>         
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Fare Class <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="FareClassId[]" id="FareClassId<?php echo $i; ?>">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM tbl_common_master WHERE ServiceId = '".$result12["TravelId"]."' AND Status='1' AND Roll=2";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($result12["FareClassId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
                                                                    <?php } ?>   
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="Name[]" class="form-control" placeholder="e.g.,Rohit Sharma" value="<?php echo $result12["Name"];?>" autocomplete="off" required>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Date Of Birth </label>
                                                                <input type="date" name="Dob[]" class="form-control" placeholder="e.g.,Rohit Sharma" value="<?php echo $result12["Dob"];?>" autocomplete="off" onchange="calAge(this.value,<?php echo $i; ?>)">
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="number" name="Age[]" id="Age<?php echo $i; ?>" class="form-control" placeholder="e.g.,32" value="<?php echo $result12["Age"];?>" autocomplete="off" min="1" required>

                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Adult/Child <span class="text-danger">*</span></label>
                                                                <select class="form-control" id="AdultChild" name="AdultChild[]" required="">
                                                                    <option selected="" disabled="" value="">Select</option>
                                                                    <option value="Adult" <?php if ($result12["AdultChild"] == 'Adult') { ?> selected <?php } ?>>Adult</option>
                                                                    <option value="Child" <?php if ($result12["AdultChild"] == 'Child') { ?> selected <?php } ?>>Child</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                                                <select class="form-control" id="Gender" name="Gender[]" required="">
                                                                    <option selected="" disabled="" value="">Select Gender</option>
                                                                    <option value="M" <?php if ($result12["Gender"] == 'M') { ?> selected <?php } ?>>M</option>
                                                                    <option value="F" <?php if ($result12["Gender"] == 'F') { ?> selected <?php } ?>>F</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">ID Proof </label>
                                                                <input type="text" name="IdProof[]" class="form-control" placeholder="e.g.,12345" value="<?php echo $result12["IdProof"];?>" autocomplete="off">
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Travel Date </label>
                                                                <input type="date" name="TravelDate[]" class="form-control" placeholder="e.g.,12345" value="<?php echo $result12["TravelDate"];?>" autocomplete="off">
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">PNR No </label>
                                                                <input type="text" name="PnrNo[]" class="form-control" placeholder="e.g.,12345" value="<?php echo $result12["PnrNo"];?>" autocomplete="off">
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Seat No </label>
                                                                <input type="text" name="SeatNo[]" class="form-control" placeholder="e.g.,S12" value="<?php echo $result12["SeatNo"];?>" autocomplete="off">
                                                            </div>

                                                            <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control txt" placeholder="e.g.,5000" value="<?php echo $result12["Price"];?>" autocomplete="off" required oninput="getSubTotal()">
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                    <a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result12['id']; ?>&action=deleteprd&oid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="feather icon-x"></i></a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++;} ?>

                                    <div id="dynamic_field">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Service <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="TravelId[]" id="TravelId<?php echo $row_cnt; ?>" onchange="getTrainName(this.value,document.getElementById('srno<?php echo $row_cnt; ?>').value)">
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

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Train/Flight Name <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="TrainNameId[]" id="TrainNameId<?php echo $row_cnt; ?>">
                                                                    <option selected value="" disabled>...</option>

                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Fare Class <span class="text-danger">*</span></label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="FareClassId[]" id="FareClassId<?php echo $row_cnt; ?>">
                                                                    <option selected value="" disabled>...</option>

                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="Name[]" class="form-control" placeholder="e.g.,Rohit Sharma" value="" autocomplete="off" required>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Date Of Birth </label>
                                                                <input type="date" name="Dob[]" class="form-control" placeholder="e.g.,Rohit Sharma" value="" autocomplete="off" onchange="calAge(this.value,<?php echo $row_cnt; ?>)">
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="number" name="Age[]" id="Age<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,32" value="" autocomplete="off" min="1" required>

                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Adult/Child <span class="text-danger">*</span></label>
                                                                <select class="form-control" id="AdultChild" name="AdultChild[]" required="">
                                                                    <option selected="" disabled="" value="">Select</option>
                                                                    <option value="Adult">Adult</option>
                                                                    <option value="Child">Child</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                                                <select class="form-control" id="Gender" name="Gender[]" required="">
                                                                    <option selected="" disabled="" value="">Select Gender</option>
                                                                    <option value="M">M</option>
                                                                    <option value="F">F</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label class="form-label">ID Proof </label>
                                                                <input type="text" name="IdProof[]" class="form-control" placeholder="e.g.,12345" value="" autocomplete="off">
                                                            </div>

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

                                                            <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $row_cnt; ?>" value="<?php echo $row_cnt; ?>">
                                                            <input type="hidden" class="form-control" name="rncnt" id="rncnt" value="<?php echo $row_cnt; ?>">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Price[]" id="Price<?php echo $row_cnt; ?>" class="form-control txt" placeholder="e.g.,5000" value="" autocomplete="off" required oninput="getSubTotal()">
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                        <button class="btn btn-secondary" type="button" id="add_more"><i class="fa fa-plus"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Sub Total <span class="text-danger">*</span></label>
                                            <input type="text" name="SubTotal" id="SubTotal" class="form-control" placeholder="" value="<?php echo $row7["SubTotal"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Service Charges <span class="text-danger">*</span></label>
                                            <input type="text" name="ExtraAmount" id="ExtraAmount" class="form-control" placeholder="" value="<?php echo $row7["ExtraAmount"]; ?>" onKeyPress="return isNumberKey(event)" oninput="balAmount(document.getElementById('SubTotal').value,document.getElementById('SgstPer').value,
document.getElementById('CgstPer').value,document.getElementById('IgstPer').value,
document.getElementById('GstAmt').value,document.getElementById('Discount').value
,document.getElementById('ExtraAmount').value,document.getElementById('TotalAmount').value
,document.getElementById('Advance').value)" required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">GST % <span class="text-danger">*</span></label>
                                            <input type="text" name="SgstPer" id="SgstPer" class="form-control" placeholder="" value="<?php echo $row7["SgstPer"]; ?>" onKeyPress="return isNumberKey(event)" oninput="balAmount(document.getElementById('SubTotal').value,document.getElementById('SgstPer').value,
document.getElementById('CgstPer').value,document.getElementById('IgstPer').value,
document.getElementById('GstAmt').value,document.getElementById('Discount').value
,document.getElementById('ExtraAmount').value,document.getElementById('TotalAmount').value
,document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div>
                                        <input type="hidden" name="CgstPer" id="CgstPer" value="0">
<input type="hidden" name="IgstPer" id="IgstPer" value="0">
                                        <!-- <div class="form-group col-md-1">
                                            <label class="form-label">CGST % <span class="text-danger">*</span></label>
                                            <input type="text" name="CgstPer" id="CgstPer" class="form-control" placeholder="" value="<?php echo $row7["CgstPer"]; ?>" onKeyPress="return isNumberKey(event)" oninput="balAmount(document.getElementById('SubTotal').value,document.getElementById('SgstPer').value,
document.getElementById('CgstPer').value,document.getElementById('IgstPer').value,
document.getElementById('GstAmt').value,document.getElementById('Discount').value
,document.getElementById('ExtraAmount').value,document.getElementById('TotalAmount').value
,document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-1">
                                            <label class="form-label">IGST % <span class="text-danger">*</span></label>
                                            <input type="text" name="IgstPer" id="IgstPer" class="form-control" placeholder="" value="<?php echo $row7["IgstPer"]; ?>" onKeyPress="return isNumberKey(event)" oninput="balAmount(document.getElementById('SubTotal').value,document.getElementById('SgstPer').value,
document.getElementById('CgstPer').value,document.getElementById('IgstPer').value,
document.getElementById('GstAmt').value,document.getElementById('Discount').value
,document.getElementById('ExtraAmount').value,document.getElementById('TotalAmount').value
,document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div> -->

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Total GST<span class="text-danger">*</span></label>
                                            <input type="text" name="GstAmt" id="GstAmt" class="form-control" placeholder="" value="<?php echo $row7["GstAmt"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>


                                       

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Total Amount<span class="text-danger">*</span></label>
                                            <input type="text" name="TotalAmount" id="TotalAmount" class="form-control" placeholder="" value="<?php echo $row7["TotalAmount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label class="form-label">Discount <span class="text-danger">*</span></label>
                                            <input type="text" name="Discount" id="Discount" class="form-control" placeholder="" value="<?php echo $row7["Discount"]; ?>" onKeyPress="return isNumberKey(event)" oninput="balAmount(document.getElementById('SubTotal').value,document.getElementById('SgstPer').value,
document.getElementById('CgstPer').value,document.getElementById('IgstPer').value,
document.getElementById('GstAmt').value,document.getElementById('Discount').value
,document.getElementById('ExtraAmount').value,document.getElementById('TotalAmount').value
,document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Net Payable<span class="text-danger">*</span></label>
                                            <input type="text" name="NetAmount" id="NetAmount" class="form-control" placeholder="" value="<?php echo $row7["NetAmount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="Advance" id="Advance" class="form-control" placeholder="" value="<?php echo $row7["Advance"]; ?>" onKeyPress="return isNumberKey(event)" oninput="balAmount(document.getElementById('SubTotal').value,document.getElementById('SgstPer').value,
document.getElementById('CgstPer').value,document.getElementById('IgstPer').value,
document.getElementById('GstAmt').value,document.getElementById('Discount').value
,document.getElementById('ExtraAmount').value,document.getElementById('TotalAmount').value
,document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Balance <span class="text-danger">*</span></label>
                                            <input type="text" name="Balance" id="Balance" class="form-control" placeholder="" value="<?php echo $row7["Balance"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Extra Charges Reason </label>
                                            <input type="text" name="ExtraReason" class="form-control" placeholder="" value="<?php echo $row7["ExtraReason"]; ?>" autocomplete="off">
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
                                                <option selected="" disabled="" value="">Select Payment Type</option>
                                                <option <?php if ($row7['PayType'] == 'Cash') { ?> selected <?php } ?> value="Cash">Cash</option>
                                                <option <?php if ($row7['PayType'] == 'Cheque') { ?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
                                                <option <?php if ($row7['PayType'] == 'UPI') { ?> selected <?php } ?> value="UPI">UPI</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div id="chequeoption" <?php if ($row7['PayType'] == 'Cheque') { ?> style="display: block;" <?php } else { ?>style="display: none;" <?php } ?>>
                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label class="form-label">Cheque No <span class="text-danger">*</span></label>
                                                <input type="text" name="ChequeNo" class="form-control" id="ChequeNo" placeholder="Cheque No" value="<?php echo $row7['ChequeNo']; ?>">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label class="form-label">Cheque Date <span class="text-danger">*</span></label>
                                                <input type="date" name="ChqDate" class="form-control" id="ChqDate" placeholder="Cheque Date" value="<?php echo $row7['ChqDate']; ?>">
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col">
                                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                                <input type="text" name="BankName" class="form-control" id="BankName" placeholder="Bank Name" value="<?php echo $row7['BankName']; ?>">
                                                <div class="clearfix"></div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row" id="upioption" <?php if ($row7['PayType'] == 'UPI') { ?> style="display: block;" <?php } else { ?>style="display: none;" <?php } ?>>
                                        <div class="form-group col">
                                            <label class="form-label">UPI No/ Transaction Id <span class="text-danger">*</span></label>
                                            <input type="text" name="UpiNo" class="form-control" id="UpiNo" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo']; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col">
                                            <label class="form-label">Narration <span class="text-danger">*</span></label>
                                            <input type="text" name="Narration" class="form-control" id="Narration" placeholder="Narration" value="<?php echo $row7['Narration']; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
        function calAge(val, srno) {
            //alert(val);alert(srno);
            var dob = new Date(val);
            //calculate month difference from current date in time  
            var month_diff = Date.now() - dob.getTime();

            //convert the calculated difference in date format  
            var age_dt = new Date(month_diff);

            //extract year from date      
            var year = age_dt.getUTCFullYear();

            //now calculate the age of the user  
            var age = Math.abs(year - 1970);

            $('#Age' + srno).val(age);
            //alert(age);
        }

        function getPayType(val) {
            if (val == 'Cheque') {
                $('#chequeoption').show();
                $('#upioption').hide();
            } else if (val == 'UPI') {
                $('#chequeoption').hide();
                $('#upioption').show();
            } else {
                $('#chequeoption').hide();
                $('#upioption').hide();
            }
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        function getSubTotal() {
            var sum = 0;
            $(".txt").each(function() {
                if (!isNaN(this.value) && this.value.length != 0) {
                    sum += parseFloat(this.value);
                }
            });
            $('#SubTotal').val(sum);

            var SubTotal = $('#SubTotal').val();
            var SgstPer = $('#SgstPer').val();
            var CgstPer = $('#CgstPer').val();
            var IgstPer = $('#IgstPer').val();
            var GstAmt = $('#GstAmt').val();
            var Discount = $('#Discount').val();
            var ExtraAmount = $('#ExtraAmount').val();
            var TotalAmount = $('#TotalAmount').val();
            var Advance = $('#Advance').val();
            balAmount(SubTotal, SgstPer, CgstPer, IgstPer, GstAmt, Discount, ExtraAmount, TotalAmount, Advance);
        }

        function balAmount(SubTotal, SgstPer, CgstPer, IgstPer, GstAmt, Discount, ExtraAmount, TotalAmount, Advance) {
            var CgstAmt = Number(ExtraAmount) * (Number(CgstPer) / 100);
            var SgstAmt = Number(ExtraAmount) * (Number(SgstPer) / 100);
            var IgstAmt = Number(ExtraAmount) * (Number(IgstPer) / 100);
            var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
            $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
            var Total_Amount = Number(SubTotal) + Number(GstAmt) + Number(ExtraAmount);
            $('#TotalAmount').val(parseFloat(Total_Amount).toFixed(2));
            var NetAmount = Number(Total_Amount) - Number(Discount);
            $('#NetAmount').val(parseFloat(NetAmount).toFixed(2));
            var Balance = Number(NetAmount) - Number(Advance);
            $('#Balance').val(parseFloat(Balance).toFixed(2));
        }

        function getFareClass(id, srno) {
            var action = "getFareClass";
            $.ajax({
                url: "ajax_files/ajax_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    $('#FareClassId' + srno).html(data);
                }
            });
        }

        function getTrainName(id, srno) {
            getFareClass(id, srno);
            var action = "getTrainName";
            $.ajax({
                url: "ajax_files/ajax_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    $('#TrainNameId' + srno).html(data);
                }
            });

        }

        $(document).ready(function() {
            getSubTotal();
            var i = $('#rncnt').val();
            $('#add_more').click(function() {
                i++;
                var html = '';
                var action = "getMore";
                $.ajax({
                    url: "ajax_files/ajax_invoice.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: i
                    },
                    success: function(data) {
                        $('#dynamic_field').append(data);
                    }
                });
            });

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                if (confirm("Are you sure you want to delete?")) {
                    $('#row' + button_id + '').remove();
                    getSubTotal();
                }
            });

            $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                var action = "getUserDetails";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#Address').val(data.Address);
                        $('#CustName').val(data.Name);
                        $('#CellNo').val(data.Phone);
                        $('#EmailId').val(data.EmailId);
                        $('#AccCode').val(data.CustomerId);
                    }
                });

            });
        });
    </script>

</body>

</html>