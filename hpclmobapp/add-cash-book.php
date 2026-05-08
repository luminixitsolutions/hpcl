<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "My Expenses";
$Page = "Recharge";
$WallMsg = "NotShow"; 
$url = "home.php";
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <?php include 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       
<?php include 'top_header.php';?>

        <!-- page content start -->
<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_cash_uses WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="view-cash-uses.php";
    </script>
<?php } ?>

  <?php 
       $FranchiseId = $_SESSION['FranchiseId'];

$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cash_book WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');

$sql22 = "SELECT SUM(TotalCashAmt) AS TotalCashAmt FROM (SELECT SUM(NetAmount) AS TotalCashAmt FROM tbl_customer_invoice WHERE FrId='$FranchiseId' AND PayType='Cash' UNION ALL SELECT SUM(NetAmount) AS TotalCashAmt FROM tbl_customer_invoice_2025 WHERE FrId='$FranchiseId' AND PayType='Cash') as a";
$row22 = getRecord($sql22);

$sql221 = "SELECT SUM(Amount) AS TotalTransferAmt FROM tbl_cash_book WHERE FrId='$FranchiseId' AND ApproveStatus=1";
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
    $dest = '../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Files = $imagepath ;
    } 
    else{
      $Files = $_POST['OldFiles'];
    }

    if($_GET['id'] == ''){
     $qx = "INSERT INTO tbl_cash_book SET FrId='$FranchiseId',FromDate='$FromDate',ToDate='$ToDate',TotalAmount='$TotalAmount',Amount='$Amount',
            BalAmt='$BalAmt',TransferDate='$TransferDate',Narration='$Narration',BankName='$BankName',AccountNo='$AccountNo',Files='$Files',
            CreatedBy='$user_id',CreatedDate='$CreatedDate',BankId='$BankId'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);
  echo "<script>alert('Record Saved Successfully!');window.location.href='view-cash-book.php';</script>";
}
else{
    $sql = "UPDATE tbl_cash_book SET FrId='$FranchiseId',FromDate='$FromDate',ToDate='$ToDate',TotalAmount='$TotalAmount',Amount='$Amount',
            BalAmt='$BalAmt',TransferDate='$TransferDate',Narration='$Narration',BankName='$BankName',AccountNo='$AccountNo',Files='$Files',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',BankId='$BankId' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Record Updated Successfully!');window.location.href='view-cash-book.php';</script>";
}
      
   

    //header('Location:courses.php'); 

  }
 ?>


        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
              
                <div class="card mb-4">
                    <form id="validation-form" method="post" enctype="multipart/form-data">
                         <div class="card-body">
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

<!--<div class="form-group col-md-4">
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>-->
    
    <div class="form-group col-md-4 ">
<label class="form-label">Bank Name</label>
 <select class="form-control" name="BankId" id="BankId" onchange="getAccNo(this.value)">
<option selected="" value="">Select Bank</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_banks WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['BankName']; ?></option>
<?php } ?>
</select>
</div>
<input type="hidden" name="BankName" id="BankName" value="<?php echo $row7["BankName"]; ?>">
    
    <div class="form-group col-md-4">
<label class="form-label">Account No <span class="text-danger">*</span></label>
<input type="text" name="AccountNo" id="AccountNo" class="form-control" placeholder="" value="<?php echo $row7["AccountNo"]; ?>" autocomplete="off" required readonly>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-12">
<label class="form-label">Upload Receipt 2<span class="text-danger">*</span></label>
<input type="file" name="Files" id="Files" class="form-control" placeholder="" autocomplete="off" accept=".png">
<input type="hidden" name="OldFiles" value="<?php echo $row7['Files'];?>" id="OldFiles">
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
</div>
</form>
                    
                </div>


            </div>
        </div>
    </main>

<?php include 'footer.php';?>
  <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>
    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
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
                url: "ajax_files/ajax_dropdown.php",
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
