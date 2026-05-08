<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Allocate-Products";

 
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





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Distributer For Allocate Other Products</h4>

<div class="card" style="padding: 10px;">

   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
              <th>Id</th>
                <th>Distributer Name</th>
               <th>Contact No</th>
               <th>State</th>
                <th>City</th>
                <th>Address</th>
                 <th>Pincode</th>
               <th>Allocate</th>
            
                
            </tr>
        </thead>
        <tbody>
            <?php 
           
            $sql = "SELECT tu.id,tu.Fname,tu.Phone,tu.Address,tu.Pincode,ts.Name AS StateName,tc.Name AS CityName FROM tbl_users tu 
                    LEFT JOIN tbl_state ts ON tu.StateId=ts.id 
                    LEFT JOIN tbl_city tc ON tu.CityId=tc.id WHERE tu.Roll=166 ";
            $sql.= " ORDER BY tu.id DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['Fname']; ?></td>
              <td><?php echo $row['Phone']; ?></td>
              <td><?php echo $row['StateName']; ?></td>
               <td><?php echo $row['CityName']; ?></td>
               <td><?php echo $row['Address']; ?></td>
               <td><?php echo $row['Pincode']; ?></td>
               <td><a href="allocate-other-product-distributer.php?frid=<?php echo $row['id']; ?>&ShopName=<?php echo $row['Fname']; ?>" class="badge badge-pill badge-secondary">Allocate Products</a></td>
              
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
 function chageSurveyDetails(val,id){
   var action = "chageSurveyDetails";
            $.ajax({
                url: "ajax_files/ajax_customer_account.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    val:val
                },
                success: function(data) {
                    alert("Survey Details Changed.");
                  
                }
            });
 }
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
