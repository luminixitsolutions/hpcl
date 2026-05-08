<?php 
	$user_id = $_SESSION['Admin']['id'];
    $sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
	$row77 = getRecord($sql77);
    $Roll = $row77['Roll'];
    $BillSoftFrId = $row77['BillSoftFrId'];
    $subzoneids = $row77['subzone'];
    //echo $row77['Options'];
	$Options = explode(',',$row77['Options2']);
	//$CocoFranchiseAccess = explode(',',$row77['CocoFranchiseAccess']);
	$CocoFranchiseAccess = $row77['CocoFranchiseAccess'];
	$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}
 ?>
<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-dark container-p-x" id="layout-navbar">

<a href="index.php" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
<span class="app-brand-logo demo">
<img src="assets/img/logo-dark.png" alt="" class="img-fluid">
</span>
<!--<span class="app-brand-text demo font-weight-normal ml-2">The Young Brains</span>-->
</a>

<div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
    
    
<a class="nav-item nav-link px-0 mr-lg-4" href="javascript:">
<i class="ion ion-md-menu text-large align-middle"></i>
</a>
</div>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
<span class="navbar-toggler-icon"></span>
</button>
<div class="navbar-collapse collapse" id="layout-navbar-collapse">
<img src="logon1.jpg" height="40px">
<hr class="d-lg-none w-100 my-2">
<div class="navbar-nav align-items-lg-center ml-auto">


<div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>
<div class="demo-navbar-user nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
<span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
	<?php if($row77['Photo']=='') {?>
<img src="user_icon.jpg" alt class="d-block ui-w-30 rounded-circle">
<?php } else{?>
	<img src="../uploads/<?php echo $row77['Photo']; ?>" alt class="d-block ui-w-30 rounded-circle" style="width: 30px;height: 30px;">
<?php } ?>
<span class="px-1 mr-lg-2 ml-2 ml-lg-0"><?php echo $row77['Fname'];?></span>
</span>
</a>
<div class="dropdown-menu dropdown-menu-right">
 <!--<a href="company-information.php" class="dropdown-item">
<i class="feather icon-user text-muted"></i> &nbsp; My profile</a>-->
<a href="change-password.php" class="dropdown-item">
<i class="feather icon-unlock text-muted"></i> &nbsp; Change Password</a>
<!-- <a href="javascript:" class="dropdown-item">
<i class="feather icon-settings text-muted"></i> &nbsp; Account settings</a> -->
<div class="dropdown-divider"></div>
<a href="logout.php" class="dropdown-item">
<i class="feather icon-power text-danger"></i> &nbsp; Log Out</a>
</div>
</div>
</div>
</div>
</nav>