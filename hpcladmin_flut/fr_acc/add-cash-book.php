<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "View-Cash-Book";
$Page = "View-Cash-Book";
$sessionid = session_id();
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
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
$sql7 = "SELECT * FROM tbl_cash_book WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');

$sql22 = "SELECT SUM(TotalCashAmt) AS TotalCashAmt FROM (SELECT SUM(NetAmount) AS TotalCashAmt FROM tbl_customer_invoice WHERE FrId='$BillSoftFrId' AND PayType='Cash' UNION ALL 
          SELECT SUM(NetAmount) AS TotalCashAmt FROM tbl_customer_invoice_2025 WHERE FrId='$BillSoftFrId' AND PayType='Cash') as a";
          //echo $sql22;
$row22 = getRecord($sql22);

$sql221 = "SELECT SUM(Amount) AS TotalTransferAmt FROM tbl_cash_book WHERE FrId='$BillSoftFrId' AND ApproveStatus=1";
$row221 = getRecord($sql221);

if($_GET['id'] == ''){
    $TotalAmount = $row22["TotalCashAmt"] - $row221['TotalTransferAmt'];
}
else{
  $TotalAmount =   $row7['TotalAmount'];
}
?>

<?php 
  if(isset($_POST['submit'])){
    $FromDate = addslashes(trim($_POST['FromDate']));
    $ToDate = addslashes(trim($_POST['ToDate']));
    $TotalAmount = addslashes(trim($_POST['TotalAmount']));
    $Amount = addslashes(trim($_POST['Amount']));
    $BalAmt = addslashes(trim($_POST['BalAmt']));
    $TransferDate = addslashes(trim($_POST['TransferDate']));
    $BankName = addslashes(trim($_POST['BankName']));
    $AccountNo = addslashes(trim($_POST['AccountNo']));
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));
    $BankId = addslashes(trim($_POST['BankId']));
    
    $randno = rand(1,100);
    $src = $_FILES['Files']['tmp_name'];
    $fnm = substr($_FILES["Files"]["name"], 0,strrpos($_FILES["Files"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Files"]["name"],strpos($_FILES["Files"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Files = $imagepath ;
    } 
    else{
      $Files = $_POST['OldFiles'];
    }

    if($_GET['id'] == ''){
        $sql = "SELECT * FROM tbl_cash_book WHERE FrId='$BillSoftFrId' AND TransferDate='$TransferDate' AND Amount='$Amount'";
        $rncnt = getRow($sql);
        if($rncnt > 0){
            echo "<script>alert('Record Already Exists!');window.location.href='add-cash-book.php';</script>";
        }
        else{
     $qx = "INSERT INTO tbl_cash_book SET FrId='$BillSoftFrId',FromDate='$FromDate',ToDate='$ToDate',TotalAmount='$TotalAmount',Amount='$Amount',
            BalAmt='$BalAmt',TransferDate='$TransferDate',Narration='$Narration',BankName='$BankName',AccountNo='$AccountNo',Files='$Files',
            CreatedBy='$user_id',CreatedDate='$CreatedDate',BankId='$BankId'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);
  
  $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new cash book record added',invid='$InvId',createddate='$createddate',roll='cashbook'";
  $conn->query($sql);
  
  echo "<script>alert('Record Saved Successfully!');window.location.href='view-cash-book.php';</script>";
        }
}
else{
    $sql = "UPDATE tbl_cash_book SET Amount='$Amount',
           TransferDate='$TransferDate',Narration='$Narration',BankName='$BankName',AccountNo='$AccountNo',Files='$Files',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',BankId='$BankId' WHERE id='$id'";
    $conn->query($sql);
    
    $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='cash book record updated',invid='$id',createddate='$createddate',roll='cashbook'";
  $conn->query($sql);
    echo "<script>alert('Record Updated Successfully!');window.location.href='view-cash-book.php';</script>";
}
      
   

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add Cash Book</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<div class="row">
<div class="col-lg-8">
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">


 
    <div class="form-group col-md-4">
<label class="form-label">Total Cash Amount </label>
<input type="text" name="TotalAmount" id="TotalAmount" class="form-control" placeholder="" value="<?php echo $TotalAmount; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

    <div class="form-group col-md-4">
<label class="form-label">Transfer Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount" id="Amount" class="form-control" placeholder="" value="<?php echo $row7["Amount"]; ?>" autocomplete="off" required oninput="balAmt()">
<div class="clearfix"></div>
    </div>

   <div class="form-group col-md-4">
<label class="form-label">Balance Amount <span class="text-danger">*</span></label>
<input type="text" name="BalAmt" id="BalAmt" class="form-control" placeholder="" value="<?php echo $row7["BalAmt"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>
    
<div class="form-group col-md-4">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="TransferDate" id="TransferDate" class="form-control" placeholder="" value="<?php echo $row7["TransferDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<div class="form-group col-md-4 ">
<label class="form-label">Bank Name</label>
 <select class="form-control" name="BankId" id="BankId" onchange="getAccNo(this.value)">
<option selected="" value="">Select Bank</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_banks WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["BankId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['BankName']; ?></option>
<?php } ?>
</select>
</div>
<input type="hidden" name="BankName" id="BankName" value="<?php echo $row7["BankName"]; ?>">
<!--<div class="form-group col-md-4">
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>-->
    
    <div class="form-group col-md-4">
<label class="form-label">Account No <span class="text-danger">*</span></label>
<input type="text" name="AccountNo" id="AccountNo" class="form-control" placeholder="" value="<?php echo $row7["AccountNo"]; ?>" autocomplete="off" required readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-12">
<label class="form-label">Upload Receipt <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" name="Files" id="Files" class="form-control" placeholder="" autocomplete="off">
<input type="hidden" name="OldFiles" value="<?php echo $row7['Files'];?>" id="OldFiles">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Files']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3">
    <a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a>
    <a href="../uploads/<?php echo $row7['Files'];?>" alt="" class="img-fluid ticket-file-img" download>View Receipt</a></div>
</span>
<?php } ?>
    </div>
    
<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"  
                                                ><?php echo $row7['Narration']; ?></textarea>
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Submit</button>
<span id="error_msg" style="color:red;"></span>

</form>
</div>
<div class="col-lg-4" id="showcart">
    
        
</div>
</div>
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
 function balAmt(){
     var TotalAmount = $('#TotalAmount').val();
      var Amount = $('#Amount').val();
      var BalAmt = Number(TotalAmount) - Number(Amount);
       $('#BalAmt').val(parseFloat(BalAmt).toFixed(2));
           
 }
 
 function getAccNo(id){
     var action = "getAccNo";
            $.ajax({
                url: "ajax_files/ajax_customer_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    var res = JSON.parse(data);
                    var BankName = res.BankName;
                    var AccNo = res.AccNo;
                  $('#BankName').val(BankName);
                  $('#AccountNo').val(AccNo);
                }
            });
 }
  function getCashAmount(FromDate,ToDate,FrId){
     var action = "getCashAmount";
            $.ajax({
                url: "ajax_files/ajax_customer_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    FromDate: FromDate,
                    ToDate:ToDate,
                    FrId:FrId
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                  $('#TotalAmount').val(parseFloat(data).toFixed(2));
                   $('#Amount').val(parseFloat(data).toFixed(2));
                    balAmt();
                }
            });
  }
   
</script>
</body>
</html>