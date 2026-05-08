<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Final-Financer";
$Page = "Final-Financer-Pending-Request-Product-Stock";
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
  $sql11 = "DELETE FROM tbl_request_product_stock WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_request_product_stock_items WHERE TransferId = '$id'";
  $conn->query($sql11);
 
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-request-product-stock.php";
    </script>
<?php } ?>

<div class="layout-content">
 
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pending Request Product Stocks
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Approve</th>
               <th>Invoice No</th>
               <th>Franchise</th> 
                <th>Request Date</th> 
                <th>Total Product</th>
                <th>Approve</th>
                <th>Pending</th>
                <th>Narration</th>
                <th>Bill</th>
               
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.ShopName FROM tbl_cust_stock_inv_2025 tp LEFT JOIN tbl_users_bill tu ON tu.id=tp.FrId ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT 
    COUNT(*) AS total_records,
    SUM(CASE WHEN checkstatus = 1 THEN 1 ELSE 0 END) AS checkstatus_1_count,
    SUM(CASE WHEN checkstatus = 0 THEN 1 ELSE 0 END) AS checkstatus_0_count
FROM tbl_cust_prod_stock_2025_temp
WHERE InvId = '".$row['id']."'";
             
                $row2 = getRecord($sql2);
              if($row2['total_records']>0){
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               
                 <td><?php if($row2['checkstatus_1_count']>0){ echo "<span style='color:green;'>Approved </span>";} else {?>
                 <span style='color:red;'>Pending</span><?php } ?></td>

                
                <td>#<?php echo $row['InvNo']; ?></td>
               <td><?php echo $row['ShopName']; ?></td>
             
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               
               <td><a href="approve-request-sell-product.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $row2['total_records']; ?></a></td> 
               <td><a href="approve-request-sell-product.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $row2['checkstatus_1_count']; ?></a></td> 
               <td><a href="approve-request-sell-product.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $row2['checkstatus_0_count']; ?></a></td> 
                <td><?php echo $row['Narration']; ?></td>
              <td><?php if($row["bill"] == '') {?>
                  <span style="color:red;">No Bill Found</span>
                 <?php } else if(file_exists('../uploads/'.$row["bill"])){?>
                 <a href="../uploads/<?php echo $row["bill"];?>" target="_blank">View Bill</a>
                  <?php }  else{?>
                <span style="color:red;">No Bill Found</span>
             <?php } ?></td>
             
          
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
