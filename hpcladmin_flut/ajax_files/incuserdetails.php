<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Options = [];
if (is_array($row77) && !empty($row77['Options2'])) {
	$Options = explode(',', $row77['Options2']);
}
?>