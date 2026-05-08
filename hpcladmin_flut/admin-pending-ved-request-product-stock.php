<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Admin-Req-Prod-Stock";
$Page = "Admin-Pending-Request-Product-Stock";
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
<h4 class="font-weight-bold py-3 mb-0">Admin Pending Request Product Stocks
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Admin Approve</th>
               <th>Accountant Approve</th>
               <th>GRN Type</th>
               <th>Invoice No</th>
               <th>Franchise</th> 
                <th>Vendor Name</th> 
                <th>Date</th> 
                <th>Total Product</th>
                <th>Narration</th>
                <th>Receipt/Bill</th>
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS GodownName,tu2.Fname,tu4.Fname AS AdminName FROM tbl_cust_stock_ved_inv tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FrId 
            LEFT JOIN tbl_users tu2 ON tu2.id=tp.VedId 
            LEFT JOIN tbl_users_bill tu4 ON tu4.id=tp.AdminBy 
            WHERE tp.AdminStatus=0
            ORDER BY tp.CreatedDate DESC";
            
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT * FROM tbl_cust_ved_prod_stock WHERE InvId='".$row['id']."'";
               $rncnt2 = getRow($sql2);

               $MgrName = $row['FinancerName'];
               $AdminName = $row['AdminName'];
               
               if($row['ProdType'] == 1){
                   $GrnType = "<span style='color:red;'>Raw Product GRN</span>";
               }
               else{
                   $GrnType = "<span style='color:green;'>Selling Product GRN</span>";
               }
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php if($row['AdminStatus']=='1'){ echo "<span style='color:green;'>Approved<br>By $AdminName </span>";} else if($row['AdminStatus']=='2'){ echo "<span style='color:red;'>Rejected <br> By $AdminName</span>";} else {?>
                 <a href="approve-ved-product-stock-request-by-admin.php?id=<?php echo $row['id']; ?>"><span style='color:orange;'>Pending By Admin</span><?php } ?></a></td>
                <td><?php if($row['AccoutantStatus']=='1'){ echo "<span style='color:green;'>Approved<br>By $MgrName </span>";} else if($row['AccoutantStatus']=='2'){ echo "<span style='color:red;'>Rejected <br> By $MgrName</span>";} else {?>
                 <span style='color:orange;'>Pending By Accoutant</span><?php } ?></td>
                 
                 <td><?php echo $GrnType;?></td>

                <td><?php echo $row['InvNo']; ?></td>
               <td><?php echo $row['GodownName']; ?></td>
             <td><?php echo $row['Fname']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
                 <?php if($row['ProdType'] == 1){?>
                 <td><a href="view-raw-grn-request-product-print.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $rncnt2; ?></a></td> 
                 <?php } else { ?>
               <td><a href="view-ved-request-product-print.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $rncnt2; ?></a></td> 
               <?php } ?>
             
                <td><?php echo $row['Narration']; ?></td>
              <td><?php if($row["bill"] == '') {?>
                  <span style="color:red;">No Receipt Found</span>
                 <?php } else if(file_exists('../uploads/'.$row["bill"])){?>
                 <a href="../uploads/<?php echo $row["bill"];?>" target="_blank">View Receipt</a>
                  <?php }  else{?>
                <span style="color:red;">No Receipt Found</span>
             <?php } ?></td>
             
          
           
             
        
        
              
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
