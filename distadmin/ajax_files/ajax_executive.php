<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$Fname = addslashes(trim($_POST['Fname']));
$Mname = addslashes(trim($_POST['Mname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$Password = addslashes($_POST['Password']);
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$GstNo = addslashes(trim($_POST['GstNo']));
$Pincode = trim($_POST['Pincode']);
$Details = addslashes(trim($_POST['Details']));
$Status = $_POST['Status'];
$CatId = $_POST['CatId'];
$Roll = $_POST['Roll'];
$AccountName = addslashes(trim($_POST['AccountName']));
$BankName = addslashes(trim($_POST['BankName']));
$AccountNo = addslashes(trim($_POST['AccountNo']));
$IfscCode = addslashes(trim($_POST['IfscCode']));
$Branch = addslashes(trim($_POST['Branch']));
$UpiNo = addslashes(trim($_POST['UpiNo']));
$Dob = addslashes(trim($_POST['Dob']));
$AadharNo = addslashes(trim($_POST['AadharNo']));
$PanNo = addslashes(trim($_POST['PanNo']));

if($_POST['AssignFranchiseDelivery']!=''){
$AssignFranchiseDelivery = implode(",", $_POST['AssignFranchiseDelivery']);
}
else{
   $AssignFranchiseDelivery = 0; 
}


$CreatedDate = date('Y-m-d');

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
	$Photo = $_POST['OldPhoto'];
}

if($id == ''){
 $sql = "INSERT INTO tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='6',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',AssignFranchiseDelivery='$AssignFranchiseDelivery',Dob='$Dob',AadharNo='$AadharNo'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$Phone2 = substr($Phone,0,5);
    $CustomerId = "D".$Phone2."".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);
 $sql2 = "INSERT INTO tbl_bank_details SET AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',Status='1',CreatedBy='$user_id',CreatedDate='$CreatedDate',userid='$EmpId',type=1";
    $conn->query($sql2);

echo "<script>alert('Record Created Successfully!');window.location.href='../view-delivery-accounts.php';</script>";
}
else{
$sql = "UPDATE tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='6',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',AssignFranchiseDelivery='$AssignFranchiseDelivery',Dob='$Dob',AadharNo='$AadharNo' WHERE id='$id'";
$conn->query($sql);

$sql3 = "SELECT * FROM tbl_bank_details WHERE AccountNo='$AccountNo' AND IfscCode='$IfscCode' AND type=1 AND userid='$id'";
$rncnt3 = getRow($sql3);
if($rncnt3 > 0){
 $sql2 = "UPDATE tbl_bank_details SET AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',Status='1',ModifiedBy='$user_id',ModifiedDate='$CreatedDate' WHERE userid='$id' AND type=1";
$conn->query($sql2);
}
else{
     $sql2 = "INSERT INTO tbl_bank_details SET AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',Status='1',CreatedBy='$user_id',CreatedDate='$CreatedDate',userid='$id',type=1";
    $conn->query($sql2);
}

echo "<script>alert('Record Updated Successfully!');window.location.href='../view-delivery-accounts.php';</script>";
}

}
