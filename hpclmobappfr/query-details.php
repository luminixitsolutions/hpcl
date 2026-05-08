<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Queries";
$UserId = $_SESSION['User']['id'];
//print_r($_SESSION["cart_item"]);

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
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="css/toastr.min.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?> 
        <div class="main-container">
            
            
           <?php 
           $CreatedDate = date('Y-m-d');
          
            $sql = "SELECT tq.*,tu.Fname,tu.Lname,tu.Photo,tu.Phone,tu.Address FROM tbl_customer_queries tq LEFT JOIN tbl_users tu ON tq.CustId=tu.id WHERE tq.id='".$_GET['id']."'";
            $row = getRecord($sql);
           ?>
            
            <div class="container mb-4">
               
                 <h6 style="font-size:18px;"><?php echo $row['Questions'];?></h6>
                 
                 <?php 
                 $sql11 = "SELECT tq.*,tu.Fname,tu.Lname,tu.Photo FROM tbl_customer_query_feedback tq LEFT JOIN tbl_users tu ON tq.CreatedBy=tu.id WHERE tq.SellId='".$_GET['id']."'";
                 $row11 = getList($sql11);
                 foreach($row11 as $result){?>
               <div class="card my-3">
                    <div class="card-body">
                        <h6 style="text-align:justify;font-size:14px;font-weight:500;">"<?php echo $result['Details'];?>"</h6>
                    </div>
                    <div class="card-footer" style="padding: 1px 1px;">
                        <div class="media">
                            <div class="avatar avatar-40 rounded-circle mr-2">
                                <figure class="background" style="background-image: url(<?php echo $UploadUrl;?>/uploads/<?php echo $result['Photo'];?>);">
                                    <img src="<?php echo $UploadUrl;?>/uploads/<?php echo $result['Photo'];?>" alt="" style="display: none;">
                                </figure>
                            </div>
                            <div class="media-body">
                                <h6 class="mb-1" style="font-size:12px;color:#0066ff;"><?php echo $result['Fname']." ".$result['Lname'];?></h6>
                                
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
                <?php } ?>
                
                <?php if($DoughtStatus == 1){} else{?>
               <br><br>
                <h6>Send Your Comment</h6>
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                   
                    <div class="card-body">
                         

                     
                        <div class="form-group float-label active">
                            <textarea class="form-control" id="Comments" name="Comments"></textarea>
                            <label class="form-control-label">Comments</label>                            
                        </div>
                        

                     
                  
                
                    </div>
                        
                          <input type="hidden" name="QueryId" value="<?php echo $_GET['id']; ?>" id="QueryId">  
                          <input type="hidden" name="CustName" value="<?php echo $row['Fname']." ".$row['Lname'];?>" id="CustName">  
                          <input type="hidden" name="Phone" value="<?php echo $row['Phone']; ?>" id="Phone">  
                          <input type="hidden" name="Address" value="<?php echo $row['Address']; ?>" id="Address">  
                      <input type="hidden" name="action" value="SaveComments" id="action">  
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Submit</button>
                    </div>
                </form>
                </div>
          <?php } ?>
            </div>

        </div>
    </main><br><br>
 <?php include_once 'footer.php'; ?>
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
      var Comments = $('#Comments').val();
       var QueryId = $('#QueryId').val();
                
             if(Comments.trim() == ''){
                 toastr.error('Please Enter Your Comment', 'Error', {timeOut: 1000}); 
                    $('#Comments').focus();
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
                    toastr.success('Comment Sent Successfully!', 'Success', {timeOut: 5000}); 
                    setTimeout(function(){
   window.location.href="query-details.php?id="+QueryId;
}, 2000);
                    //
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Update');
                }  
           })  



    }

  });

       
});
</script>
</body>
</html>
