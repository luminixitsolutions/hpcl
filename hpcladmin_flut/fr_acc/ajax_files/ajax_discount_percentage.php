<?php 
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['Admin']['id'];
$frid = $_SESSION['fr_admin'];
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
$Status = $_POST["Status"];
$CreatedDate = date('Y-m-d H:i:s');
$query = "SELECT * FROM tbl_fr_billsoft_discount WHERE Percentage = '$Name' AND FrId='$frid'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_fr_billsoft_discount SET Percentage = '$Name',modified_time='$modified_time',push_flag=1,FrId='$frid',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($qx);
	echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_fr_billsoft_discount WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
$Status = $_POST["Status"];
$query = "SELECT * FROM tbl_fr_billsoft_discount WHERE Percentage = '$Name' AND id != '$id' AND FrId='$frid'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE tbl_fr_billsoft_discount SET Percentage = '$Name',modified_time='$modified_time',push_flag=1,FrId='$frid',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
      $query = "UPDATE tbl_fr_billsoft_discount SET delete_flag=1,modified_time='$modified_time',push_flag=1,ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }


  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
            
              <th>Discount %</th>
          <th>Status</th>
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
              
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
   $sql = "SELECT * FROM tbl_fr_billsoft_discount WHERE FrId='$frid' ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>

             <td><?php echo $nx['Percentage']; ?></td>
             <td><?php if($nx['delete_flag']=='0'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
             <td>
                   <?php if(in_array("10", $Options)){?>
                 <a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
                  <?php } if(in_array("11", $Options)){?>
                 <a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
                  <?php } ?>
             </td><?php } ?>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
      $('#example').DataTable( {
        responsive: true
      });
      });
    </script>
 <?php }
?>