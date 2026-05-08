<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Request-Product-Stock";
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




<div class="layout-content">
 
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View MRP Request Product Stocks
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Purchase Dept Status</th>
             <th>Product Excel</th>
            <th>Invoice No</th>
            <th>Franchise</th> 
            <th>Request Date</th> 
            <th>Total Products</th>
            <th>Approved Prod</th>
            <th>Rejected Prod</th>
            <th>Pending Prod</th>
            <th>Narration</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $sql = "SELECT tp.*, tu.ShopName, tu5.Fname AS PurchaseName 
                FROM tbl_fr_req_stock_inv tp 
                LEFT JOIN tbl_users_bill tu ON tu.id = tp.FrId 
                LEFT JOIN tbl_users_bill tu5 ON tu5.id = tp.PurchaseBy 
                WHERE tp.ProdType=0
                ORDER BY tp.CreatedDate DESC";
        $res = $conn->query($sql);

        while ($row = $res->fetch_assoc()) {
            $PurchaseName = $row['PurchaseName'];
            $purchasedate = $row['PurchaseApproveDate'] ? date("d/m/Y", strtotime($row['PurchaseApproveDate'])) : '-';
            $invId = $row['id'];

            // Fetch product statuses
            $sqlItems = "SELECT PurchaseStatus FROM tbl_fr_req_prod_stock WHERE InvId = '$invId'";
            $items = getList($sqlItems);

            $approveCount = $rejectCount = $pendingCount = 0;
            foreach ($items as $item) {
                if ($item['PurchaseStatus'] == '1') $approveCount++;
                elseif ($item['PurchaseStatus'] == '2') $rejectCount++;
                else $pendingCount++;
            }

            $totalProducts = count($items);

            // Determine status label and color
            $statusLabel = 'Pending';
            $statusColor = 'orange';
            if ($approveCount == $totalProducts && $totalProducts > 0) {
                $statusLabel = 'Approved';
                $statusColor = 'green';
            } elseif ($rejectCount == $totalProducts && $totalProducts > 0) {
                $statusLabel = 'Rejected';
                $statusColor = 'red';
            } elseif ($approveCount > 0 || $rejectCount > 0) {
                $statusLabel = 'Partially Approved';
                $statusColor = '#007bff';
            }

            // Status display text
            $statusText = ($statusLabel == 'Pending') 
                ? 'Pending By Purchase Dept'
                : "$statusLabel<br>By $PurchaseName | $purchasedate";
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td>
                <a href="approve-fr-prod-stock-req-by-purchase.php?id=<?php echo $row['id']; ?>" style="text-decoration: none;">
                    <span style="color: <?php echo $statusColor; ?>; font-weight: 500;"><?php echo $statusText; ?></span>
                </a>
            </td>
           
            <td><a href="view-product-stock-req-excel.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-finish" target="_new">Excel</button></td>
           
            <td><?php echo $row['InvNo']; ?></td>
            <td><?php echo $row['ShopName']; ?></td>
            <td><?php echo date("d/m/Y", strtotime($row['StockDate'])); ?></td>
            <td><span style="color: blue; font-weight: bold;"><?php echo $totalProducts; ?></span></td>
            <td><span style="color: green; font-weight: bold;"><?php echo $approveCount; ?></span></td>
            <td><span style="color: red; font-weight: bold;"><?php echo $rejectCount; ?></span></td>
            <td><span style="color: orange; font-weight: bold;"><?php echo $pendingCount; ?></span></td>
            <td><?php echo $row['Narration']; ?></td>
        </tr>
        <?php $i++; } ?>
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
