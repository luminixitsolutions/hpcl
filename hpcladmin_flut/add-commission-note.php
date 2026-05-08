<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Financer";
$Page = "Commision-Note";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - Financer</title>
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
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_commision_note WHERE id='$id'";
$row7 = getRecord($sql7);
$sql = "SELECT MAX(id) AS MaxId FROM tbl_commision_note";
$row = getRecord($sql);

if($_GET['id'] == ''){
    $InvoiceNo = $row['MaxId']+1; 
    $NoteDate = date('Y-m-d');
    $PaymentDate = date('Y-m-d');
}
else{
    $InvoiceNo = $row7['NoteNo'];
    $NoteDate = $row7['NoteDate'];
    $PaymentDate = $row7['PaymentDate'];
}
if(isset($_POST['submit'])){
    $NoteDate = addslashes(trim($_POST['NoteDate']));
    $Description = addslashes(trim($_POST['Description']));
    $Narration = addslashes(trim($_POST['Narration']));
    $FinancerId = addslashes(trim($_POST['FinancerId']));
    $SalesAmount = addslashes(trim($_POST['SalesAmount']));
    $NonGstSale = addslashes(trim($_POST['NonGstSale']));
    $NoteNo = addslashes(trim($_POST['NoteNo']));
    $Commision = addslashes(trim($_POST['Commision']));
    $Amount = addslashes(trim($_POST['Amount']));
    $PaymentDate = addslashes(trim($_POST['PaymentDate']));
    $BankRefNo = addslashes(trim($_POST['BankRefNo']));
    $SubAmount = addslashes(trim($_POST['SubAmount']));
    $Tds = addslashes(trim($_POST['Tds']));
    $CreatedDate = date('Y-m-d');
    if($_GET['id'] == ''){
    $sql = "INSERT INTO tbl_commision_note SET SubAmount='$SubAmount',Tds='$Tds',FinancerId='$FinancerId',Description='$Description',SalesAmount='$SalesAmount',NonGstSale='$NonGstSale',Commision='$Commision',Amount='$Amount',NoteNo='$NoteNo',Narration='$Narration',CreatedBy='$user_id',CreatedDate='$CreatedDate',PaymentDate='$PaymentDate',NoteDate='$NoteDate',BankRefNo='$BankRefNo'";
    $conn->query($sql);
    }
    else{
        $sql = "UPDATE tbl_commision_note SET SubAmount='$SubAmount',Tds='$Tds',FinancerId='$FinancerId',Description='$Description',SalesAmount='$SalesAmount',NonGstSale='$NonGstSale',Commision='$Commision',Amount='$Amount',NoteNo='$NoteNo',Narration='$Narration',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',PaymentDate='$PaymentDate',NoteDate='$NoteDate',BankRefNo='$BankRefNo' WHERE id='$id'";
    $conn->query($sql);
    }

   

                    echo "<script>alert('Record Saved Successfully!');window.location.href='view-commision-note.php';</script>";
}


?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Commission Note</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">

<div class="form-row">


<div class="form-group col-md-12">
<label class="form-label"> Financer <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="FinancerId" id="FinancerId" required >

<option selected="" value="">Select Financer</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=105 AND Status=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["FinancerId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

</div>

 
<div class="form-row">
<div class="form-group col-md-12">
<label class="form-label">Description <span class="text-danger">*</span></label>
<input type="text" name="Description" id="Description" class="form-control" placeholder="" value="<?php echo $row7["Description"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
    
<div class="form-group col-md-2">
<label class="form-label">Sale Amount <span class="text-danger">*</span></label>
<input type="text" name="SalesAmount" id="SalesAmount" class="form-control" placeholder="" value="<?php echo $row7["SalesAmount"]; ?>" autocomplete="off" required oninput="calNonGstAmt()">
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-2">
<label class="form-label">Non GST Amount <span class="text-danger">*</span></label>
<input type="text" name="NonGstSale" id="NonGstSale" class="form-control" placeholder="" value="<?php echo $row7["NonGstSale"]; ?>" autocomplete="off" required oninput="TotAmount(document.getElementById('NonGstSale').value,document.getElementById('Commision').value)">
<div class="clearfix"></div>
    </div>
    
     <div class="form-group col-md-2">
<label class="form-label">Commission % <span class="text-danger">*</span></label>
<input type="text" name="Commision" id="Commision" class="form-control" placeholder="" value="<?php echo $row7["Commision"]; ?>" autocomplete="off" required oninput="TotAmount(document.getElementById('NonGstSale').value,document.getElementById('Commision').value)">
<div class="clearfix"></div>
    </div> 
    
    <div class="form-group col-md-2">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="text" name="SubAmount" id="SubAmount" class="form-control" placeholder="" value="<?php echo $row7["SubAmount"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-2">
<label class="form-label">TDS <span class="text-danger">*</span></label>
<input type="text" name="Tds" id="Tds" class="form-control" placeholder="" value="<?php echo $row7["Tds"]; ?>" autocomplete="off"  required oninput="calTotalAmt()">
<div class="clearfix"></div>
    </div>

<div class="form-group col-md-2">
<label class="form-label">Total Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount" id="Amount" class="form-control" placeholder="" value="<?php echo $row7["Amount"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
<div class="form-group col-md-2">
<label class="form-label">Note No <span class="text-danger">*</span></label>
<input type="text" name="NoteNo" id="NoteNo" class="form-control" placeholder="" value="<?php echo $InvoiceNo; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
    
<div class="form-group col-md-2">
<label class="form-label">Note Date <span class="text-danger">*</span></label>
<input type="date" name="NoteDate" id="NoteDate" class="form-control txt" placeholder="" value="<?php echo $NoteDate; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div> 
<div class="form-group col-md-2">
<label class="form-label">Payment Date <span class="text-danger">*</span></label>
<input type="date" name="PaymentDate" id="PaymentDate" class="form-control txt" placeholder="" value="<?php echo $PaymentDate; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
<div class="form-group col-md-4">
<label class="form-label">Bank Reference No <span class="text-danger">*</span></label>
<input type="text" name="BankRefNo" id="BankRefNo" class="form-control txt" placeholder="" value="<?php echo $row7["BankRefNo"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

    
<div class="form-group col-md-8">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <input type="text" name="Narration" id="Narration" class="form-control" required value="<?php echo $row7["Narration"]; ?>">
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
 function calNonGstAmt(){
     var SalesAmount = $('#SalesAmount').val();
     var NonGstSale = Number(SalesAmount)/1.05;
     $('#NonGstSale').val(Math.round(NonGstSale));
 }
  function TotAmount(gstamt,comm){
      var TotAmt = Number(gstamt) * (Number(comm)/100);
      //$('#Amount').val(parseFloat(TotAmt).toFixed(2));
      $('#SubAmount').val(Math.round(TotAmt));
  }
  
  function calTotalAmt(){
      var Tds = $('#Tds').val();
      var SubAmount = $('#SubAmount').val();
      var Amount = Number(SubAmount)-(Number(SubAmount)*(Number(Tds)/100));
      $('#Amount').val(Math.round(Amount));
      
  }
</script>
</body>
</html>