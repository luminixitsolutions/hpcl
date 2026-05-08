<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Amount = $_POST["Amount"];
$Duration = $_POST["Duration"];
$Period = $_POST["Period"];
$Discount = $_POST['Discount'];
$Detail1 = addslashes(trim($_POST["Detail1"]));
$Detail2 = addslashes(trim($_POST["Detail2"]));
$Detail3 = addslashes(trim($_POST["Detail3"]));
$Detail4 = addslashes(trim($_POST["Detail4"]));
$Detail5 = addslashes(trim($_POST["Detail5"]));

$Title1 = addslashes(trim($_POST["Title1"]));
$Title2 = addslashes(trim($_POST["Title2"]));
$Title3 = addslashes(trim($_POST["Title3"]));
$Title4 = addslashes(trim($_POST["Title4"]));
$Title5 = addslashes(trim($_POST["Title5"]));

$Title6 = addslashes(trim($_POST["Title6"]));
$Title7 = addslashes(trim($_POST["Title7"]));
$Title8 = addslashes(trim($_POST["Title8"]));
$Title9 = addslashes(trim($_POST["Title9"]));
$Title10 = addslashes(trim($_POST["Title10"]));

$CreatedDate = date('Y-m-d H:i:s');

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

$query = "SELECT * FROM tbl_packages WHERE (Amount = '$Amount' OR Name='$Name')";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_packages SET Name = '$Name',Amount='$Amount',Duration='$Duration',Period = '$Period',Detail1='$Detail1',
Title1='$Title1',Detail2='$Detail2',Title2='$Title2',Detail3='$Detail3',Title3='$Title3',Detail4='$Detail4',
Title4='$Title4',Detail5='$Detail5',Title5='$Title5',Status='$Status',Photo='$Photo',CreatedDate='$CreatedDate',
CreatedBy='$user_id',Title6='$Title6',Title7='$Title7',Title8='$Title8',Title9='$Title9',Title10='$Title10'";
	$conn->query($qx);
	echo 1;
}
}

if($_POST['action'] == 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_packages WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if($_POST['action'] == 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Amount = $_POST["Amount"];
$Duration = $_POST["Duration"];
$Period = $_POST["Period"];
$Discount = $_POST['Discount'];
$Detail1 = addslashes(trim($_POST["Detail1"]));
$Detail2 = addslashes(trim($_POST["Detail2"]));
$Detail3 = addslashes(trim($_POST["Detail3"]));
$Detail4 = addslashes(trim($_POST["Detail4"]));
$Detail5 = addslashes(trim($_POST["Detail5"]));

$Title1 = addslashes(trim($_POST["Title1"]));
$Title2 = addslashes(trim($_POST["Title2"]));
$Title3 = addslashes(trim($_POST["Title3"]));
$Title4 = addslashes(trim($_POST["Title4"]));
$Title5 = addslashes(trim($_POST["Title5"]));

$Title6 = addslashes(trim($_POST["Title6"]));
$Title7 = addslashes(trim($_POST["Title7"]));
$Title8 = addslashes(trim($_POST["Title8"]));
$Title9 = addslashes(trim($_POST["Title9"]));
$Title10 = addslashes(trim($_POST["Title10"]));

$CreatedDate = date('Y-m-d H:i:s');

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
$query = "SELECT * FROM tbl_packages WHERE (Amount = '$Amount' OR Name='$Name') AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
   $query2 = "UPDATE tbl_packages SET Name = '$Name',Amount='$Amount',Duration='$Duration',Period = '$Period',Detail1='$Detail1',
   Title1='$Title1',Detail2='$Detail2',Title2='$Title2',Detail3='$Detail3',Title3='$Title3',Detail4='$Detail4',Title4='$Title4',
   Detail5='$Detail5',Title5='$Title5',Status='$Status',Photo='$Photo',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',
   Title6='$Title6',Title7='$Title7',Title8='$Title8',Title9='$Title9',Title10='$Title10'
   WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}
}

  if($_POST['action'] == 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_packages WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }


  if($_POST['action']=='view'){?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Package Name</th>
               <th>Price</th>
             <!--  <th>Discount</th>-->
                 <th>Duration</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_packages ORDER BY id ASC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
    if($nx['Period'] == 1){
      $Period = "Month";
    }
    else{
      $Period = "Year";
    }
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             <td><?php echo $nx['Name']; ?></td>
              <td>&#8377;<?php echo $nx['Amount']; ?></td>
              <!--<td><?php echo $nx['Discount']; ?>%</td>-->
                <td><?php echo $nx['Duration']." ".$Period; ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <td><a data-id="" href='add-package.php?id=<?php echo $nx['id']; ?>' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class=""><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
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
 
 
   if($_POST['action'] == 'getPkgDetails') {
   
      $id = $_POST['id'];
      $PkgDate = date('Y-m-d');
      $query = "SELECT * FROM tbl_packages WHERE id = '$id'";
      $row = getRecord($query);
       $Duration = $row['Duration'];
    if($row['Period'] == '1'){
      $Period = "month";
    }
    else{
      $Period = "years";
    }
    $date = strtotime($PkgDate);
    $valid_period = "+ ".$Duration." ".$Period;
     $new_date = strtotime($valid_period, $date);
      $PkgExp = date('Y-m-d', $new_date);
      echo json_encode(array('PkgAmount'=>$row['Amount'],'PkgOffer'=>$row['Discount'],'PkgPoints'=>$row['Points'],'PkgDate'=>$PkgDate,'Validity'=>$PkgExp));

  }
?>