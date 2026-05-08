<?php 
error_reporting(0);
session_start();
include_once '../config.php';
unset($_SESSION['UserId']);
unset($_SESSION['Admin']);
unset($_SESSION['Roll']);
setcookie("member_login",$username,time()- 3600);
?>
<script language="javascript">
window.location.href="index.php";
</script>