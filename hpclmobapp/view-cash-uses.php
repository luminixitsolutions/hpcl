<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
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
                                                                   
                 <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>
               
               
               <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">Use Cash Amount</h4>
      </div>
      <div class="modal-body">
        
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
                            <label class="form-control-label">Date</label>                            
                        </div>

                   

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Narration" name="Narration" value="<?php echo $row7['Narration']; ?>">
                            <label class="form-control-label">Use For</label>                            
                        </div>
                        
                
                    </div>
                        
                          <input type="hidden" name="CreatedBy" value="<?php echo $_SESSION['User']['id']; ?>" id="CreatedBy">  
                      <input type="hidden" name="action" value="NewReg" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit" onclick="save()">Submit</button>
                    </div>
                </form>
                </div>
                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

                        <?php 
                             $sql = "SELECT * FROM tbl_cash_uses WHERE UserId='$FranchiseId' ORDER BY id DESC";
                            $row = getList($sql);
                            foreach($row as $result){
                            ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="mb-1">&#8377;<?php echo number_format($result['Amount'],2);?> </h5>
                                        <p class="text-secondary"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['ExpenseDate'])))?><br>
                                            <?php echo $result['Narration'];?>
                                       </p>
                                     
                                       
                                        
                                        

                                    </div>
                                    <div class="col-auto pl-0">
                                       
                                            <a href="add-cash-uses.php?id=<?php echo $result['id'];?>" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-edit"></i></a>
                                        <!--<a href="javascript:void(0)" onclick="getExpId(<?php echo $result['id'];?>)"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>-->
                                        <a href="view-cash-uses.php?action=delete&id=<?php echo $result['id'];?>"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>
                     
                                      
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                         <?php } ?>
                        
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
