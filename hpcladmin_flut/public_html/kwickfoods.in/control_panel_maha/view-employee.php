<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Employee";
$Page = "View-Employee";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Employee Account List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_users WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-employee.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Employee Account List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-employee.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               <!-- <th>QR Code</th>-->
                <th>Photo</th>
                <th>Employee Name</th>
                <th>Designation</th>
              <!--  <th>Roll</th>
                <th>Reporting Manager</th>
                 <th>Under By Reporting Manager</th>-->
                <th>Email Id</th>
                <th>Contact No</th>
                <th>Password</th> 
                <th>Another Contact No</th>
                <th>Address</th>
                 
                 
                <th>Status</th>
                <th>Register Date</th>
               <th>Aadhar Card No</th>
                <th>Blood Group</th>
                <th>Bank Holder Name</th>
                <th>Bank Name</th>
                <th>Account No</th>
                <th>Branch</th>
                <th>IFSC Code</th>
                <th>UPI ID</th>
                 
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT tu.*,tut.Name,tu2.Fname As UnderName FROM tbl_users_bill tu 
                    LEFT JOIN tbl_user_type tut ON tut.id=tu.Roll 
                    LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.Roll IN(63) AND tu.BillSoftFrId='$user_id' ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               if($row['UnderUser'] == 0){
                  $UnderUserName = "NA";
                }
                else{
                  $UnderUserName = $row['UnderName'];
                }
             ?>
            <tr>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="add-employee.php?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this user account?\nNote : Delete all record related this user (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
                <!--<td> <?php if($row["Barcode"] == '') {?>
                  -
                 <?php } else if(file_exists('../barcodes/'.$row["Barcode"])){?>
                 <a href="../barcodes/<?php echo $row["Barcode"];?>" target="_new"><img src="../barcodes/<?php echo $row["Barcode"];?>?nocache=<?php echo time(); ?>" class="d-block ui-w-40" alt="" style="width: 40px;height: 40px;"></a>
                  <?php }  else{?>
                 -
             <?php } ?></td>-->
               <td> <?php if($row["Photo"] == '') {?>
                  <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
                <td><?php echo $row['Designation'];?></td>
                <!--  <td><?php echo $row['Name'];?></td>
                <td><?php if($row['ReportingMgr']=='1'){echo "<span style='color:green;'>Yes</span>";} else { echo "<span style='color:red;'>No</span>";} ?></td>
              <td><?php echo $UnderUserName;?></td>-->
                
                <td><?php echo $row['EmailId']; ?></td>
               <td><?php echo $row['Phone']; ?></td>
               <td><?php echo $row['Password']; ?></td> 
               <td><?php echo $row['Phone2']; ?></td>
               <td><?php echo $row['Address']; ?></td>
                <!--   -->
              
                  
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
              
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
             <td><?php echo $row['AadharNo']; ?></td>
            <td><?php echo $row['BloodGroup']; ?></td>
            <td><?php echo $row['AccountName']; ?></td>
            <td><?php echo $row['BankName']; ?></td>
            <td><?php echo $row['AccountNo']; ?></td>
            <td><?php echo $row['Branch']; ?></td>
            <td><?php echo $row['IfscCode']; ?></td>
            <td><?php echo $row['UpiNo']; ?></td>
           
        
              
            </tr>
           <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
