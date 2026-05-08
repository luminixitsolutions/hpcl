<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Vendor-Expenses";
$Page = "Vendor-Expenses";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
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



<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_vendor_expenses WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="vendor-expenses.php";
    </script>
<?php } 

if($_REQUEST['action'] == 'changestatus'){
    $id = $_REQUEST["id"];
    $val = $_REQUEST["val"];
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    $sql3 = "SELECT * FROM tbl_vendor_expenses WHERE id = '$id'";
    $row3 = getRecord($sql3);
    $UserId = $row3['UserId'];
    $Amount = $row3['Amount'];
    if($val == 0){
        $sql = "UPDATE tbl_vendor_expenses SET Status=1 WHERE id='$id'";
        $conn->query($sql);
        $sql2 = "INSERT INTO wallet SET ExpId='$id',UserId='$UserId',Amount='$Amount',Narration='Expense Amount Approved',Status='Dr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
        $conn->query($sql2);
    }
    else{
        $sql = "UPDATE tbl_vendor_expenses SET Status=0 WHERE id='$id'";
        $conn->query($sql);
        $sql2 = "DELETE FROM wallet WHERE ExpId='$id'";
        $conn->query($sql2);
    }
 ?>
    <script type="text/javascript">
      alert("Record Saved Successfully");
      window.location.href="vendor-expenses.php";
    </script>
<?php   
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Vendor Expense
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                
                 
                   <th>Admin Approve</th>
                   
               <th>Photo</th>
                <th>Vendor Name</th>
                
                <th>Amount</th>
            
                <th>Narration</th>
                 <th>Receipt</th>
          
                <th>Expense Date</th>
               
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
            
               $sql = "SELECT te.*,tu.Fname,tu.Lname,tu.Photo AS Uphoto FROM tbl_vendor_expenses te 
                INNER JOIN tbl_users tu ON tu.id=te.UserId 
                WHERE te.BranchId='$BillSoftFrId' AND te.BranchStatus=0"; 
            
            if($_REQUEST['val'] =='today'){
                $sql.=" AND te.CreatedDate='".date('Y-m-d')."'";
            }
            $sql.=" ORDER BY te.CreatedDate DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                    $MgrName = $row['MgrName'];
               
             ?>
            <tr>
                

 
 <td id="showstatus<?php echo $row['id']; ?>"><?php if($row['BranchStatus']=='1'){ echo "<span style='color:green;'>Approved<br>By $MgrName </span>";} else if($row['BranchStatus']=='2'){ echo "<span style='color:red;'>Rejected <br> By $MgrName</span>";} else {?>
  <a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default" id="add_button" onclick="getExpenseVal(<?php echo $row['id']; ?>)">Approve By Popup</a><?php } ?></td>
 
               <td> <?php if($row["Uphoto"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Uphoto"])){?>
                 <img src="../uploads/<?php echo $row["Uphoto"];?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
                
                <td><?php echo $row['Amount']; ?></td>
            
                  <td><?php echo $row['Narration']; ?></td>
              <td><?php if($row["Photo"] == '') {?>
                  <span style="color:red;">No Receipt Found</span>
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <a href="../uploads/<?php echo $row["Photo"];?>" target="_blank">View Receipt</a>
                  <?php }  else{?>
                <span style="color:red;">No Receipt Found</span>
             <?php } ?></td>
             
            
                     
                
              
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ExpenseDate']))); ?></td>
           
            </tr>
           <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>


<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Approve  
<span class="font-weight-light">Expense</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
    <div class="form-row">
        <input type="hidden" name="id" value="" id="id">
        <input type="hidden" name="EmpId" value="" id="EmpId">
       <input type="hidden" name="action" value="MgrSave" id="action">
 <div class="form-group col-md-12">
                                            <label class="form-label">Vednor Name</label>
                                            <input type="text" id="EmplName" class="form-control"
                                                placeholder="" value="<?php echo $row7['Fname']." ".$row7['Lname']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                       
                                        
                                         <div class="form-group col-md-4">
                                            <label class="form-label">Expense Amount</label>
                                            <input type="text" name="ExpenseAmt" class="form-control" id="ExpenseAmt"
                                                placeholder="" value="<?php echo $row7["Amount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Approve Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="BranchAmount" class="form-control" id="BranchAmount"
                                                placeholder="" value="<?php echo $row7["BranchAmount"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>
                                     
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Expense Date</label>
                                            <input type="date" name="ExpenseDate" class="form-control" id="ExpenseDate"
                                                placeholder="" value="<?php echo $row7["ExpenseDate"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-12">
                                            <label class="form-label">Narration</label>
                                            <input type="text" name="ExpenseFor" class="form-control" id="ExpenseFor"
                                                placeholder="" value="<?php echo $row7["Narration"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-6">
                                            <label class="form-label">Approve Date</label>
                                            <input type="date" name="ApproveDate" id="ApproveDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ApproveDate"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="BranchStatus" name="BranchStatus" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["BranchStatus"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($row7["BranchStatus"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($row7["BranchStatus"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
 <div class="form-group col-md-12">
                                            <label class="form-label">Comment</label>
                                            <textarea id="BranchComment" name="BranchComment" class="form-control"
                                                placeholder=""></textarea>
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

<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Record Not Saved',
      location: isRtl ? 'tl' : 'tr'
    });
  }
 
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Approved Successfully!',
      location: isRtl ? 'tl' : 'tr'
    });
  }
function getExpenseVal(id){
    var action = "fetch_expense_details";
 $.ajax({  
                url:"ajax_files/ajax_expense_details.php",  
                method:"POST",  
                data:{action:action,id:id},  
                success:function(data){  
                    console.log(data);
                    var res = JSON.parse(data);
                    var rowdata = res.rowdata;
                    var walletamt = res.walletamt;
                    $('#EmplName').val(rowdata.Fname);  
                    $('#Wallet').val(walletamt); 
                    $('#ExpenseAmt').val(rowdata.Amount); 
                    $('#BranchAmount').val(rowdata.BranchAmount); 
                    $('#ExpenseDate').val(rowdata.ExpenseDate); 
                    $('#ExpenseFor').val(rowdata.Narration); 
                       $('#id').val(id);  
                       $('#EmpId').val(rowdata.UserId);  
                       $('.insert_frm').modal('show');
                     
                     
                }  
           });
}
 function changeStatus(id,val){
     window.location.href='vendor-expenses.php?action=changestatus&id='+id+'&status='+val;
 }
    $(document).ready(function() {
    $('#example').DataTable({
    });
    
     $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var action = $('#action').val();
    if ($('#validation-form').valid()){ 
         $.ajax({  
                url :"ajax_files/ajax_expense_details.php",  
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
               var res = JSON.parse(data);
               var response = res.response;
               var id = res.id;
               var status = res.status;
                    if(response == 1){
                        $('#showstatus'+id).html(status);
                      success_toast();
                      $('#EmplName').val('');  
                    $('#Wallet').val('');
                    $('#ExpenseAmt').val('');
                    $('#BranchAmount').val('');
                    $('#BranchAmount').val('');
                    $('#ExpenseDate').val('');
                    $('#AccAmount').val('');
                    $('#ExpenseFor').val('');
                    $('#BranchComment').val('');
                      $('.insert_frm').modal('hide'); 
                      
                    }
                    else{
                      error_toast();
                      $('.insert_frm').modal('show'); 
                    }
                      $('#submit').attr('disabled',false);
                       $('#submit').text('Submit');
                        $('#action').val("Save");  
                }  
           })  

  }
else{
    return false;
}
  });
});
</script>
</body>
</html>
