<?php
session_start();
include_once '../config.php';
include('../../libs/phpqrcode/qrlib.php');
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$Fname = addslashes(trim($_POST['Fname']));
$Mname = addslashes(trim($_POST['Mname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone22'];
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

$FatherPhone = addslashes(trim($_POST['FatherPhone']));
$Designation = addslashes(trim($_POST['Designation']));
$Dob = addslashes(trim($_POST['Dob']));
$AadharNo = addslashes(trim($_POST['AadharNo']));
$BloodGroup = addslashes(trim($_POST['BloodGroup']));
$JoinDate = addslashes(trim($_POST['JoinDate']));
$EmailId2 = addslashes(trim($_POST['EmailId2']));
$PerDaySalary = addslashes(trim($_POST['PerDaySalary']));


$Status = $_POST['Status'];
$CatId = $_POST['CatId'];
$Roll = $_POST['Roll'];


$PanNo = addslashes(trim($_POST['PanNo']));
$CompId = addslashes(trim($_POST['CompId']));
$BranchId = addslashes(trim($_POST['BranchId']));
$CreatedDate = date('Y-m-d');
$pageval = addslashes(trim($_POST['pageval']));

$AccountName = addslashes(trim($_POST['AccountName']));
$BankName = addslashes(trim($_POST['BankName']));
$AccountNo = addslashes(trim($_POST['AccountNo']));
$IfscCode = addslashes(trim($_POST['IfscCode']));
$Branch = addslashes(trim($_POST['Branch']));
$UpiNo = addslashes(trim($_POST['UpiNo']));
$UnderUser = addslashes(trim($_POST['UnderUser']));
$ReportingMgr = addslashes(trim($_POST['ReportingMgr']));
$ResignStatus = addslashes(trim($_POST['ResignStatus']));
$ResignDate = addslashes(trim($_POST['ResignDate']));
$ResignComment = addslashes(trim($_POST['ResignComment']));
$Percentage = addslashes(trim($_POST['Percentage']));

if($_POST['CocoFranchiseAccess']!=''){
$CocoFranchiseAccess = implode(",", $_POST['CocoFranchiseAccess']);
}
else{
$CocoFranchiseAccess = 0; 
}

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

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
  $Photo2 = $_POST['OldPhoto2'];
}


$randno3 = rand(1,100);
$src3 = $_FILES['Photo3']['tmp_name'];
$fnm3 = substr($_FILES["Photo3"]["name"], 0,strrpos($_FILES["Photo3"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["Photo3"]["name"],strpos($_FILES["Photo3"]["name"],"."));
$dest3 = '../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$Photo3 = $imagepath3 ;
} 
else{
  $Photo3 = $_POST['OldPhoto3'];
}

$tempDir = '../../barcodes/'; 

if($id == ''){
    $sql2 = "SELECT * FROM tbl_users_bill WHERE Phone='$Phone' AND Roll='$Roll'";
 $rncnt2 = getRow($sql2);
 if($rncnt2 > 0){
     echo "<script>alert('Phone No Already Exists!');window.location.href='../view-financer.php';</script>";
 }   
 else{
$sql = "INSERT INTO tbl_users_bill SET Percentage='$Percentage',CocoFranchiseAccess='$CocoFranchiseAccess',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',PerDaySalary='$PerDaySalary',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',UnderUser='$UnderUser',ReportingMgr='$ReportingMgr',ResignStatus='$ResignStatus',ResignDate='$ResignDate',ResignComment='$ResignComment'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);


$sql3 = "INSERT INTO customer_address SET UserId='$EmpId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'";
$conn->query($sql3);

$filename = $EmpId.".png";
$codeContents = $Phone;
QRcode::png($codeContents, $tempDir.''.$filename, QR_ECLEVEL_L, 5);
$Barcode = $filename;

$CustomerId = "C".$EmpId;
$sql3 = "UPDATE tbl_users_bill SET Barcode='$Barcode',CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);

  echo "<script>alert('Record Created Successfully!');window.location.href='../view-financer.php';</script>";
}
}
else{
     $sql2 = "SELECT * FROM tbl_users_bill WHERE Phone='$Phone' AND id!='$id' AND Roll='$Roll'";
 $rncnt2 = getRow($sql2);
 if($rncnt2 > 0){
     echo "<script>alert('Phone No Already Exists!');window.location.href='../view-financer.php?id=$id';</script>";
 }  
 else{
     $filename = $id.".png";
$codeContents = $Phone;
QRcode::png($codeContents, $tempDir.''.$filename, QR_ECLEVEL_L, 5);
$Barcode = $filename;

$sql = "UPDATE tbl_users_bill SET Percentage='$Percentage',CocoFranchiseAccess='$CocoFranchiseAccess',Barcode='$Barcode',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',PerDaySalary='$PerDaySalary',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',UnderUser='$UnderUser',ReportingMgr='$ReportingMgr',ResignStatus='$ResignStatus',ResignDate='$ResignDate',ResignComment='$ResignComment' WHERE id='$id'";
$conn->query($sql);


$sql3 = "UPDATE customer_address SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='$AreaId',Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate' WHERE UserId='$id'";
$conn->query($sql3);

  echo "<script>alert('Record Updated Successfully!');window.location.href='../view-financer.php';</script>";
}
}

}

if($_POST['action'] == 'deletePhoto'){
   	$id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $q = "UPDATE tbl_users_bill SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if($_POST['action'] == 'getUserDetails'){
$id = $_POST['id'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users_bill tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.id='$id'";
$row = getRecord($sql);
echo json_encode($row);
}

if($_POST['action'] == 'getUserDetails2'){
$CellNo = $_POST['CellNo'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users_bill tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.Phone='$CellNo'";
$row = getRecord($sql);
echo json_encode($row);
}