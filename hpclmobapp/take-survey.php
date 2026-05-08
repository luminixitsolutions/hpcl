<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Feedback";
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
        

        <div class="main-container">
            <div class="container">
               
<form id="validation-form" method="post" autocomplete="off">
     <input type="hidden" name="CustId" value="<?php echo $_REQUEST['custid'];?>">
               

                <div class="card">
                     
                
                   
                    <div class="card-body">
                      
                         <div class="form-group float-label active">
                            <input type="text" name="ShopName" id="ShopName" class="form-control"
                                                placeholder="" value="<?php echo $row7["ShopName"]; ?>" autofocus>
                            <label class="form-control-label">Shop Name</label>                            
                        </div>
                        
               <div class="form-group float-label active">
                            <input type="date" name="VisitDate" id="VisitDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["VisitDate"]; ?>">
                            <label class="form-control-label">Date Of Visit</label>                            
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="ShopLocation" id="ShopLocation" class="form-control"
                                                placeholder="" value="<?php echo $row7["ShopLocation"]; ?>">
                            <label class="form-control-label active">Shop Location</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="CleaningRate" id="CleaningRate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CleaningRate"]; ?>">
                            <label class="form-control-label active">Cleaning Rate from 1 to 5</label>
                        </div>

                         <div class="form-group float-label active">
                          <input type="text" name="EmpDressCode" id="EmpDressCode" class="form-control"
                                                placeholder="" value="<?php echo $row7["EmpDressCode"]; ?>">
                            <label class="form-control-label active">Employee Dress Code</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Offer" id="Offer" class="form-control"
                                                placeholder="" value="<?php echo $row7["Offer"]; ?>">
                            <label class="form-control-label active">Product Offer On Shop</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Quality" id="Quality" class="form-control"
                                                placeholder="" value="<?php echo $row7["Quality"]; ?>">
                            <label class="form-control-label active">Product Quality</label>
                        </div>

                         <div class="form-group float-label active">
                          <input type="text" name="Communication" id="Communication" class="form-control"
                                                placeholder="" value="<?php echo $row7["Communication"]; ?>">
                            <label class="form-control-label active">Client Communication</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Suggest" id="Suggest" class="form-control"
                                                placeholder="" value="<?php echo $row7["Suggest"]; ?>">
                            <label class="form-control-label active">Suggest to-do changes if any</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Remark" id="Remark" class="form-control"
                                                placeholder="" value="<?php echo $row7["Remark"]; ?>">
                            <label class="form-control-label active">Find Remark</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Rating" id="Rating" class="form-control"
                                                placeholder="" value="<?php echo $row7["Rating"]; ?>">
                            <label class="form-control-label active">Rating from 1 to 5</label>
                        </div>

                        <div class="form-group float-label active">
                          <input type="text" name="Contribution" id="Contribution" class="form-control"
                                                placeholder="" value="<?php echo $row7["Contribution"]; ?>">
                            <label class="form-control-label active">Contribution</label>
                        </div>
                   
                   
                        
                <input type="hidden" name="ExeId" value="<?php echo $_SESSION['User']['id']; ?>" id="ExeId">  

                      <input type="hidden" name="action" value="SaveFeedback" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit">Submit</button>
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
                        toastr.error('Today Survey Report Already Given ', 'Error', {timeOut: 5000}); 
                     
                     }
                     else{
                    toastr.success('Today Survey Report Sent Successfully!', 'Success', {timeOut: 2000}); 
                    window.location.href="survey-thank-you.php";
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
