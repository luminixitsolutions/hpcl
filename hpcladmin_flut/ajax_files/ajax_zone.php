<?php 
session_start();
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/incuserdetails.php';

$user_id = $_SESSION['Admin']['id'];
$action = req('action', '');

if ($action === 'Add') {
$Name = addslashes(trim($_POST["Name"]));
$Price = addslashes(trim($_POST["Price"]));
$Feedback = addslashes(trim($_POST["Feedback"]));
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
$query = "SELECT * FROM tbl_zone WHERE Name = '$Name'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_zone SET Name = '$Name',Status='$Status',Photo='$Photo'";
	$conn->query($qx);
	echo 1;
}
}

if ($action === 'fetch_record') {
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_zone WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if ($action === 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Price = addslashes(trim($_POST["Price"]));
$Feedback = addslashes(trim($_POST["Feedback"]));
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
$query = "SELECT * FROM tbl_zone WHERE Name = '$Name' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE tbl_zone SET Name = '$Name', Status='$Status',Photo='$Photo' WHERE id = '$id'";
 	$conn->query($query2);
 }
  echo 1;
}

if ($action === 'delete') {
   
      $id = $_POST['id'];
       $query2 = "SELECT Photo FROM tbl_zone WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo = $row2['Photo'];
      $query = "DELETE FROM tbl_zone WHERE id = '$id'";
      $conn->query($query);
       $src = "../../uploads/$Photo";
        unlink($src);
      echo "Delete Successfully";

  }

if ($action === 'deletePhoto') {
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_zone SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Category Photo Delete Successfully";
} 

if ($action === 'getProdDetails') {
    $id = $_POST['id'];
    $VedId = $_POST['VedId'];
    $sql = "SELECT * FROM tbl_zone WHERE id='$id'";
    $row = getRecord($sql);
    $Price = $row['Price'];
    $sql2 = "SELECT * FROM tbl_vendor_price WHERE ProdId='$id' AND VedId='$VedId'";
    $rncnt2 = getRow($sql2);
    if($rncnt2 > 0){
      $row2 = getRecord($sql2);
      $VedPrice = $row2['VedPrice'];
    }
    else{
      $VedPrice = $row['Price'];
    }
    

    echo json_encode(array('Price'=>$Price,'VedPrice'=>$VedPrice));
   }
   
   
   
if ($action === 'view') { ?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>Id</th>
              
              <th>Zone</th>
             
               <th>Status</th>
               
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_zone ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $nx['id']; ?></td>
           
             <td><?php echo $nx['Name']; ?></td>
           
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
          
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
 <?php }
?>