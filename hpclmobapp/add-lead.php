<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "New Lead";
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
        <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_bp_leads WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

 
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                   <div class="card-body">
                       <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>">
                   <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Name" id="Name" value="<?php echo $row7['Name']; ?>" autofocus required>
                            <label class="form-control-label">Customer Name</label>
                        </div>
                        
                         

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Phone" id="Phone" value="<?php echo $row7['Phone']; ?>" required>
                            <label class="form-control-label">Contact No</label>
                        </div>

                        <div class="form-group float-label active">
                            <input type="email" class="form-control" name="EmailId" id="EmailId" value="<?php echo $row7['EmailId']; ?>" >
                            <label class="form-control-label">Email Id</label>
                        </div>
                         
                        

                        <div class="form-group float-label active">
                            <input type="text" class="form-control" id="Address" name="Address" value="<?php echo $row7['Address']; ?>">
                            <label class="form-control-label">Address</label>                            
                        </div>
                        
                         <div class="form-group float-label active">
                            <input type="date" class="form-control" name="CreatedDate" id="CreatedDate" value="<?php echo $row7['CreatedDate']; ?>">
                            <label class="form-control-label">Lead Date</label>                            
                        </div>
                     
                  
                
                    </div>
                        
                          <input type="hidden" name="UserId" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="SaveLead" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" name="submit" id="submit">Submit</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
        $.ajax({  
                url :"ajax_files/ajax_lead.php",  
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
                        toastr.error('Lead Already Saved ', 'Error', {timeOut: 5000}); 
                     
                     }
                     else{
                    toastr.success('Lead Saved Saved Successfully!', 'Success', {timeOut: 2000}); 
                    window.location.href="view-leads.php";
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Submit');
                }  
           });
        });

  });
</script>
   
</body>

</html>
