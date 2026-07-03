<?php 
session_start();
include_once __DIR__ . '/../config.php';
$user_id = $_SESSION['Admin']['id'];
$action = req('action', '');

if ($action === 'Add'){
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$CountryId = $_POST["CountryId"];
$StateId = $_POST["StateId"];
$CityId = $_POST['CityId'];

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

$query = "SELECT * FROM tbl_pincode WHERE Pincode = '$Name' AND CountryId='$CountryId' AND StateId='$StateId' AND CityId='$CityId'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
$qx = "INSERT INTO tbl_pincode SET Pincode = '$Name',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Status='$Status'";
	$conn->query($qx);
	echo 1;
}
}

if ($action === 'fetch_record'){
 $id = $_POST['id'];
    $query = "SELECT * FROM tbl_pincode WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode($row);


}

if ($action === 'Edit') {
     $id = $_POST['id'];
$Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$CountryId = $_POST["CountryId"];
$StateId = $_POST["StateId"];
$CityId = $_POST['CityId'];

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

$query = "SELECT * FROM tbl_pincode WHERE Pincode = '$Name' AND CountryId='$CountryId' AND StateId='$StateId' AND CityId='$CityId' AND id != '$id'";
$result = $conn->query($query);
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0){
  echo 0;
}
else{
  $query2 = "UPDATE tbl_pincode SET Pincode = '$Name',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Status='$Status' WHERE id = '$id'";
 	$conn->query($query2);
  echo 1;
}
}

  if ($action === 'delete') {
   
      $id = $_POST['id'];
      $query = "DELETE FROM tbl_pincode WHERE id = '$id'";
      $conn->query($query);
      echo "Delete Successfully";

  }

if ($action === 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_pincode SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Photo Delete Successfully";
}
if ($action === 'load') {
    header('Content-Type: application/json; charset=utf-8');

    $draw = (int) req('draw', 0);
    $start = max(0, (int) req('start', 0));
    $length = (int) req('length', 25);
    if ($length < 1) {
        $length = 25;
    }
    if ($length > 500) {
        $length = 500;
    }

    $searchValue = '';
    if (isset($_POST['search']['value'])) {
        $searchValue = trim((string) $_POST['search']['value']);
    }

    $baseFrom = "FROM tbl_pincode sb
        LEFT JOIN tbl_state s ON s.id = sb.StateId
        LEFT JOIN tbl_country c ON c.id = sb.CountryId
        LEFT JOIN tbl_city ct ON ct.id = sb.CityId";

    $where = " WHERE 1=1 ";
    if ($searchValue !== '') {
        $sv = $conn->real_escape_string($searchValue);
        $where .= " AND (
            sb.Pincode LIKE '%$sv%' OR
            c.Name LIKE '%$sv%' OR
            s.Name LIKE '%$sv%' OR
            ct.Name LIKE '%$sv%'
        )";
    }

    $totalRow = $conn->query("SELECT COUNT(*) AS cnt FROM tbl_pincode")->fetch_assoc();
    $recordsTotal = (int) ($totalRow['cnt'] ?? 0);

    $filteredRow = $conn->query("SELECT COUNT(*) AS cnt $baseFrom $where")->fetch_assoc();
    $recordsFiltered = (int) ($filteredRow['cnt'] ?? 0);

    $orderCol = 0;
    $orderDir = 'DESC';
    if (isset($_POST['order'][0]['column'])) {
        $orderCol = (int) $_POST['order'][0]['column'];
    }
    if (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') {
        $orderDir = 'ASC';
    }

    $orderMap = [
        0 => 'sb.id',
        1 => 'c.Name',
        2 => 's.Name',
        3 => 'ct.Name',
        4 => 'sb.Pincode',
        5 => 'sb.Status',
        6 => 'sb.id',
    ];
    $orderBy = $orderMap[$orderCol] ?? 'sb.id';

    $sql = "SELECT sb.id, sb.Pincode, sb.Status,
            c.Name AS Country, s.Name AS State, ct.Name AS City
            $baseFrom $where
            ORDER BY $orderBy $orderDir
            LIMIT $start, $length";

    $result = $conn->query($sql);
    $data = [];
    $srno = $start + 1;

    while ($row = $result->fetch_assoc()) {
        $status = ($row['Status'] == '1')
            ? "<span style='color:green;'>Active</span>"
            : "<span style='color:red;'>Inactive</span>";

        $id = (int) $row['id'];
        $actions = '<a data-id="' . $id . '" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;'
            . '<a data-id="' . $id . '" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Delete" class="delete"><i class="lnr lnr-trash text-danger"></i></a>';

        $data[] = [
            $srno++,
            htmlspecialchars($row['Country'] ?? '', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['State'] ?? '', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['City'] ?? '', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['Pincode'] ?? '', ENT_QUOTES, 'UTF-8'),
            $status,
            $actions,
        ];
    }

    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
    ]);
    exit;
}

  if ($action === 'view'){?>
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
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT ct.Name As City,c.Name As Country,s.Name As State,sb.* FROM tbl_pincode sb LEFT JOIN tbl_state s ON s.id=sb.StateId 
    LEFT JOIN tbl_country c ON c.id=sb.CountryId 
    LEFT JOIN tbl_city ct ON ct.id=sb.CityId ORDER BY sb.id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
             
             <td><?php echo $nx['Country']; ?></td>
              <td><?php echo $nx['State']; ?></td>
                <td><?php echo $nx['City']; ?></td>
                <td><?php echo $nx['Pincode']; ?></td>
             <td><?php if($nx['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Inactive</span>";} ?></td>
             <td><a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit" class="update"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a data-id="<?php echo $nx['id']; ?>" href='javascript:void(0);' data-toggle="tooltip" data-placement="top" title="Delete" data-original-title="Delete" class="delete" id="bootbox-confirm"><i class="lnr lnr-trash text-danger"></i></a>
             </td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
 <?php }
?>
