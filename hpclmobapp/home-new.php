<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";
$url = "home.php";
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}
//echo $sql11;
$Roll = $row['Roll'];
if($Roll == 5){
    $BillSoftFrId = $row['id'];
}
else if($Roll != 5){
    $BillSoftFrId = $row['BillSoftFrId'];
}
else{
    $BillSoftFrId = 0;
}

if($Roll == 5){
    echo "<script>window.location.href='../billsoftmobappfr/home.php?frid=$BillSoftFrId';</script>";exit();
}
else if($BillSoftFrId!=0){
    echo "<script>window.location.href='../billsoftmobappfr/home.php?frid=$BillSoftFrId';</script>";exit();
}
if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Maha Buddy</title>

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
 
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">

 <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
   
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    <!-- screen loader -->
   

    <!-- menu main -->
    <?php include_once 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       <?php include 'top_header.php';?>

<?php if($_GET['ownfr']==''){
    $ownfr = 1;
}
else{
     $ownfr = $_GET['ownfr'];
}
?>
     

        <!-- page content start -->

        <div class="main-container" style="padding-top: 80px;">

            <div class="container">
               
<div class="swiper-container categories2tab1 text-center mb-4">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <?php if($ownfr == 1){?>
                            <a href="home.php?ownfr=1&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">COCO</a>
                            <?php } else {?>
                            <a href="home.php?ownfr=1&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">COCO</a>
                            <?php } ?>
                        </div>
                        <div class="swiper-slide">
                            <?php if($ownfr == 2){?>
                            <a href="home.php?ownfr=2&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">FOFO</a>
                            <?php } else {?>
                            <a href="home.php?ownfr=2&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">FOFO</a>
                            <?php } ?>
                        </div>
                        <div class="swiper-slide">
                           <?php if($ownfr == 0){?>
                            <a href="home.php?ownfr=0&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-default active rounded">Other</a>
                            <?php } else {?>
                            <a href="home.php?ownfr=0&calendar=<?php echo $_REQUEST['calendar'];?>&FromDate=<?php echo $_REQUEST['FromDate'];?>&ToDate=<?php echo $_REQUEST['ToDate'];?>" class="btn btn-sm btn-outline-default rounded">Other</a>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination white-pagination text-left mb-3"></div>
                </div>
                
                
                <input type="text" class="form-control" id="SearchData" placeholder="Search Franchise" oninput="fetchFranchiseInv()">
                <input type="hidden" id="ownfr" value="<?php echo $ownfr;?>">
                <input type="hidden" id="CocoFranchiseAccess" value="<?php echo $CocoFranchiseAccess;?>">
                <input type="hidden" id="Roll" value="<?php echo $Roll;?>">
                <input type="hidden" id="calendar" value="<?php echo $_REQUEST['calendar'];?>">
                <br>
               
                <span id="showdata">
                    
                </span>
                
     
                

                

            </div>
        </div>
    </main>


     <?php include_once 'footer.php';?>

    <!-- color settings style switcher -->
   <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>



    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
 <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
  
    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
 <script type="text/javascript" src="js/toastr.min.js"></script>
    <script type="text/javascript">
    function fetchFranchiseInv(){
        var SearchData = $('#SearchData').val();
        var ownfr = $('#ownfr').val();
        var CocoFranchiseAccess = $('#CocoFranchiseAccess').val();
        var Roll = $('#Roll').val();
        var calendar = $('#calendar').val();
        var action = 'fetchFranchiseInv';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
   data:{action:action,SearchData:SearchData,ownfr:ownfr,CocoFranchiseAccess:CocoFranchiseAccess,Roll:Roll,calendar:calendar},  
  success: function(data){
      console.log(data);
      $('#showdata').html(data);
  }
  });
    }
   function featured(){
        if($('#Featured').prop('checked') == true) {
            $('#Featured').val(1);
        }
        else{
           $('#Featured').val(0);
            }
        }
function category_lists(){
  var action = 'view';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_category.php",
   data:{action:action},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Category Name Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
        toastr.success('New Category Added Successfully!', 'Success', {timeOut: 2000});
   
  }
function update_toast(){
     toastr.success('Category Updated Successfully!', 'Success', {timeOut: 2000});
      
  }

  $(document).ready(function() {
fetchFranchiseInv();
   
      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Category</span>");  
           $('#action').val("Add");  
           $('#id').val('');
        $('#srno').val('');
      $('#Name').val('');
      $('#Icon').val('');
      $('#Photo').val('');
        $('#OldPhoto').val(''); 
        $('#show_photo').hide();
        
         $('#Photo2').val('');
        $('#OldPhoto2').val(''); 
        $('#show_photo2').hide();
        
      $('#Status').attr("selected","selected").val(null);
      $('#Roll').attr("selected","selected").val('3');
      
       $('#Featured').val(1).prop('checked',false);
       $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
         e.preventDefault();    
      var action = $('#action').val();
         $.ajax({  
                url :"ajax_files/ajax_customer_category.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
               
                    if(data == 1){
                      if(action == 'Edit'){
                        update_toast();
                      }
                      else{
                      success_toast();
                      }
                      $('.insert_frm').modal('hide'); 
                      setTimeout(function(){  
                      window.location.href="prod-category.php";
                    }, 1000);  
                    }
                    else{
                      error_toast();
                      $('.insert_frm').modal('show'); 
                      
                    }
                  //category_lists();
                      $('#submit').attr('disabled',false);
                       $('#submit').text('Submit');
                        $('#action').val("Add");  
                }  
           })  

 
  });


      $(document).on("click", ".update", function(event){
 event.preventDefault();
 event.stopPropagation();
 var id = $(this).attr("data-id");
 var action = "fetch_record";
 $.ajax({  
                url:"ajax_files/ajax_customer_category.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                    $('#srno').val(data.srno);  
                     $('#Name').val(data.Name);  
                      $('#Icon').val(data.Icon);
                      $('#OldPhoto').val(data.Photo); 
                      $('#Photo').val(''); 
                      
                       $('#OldPhoto2').val(data.Photo2); 
                      $('#Photo2').val(''); 
                      
                      $('#Roll').val(data.Roll).attr("selected",true);  
                    $('#Status').val(data.Status).attr("selected",true);  
                   if(data.Featured == 1){

        $('#Featured').val(data.Featured).prop('checked',true);
      }
      else{
       
        $('#Featured').val(data.Featured).prop('checked',false);
      }
                     $('#action').val('Edit'); 
                    if(data.Photo==''){
                       $('#show_photo').hide();
                    } else{
                       $('#show_photo').show();
                    $('#show_photo').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/'+data.Photo+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                  
                  if(data.Photo2==''){
                       $('#show_photo2').hide();
                    } else{
                       $('#show_photo2').show();
                    $('#show_photo2').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo2"></a><img src="../uploads/'+data.Photo2+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                       $('#id').val(id);  
                       $('#submit').text("Update");   
                       $('.insert_frm').modal('show');
                         $('.modal-title').html("Update <span class='font-weight-light'>Category</span>"); 
                     
                }  
           });
});



 $(document).on("click", ".delete", function(event){
 event.preventDefault();
 var id = $(this).attr("data-id");
 var action = "delete";
 //alert(id);
   swal({
            title: "Are you sure?",
            text: "Deleted All Records Related this Category & You will not be able to recover this Category!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                 $.ajax({  
                url:"ajax_files/ajax_customer_category.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Category has been deleted.", "success");
              window.location.href="prod-category.php";
              //category_lists();

                     }  
           });
                
            } else {
                swal("Cancelled", "Category is safe :)", "error");
            }
        });
        
/* bootbox.confirm({
            message: 'Are you sure?',
            className: 'bootbox-sm',
                callback: function(result) {
                 if(result == true){
                
                 }
                 else{
                    
                 }
            },
        }); */
           
           
 });

 $(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Category Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_customer_category.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
      category_lists();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });
   
   $(document).on("click", "#delete_photo2", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Category Icon?"))  
           {  
             var action = "deletePhoto2";
             var id = $('#id').val();
             var Photo = $('#OldPhoto2').val();
             $.ajax({
    url:"ajax_files/ajax_customer_category.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo2').hide();
      $('#OldPhoto2').val('');
      category_lists();
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });

} );
</script>
</body>

</html>
