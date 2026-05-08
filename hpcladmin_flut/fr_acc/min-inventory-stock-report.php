<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-2025";
$Page = "Product-Stock-Report-2025";
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
<h4 class="font-weight-bold py-3 mb-0">Min MRP Inventory Stock Report
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Product Name</th>
                <th>Category Name</th>
                <th>Purchase Price</th>
                <th>Min Qty</th>
                <th>Carry Forword</th>
                 <th>Credit</th>
                <th>Debit</th>
                <th>Balance</th>
              <th>Amount</th>
              
             
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            
            $sql = "SELECT * FROM ( SELECT COALESCE(p.MinQty, 0) AS MinQty, 
                COALESCE(SUM(CASE WHEN s.Status = 'Cr' THEN s.Qty ELSE 0 END), 0) AS creditqty,
                COALESCE(SUM(CASE WHEN s.Status = 'Dr' THEN s.Qty ELSE 0 END), 0) AS debitqty,
                COALESCE(SUM(CASE WHEN s.Status = 'Cr' THEN s.Qty ELSE 0 END) - SUM(CASE WHEN s.Status = 'Dr' THEN s.Qty ELSE 0 END), 0) AS balqty,p.PurchasePrice,
                tcc.Name AS CatName,p.ProductName 
                FROM tbl_cust_products_2025 p INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                LEFT JOIN tbl_cust_prod_stock_2025 s ON p.id = s.ProdId AND s.ProdType = 0 AND s.FrId = '$BillSoftFrId' 
                AND s.StockDate BETWEEN '2025-01-28' AND '".date('Y-m-d')."' WHERE p.CreatedBy = '$BillSoftFrId' AND p.ProdType = 0 AND p.ProdType2 IN (1,3) 
                AND p.CatId != 28 AND p.delete_flag = 0 AND p.checkstatus = 1 GROUP BY p.id ORDER BY p.ProductName ASC) as a WHERE balqty < MinQty";
                                                                        
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql21 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,COALESCE(sum(creditqty)-sum(debitqty), 0) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='".$row['ProdId']."' AND ProdType=0 AND FrId='$BillSoftFrId' AND StockDate<'2025-01-28' GROUP by Status) as a";
                $row21 = getRecord($sql21);
             ?>
             
            <tr style="">
               <td><?php echo $i; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
              <td><?php echo $row['CatName']; ?></td>
               <td><?php echo $row['PurchasePrice']; ?></td>
              <td><?php echo $row['MinQty']; ?></td>
              <td><?php echo $row21['balqty']; ?></td>
                <td><?php echo $row['creditqty']; ?></td>
                <td><?php echo $row['debitqty']; ?></td>
                <td><?php echo $row21['balqty']+$row['balqty']; ?></td>
                <td><?php echo ($row21['balqty']+$row['balqty'])*$row['PurchasePrice']; ?></td>
              
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
        order: [[7, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
