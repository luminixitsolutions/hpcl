<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "KYC";
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
$sql7 = "SELECT * FROM tbl_kyc WHERE id='".$_GET['id']."'";
$row7 = getRecord($sql7);
?>
        <div class="main-container">
            <div class="container">
               
<form id="validation-form" method="post" autocomplete="off">
     <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
               

                <div class="card">
                     
                
                   
                    <div class="card-body">
                      
                         <div class="form-group float-label active">
                            <input type="text" name="Name" id="Name" class="form-control"
                                                placeholder="" value="<?php echo $row7["Name"]; ?>" autofocus>
                            <label class="form-control-label">Full Name</label>                            
                        </div>
                        
              

                        <div class="form-group float-label active">
                          <input type="text" name="Address" id="Address" class="form-control"
                                                placeholder="" value="<?php echo $row7["Address"]; ?>">
                            <label class="form-control-label active">Address</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="" value="<?php echo $row7["Phone"]; ?>">
                            <label class="form-control-label active">Mobile Number</label>
                        </div>

                         <div class="form-group float-label active">
                          <input type="text" name="Phone2" id="Phone2" class="form-control"
                                                placeholder="" value="<?php echo $row7["Phone2"]; ?>">
                            <label class="form-control-label active">Alteranate Mobile Number</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Profession" id="Profession" class="form-control"
                                                placeholder="" value="<?php echo $row7["Profession"]; ?>">
                            <label class="form-control-label active">Current Profession</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="PanNo" id="PanNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PanNo"]; ?>">
                            <label class="form-control-label active">Pan Card Number</label>
                        </div>

                         <div class="form-group float-label active">
                          <input type="text" name="AadharNo" id="AadharNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["AadharNo"]; ?>">
                            <label class="form-control-label active">Aadhar Card Number</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="FsiicNo" id="FsiicNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["FsiicNo"]; ?>">
                            <label class="form-control-label active">FSIIC Number</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="ShopActNo" id="ShopActNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["ShopActNo"]; ?>">
                            <label class="form-control-label active">Shop Act Number</label>
                        </div>

                         <div class="form-group float-label active">
                            <input type="date" name="Dob" id="Dob" class="form-control"
                                                placeholder="" value="<?php echo $row7["Dob"]; ?>">
                            <label class="form-control-label">Date Of Birth</label>                            
                        </div>

                         <div class="form-group float-label active">
                            <input type="date" name="AnniversaryDate" id="AnniversaryDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["AnniversaryDate"]; ?>">
                            <label class="form-control-label">Date Of Anniversary</label>                            
                        </div>

                        <div class="form-group float-label active">
                            <input type="date" name="ShopOpenDate" id="ShopOpenDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ShopOpenDate"]; ?>">
                            <label class="form-control-label">Shop Opening Date</label>                            
                        </div>

                       
                   
                   
                        
                <input type="hidden" name="ExeId" value="<?php echo $_SESSION['User']['id']; ?>" id="ExeId">  

                      <input type="hidden" name="action" value="SaveKyc" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit">Submit</button>
                    </div>

                </div>
            </div>
             </form>
        </div>
    </main>

    <!-- footer-->
    
<?php include_once 'footer.php'; ?>

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
    $(document).ready(function() {
             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
        $.ajax({  
                url :"ajax_files/ajax_survey.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 

               
                 if(data == 0){
                        toastr.error('KYC Form Not Submitted ', 'Error', {timeOut: 5000}); 
                     
                     }
                     else{
                    toastr.success('KYC Form Submitted Successfully!', 'Success', {timeOut: 2000}); 
                    window.location.href="view-kyc.php";
                     }

                
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           });
        });

  });
</script>
</body>

</html>
