<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Masters";
$Page="Location";
$Page2 = "Pincode";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Pincode</title>
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
<h4 class="font-weight-bold py-3 mb-0">List Of Pincode
  <span style="float: right;">
<button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#modals-default" id="add_button"><i class="ion ion-md-add mr-2"></i> Add More</button></span></h4><br>
<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light">Pincode</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 

      <div class="form-row">
<div class="form-group col">
<label class="form-label">Country <span class="text-danger">*</span></label>
  <select class="form-control" id="CountryId" name="CountryId" required="">
<option selected="" disabled="" value="">Select Country</option>
<?php 
        $q = "select * from tbl_country WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
</div>

 <div class="form-row">
<div class="form-group col">
<label class="form-label">State <span class="text-danger">*</span></label>
  <select class="form-control" id="StateId" name="StateId" required="">
<option selected="" disabled="" value="">Select State</option>
</select>
<div class="clearfix"></div>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">City <span class="text-danger">*</span></label>
  <select class="form-control" id="CityId" name="CityId" required="">
<option selected="" disabled="" value="">Select City</option>
</select>
<div class="clearfix"></div>
</div>
</div>

  <div class="form-row">
<div class="form-group col">
<label class="form-label">Pincode <span class="text-danger">*</span></label>
<input type="text" name="Name" class="form-control" id="Name" placeholder="" required>
<div class="clearfix"></div>
</div>
</div>


 

<div class="form-row">
<div class="form-group col">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<!-- <option selected="" disabled="" value="">Select Status</option> -->
<option value="1" selected="">Active</option>
<option value="2">Inctive</option>
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
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Country</th>
              <th>State</th>
              <th>City</th>
              <th>PinCode</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
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
<script type="text/javascript">
var pincodeTable;

function initPincodeTable() {
  if ($.fn.DataTable.isDataTable('#example')) {
    $('#example').DataTable().ajax.reload(null, false);
    return;
  }
  pincodeTable = $('#example').DataTable({
    processing: true,
    serverSide: true,
    scrollX: true,
    pageLength: 25,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    dom: 'Bfrtip',
    buttons: ['excelHtml5'],
    ajax: {
      url: 'ajax_files/ajax_pincode.php?action=load',
      type: 'POST',
      error: function() {
        $.growl.error({
          title: 'Error',
          message: 'Could not load pincodes. Please refresh the page.'
        });
      }
    },
    order: [[0, 'desc']],
    columnDefs: [
      { orderable: false, searchable: false, targets: 6 }
    ]
  });
}

function reloadPincodeTable() {
  if (pincodeTable) {
    pincodeTable.ajax.reload(null, false);
  } else {
    initPincodeTable();
  }
}
    function getState(CountryId,StateId){
      var action = "getState";
        $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:CountryId},
    success:function(data)
    {
      $('#StateId').html(data);
        $('#StateId').val(StateId).attr("selected",true);  
    }
    });
    }
    function getCity(StateId,CityId){
      var action = "getCity";
        $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:StateId},
    success:function(data)
    {
      $('#CityId').html(data);
        $('#CityId').val(CityId).attr("selected",true);  
    }
    });
    }
  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Pincode Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'New Pincode Added Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }
function update_toast(){
             var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Pincode Updated Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }

  $(document).ready(function() {
      initPincodeTable();

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

      $('#add_button').click(function(){  
           $('.modal-title').html("Add <span class='font-weight-light'>Pincode</span>");  
           $('#action').val("Add");  
           $('#id').val('');

      $('#Name').val('');
       $('#Photo').val('');
        $('#OldPhoto').val(''); 
        $('#show_photo').hide();
      $('#Status').attr("selected","selected").val(1);
      $('#CityId').attr("selected","selected").val(0);
       $('#CountryId').attr("selected","selected").val(null);
        $('#StateId').val(null).attr("selected",true);
       $('#submit').text('Submit');
          
      }) 
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var action = $('#action').val();
    if ($('#validation-form').valid()){ 
         $.ajax({  
                url :"ajax_files/ajax_pincode.php",  
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
                  reloadPincodeTable();
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
                url:"ajax_files/ajax_pincode.php",  
                method:"POST",  
                data:{action:action,id:id},  
                dataType:"json",  
                success:function(data){  
                    
                   
                     $('#Name').val(data.Pincode);  
                     $('#OldPhoto').val(data.Photo); 
                      $('#Photo').val(''); 
                        if(data.Photo==''){
                       $('#show_photo').hide();
                    } else{
                       $('#show_photo').show();
                    $('#show_photo').html('<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/'+data.Photo+'" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>');
                  }
                    $('#Status').val(data.Status).attr("selected",true);  
                    $('#CityId').val(data.CityId).attr("selected",true); 
                 $('#CountryId').val(data.CountryId).attr("selected",true); 
                 var CountryId = data.CountryId;
                 var StateId = data.StateId;
                 var CityId = data.CityId;
                 getState(CountryId,StateId); 
                 getCity(StateId, CityId); 
                  
                     $('#action').val('Edit'); 
                   
                       $('#id').val(id);  
                       $('#submit').text("Update");   
                       $('.insert_frm').modal('show');
                         $('.modal-title').html("Update <span class='font-weight-light'>Pincode</span>"); 
                     
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
            text: "You will not be able to recover this Pincode!",
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
                url:"ajax_files/ajax_pincode.php",  
                method:"POST",  
                data:{action:action,id:id},  
               
                success:function(data){
              swal("Deleted!", "Pincode has been deleted.", "success");
              
              reloadPincodeTable();

                     }  
           });
                
            } else {
                swal("Cancelled", "Pincode is safe :)", "error");
            }
        });

           
 });


 $(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_pincode.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
     reloadPincodeTable();
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
