<?php session_start();
require_once 'config.php';
require_once ("database.php");
require_once 'auth.php';
$PageName = "Send Money";
$WallMsg = "NotShow";
$Page = "Home";
$UserId = $_SESSION['User']['id'];
$sql10 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row10 = getRecord($sql10);
$Name = $row10['Fname']." ".$row10['Lname'];
$Phone1 = $row10['Phone'];
$EmailId = $row10['EmailId'];


 //send notification to receiver user
    // $Title = "Amount Received";
    // $sql73 = "SELECT Tokens,id FROM tbl_users WHERE id='1' AND Tokens!=''";
    // $data=mysqli_query($con,$sql73);
        
    //     while($row=mysqli_fetch_array($data))
    //     {
            
    //         $ReceiverId = $row['id'];
    //         $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id',ReceiverId='$UserId2',Title='$Title',Message='$Narration3',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
    //         $conn->query($sql);

    //         $title = $Title;
    //         $body =  $Message;
    //         $reg_id = $row[0];
    //         $registrationIds = array($reg_id);
    //         //$imgurl = "https://rjorg.in/teasoftware/uploads/44_2504_tanduri-chai.jpg";
    //         //$url = "$SiteUrl/profile.php?id=$UserId";
    //         include '../incnotification.php';
         
    //     }
 ?>
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
    

        <div class="main-container">
            <div class="container mb-4">
              
            <div class="container mt-3 mb-4 text-center">
            <h2 class=""> &#8377;<?php echo number_format($mybalance,2); ?></h2>
            <p class=" mb-4">Total Balance</p>
        </div>
        <div class="main-container">
            <div class="">
                <div class="card">
                    <div class="card-body">
                        <h4>Enter a phone number</h4>
                        <p class=" mb-2">Pay someone using an verified phone number</p>
                        <div class="form-group float-label mb-0">
                            <input style="border:1px solid rgba(0, 0, 0, 0.2);" type="number" class="form-control" autofocus="" name="Phone" id="Phone" value="" oninput="chechPhone()">
                            <label class="form-control-label">Phone number</label>
                        </div>
                        <span id="error_msg" style="color: red;display:none;">You have insufficient balance</span>
                        <br><span id="error_msg2" style="color: red;display:none;">Invalid Mobile No</span>
                    </div>
                    <input type="hidden" name="UserId2" value="" id="UserId2">  
                    <div class="card-footer">
                        <button type="button" onclick="payAmount()" id="submit" class="btn btn-primary btn-block rounded" disabled>Pay Now</button>
                        <a href="add-money.php" class="btn btn-primary btn-block rounded">Add Money</a>
                    </div>
                    
                </div>
                <input type="hidden" name="UserId" value="<?php echo $UserId;?>" id="UserId">
                
                <!-- <div class="card">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush" id="userlist">
                            
                            
                        </ul>
                    </div>
                </div> -->
            

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
     function payAmount(){
         var Phone = $('#Phone').val();
         var UserId2 = $('#UserId2').val();
         window.location.href="pay-phone-no.php?phone="+Phone+"&uid="+UserId2;
     }
    function userLists(Phone){
        var action = "userLists";
         $.ajax({
    url:"ajax_files/ajax_wallet.php",
    method:"POST",
    data : {action:action,Phone:Phone},
    success:function(data)
    {
        $('#userlist').html(data);
    }
    });
    }
    
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
            $('#error_msg').hide();
        }
        else{
            $('#submit').attr('disabled','disabled');
            $('#error_msg').show();
        }
    }
    });
     }

    function getUserDetails(Phone){
        var action = "checkPhone";
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
        $('#error_msg2').show();
        //$('#Phone').val('');
        $('#submit').attr('disabled','disabled');
        }
        else{
        $('#Fname').val(res.Name);
        $('#UserId2').val(res.id);
        $('#Phone').val(Phone);
        $('#error_msg2').hide();
        $('#submit').attr('disabled',false);
        $('#userlist').html('');
        }
        chechBal();
    }
    });
    }
     function chechPhone(){
        var action = "checkPhone";
        var Phone = $('#Phone').val();
        //userLists(Phone);
        if(! /^([0-9]{10})+$/.test(Phone)) {
            //alert("Mobile Number must be 10 Digit!");
            // $('#Phone').focus();
        }
        else{
         $.ajax({
    url:"ajax_files/ajax_wallet.php",
    method:"POST",
    data : {action:action,Phone:Phone},
    success:function(data)
    {
        console.log(data);
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
        chechBal();
        getUserDetails(Phone);
    }
    });
}
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
             
                else if(Phone.trim() == ''){
                      /*$('#danger_message').css('display','block').html("Please Enter Phone No");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); */
                     toastr.error('Please Enter Phone No', 'Error', {timeOut: 2000}); 
                    $('#Phone').focus();
                }
                else if(! /^([0-9]{10})+$/.test(Phone)) {
                /* $('#danger_message').css('display','block').html("Mobile Number must be 10 Digit!");
                        setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); */
                    toastr.error('Mobile Number must be 10 Digit!', 'Error', {timeOut: 2000}); 
                  $('#Phone').focus();
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
