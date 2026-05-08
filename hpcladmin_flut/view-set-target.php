<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Target-Complete";
$Page = "Set-Target";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Task List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
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
  $sql11 = "DELETE FROM tbl_set_target WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-set-target.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Set Target List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="set-target.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">

     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  <div class="form-group col-md-4">
<label class="form-label"> Account<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="UserId" id="UserId" required>
<option selected="" value="all">All</option>
 <?php 
     $sql1 = "SELECT * FROM tbl_users_bill WHERE Roll=5";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
          
      ?>
    <option <?php if($_POST['UserId'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
<?php } ?>
 
</select>
<div class="clearfix"></div>
</div>

<!--<div class="form-group col-md-3">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-3">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>-->
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top: 30px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="form-group col-md-1"
<label class="form-label">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Franchise Name</th>
                <th>Month</th> 
                <th>Year</th> 
                <th>Target</th> 
                <th>QSR KITCHEN SALES (Qty)</th> 
                <th>PACK FOOD SALES (Qty)</th> 
                <th>CROSS SALES (Qty)</th> 
                <th>Created By</th> 
                <th>CreatedDate</th> 
               
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tc.ShopName,tc2.Fname As CreatedName FROM tbl_set_target tp 
            LEFT JOIN tbl_users_bill tc ON tc.id=tp.frid 
            LEFT JOIN tbl_users_bill tc2 ON tc2.id=tp.CreatedBy WHERE 1";
            if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.frid='$UserId'";
                }
            }
           
            $sql.= " ORDER BY tp.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $TotPrice+=$row['Price'];
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['ShopName']; ?></td> 
              <td><?php echo $row['month']; ?></td> 
              <td><?php echo $row['year']; ?></td> 
              <td><?php echo $row['target']; ?></td> 
              <td><?php echo $row['qsrkitchen_target']; ?></td> 
              <td><?php echo $row['packfood_target']; ?></td> 
              <td><?php echo $row['cross_sale_target']; ?></td> 
              <td><?php echo $row['CreatedName']; ?></td> 
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['createddate']))); ?></td>
             
             
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="set-target.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){
               
                ?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
        
              
            </tr>
           <?php $i++;} ?>
           
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
    });
});
</script>
</body>
</html>
