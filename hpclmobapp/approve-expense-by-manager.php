<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Approve Expenses";
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
       
                        <?php 
$id = $_GET['id'];
$sql7 = "SELECT te.*,tu.Fname,tu.Lname,tu.Photo AS Uphoto FROM tbl_expense_request te LEFT JOIN tbl_users tu ON tu.id=te.UserId WHERE te.id='$id'";
$row7 = getRecord($sql7);

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='".$row7['UserId']."' GROUP BY Status) as a";
    $row88 = getRecord($sql88);
    $Wallet = $row88['Credit'] - $row88['Debit'];
    
  if(isset($_POST['submit'])){
    $ApproveDate = addslashes(trim($_POST["ApproveDate"]));
     $MannagerComment = addslashes(trim($_POST["MannagerComment"]));
  $MgrAmount = addslashes(trim($_POST["MgrAmount"]));
  $ManagerStatus = addslashes(trim($_POST["ManagerStatus"]));

 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_expense_request SET ApproveDate='$ApproveDate',MannagerComment='$MannagerComment',MgrAmount='$MgrAmount',ManagerStatus='$ManagerStatus',MrgBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);
  ?>
  <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Success",
  text: "Approved Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="manager-expense-request.php";
  }
}); });</script>
<?php 

  }  
?>


 
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                   <div class="card-body">
                       <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>">
                   <div class="form-group float-label active">
                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['Fname']." ".$row7['Lname']; ?>" readonly>
                            <label class="form-control-label">Employee Name</label>
                        </div>
                        
                         <div class="form-group float-label active">
                             <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $Wallet; ?>" readonly>
                            <label class="form-control-label">Wallet Amount</label>                            
                        </div>
                        
                         <div class="form-group float-label active">
                             <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["Amount"]; ?>" readonly>
                            <label class="form-control-label">Expense Amount</label>                            
                        </div>
                        
                        <div class="form-group float-label active">
                            <input type="text" name="MgrAmount" class="form-control"
                                                placeholder="" value="<?php echo $row7["Amount"]; ?>" required>
                                                <label class="form-control-label">Approve Amount</label>                            
                        </div>
                        
                         <div class="form-group float-label active">
                           <input type="date" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ExpenseDate"]; ?>" readonly>
                                                
                                             <label class="form-control-label">   Approve Amount</label>                            
                        </div>
                        
                         <div class="form-group float-label active">
                           <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["Narration"]; ?>" readonly>
                                                
                                             <label class="form-control-label">   Expense For</label>                            
                        </div>
                        
                        <div class="form-group float-label active">
                           <input type="date" name="ApproveDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ApproveDate"]; ?>" required>
                                                
                                             <label class="form-control-label">   Approve Date</label>                            
                        </div>
                        
                        <div class="form-group float-label active">
                           <select class="form-control" id="ManagerStatus" name="ManagerStatus" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["Status"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($row7["Status"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($row7["Status"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select>
                                                
                                             <label class="form-control-label">  Status</label>                            
                        </div>

                        


                   

                        <div class="form-group float-label active">
                             <textarea name="MannagerComment" class="form-control"  placeholder=""><?php echo $row7["MannagerComment"]; ?></textarea>
                            <label class="form-control-label">Comment</label>                            
                        </div>
                        
                        
                          <input type="hidden" name="CreatedBy" value="<?php echo $_SESSION['User']['id']; ?>" id="CreatedBy">  
                      <input type="hidden" name="action" value="NewReg" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit" onclick="save()">Submit</button>
                    </div><br><br><br>
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
    
   <script>
       /*function save(){
           $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
       }*/
   </script>
</body>

</html>
