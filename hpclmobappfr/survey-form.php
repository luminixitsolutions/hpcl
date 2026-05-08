<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Today Survey";
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
    $id = $_GET['id'];
    $sql22 = "SELECT * FROM tbl_survey WHERE id='$id'";
    $row22 = getRecord($sql22);
?>
        <div class="main-container">
            <div class="container">
               
<form id="validation-form" method="post" autocomplete="off">

    <div class="form-group float-label active">
                            <select class="form-control" name="CustId" id="CustId" required="" onchange="checkTodayVisit(this.value,'<?php echo date('Y-m-d');?>')">
                                <option value="" selected="" disabled="">Select Shop</option>
                                <?php 
                                    $sql = "SELECT * FROM tbl_users WHERE Roll=5 AND Status=1";
                                    $row = getList($sql);
                                    foreach($row as $result){?>
                                <option <?php if($row22['CustId'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>"><?php echo $result['Fname']." (".$result['Phone'].")";?></option>
                            <?php } ?>
                            </select>
                            <label class="form-control-label">Shop Name</label>                            
                        </div>

               <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">1. Shop Opening Time</h6>
                    </div>
                    <input type="hidden" name="Question1" value="Shop Opening Time">
                    <div class="card-body">
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer1" value="5 Am" class="custom-control-input" id="Option1" <?php if($row22['Answer1']=='5 Am'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option1">5 Am</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer1" value="6 Am" class="custom-control-input" id="Option2" <?php if($row22['Answer1']=='6 Am'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option2">6 Am</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer1" value="7 Am" class="custom-control-input" id="Option3" <?php if($row22['Answer1']=='7 Am'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option3">7 Am</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer1" value="After 8 Am" class="custom-control-input" id="Option4" <?php if($row22['Answer1']=='After 8 Am'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option4">After 8 Am</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">2. Shop Closing Time</h6>
                    </div>
                    <input type="hidden" name="Question2" value="Shop Closing Time">
                    <div class="card-body">
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer2" value="5 pm" class="custom-control-input" id="Option21" <?php if($row22['Answer2']=='5 pm'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option21">5 pm</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer2" value="6 pm" class="custom-control-input" id="Option22" <?php if($row22['Answer2']=='6 pm'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option22">6 pm</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer2" value="7 pm" class="custom-control-input" id="Option23" <?php if($row22['Answer2']=='7 pm'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option23">7 pm</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer2" value="After 8 pm" class="custom-control-input" id="Option24" <?php if($row22['Answer2']=='After 8 pm'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option24">After 8 pm</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">3. All Employees Wearing Dress Code</h6>
                    </div>
                    <input type="hidden" name="Question3" value="All Employees Wearing Dress Code">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer3" value="Yes"  class="custom-control-input" id="Option31" <?php if($row22['Answer3']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option31">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer3" value="No"  class="custom-control-input" id="Option32" <?php if($row22['Answer3']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option32">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">4. Shoppe Clean And Hygiene</h6>
                    </div>
                    <input type="hidden" name="Question4" value="Shoppe Clean And Hygiene">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer4" value="Yes"  class="custom-control-input" id="Option41" <?php if($row22['Answer4']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option41">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer4" value="No"  class="custom-control-input" id="Option42" <?php if($row22['Answer4']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option42">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">5. CCTV & Cameras Working Fine</h6>
                    </div>
                    <input type="hidden" name="Question5" value="CCTV & Cameras Working Fine">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer5" value="Yes"  class="custom-control-input" id="Option51" <?php if($row22['Answer5']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option51">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer5" value="No"  class="custom-control-input" id="Option52" <?php if($row22['Answer5']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option52">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">6. Offers & Combo Display Clear In Front Of Shop</h6>
                    </div>
                    <input type="hidden" name="Question6" value="Offers & Combo Display Clear In Front Of Shop">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer6" value="Yes"  class="custom-control-input" id="Option61" <?php if($row22['Answer6']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option61">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer6" value="No"  class="custom-control-input" id="Option62" <?php if($row22['Answer6']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option62">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">7. Shop Owner Doing Activity For Google Rating & Clint Data On Bill Machine</h6>
                    </div>
                    <input type="hidden" name="Question7" value="Shop Owner Doing Activity For Google Rating & Clint Data On Bill Machine">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer7" value="Yes"  class="custom-control-input" id="Option71" <?php if($row22['Answer7']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option71">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer7" value="No"  class="custom-control-input" id="Option72" <?php if($row22['Answer7']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option72">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">8. Check Availability 15 Days All Stock Of Premix And Tea Premix.</h6>
                    </div>
                    <input type="hidden" name="Question8" value="Check Availability 15 Days All Stock Of Premix And Tea Premix.">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer8" value="Yes"  class="custom-control-input" id="Option81" <?php if($row22['Answer8']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option81">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer8" value="No"  class="custom-control-input" id="Option82" <?php if($row22['Answer8']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option82">No</label>
                        </div>
                       
                    </div>
                </div>

                 <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">9. Shop Owners Doing Cross Sell Activity Of High Value Combo</h6>
                    </div>
                    <input type="hidden" name="Question9" value="Shop Owners Doing Cross Sell Activity Of High Value Combo">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer9" value="Yes"  class="custom-control-input" id="Option91" <?php if($row22['Answer9']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option91">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer9" value="No"  class="custom-control-input" id="Option92" <?php if($row22['Answer9']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option92">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">10. Bill Time Shop Manager Are Asking Clint To anything Parcel For Family</h6>
                    </div>
                    <input type="hidden" name="Question10" value="Bill Time Shop Manager Are Asking Clint To anything Parcel For Family">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer10" value="Yes"  class="custom-control-input" id="Option101" <?php if($row22['Answer10']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option101">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer10" value="No"  class="custom-control-input" id="Option102" <?php if($row22['Answer10']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option102">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">11. Shop Doing 100% Prepaid Billing</h6>
                    </div>
                    <input type="hidden" name="Question11" value="Shop Doing 100% Prepaid Billing">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer11" value="Yes"  class="custom-control-input" id="Option111" <?php if($row22['Answer11']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option111">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer11" value="No"  class="custom-control-input" id="Option112" <?php if($row22['Answer11']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option112">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">12. Shop Doing Self Service Only</h6>
                    </div>
                    <input type="hidden" name="Question12" value="Shop Doing Self Service Only">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer12" value="Yes"  class="custom-control-input" id="Option121" <?php if($row22['Answer12']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option121">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer12" value="No"  class="custom-control-input" id="Option122" <?php if($row22['Answer12']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option122">No</label>
                        </div>
                       
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">13. Customer Commination Good </h6>
                    </div>
                    <input type="hidden" name="Question13" value="Customer Commination Good ">
                   <div class="card-body">
                       <!--  <input type="text" name="Answer4" class="form-control"> -->
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer13" value="Yes"  class="custom-control-input" id="Option131" <?php if($row22['Answer13']=='Yes'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option131">Yes</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="radio" name="Answer13" value="No"  class="custom-control-input" id="Option132" <?php if($row22['Answer13']=='No'){?> checked <?php } ?>>
                            <label class="custom-control-label" for="Option132">No</label>
                        </div>
                       
                    </div>
                </div>
                

              <div class="form-group float-label active">
                          <textarea name="Suggestion" id="Suggestion" class="form-control"
                                                placeholder=""><?php echo $row22['Suggestion'];?></textarea>
                            <label class="form-control-label active">Any Suggestion /Complaint About Mahachai Service</label>
                        </div>

                <div class="card">
                     
                
                   
                    <div class="card-body">
                      
                         
                        
                 <input type="hidden" name="ExeId" value="<?php echo $_SESSION['User']['id']; ?>" id="ExeId">  

                      <input type="hidden" name="action" value="SaveFeedback" id="action">    
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Submit</button>
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
        function checkTodayVisit(cid,date){
            var action = "checkTodayVisit";
    $.ajax({
    url:"ajax_files/ajax_survey.php",
    method:"POST",
    data : {action:action,cid:cid,date:date},
    success:function(data)
    {
        //alert(data);
        if(data != 0){
            window.location.href="survey-form.php?id="+data;
        }
        else{
            $("input[type=radio]").prop('checked', false);
            $('#Suggestion').val('');
            //window.location.href="survey-form.php";
        }
    }
    });

        }
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

               
                /*if(data == 0){
                        toastr.error('Today Survey Report Already Given ', 'Error', {timeOut: 5000}); 
                     
                     }
                     else{*/
                    toastr.success('Today Shop Visit Report Saved Successfully!', 'Success', {timeOut: 2000}); 
                    window.location.href="survey-thank-you.php?val="+data;
                     //}
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           });
        });

  });
</script>
</body>

</html>
