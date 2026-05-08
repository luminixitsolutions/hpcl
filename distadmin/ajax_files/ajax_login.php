<?php 
session_start();
include_once '../config.php';
if($_POST['action'] == 'Login'){
$username = trim($_POST['Username']);
$password = $_POST['Password'];
$Roll = $_POST['Roll'];
$query = "SELECT * FROM tbl_users_bill WHERE Phone = '$username' AND Roll IN (166) AND Status=1";
    $result = $conn->query($query);
     $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    if($rncnt > 0){
        $Phone = $row['Phone'];
        $uid = $row['id'];
         $otp = rand(1000,9999);
        $_SESSION['otp'] = $otp;
         $smstxt = "Please enter ".$otp." OTP on our platform to complete the verification process. Thank you for choosing Maha Chai.";
  $dltentityid = "1501701120000037351";
  $dlttempid = "1707169838793992439";
  if($Phone == '9595454907'){}
  else{
 include '../../incsmsapi.php';  
  }
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
 $query = "SELECT * FROM tbl_users_bill WHERE Phone = '$username' AND Password='$password' AND Roll IN (1,63,64) AND BillSoftFrId=0";
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
$Uid = addslashes(trim($_POST['Uid']));
$LoginDate = date('Y-m-d');
$LoginTime = date('H:i:s');
$MasterOtp = "071193"; // Define your master OTP here
if($YourOtp == $GetOtp || $YourOtp == $MasterOtp){
$query = "SELECT * FROM tbl_users_bill WHERE Phone = '$Phone' AND id='$Uid'";
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
echo json_encode(array('Status'=>1,'Username'=>$Phone,'uid'=>$uid,'roll'=>$row['Roll'],'KycStatus'=>$row['KycStatus']));
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
?>