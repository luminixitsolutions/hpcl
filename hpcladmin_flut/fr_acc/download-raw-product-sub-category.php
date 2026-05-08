<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Raw-Products";
$Page = "Download-Raw-Product-Sub-Catgeory";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Stock List</title>
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





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Sub Category Of Raw Products
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Cat Id</th>
                <th>Sub Category Name</th>
                 <th></th>
              <th>Status</th>
              <th>FrId</th>
              <th>CreatedBy</th>
              <th>CreatedDate</th>
              <th></th>
              <th></th>
              
             
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            
            $sql = "SELECT * FROM tbl_raw_prod_sub_category WHERE FrId=9";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
             ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td></td>
                <td><?php echo $row['Name']; ?></td>
              
                <td></td>
                <td>1</td>
                <td><?php echo $BillSoftFrId;?></td>
                <td><?php echo $BillSoftFrId; ?></td>
                <td><?php echo date('Y-m-d');?></td>
                <td></td>
                <td></td>
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
