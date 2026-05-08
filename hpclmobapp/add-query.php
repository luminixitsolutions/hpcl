<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Ask Query";
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
        <div class="main-container">
            <div class="container">
               
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                   
                    <div class="card-body">
                         

                     
                        <div class="form-group float-label active">
                            <textarea class="form-control" id="Questions" name="Questions"><?php echo $row11['Questions']; ?></textarea>
                            <label class="form-control-label">Query</label>                            
                        </div>
                        

                     
                  
                
                    </div>
                        
                          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="SaveQuery" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Submit</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>
<script src="js/toastr.min.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function() {

             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var Questions = $('#Questions').val();
       
                
             if(Questions.trim() == ''){
                    //alert("Please Enter Address");
                    $('#Questions').focus();
                }
               
    else { 
      
         $.ajax({  
                url :"ajax_files/ajax_queries.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  console.log(data);
                     if(data == 0){
                        toastr.error('Record Not Saved.', 'Error', {timeOut: 1000}); 
                     
                     }
                     else{
                    toastr.success('Query Created Successfully!', 'Success', {timeOut: 5000}); 
                    window.location.href="view-query.php";
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           })  



    }

  });

           $(document).on("change", "#CountryId", function(event){
  var val = this.value;
   var action = "getState";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {

      $('#StateId').html(data);
    }
    });

 });


     $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId').html(data);
    }
    });

 });
});
</script>
</body>

</html>
