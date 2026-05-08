<?php 
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Options = explode(',',$row77['Options']);
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Roll = addslashes(trim($_POST["Roll"]));
$Phone2 = addslashes(trim($_POST["Phone2"]));
$EmailId = addslashes(trim($_POST["EmailId"]));
$ServiceId = addslashes(trim($_POST["ServiceId"]));
$Status = $_POST["Status"];
$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
  //$Photo = $_POST['OldPhoto'];
}
$CreatedDate = date('Y-m-d');

$qx = "INSERT INTO tbl_common_master SET Name = '$Name',ServiceId='$ServiceId',Status='$Status',Roll='$Roll'";
	$conn->query($qx);
	echo 1;

}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_common_master WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Roll = addslashes(trim($_POST["Roll"]));
$Phone2 = addslashes(trim($_POST["Phone2"]));
$EmailId = addslashes(trim($_POST["EmailId"]));
$ServiceId = addslashes(trim($_POST["ServiceId"]));
$OldPhoto = $_POST['OldPhoto'];
$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
  $src = "../../uploads/$OldPhoto";
  unlink($src); 
$Photo = $imagepath ;
} 
else{
  $Photo = $_POST['OldPhoto'];
}
$ModifiedDate = date('Y-m-d');

  $query2 = "UPDATE tbl_common_master SET Name = '$Name', ServiceId='$ServiceId',Status='$Status',Roll='$Roll' WHERE id = '$id'";
 	$conn->query($query2);
 
  echo 1;
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_common_master WHERE id = '$id'";
      $conn->query($query);

      
      echo "Delete Successfully";

  }

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_common_master SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Photo Delete Successfully";
} 
  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <?php if($_POST['Roll']==2){?>
                <th>Service</th>
                <?php } ?>
              <th>Name</th>
              
               <th>Status</th>
               

               <th>Action</th>
              
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
 $Roll = $_POST['Roll'];
  $sql = "SELECT tc.*,tc2.Name As Service FROM tbl_common_master tc 
          LEFT JOIN tbl_common_master tc2 ON tc2.id=tc.ServiceId WHERE tc.Roll='$Roll' ORDER BY tc.id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <?php if($_POST['Roll']==2){?>
             <td><?php echo $nx['Service']; ?></td>
             <?php } ?>
             <td><?php echo $nx['Name']; ?></td>
           
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
          
           
             <td>
                
                 <a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
                
                 <a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
                 
             </td>
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