<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-2025";
$Page = "Raw-Product-Stock-Report-2025";
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
<h4 class="font-weight-bold py-3 mb-0">Raw Product Stock Report
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Raw Product Name</th>
                 <th>Credit</th>
              <th>Debit</th>
              <th>Balance</th>
              <th>Unit</th>
              
             
            
            </tr>
        </thead>
        <tbody>
             <?php 
            $i=1;
            
            $sql = "SELECT ts.FrId,ts.ProdId,ts.Unit,ts.Unit2,p.ProductName,tcc.Name As CatName,p.MinQty FROM tbl_cust_prod_stock_2025 ts 
                    INNER JOIN tbl_cust_products_2025 p ON ts.ProdId=p.id 
                  
                    INNER JOIN tbl_cust_category_2025 tcc ON p.CatId=tcc.id WHERE ts.Status='Cr' AND ts.FrId='$BillSoftFrId' AND ts.ProdType=1 AND ts.StockDate>'2025-10-07'";

                    if($_POST['FrId']){
                $FrId = $_POST['FrId'];
                if($FrId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.FrId='$FrId'";
                }
            }

            if($_POST['CatId']){
                $CatId = $_POST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND p.CatId='$CatId'";
                }
            }
            
            if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND ts.StockDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND ts.StockDate<='$ToDate'";
            }
            
            $sql.=" GROUP BY p.id ORDER BY ts.id DESC";    
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE FrId='".$row['FrId']."' AND ProdId='".$row['ProdId']."' AND ProdType=1";
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND StockDate>='$FromDate'";
                }
                if($_REQUEST['ToDate']){
                    $ToDate = $_REQUEST['ToDate'];
                    $sql2.= " AND StockDate<='$ToDate'";
                }
               
                $sql2.= " GROUP by Status) as a";
                $row2 = getRecord($sql2);
                //echo $sql2;
                $MinQty = $row['MinQty'];
                $BalQty = $row2['balqty'];
                if($BalQty <  $MinQty){
                    $bgcolor = "background-color: #ff9f9f;";
                }
                else{
                    $bgcolor = "";
                }
                
                if($row['Unit']!='Pieces'){
                    $creditqty = ($row2['creditqty']/1000);
                    $debitqty = ($row2['debitqty']/1000);
                    $balqty = ($row2['balqty']/1000);
                    
                }
                else{
                    $creditqty = $row2['creditqty'];
                    $debitqty = $row2['debitqty'];
                    $balqty = $row2['balqty'];
                }
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               
               <td><?php echo $row['ProductName']; ?></td>
              
                <td><?php echo $creditqty; ?></td>
                <td><?php echo $debitqty; ?></td>
                <td><?php echo $balqty; ?></td>
              <td><?php echo $row['Unit2']; ?></td>
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
