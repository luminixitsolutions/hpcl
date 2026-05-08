<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action']=='SaveFeedback'){
	$CustId = $_POST['CustId'];
$Question1 = addslashes(trim($_POST['Question1']));	
$Answer1 = addslashes(trim($_POST['Answer1']));
$Question2 = addslashes(trim($_POST['Question2']));	
$Answer2 = addslashes(trim($_POST['Answer2']));
$Question3 = addslashes(trim($_POST['Question3']));	
$Answer3 = addslashes(trim($_POST['Answer3']));
$Question4 = addslashes(trim($_POST['Question4']));	
$Answer4 = addslashes(trim($_POST['Answer4']));
$Question5 = addslashes(trim($_POST['Question5']));	
$Answer5 = addslashes(trim($_POST['Answer5']));
$Question6 = addslashes(trim($_POST['Question6']));	
$Answer6 = addslashes(trim($_POST['Answer6']));
$Question7 = addslashes(trim($_POST['Question7']));	
$Answer7 = addslashes(trim($_POST['Answer7']));
$Question8 = addslashes(trim($_POST['Question8']));	
$Answer8 = addslashes(trim($_POST['Answer8']));
$Question9 = addslashes(trim($_POST['Question9']));	
$Answer9 = addslashes(trim($_POST['Answer9']));
$Question10 = addslashes(trim($_POST['Question10']));	
$Answer10 = addslashes(trim($_POST['Answer10']));
$Question11 = addslashes(trim($_POST['Question11']));	
$Answer11 = addslashes(trim($_POST['Answer11']));
$Question12 = addslashes(trim($_POST['Question12']));	
$Answer12 = addslashes(trim($_POST['Answer12']));
$Question13 = addslashes(trim($_POST['Question13']));	
$Answer13 = addslashes(trim($_POST['Answer13']));
$Suggestion = addslashes(trim($_POST['Suggestion']));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

$sql4 = "SELECT Fname FROM tbl_users WHERE id='$CustId'";
$row4 = getRecord($sql4);
$CustName = "<b>Shop Name : </b>".$row4['Fname'];

$sql3 = "SELECT * FROM tbl_cashback_amount WHERE id=2";
$row3 = getRecord($sql3);
$CashbackAmt = $row3['Price'];
$sql = "SELECT * FROM tbl_survey WHERE CustId='$CustId' AND CreatedDate='$CreatedDate' AND Type=1";
$rncnt = getRow($sql);
if($rncnt > 0){
	$sql = "DELETE FROM tbl_survey WHERE CustId='$CustId' AND CreatedDate='$CreatedDate' AND Type=1";
	$conn->query($sql);
	$sql = "DELETE FROM wallet WHERE UserId2='$CustId' AND UserId='$user_id' AND ExeVisit=1 AND CreatedDate='$CreatedDate'";
	$conn->query($sql);
	$sql = "INSERT INTO tbl_survey SET CustId='$CustId',ExeId='$user_id',Question1='$Question1',Answer1='$Answer1',Question2='$Question2',Answer2='$Answer2',Question3='$Question3',Answer3='$Answer3',Question4='$Question4',Answer4='$Answer4',Question5='$Question5',Answer5='$Answer5',Question6='$Question6',Answer6='$Answer6',Question7='$Question7',Answer7='$Answer7',Question8='$Question8',Answer8='$Answer8',Question9='$Question9',Answer9='$Answer9',Question10='$Question10',Answer10='$Answer10',Question11='$Question11',Answer11='$Answer11',Question12='$Question12',Answer12='$Answer12',Question13='$Question13',Answer13='$Answer13',Suggestion='$Suggestion',Status='1',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Type=1";
	$conn->query($sql);
	
	$sql_12 = "INSERT INTO wallet SET UserId2='$CustId',UserId='$user_id',Amount='$CashbackAmt',Status='Cr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Narration='Shop Visit Survey Cashback',ExeVisit=1,CustName='$CustName'";
$conn->query($sql_12);
	echo $CashbackAmt;

    	
	//echo 0;
}
else{
	$sql = "INSERT INTO tbl_survey SET CustId='$CustId',ExeId='$user_id',Question1='$Question1',Answer1='$Answer1',Question2='$Question2',Answer2='$Answer2',Question3='$Question3',Answer3='$Answer3',Question4='$Question4',Answer4='$Answer4',Question5='$Question5',Answer5='$Answer5',Question6='$Question6',Answer6='$Answer6',Question7='$Question7',Answer7='$Answer7',Question8='$Question8',Answer8='$Answer8',Question9='$Question9',Answer9='$Answer9',Question10='$Question10',Answer10='$Answer10',Question11='$Question11',Answer11='$Answer11',Question12='$Question12',Answer12='$Answer12',Question13='$Question13',Answer13='$Answer13',Suggestion='$Suggestion',Status='1',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Type=1";
	$conn->query($sql);
	
	$sql_12 = "INSERT INTO wallet SET UserId2='$CustId',UserId='$user_id',Amount='$CashbackAmt',Status='Cr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Narration='Shop Visit Survey Cashback',ExeVisit=1,CustName='$CustName'";
$conn->query($sql_12);


	echo $CashbackAmt;
}
}

/*if($_POST['action']=='SaveFeedback'){
	$CustId = $_POST['CustId'];
	$ShopName = addslashes(trim($_POST['ShopName']));
	$VisitDate = addslashes(trim($_POST['VisitDate']));
	$ShopLocation = addslashes(trim($_POST['ShopLocation']));
	$CleaningRate = addslashes(trim($_POST['CleaningRate']));
	$EmpDressCode = addslashes(trim($_POST['EmpDressCode']));
	$Offer = addslashes(trim($_POST['Offer']));
	$Quality = addslashes(trim($_POST['Quality']));
	$Communication = addslashes(trim($_POST['Communication']));
	$Suggest = addslashes(trim($_POST['Suggest']));
	$Remark = addslashes(trim($_POST['Remark']));
	$Rating = addslashes(trim($_POST['Rating']));
	$Contribution = addslashes(trim($_POST['Contribution']));
	$CreatedDate = date('Y-m-d');
	$sql = "INSERT INTO tbl_exe_shop_feedback SET CustId='$CustId',ExeId='$user_id',ShopName='$ShopName',VisitDate='$VisitDate',ShopLocation='$ShopLocation',CleaningRate='$CleaningRate',EmpDressCode='$EmpDressCode',Offer='$Offer',Quality='$Quality',Communication='$Communication',Suggest='$Suggest',Remark='$Remark',Rating='$Rating',Contribution='$Contribution',Status=1,CreatedDate='$CreatedDate'";
	$conn->query($sql);

	$sql_12 = "INSERT INTO tbl_points SET UserId='$user_id',Points='25',Status=1,CrDr='cr',CreatedDate='$CreatedDate',Narration='Survey Feedback Credit Points'";
$conn->query($sql_12);
	echo 1;
	}*/

	if($_POST['action']=='SaveKyc'){
	$id = $_POST['id'];
	$Name = addslashes(trim($_POST['Name']));
	$Address = addslashes(trim($_POST['Address']));
	$Phone = addslashes(trim($_POST['Phone']));
	$Phone2 = addslashes(trim($_POST['Phone2']));
	$Profession = addslashes(trim($_POST['Profession']));
	$PanNo = addslashes(trim($_POST['PanNo']));
	$AadharNo = addslashes(trim($_POST['AadharNo']));
	$FsiicNo = addslashes(trim($_POST['FsiicNo']));
	$ShopActNo = addslashes(trim($_POST['ShopActNo']));
	$Dob = addslashes(trim($_POST['Dob']));
	$AnniversaryDate = addslashes(trim($_POST['AnniversaryDate']));
	$ShopOpenDate = addslashes(trim($_POST['ShopOpenDate']));
	$CreatedDate = date('Y-m-d');
	if($id == ''){
	$sql = "INSERT INTO tbl_kyc SET ExeId='$user_id',Name='$Name',Address='$Address',Phone='$Phone',Phone2='$Phone2',Profession='$Profession',PanNo='$PanNo',AadharNo='$AadharNo',FsiicNo='$FsiicNo',ShopActNo='$ShopActNo',Dob='$Dob',AnniversaryDate='$AnniversaryDate',ShopOpenDate='$ShopOpenDate',Status=1,CreatedDate='$CreatedDate'";
	$conn->query($sql);
	echo 1;
}
else{
	$sql = "UPDATE tbl_kyc SET Name='$Name',Address='$Address',Phone='$Phone',Phone2='$Phone2',Profession='$Profession',PanNo='$PanNo',AadharNo='$AadharNo',FsiicNo='$FsiicNo',ShopActNo='$ShopActNo',Dob='$Dob',AnniversaryDate='$AnniversaryDate',ShopOpenDate='$ShopOpenDate' WHERE id='$id'";
	$conn->query($sql);
	echo 1;
	}
}

if($_POST['action']=='checkTodayVisit'){
$cid = $_POST['cid'];
$date = $_POST['date'];
$sql = "SELECT id FROM tbl_survey WHERE CustId='$cid' AND CreatedDate='$date' AND Type=1";
$rncnt = getRow($sql);
if($rncnt > 0){
	$row = getRecord($sql);
	echo $row['id'];
}
else{
	echo 0;
}
}

?>