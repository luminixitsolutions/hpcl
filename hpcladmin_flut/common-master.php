<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
if($_GET['pageid']==1){
$Page = "Travelling-Services";
$Label = "Travelling Services";
}
if($_GET['pageid']==2){
$Page = "Fare-Class";
$Label = "Fare Class";
}
if($_GET['pageid']==3){
$Page = "Division-Name";
$Label = "Division Name";
}
if($_GET['pageid']==4){
$Page = "Segment";
$Label = "Segment";
}
if($_GET['pageid']==5){
$Page = "Family";
$Label = "Family";
}
if($_GET['pageid']==6){
$Page = "Class";
$Label = "Class";
}
if($_GET['pageid']==7){
$Page = "Mc-Desc";
$Label = "Mc. Desc";
}
if($_GET['pageid']==8){
$Page = "Brand-Desc";
$Label = "Brand Desc";
}
$MainPage="Masters";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | <?php echo $Label;?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">List Of <?php echo $Label;?>
 
  <span style="float: right;">
<button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#modals-default" id="add_button"><i class="ion ion-md-add mr-2"></i> Add More</button></span></h4><br>
<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light"><?php echo $Label;?></span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 
 <input type="hidden" name="Roll" id="Roll" value="<?php echo $_GET['pageid'];?>"/> 
<?php if($_GET['pageid']==2){?>
    <div class="form-row">
 <div class="form-group col-md-12">
<label class="form-label">Service <span class="text-danger">*</span></label>
<select class="form-control" style="width: 100%" data-allow-clear="true" name="ServiceId" id="ServiceId">
  <option selected value="" disabled>Search Service</option>
  <?php 
    $sql4 = "SELECT * FROM tbl_common_master WHERE Roll=1 AND Status=1";
    $row4 = getList($sql4);
    foreach($row4 as $result){
  ?>
  <option value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
  <?php } ?>
</select>
</div>
</div>
<?php } ?>
 <div class="form-row">
<div class="form-group col">
<label class="form-label"><?php echo $Label;?> Name <span class="text-danger">*</span></label>
<input type="text" name="Name" class="form-control" id="Name" placeholder="Name" required>
<div class="clearfix"></div>
</div>
</div>

 
<div class="form-row">
<div class="form-group col">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1">Active</option>
<option value="0">Inctive</option>
</select>
<div class="clearfix"></div>
</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger" id="submit" name="submit">Submit</button>
</div>
</form>
</div>
</div>
<div class="card">
<div class="card-datatable table-responsive" id="custresult">
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>


<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
function product_lists(){
  var action = 'view';
  var Roll = $('#Roll').val();
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_common.php",
   data:{action:action,Roll:Roll},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Record Name Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
      /*swal({
            title: "Success!",
            text: "New Product has been Added.",
            type: "success",
            showCancelButton: false,
            confirmButtonClass: 'btn-success',
            confirmButtonText: 'Ok!'
            });*/
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'New Record Added Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }
function update_toast(){
             var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Record Updated Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }

  $(document).ready(function() {
      product_lists();
    $('#example').DataTable( {
      
    } );

      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Record</span>");  
           $('#action').val("Add");  
           $('#id').val('');

      $('#Name').val('');
      $('#Phone2').val('');
      $('#EmailId').val('');
      $('#Address').val('');
      $('#Photo').val('');
        $('#OldPhoto').val(''); 
        $('#show_photo').hide();
        $('#ServiceId').attr("selected","selected").val('');
      $('#Status').attr("selected","selected").val(1);
       $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var action = $('#action').val();
    if ($('#validation-form').valid()){ 
         $.ajax({  
                url :"ajax_files/ajax_common.php",  
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
                    }
                    else{
                      error_toast();
                      $('.insert_frm').modal('show'); 
                    }
                  product_lists();
                      $('#submit').attr('disabled',false);
                       $('#submit').text('Submit');
                        $('#action').val("Add");  
                }  
           })  

  }
else{
    return false;
}
  });


      $(document).on("click", ".update", function(event){
 event.preventDefault();
 event.stopPropagation();
 var id = $(this).attr("data-id");

 var action = "fetch_record";
 $.ajax({  
                url:"ajax_files/ajax_common.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                   
                     $('#Name').val(data.Name);  
                      $('#Phone').val(data.Phone);
                      $('#Phone2').val(data.Phone2);
                      $('#EmailId').val(data.EmailId);
                      $('#Address').val(data.Address);
                      $('#OldPhoto').val(data.Photo); 
                      $('#Photo').val(''); 
                    $('#Status').val(data.Status).attr("selected",true);  
                    $('#ServiceId').attr("selected","selected").val(data.ServiceId);
                     $('#action').val('Edit'); 
                    if(data.Photo==''){
                       $('#show_photo').hide();
                    } else{
                       $('#show_photo').show();
                    $('#show_photo').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/'+data.Photo+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                       $('#id').val(id);  
                       $('#submit').text("Update");   
                       $('.insert_frm').modal('show');
                         $('.modal-title').html("Update <span class='font-weight-light'>Record</span>"); 
                     
                }  
           });
});



 $(document).on("click", ".delete", function(event){
 event.preventDefault();
 var id = $(this).attr("data-id");
 var action = "delete";
 
   swal({
            title: "Are you sure?",
            text: "Deleted All Records Related this Record & You will not be able to recover this Product!",
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
                url:"ajax_files/ajax_common.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
                    
              swal("Deleted!", "Record has been deleted.", "success");
              
              product_lists();

                     }  
           });
                
            } else {
                swal("Cancelled", "Record is safe :)", "error");
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
if(confirm("Are you sure you want to delete Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_common.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
     product_lists();
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
