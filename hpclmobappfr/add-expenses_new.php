<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Expense Request";
$UserId = $_SESSION['User']['id']; ?>
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
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  <link rel="stylesheet" href="example/css/slim.min.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
        <?php 
        
        $sql55 = "SELECT * FROM tbl_company_emails WHERE id=1";
        $row55 = getRecord($sql55);
        
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_expense_request WHERE id='$id'";
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

    
$sql23 = "SELECT * FROM tbl_crop_image WHERE UserId='$user_id' AND SrNo=3";
$res23 = $conn->query($sql23);
$row23 = $res23->fetch_assoc();
$Photo = $row23['Image'];

$sql22 = "SELECT * FROM tbl_crop_image WHERE UserId='$user_id' AND SrNo=4";
$res22 = $conn->query($sql22);
$row22 = $res22->fetch_assoc();
$Photo2 = $row22['Image'];
if($_GET['id']==''){
     $qx = "INSERT INTO tbl_expense_request SET Photo='$Photo',Photo2='$Photo2',UserId = '$UserId',Status='0',Narration = '$Narration',Amount='$Amount',PaymentMode = '$PaymentMode',ExpenseDate='$ExpenseDate',CreatedDate='$CreatedDate',CreatedBy='$user_id',Gst='$Gst'";
  $conn->query($qx);
  $to = $row55['ExpMail'];
  $allmail = $row55['AllMail'];
  include("incenquirymail.php");
  include("sendmailsmtp.php");
  $sql = "DELETE FROM tbl_crop_image WHERE UserId='$user_id'";
  $conn->query($sql);
  ?>
  <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "Your expenses request successfully submitted.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="view-expenses.php";
  }
}); });</script>
<?php 
}

    //header('Location:courses.php'); 

  }
 ?>
 
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                   <div class="card-body">
                       <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>">
                   <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Amount" id="Amount" value="<?php echo $row7['Amount']; ?>" autofocus required>
                            <label class="form-control-label">Amount</label>
                        </div>
                        
                         <div class="form-group float-label active">
                            <input type="date" class="form-control" name="ExpenseDate" id="ExpenseDate" value="<?php echo $row7['ExpenseDate']; ?>">
                            <label class="form-control-label">Expense Date</label>                            
                        </div>

                        
                          <div class="form-group float-label active">
                               <select class="form-control" name="PaymentMode" id="PaymentMode" required>
<option selected="" value="" disabled>Select Payment Type</option>
  <option value="Cash" <?php if($row7["PaymentMode"] == 'Cash') {?> selected <?php } ?>>
    By Cash</option>
<option value="Online" <?php if($row7["PaymentMode"] == 'Online') {?> selected <?php } ?>>
    By Online</option>
</select>
                                  <label class="form-control-label">Payment Type</label>
                            </div>


                   

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Narration" name="Narration" value="<?php echo $row7['Narration']; ?>">
                            <label class="form-control-label">Narration</label>                            
                        </div>
                        
                        
                          <div class="form-group float-label active">
                         <main>
    <div class="slim" data-service="example/async.php?Roll=3" data-did-remove="handleImageRemoval">
        
        <input type="file" name="slim[]" id="Photo" name="car3_logo" class="input_css"/>
      
    </div>
</main>
<label class="form-control-label">Upload Receipt</label>    
</div>

 <div class="form-group float-label active">
                         <main>
    <div class="slim" data-service="example/async.php?Roll=4" data-did-remove="handleImageRemoval">
        
        <input type="file" name="slim[]" id="Photo" name="car3_logo" class="input_css"/>
      
    </div>
</main>
<label class="form-control-label">Upload Payment Receipt</label>    
</div>

                         
                   <div class="form-group float-label active">
                               <select class="form-control" name="Gst" id="Gst" required>
<!--<option selected="" value="" disabled>GST</option>-->
<option value="No" <?php if($row7["Gst"] == 'No') {?> selected <?php } ?>>
   No</option>
  <option value="Yes" <?php if($row7["Gst"] == 'Yes') {?> selected <?php } ?>>
   Yes</option>

</select>
                                  <label class="form-control-label">GST</label>
                            </div>
                             <input type="hidden" name="CreatedBy" value="<?php echo $_SESSION['User']['id']; ?>" id="CreatedBy">  
                      <input type="hidden" name="action" value="NewReg" id="action">  
                    
                <div class="form-group float-label active" style="padding-bottom: 50px;">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit" onclick="save()">Submit</button>
                     </div>
                    </div>
                        
                         
                </form>
                </div>
            </div>
        </div>
    </main>

    <!-- footer-->
    
<?php include 'footer.php';?>

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
    <script src="example/js/slim.kickstart.min.js"></script>
  <script>
    // load this code when the document has loaded

    document.addEventListener('DOMContentLoaded', function() {

        // get a reference to the remove button

        var button = document.querySelector('#remove-button');

        // listen to clicks on the remove button

        button.addEventListener('click', function() {

            // get the element with id 'my-cropper'

            var element = document.querySelector('#my-cropper');

            // find the cropper attached to the element

            var cropper = Slim.find(element);

            // call the remove method on the cropper

            cropper.remove();

        });

    });

    </script>

  <script>

    function handleImageRemoval(data) {

        // can't continue without server file name

        if (!data.server) { return; }

        // setup request and send

        var name = data.server.file;

        var url = 'example/async-remove.php';

        var xhr = new XMLHttpRequest();

        xhr.open('GET', url + (url.indexOf('?')===-1?'?':':') + 'name=' + name, true);

        xhr.send();

    }

    </script>
</body>

</html>
