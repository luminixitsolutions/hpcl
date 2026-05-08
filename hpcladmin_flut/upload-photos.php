<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Upload-Photos";
$Page = "Upload-Photos";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Category</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">List Of Upload Photos
 <?php if(in_array("14", $Options)) {?>
 <span style="float: right;">
<button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#modals-default" id="add_button"><i class="ion ion-md-add mr-2"></i> Add More</button></span><?php } ?></h4><br>
<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light">Photos</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 
  <div class="form-row">
<div class="form-group col">
<label class="form-label">Name <span class="text-danger">*</span></label>
<input type="text" name="Name" class="form-control" id="Name" placeholder="" value="" required>
<div class="clearfix"></div>
</div>
</div>

  <div class="form-row">
<div class="form-group col">
  <label class="form-label">Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label><br>
 <span id="show_photo">
</span>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Photo For <span class="text-danger">*</span></label>
  <select class="form-control" id="Roll" name="Roll" required="">
<option value="Product">Product</option>
<option value="Category">Category</option>
</select>
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
  url: "ajax_files/ajax_upload_photos.php",
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
      /*swal({
            title: "Success!",
            text: "New Category has been Added.",
            type: "success",
            showCancelButton: false,
            confirmButtonClass: 'btn-success',
            confirmButtonText: 'Ok!'
            });*/
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'New Category Added Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }
function update_toast(){
             var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Category Updated Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }

  $(document).ready(function() {
      category_lists();
    $('#example').DataTable( {
      
    } );

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
      $('#Roll').attr("selected","selected").val('Product');
      
       $('#Featured').val(1).prop('checked',false);
       $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var action = $('#action').val();
    if ($('#validation-form').valid()){ 
         $.ajax({  
                url :"ajax_files/ajax_upload_photos.php",  
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
                  category_lists();
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
                url:"ajax_files/ajax_upload_photos.php",  
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
                url:"ajax_files/ajax_upload_photos.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Category has been deleted.", "success");
              
              category_lists();

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
    url:"ajax_files/ajax_upload_photos.php",
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
    url:"ajax_files/ajax_upload_photos.php",
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
