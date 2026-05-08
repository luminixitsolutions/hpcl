<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
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
$PageName = "My Expenses";
$Page = "Recharge";
$WallMsg = "NotShow"; 
$url = "home.php";?>
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
$sql7 = "SELECT * FROM tbl_cash_uses WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

<?php 
  if(isset($_POST['submit'])){
$Narration = addslashes(trim($_POST["Narration"]));
$Amount = addslashes(trim($_POST["Amount"]));
$PaymentMode = addslashes(trim($_POST["PaymentMode"]));
$ExpenseDate = addslashes(trim($_POST["ExpenseDate"]));
$Gst = addslashes(trim($_POST["Gst"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
    $Photo2 = $_POST['OldPhoto2'];
}

if($_GET['id']==''){
     $qx = "INSERT INTO tbl_cash_uses SET UserId = '$FranchiseId',Status='0',Narration = '$Narration',Amount='$Amount',ExpenseDate='$ExpenseDate',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
  $conn->query($qx);
  //$to = $row55['ExpMail'];
  //$allmail = $row55['AllMail'];
  //include("incenquirymail.php");
  //include("sendmailsmtp.php");?>
  <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "Cash Uses successfully.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-cash-uses.php";
  }
}); });</script>
<?php 
}
else{
 
    $query2 = "UPDATE tbl_cash_uses SET Narration = '$Narration',Amount='$Amount',ExpenseDate='$ExpenseDate',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);
  ?>
  <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: " updated successfully .",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-cash-uses.php";
  }
}); });</script>
<?php 

}
    //header('Location:courses.php'); 

  }
 ?>
        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
                 <div style="float:right;">
                                                                   
                 <a href="add-cash-book.php" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>
               
               
               

                        <?php 
$sql = "SELECT * FROM tbl_cash_book WHERE FrId='$FranchiseId' ORDER BY id DESC";
$row = getList($sql);

foreach ($row as $result) {
    // Format amount
    $amount = number_format($result['Amount'], 2);

    // Format date
    $transferDate = date("d/m/Y", strtotime(str_replace('-', '/', $result['TransferDate'])));
    $approveDate = !empty($result['ApproveDate']) ? date("d/m/Y", strtotime(str_replace('-', '/', $result['ApproveDate']))) : '—';

    // Status formatting
    if ($result['ApproveStatus'] == 1) {
        $statusText = "Approved";
        $statusClass = "success";
    } elseif ($result['ApproveStatus'] == 2) {
        $statusText = "Rejected";
        $statusClass = "danger";
    } else {
        $statusText = "Pending";
        $statusClass = "warning";
    }

    // Approve comment
    $approveComment = !empty($result['ApproveComment']) ? htmlspecialchars($result['ApproveComment']) : 'No comments';
?>
    <div class="card shadow-sm border-left-<?php echo $statusClass; ?> mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Amount & Date -->
                <div class="col-md-8">
                    <h5 class="mb-1 text-dark">&#8377; <?php echo $amount; ?></h5>
                    <p class="mb-1 text-muted">
                        <i class="fa fa-calendar-alt"></i> <?php echo $transferDate; ?>
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-info-circle"></i> <?php echo htmlspecialchars($result['Narration']); ?>
                    </p>

                    <!-- Status Badge -->
                    <p class="mb-1">
                        <strong>Status: </strong>
                        <span class="badge badge-<?php echo $statusClass; ?>">
                            <?php echo $statusText; ?>
                        </span>
                        <?php if ($result['ApproveStatus'] != 0) { ?>
                            <small class="text-muted ml-2">
                                <i class="fa fa-check-circle"></i> <?php echo $approveDate; ?>
                            </small>
                        <?php } ?>
                    </p>

                    <!-- Comment -->
                    <p class="text-secondary mb-0">
                        <strong>Comment:</strong> <?php echo $approveComment; ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-4 text-right">
                    <!-- Example action buttons if needed -->
                    <!--
                    <a href="add-cash-book.php?id=<?php echo $result['id']; ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    -->
                </div>
            </div>
        </div>
    </div>
<?php 
} 
?>

                        
                        <input type="text" class="Exp_Id" value="">
                    
                    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h5 class="modal-title">Are you sure?</h5>
      </div>
      <div class="modal-body">
          
        <button type="button" class="btn btn-success" onclick="delete()">YES</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
      </div>
     
    </div>

  </div>
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
    function getExpId(id){
        $('#myModal').modal('show');
        $('.Exp_Id').val(id);
    }
</script>
   
</body>

</html>
