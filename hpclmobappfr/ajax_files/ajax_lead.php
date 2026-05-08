<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$sql2 = "SELECT Phone FROM tbl_users WHERE id='$user_id'";
$row2 = getRecord($sql2);
$UserPhone = $row2['Phone'];
if($_POST['action']=='SaveLead'){
	$id = $_POST['id'];
	$Name = addslashes(trim($_POST['Name']));
	$Phone2 = addslashes(trim($_POST['Phone']));
	$EmailId = addslashes(trim($_POST['EmailId']));
	$Address = addslashes(trim($_POST['Address']));
	$CreatedDate = $_POST['CreatedDate'];
	if($id == ''){
		$sql2 = "SELECT * FROM tbl_bp_leads WHERE Phone='$Phone2'";
		$rncnt2 = getRow($sql2);
		if($rncnt2 > 0){
			echo 0;
		}
		else{
		$sql = "INSERT INTO tbl_bp_leads SET UserId='$user_id',Name='$Name',Phone='$Phone2',EmailId='$EmailId',Address='$Address',Status=0,CreatedDate='$CreatedDate'";
		$conn->query($sql);
		
		$Phone = $UserPhone;
	 $smstxt = "Congratulations! You've successfully added a new lead to our system. Name: ".$Name." Phone No. ".$Phone2." Thank you for your contribution to our success!";
  $dltentityid = "1501701120000037351";
  $dlttempid = "1707169875451863126";
  include '../../incsmsapi.php';  
		echo 1;
		}
	}
	else{
		$sql2 = "SELECT * FROM tbl_bp_leads WHERE Phone='$Phone2' AND id!='$id'";
		$rncnt2 = getRow($sql2);
		if($rncnt2 > 0){
			echo 0;
		}
		else{
		$sql = "UPDATE tbl_bp_leads SET Name='$Name',Phone='$Phone2',EmailId='$EmailId',Address='$Address' WHERE id='$id'";
		$conn->query($sql);
		
		$Phone = $UserPhone;
	 $smstxt = "Congratulations! You've successfully added a new lead to our system. Name: ".$Name." Phone No. ".$Phone2." Thank you for your contribution to our success!";
  $dltentityid = "1501701120000037351";
  $dlttempid = "1707169875451863126";
  include '../../incsmsapi.php';  
		echo 1;
		}
	}
	
}
?>