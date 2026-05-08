<?php
include '../config.php';
session_start();
$user_id = $_POST['FrId'];
$CreatedDate = date('Y-m-d');
$modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
$frid = $_SESSION['FrId'] ?? 1;

$action = $_POST['action'] ?? '';

if ($action == 'view') {
  $q = "SELECT * FROM tbl_fr_billsoft_discount WHERE FrId='$user_id' ORDER BY id DESC";
  $r = $conn->query($q);
  echo '<table class="table table-bordered table-striped align-middle text-center">
          <thead><tr><th>#</th><th>Discount %</th><th>Action</th></tr></thead><tbody>';
  $i=1;
  while($row = $r->fetch_assoc()){
    echo "<tr>
      <td>{$i}</td>
      <td>{$row['Percentage']}%</td>
     <td class='action-buttons'>
        <button class='table-action-btn btn-edit update' data-id='{$row['id']}'><i class='bi bi-pencil'></i></button>
        <button class='table-action-btn btn-delete delete' data-id='{$row['id']}'><i class='bi bi-trash'></i></button>
      </td>
    </tr>";
    $i++;
  }
  echo '</tbody></table>';
}

elseif ($action == 'fetch_record') {
  $id = $_POST['id'];
  $sql = "SELECT * FROM tbl_fr_billsoft_discount WHERE id='$id'";
  $res = $conn->query($sql);
  echo json_encode($res->fetch_assoc());
}

elseif ($action == 'Add') {
  $Name = trim($_POST['Name']);
  $FrId = trim($_POST['FrId']);
  $check = $conn->query("SELECT id FROM tbl_fr_billsoft_discount WHERE Percentage='$Name'");
  if ($check->num_rows > 0) { echo 0; exit; }

  $qx = "INSERT INTO tbl_fr_billsoft_discount 
         SET Percentage='$Name', modified_time='$modified_time', push_flag=1, 
             FrId='$FrId', CreatedBy='$FrId', CreatedDate='$CreatedDate'";
  $conn->query($qx);
  echo 1;
}

elseif ($action == 'Edit') {
  $id = $_POST['id'];
  $Name = trim($_POST['Name']);
  $query2 = "UPDATE tbl_fr_billsoft_discount 
             SET Percentage='$Name', modified_time='$modified_time', push_flag=1, 
                 ModifiedBy='$user_id', ModifiedDate='$CreatedDate' 
             WHERE id='$id'";
  $conn->query($query2);
  echo 1;
}

elseif ($action == 'delete') {
  $id = $_POST['id'];
  $conn->query("DELETE FROM tbl_fr_billsoft_discount WHERE id='$id'");
  echo 1;
}
?>
