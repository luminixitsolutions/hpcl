<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "Assign-Franchise-Zone";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

 <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_assign_fr_to_zone WHERE id = '$id'";
  $conn->query($sql11);
 
  
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
       window.location.href="view-assign-franchise-to-zone.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Assigning Dealer To Zone
<?php if(in_array("14", $Options)) {?>  
<span style="float: right;">
<a href="assign-franchise-to-zone.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Assign Fr</a></span>
<?php } ?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
       <thead>
            <tr>
              <th>#</th>
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
              <th>Zone</th>
               <th>Dealer</th>
          
             
            </tr>
        </thead>
<tbody>
<?php 
$i=1;
 $sql2 = "SELECT tpf.*,tz.Name FROM tbl_assign_fr_to_zone tpf INNER JOIN tbl_zone tz ON tz.id=tpf.zone ORDER BY tpf.id DESC"; 

    $res2 = $conn->query($sql2);
    $row_cnt = mysqli_num_rows($res2);
    if($row_cnt > 0){
    while($row = $res2->fetch_assoc()){ 
        $frids = $row['frids'];
        if($row['frids']!=''){
            $sql = "SELECT GROUP_CONCAT(ShopName SEPARATOR ' , ') AS ShopName FROM tbl_users_bill WHERE id IN($frids)";
            $row2 = getRecord($sql);
        }
    ?>
<tr>
     <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
<td>
    <?php if(in_array("10", $Options)){?>
 <a href="assign-franchise-to-zone.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
 <?php } if(in_array("11", $Options)){?>
 <a onClick="return confirm('Are you sure you want delete this Service?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
 <?php } ?>
</td><?php } ?>
             <td><?php echo $i; ?></td>
             <td><?php echo $row['Name']; ?></td>
             
                <td><?php echo $row2['ShopName']; ?></td>
            
        
</tr>
<?php $i++;}} ?>

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


<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
 
	$(document).ready(function() {
    $('#example').DataTable({
      
    });
});
</script>
</body>
</html>
