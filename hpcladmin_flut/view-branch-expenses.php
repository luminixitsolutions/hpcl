<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Expenses";
$Page = "View-Expenses";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
  $sql11 = "DELETE FROM tbl_expenses WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE ExpenseId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-expenses.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Expenses List
<?php if(in_array("14", $Options)) {?>
  <span style="float: right;">
<a href="add-branch-expenses.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add More</a></span><?php } ?>
</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-3">
<label class="form-label"> Branch<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<option selected="" value="all">All</option>
 <?php
                                                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=5 AND OwnFranchise=1";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                ?>
                                                    <option <?php if ($_POST["BranchId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname'] . " - " . $result['Phone']; ?></option>
                                                <?php } ?>
</select>
<div class="clearfix"></div>
</div>


<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
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
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
              
               <th>Branch</th> 
               <th>Employee Name</th> 
                <th>Amount</th>
                <th>Date</th>
                <th>Payment Mode</th>
            <th>Narration</th>
               
                
               
                 
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
              
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname,tu2.Fname As EmpName FROM tbl_branch_expenses tp 
                    LEFT JOIN tbl_users tu ON tp.BranchId=tu.id 
                    LEFT JOIN tbl_users tu2 ON tp.CreatedBy=tu2.id WHERE 1";

            
            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.BranchId='$BranchId'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tp.ExpenseDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tp.ExpenseDate<='$ToDate'";
            }
            $sql.="
            ORDER BY tp.ExpenseDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $TotalAmt+=$row['Amount'];
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
          
               
                  <td><?php echo $row['Fname']; ?></td>
              <td><?php echo $row['EmpName']; ?></td>
                    
                <td><?php echo $row['Amount']; ?></td>
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ExpenseDate']))); ?></td>
            <td><?php echo $row['PaymentMode']; ?></td>
            <td><?php echo $row['Narration']; ?></td>
         <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
            <?php if(in_array("10", $Options)){?>
              <a href="add-branch-expenses.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
           <?php } ?>
            </td> 
        <?php } ?>
              
            </tr>
           <?php $i++;} ?>

           <tr>
                <td><?php echo $i;?></td>
               
                <th>Total</th>
                <th><?php echo $TotalAmt;?></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
           </tr>
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
      "scrollX": true
    });
});
</script>
</body>
</html>
