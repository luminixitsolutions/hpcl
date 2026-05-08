<?php 
session_start();
include_once '../config.php';
if($_POST['action'] == 'Login'){
$username = trim($_POST['Username']);
$password = $_POST['Password'];
$Roll = $_POST['Roll'];
// $query = "SELECT * FROM tbl_users_bill WHERE Phone = '$username' AND Roll IN (1,63,64,5)";
$query = "SELECT * FROM tbl_users_bill WHERE Phone = '$username' AND Password='$password' AND Roll IN (5) AND Status=1 AND ShowFrStatus=1";
    $result = $conn->query($query);
     $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    if($rncnt > 0){
        $Phone = $row['Phone'];
        $uid = $row['id'];
       
         $_SESSION['Admin'] = $row;
         $_SESSION['Roll'] = $row['Roll'];
        echo json_encode(array('Status'=>1,'Roll'=>$row['Roll'],'Username'=>$Phone,'uid'=>$uid));
    }
    else{
        unset($_SESSION['Admin']);
        unset($_SESSION['Roll']);
        echo json_encode(array('Status'=>0));
    } 
}

if($_POST['action'] == 'Login2'){
$username = trim($_POST['Username']);
$password = $_POST['Password'];
$Roll = $_POST['Roll'];
 $query = "SELECT * FROM tbl_users_bill WHERE Phone = '$username' AND Password='$password' AND Roll IN (1,63,64)";
  $rncnt = getRow($query);
    if($rncnt > 0){
        $row = getRecord($query);
        $Phone = $row['Phone'];
        $uid = $row['id'];
       $_SESSION['Admin'] = $row;
        echo json_encode(array('Status'=>1,'Roll'=>$row['Roll'],'Username'=>$Phone,'uid'=>$uid));
    }
    else{
        unset($_SESSION['Admin']);
        unset($_SESSION['Roll']);
        echo json_encode(array('Status'=>0));
    } 
}

if($_POST['action']=='OtpVerify'){
 $Phone = addslashes(trim($_POST['Phone']));
 $YourOtp = addslashes(trim($_POST['YourOtp']));
$GetOtp = addslashes(trim($_POST['GetOtp']));
$uid = $_POST['uid'];
$LoginDate = date('Y-m-d');
$LoginTime = date('H:i:s');
if($YourOtp == $GetOtp){
$query = "SELECT * FROM tbl_users_bill WHERE id = '$uid' AND Status=1";
$rncnt = getRow($query);
if($rncnt > 0){
$row = getRecord($query);
$_SESSION['Admin'] = $row;
$Phone = $row['Phone'];
$uid = $row['id'];
setcookie("member_login",$Phone,time()+ 64800);
unset($_SESSION['otp']);
$sql = "INSERT INTO tbl_login_time SET UserId='$uid',LoginDate='$LoginDate',LoginTime='$LoginTime'";
$conn->query($sql);
echo json_encode(array('Status'=>1,'Username'=>$Phone,'uid'=>$uid,'roll'=>$row['Roll'],'KycStatus'=>$row['KycStatus'],'title'=>str_replace(" ","#",$row['title']),'address'=>str_replace(" ","#",$row['Address']),'mobile'=>str_replace(" ","#",$row['PrintMobNo']),'gstin'=>str_replace(" ","#",$row['GstNo']),'receipt_title'=>'Retail#Receipt','terms_condition'=>str_replace(" ","#",$row['terms_condition']),'bottom_title'=>str_replace(" ","#",$row['bottom_title'])));
}
}
else{
echo json_encode(array('Status'=>0));   
}
}  

if($_POST['action'] == 'resendotp'){
    $Phone = $_POST['Phone'];
    $otp = rand(1000,9999);
        $_SESSION['otp'] = $otp;
         $smstxt = "Please enter ".$otp." OTP on our platform to complete the verification process. Thank you for choosing Maha Chai.";
  $dltentityid = "1501701120000037351";
  $dlttempid = "1707169838793992439";
  include '../../incsmsapi.php';  
    echo $otp;
}

if($_POST['action'] == 'checkLogin'){
    $LoginUserId = $_POST['LoginUserId'];
    $sql = "SELECT * FROM tbl_users_bill WHERE id='$LoginUserId'";
    $row = getRecord($sql);
    $_SESSION['Admin'] = $row;
    echo $_SESSION['Admin']['id'];
}
?>