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
<h4 class="font-weight-bold py-3 mb-0">Assets Inventory Stock Report
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
            
            /*$sql = "SELECT ts.ProdId,p.ProductName,tcc.Name As CatName,p.MinQty,p.PurchasePrice FROM tbl_cust_prod_stock_2025 ts 
                    INNER JOIN tbl_cust_products_2025 p ON ts.ProdId=p.id 
                    INNER JOIN tbl_cust_category_2025 tcc ON p.CatId=tcc.id WHERE ts.FrId='$BillSoftFrId' AND ts.Status='Cr' AND ts.ProdType=0 AND ts.StockDate>='2025-01-28' AND ts.StockDate<='".date('Y-m-d')."'";*/
            
            $sql = "SELECT p.BarcodeNo,p.CreatedBy AS FrId, p.id AS ProdId, p.ProductName, tcc.Name AS CatName, COALESCE(p.MinQty, 0) AS MinQty ,p.PurchasePrice,p.MinPrice 
                                FROM tbl_cust_products_2025 p 
                                
                                INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                                WHERE p.CreatedBy = '$BillSoftFrId' AND p.ProdType = 0 AND p.ProdType2 IN (1,3) AND p.CatId=28 AND p.delete_flag=0 AND p.checkstatus=1 
                                ";
            if($_POST['CatId']){
                $CatId = $_POST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND p.CatId='$CatId'";
                }
            }
            
            /*if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND ts.StockDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND ts.StockDate<='$ToDate'";
            }*/
            
            $sql.=" GROUP BY p.id ORDER BY p.ProductName ASC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT COALESCE(sum(creditqty), 0) AS creditqty,COALESCE(sum(debitqty), 0) AS debitqty,COALESCE(sum(creditqty)-sum(debitqty), 0) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='".$row['ProdId']."' AND ProdType=0 AND FrId='$BillSoftFrId'";
                $sql2.= " AND StockDate>='2025-01-28'";
                $sql2.= " AND StockDate<='".date('Y-m-d')."'";
                /*if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND StockDate>='$FromDate'";
                }
                if($_REQUEST['ToDate']){
                    $ToDate = $_REQUEST['ToDate'];
                    $sql2.= " AND StockDate<='$ToDate'";
                }*/
               
                $sql2.= " GROUP by Status) as a";
                 
                $row2 = getRecord($sql2);
                
                $MinQty = $row['MinQty'];
                $BalQty = $row2['balqty'];
                if($BalQty <  $MinQty){
                    $bgcolor = "background-color: #ff9f9f;";
                }
                else{
                    $bgcolor = "";
                }
                
                $sql21 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,COALESCE(sum(creditqty)-sum(debitqty), 0) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='".$row['ProdId']."' AND ProdType=0 AND FrId='$BillSoftFrId' AND StockDate<'2025-01-28'";
               
                $sql21.= " GROUP by Status) as a";
                $row21 = getRecord($sql21);
             ?>
             
            <tr style="<?php echo $bgcolor;?>">
               <td><?php echo $i; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
              <td><?php echo $row['CatName']; ?></td>
               <td><?php echo $row['PurchasePrice']; ?></td>
              <td><?php echo $row['MinQty']; ?></td>
              <td><?php echo $row21['balqty']; ?></td>
                <td><?php echo $row2['creditqty']; ?></td>
                <td><?php echo $row2['debitqty']; ?></td>
                <td><?php echo $row21['balqty']+$row2['balqty']; ?></td>
                <td><?php echo ($row21['balqty']+$row2['balqty'])*$row['PurchasePrice']; ?></td>
              
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
