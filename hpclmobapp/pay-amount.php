<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Send Money";
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
        
        
        <?php 
            $mobno = $_REQUEST['mobno'];
            $sql = "SELECT * FROM tbl_users WHERE Phone='$mobno'";
            $rncnt = getRow($sql);
            $row = getRecord($sql);
            if($rncnt > 0){}
            else{
                echo "<script>window.location.href='mobile-not-found.php';</script>";
            }
        ?>
        
        <div class="main-container">     
        <div class="container mb-4">
              
            <div class="container mt-3 mb-4 text-center">
            <h2 class="">&#8377; <?php echo number_format($mybalance,2); ?></h2>
            <p class=" mb-4">Total Balance</p>
        </div>
        
                <div class="card">
                    <div class="card-body">
                     
                     <div align="center"> 
                      <?php if($row['Photo'] == ''){?>
                     <img class="avatar avatar-50 shadow-sm rounded-circle ml-2" src="no_profile.jpg" alt="">
                     <?php } else { ?>
                      <img class="avatar avatar-50 shadow-sm rounded-circle ml-2" src="../uploads/<?php echo $row['Photo'];?>" alt="">
                     <?php } ?>
                        <h4 style="font-size:17px;padding-top:10px;">Paying <?php echo $row['Fname'];?></h4>
                        <p class=" mb-2"><?php echo $row['Phone'];?></p>
                        </div><br>
                        
         <form id="validation-form" method="post" autocomplete="off">
               <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
            <div class="container mb-4">
                
                  <input type="hidden" name="UserId2" value="<?php echo $row['id']; ?>" id="UserId2">  
                <div class="form-group mb-1">
                    <input type="number" name="Amount" id="Amount" class="form-control large-gift-card" value="" placeholder="&#x20B9; 0" autofocus>
                </div>
                
                <div class="form-group float-label active">
                    <input type="text" class="form-control" name="Narration" id="Narration" value="" >
                      <label class="form-control-label">Add a Note</label>
                </div>
                 
                

               
                <!-- <div class="form-group float-label active">
                            <input type="number" class="form-control" name="Phone" id="Phone" value="" oninput="chechPhone()">
                            <label class="form-control-label">Phone Number</label>                            
                        </div>
                        
                 <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Fname" id="Fname" value="" readonly>
                            <label class="form-control-label">Full Name</label>
                        </div>
                        
                <div class="form-group float-label active">
                    <textarea class="form-control" name="Narration" id="Narration" placeholder=""></textarea>
                      <label class="form-control-label">Add a Note</label>
                </div>-->
            </div>
            
              <input type="hidden" class="form-control" name="Phone" id="Phone" value="<?php echo $mobno;?>">
              <input type="hidden" class="form-control" name="Fname" id="Fname" value="<?php echo $row['Fname'];?>" readonly>

            <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="SendMoney" id="action">  
            
                      <div class="container text-center">
                 <button class="btn btn-default mb-2 mx-auto rounded" type="submit" id="submit">Transfer</button>
                 <p style="color:red;display:none" id="error_msg">Invalid Phone No</p> 
                 <a href="add-money.php" class="btn btn-default mb-2 mx-auto rounded">Add Money</a>
                </div>
                <p style="color:red;display:none;text-align:center;" id="error_msg2">You have insufficient balance</p>
            </form>
            
            
            
        </div>
        
        
        </div>
    </main>

    <!-- footer-->
    
    <?php include_once 'footer.php';?>

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
      function chechBal(){
        var UserId = $('#UserId').val();
        var action = "chechBal";
         $.ajax({
    url:"ajax_files/ajax_wallet.php",
    method:"POST",
    data : {action:action},
    success:function(data)
    {
        console.log(data);
        if(data > 0){
            $('#submit').attr('disabled',false);
            $('#error_msg2').hide();
        }
        else{
            $('#submit').attr('disabled','disabled');
            $('#error_msg2').show();
        }
    }
    });
     }
    $(document).ready(function() {
      chechBal();
             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var Amount = $('#Amount').val();
       var UserId2 = $('#UserId2').val();
                var Lname = $('#Lname').val();
                var Phone = $('#Phone').val();

                if(Amount.trim() == ''){
                //   $('#danger_message').css('display','block').html("Please Enter Amount");
                //         setTimeout(function(){  
                //         $('#danger_message').fadeOut("Slow"); 
                //     }, 2000); 
                 toastr.error('Please Enter Amount', 'Error', {timeOut: 2000}); 
                    $('#Amount').focus();
                }
             
            
               
    else { 
      
         $.ajax({  
                url :"ajax_files/ajax_wallet.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  //console.log(data);exit();
                  var res = JSON.parse(data);
                  var amount = res.amount;
                  var paidto = res.paidto;
                     if(data == 0){
                        toastr.error('Amount Not Transfer', 'Error', {timeOut: 1000}); 
                     
                     }
                     else{
                    //toastr.success('Amount Transfer Successfully!', 'Success', {timeOut: 5000}); 
                    window.location.href="transfer-success.php?amount="+amount+"&paidto="+paidto;
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Transfer');
                }  
           })  



    }

  });

          
});
</script>
</body>

</html>
