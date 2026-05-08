<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Approve-Request-Product-Stock";
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
  $sql11 = "DELETE FROM tbl_request_product_stock_2025 WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_request_product_stock_items_2025 WHERE TransferId = '$id'";
  $conn->query($sql11);
 
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-request-product-stock.php";
    </script>
<?php } ?>

<div class="layout-content">
 
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Approve Request Product Stocks
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Invoice No</th>
               <th>Franchise</th> 
            <!--<th>Vendor Name</th> -->
                <th>Request Date</th> 
                <th>Approve Date</th> 
                <th>Total Product</th>
               <th>GST Amt</th>
               <th>Total Amt</th>
                <th>Narration</th>
             
                <th>Created Date</th>
              <th>Status</th>
             
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS GodownName,tu2.Fname FROM tbl_request_product_stock_2025 tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FromFrId 
            LEFT JOIN tbl_users tu2 ON tu2.id=tp.VedId 
            WHERE tp.UpdateStatus=1
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT * FROM tbl_request_product_stock_items_2025 WHERE TransferId='".$row['id']."'";
               $rncnt2 = getRow($sql2);
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
                <td>#<?php echo $row['id']; ?></td>
               <td><?php echo $row['GodownName']; ?></td>
            <!-- <td><?php echo $row['Fname']; ?></td>-->
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
                  <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['UpdatedDate']))); ?></td>
               <td><a href="approve-request-product-print.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $rncnt2; ?></a></td> 
              <td><?php echo $row['GstAmount']; ?></td>
              <td><?php echo $row['TotalAmount']; ?></td>
                <td><?php echo $row['Narration']; ?></td>
             
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
              <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
        
        
              
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
    });
});

function approve(id,status){
    var action = "approveRequest";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    status:status
                },
                success: function(data) {
                    //alert(data);
                    alert('Status Updated Successfully!');
                    if(data == 1){
                    $('#Check_Id'+id).prop('checked',true);
                    }
                    else{
                       $('#Check_Id'+id).prop('checked',false); 
                    }
                  
                }
            });
}
 function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            approve(id,1);
        }
        else{
           $('#CheckId'+id).val(0);
           approve(id,0);
            }
        }
</script>
</body>
</html>
