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
        
        
        
        <div class="main-container">      
         <form id="validation-form" method="post" autocomplete="off">
               <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
            <div class="container mb-4">
                 
               

               
                 <div class="form-group float-label active">
                            <input type="number" class="form-control" name="WalletAmt" id="WalletAmt" value="<?php echo $mybalance;?>">
                            <label class="form-control-label">Available Wallet Amount</label>                            
                        </div>
                        
                 <div class="form-group float-label active">
                            <input type="text" class="form-control" name="Amount" id="Amount" value="" autofocus oninput="balAmount()">
                            <label class="form-control-label">Withdrawl Amount</label>
                        </div>
                        
                        <div class="form-group float-label active">
                            <input type="text" class="form-control" name="BalAmt" id="BalAmt" value="" readonly>
                            <label class="form-control-label">Balance Amount</label>
                        </div>
                        
                <div class="form-group float-label active">
                    <textarea class="form-control" name="Narration" id="Narration" placeholder=""></textarea>
                      <label class="form-control-label">Add a Note</label>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo $_SESSION['User']['id']; ?>" id="UserId">  
                      <input type="hidden" name="action" value="WithdrawReq" id="action">  
            
            <div class="container text-center">
                 <button class="btn btn-default mb-2 mx-auto rounded" type="submit" id="submit">Send Request</button>
                
            </div>
            </form>
        </div>
        
        
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
    
    function balAmount(){
        var WalletAmt = $('#WalletAmt').val();
        var Amount = $('#Amount').val();
        var Balance = Number(WalletAmt) - Number(Amount);
        $('#BalAmt').val(Balance);
    }
    function chechPhone(){
        var action = "checkPhone";
        var Phone = $('#Phone').val();
         $.ajax({
    url:"ajax_files/ajax_wallet.php",
    method:"POST",
    data : {action:action,Phone:Phone},
    success:function(data)
    {
        //alert(data);
        var res = JSON.parse(data);
        if(res.Status == 0){
            $('#Fname').val('');
        $('#UserId2').val(0);
        $('#error_msg').show();
        $('#submit').attr('disabled','disabled');
        }
        else{
        $('#Fname').val(res.Name);
        $('#UserId2').val(res.id);
        $('#error_msg').hide();
        $('#submit').attr('disabled',false);
        }
    }
    });
    }
    $(document).ready(function() {

             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var Amount = $('#Amount').val();
     
                if(Amount.trim() == ''){
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
                        toastr.error('previous Request already in pending queue..please wait for aprroval.', 'Error', {timeOut: 3000}); 
                     
                     }
                     else{
                    toastr.success('Amount Withdraw Request Sent Successfully!', 'Success', {timeOut: 2000}); 
                    window.location.href="view-withdraw-request.php";
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
