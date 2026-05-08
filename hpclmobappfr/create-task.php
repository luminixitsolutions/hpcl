<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Create Task";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; ?>
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

<style>
    .custom-control {
  line-height: 24px;
  padding-top: 5px;
}
</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
<?php 
if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    echo "<script>window.location.href='take-survey.php?custid=$CustId';</script>";
}
?>
        <div class="main-container">
            <div class="container">
               
<form id="validation-form" method="post" autocomplete="off">
               

                <div class="card">
                     
                
                   
                    <div class="card-body">
                      
                         
                          <div class="card-body">
                      
                         
                         <div class="form-group float-label active">
                            <input type="date" name="TaskDate" id="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>">
                            <label class="form-control-label">Task Date</label>                            
                        </div>

                        <div class="form-group float-label active">
                          <textarea name="TaskName" id="TaskName" class="form-control"
                                                placeholder=""><?php echo $row7["TaskName"]; ?></textarea>
                            <label class="form-control-label active">Task Name</label>
                        </div>
                   
                        
                <input type="hidden" name="ExeId" value="<?php echo $_SESSION['User']['id']; ?>" id="ExeId">  
                <input type="hidden" name="CustId" value="" id="CustId">  
                      <input type="hidden" name="action" value="Save" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="button" name="submit" id="submit" onclick="save()">Created</button>
                    </div>
               
                </div>
            </div>
             </form>
        </div>
    </main>

    <!-- footer-->
    


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
    
     <script type="text/javascript">

        function save(){
            var TaskDate = $('#TaskDate').val();
            var TaskName = $('#TaskName').val();
            var action = "SaveTask";
    $.ajax({
    url:"ajax_files/ajax_queries.php",
    method:"POST",
    data : {action:action,TaskDate:TaskDate,TaskName:TaskName},
    success:function(data)
    {
        console.log(data);
       
        if(data == 1){
            toastr.success('Task Created Successfully!', 'Success', {timeOut: 2000});  
            window.location.href="view-task.php";
              
        }
        else{
            toastr.error('Task Not Created. Try Again...', 'Error', {timeOut: 5000}); 
        }
      
    }
    });
        }
    
</script>
</body>

</html>
