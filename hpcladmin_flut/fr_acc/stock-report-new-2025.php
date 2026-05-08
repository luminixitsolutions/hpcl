<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-2025";
$Page = "Product-Stock-Report-2-2025";
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
<h4 class="font-weight-bold py-3 mb-0">Product Stock Report
</h4>

<div class="card" style="padding: 10px;">
       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    

<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>

<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
   <?php if(isset($_POST['Search'])) {?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Product Name</th>
                <th>Category Name</th>
                 
                 <th></th>
                 <th >Opening Stock</th>
                 <th></th>
              
              <th></th>
              <th >Purchase</th>
                 <th></th>
             
              <th></th>
               <th>Sale</th>
                 <th></th>
              
              <th></th>
              <th >Closing Stock</th>
                 <th></th>
 
            </tr>
           
        </thead>
        <tbody>
            <tr>
               <th></th>
                <th></th>
                <th></th>
                 <th>Qty</th>
              <th>Rate</th>
              <th>Amount</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Amount</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Amount</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Amount</th>
             
 
            </tr>
            <?php 
            $i=1;
            $sql = "SELECT p.id AS ProdId,p.ProductName,tcc.Name As CatName,p.MinPrice,p.PurchasePrice FROM tbl_cust_products_2025 p 
                    INNER JOIN tbl_cust_category_2025 tcc ON p.CatId=tcc.id WHERE p.CreatedBY='$BillSoftFrId' AND p.ProdType=0 AND p.delete_flag=0 AND p.checkstatus=1";
            $row = getList($sql);
            foreach($row as $result){
                $sql3 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE FrId='$BillSoftFrId' AND ProdId='".$result['ProdId']."' AND ProdType=0 ";
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql3.= " AND StockDate<'$FromDate'";
                }
                $sql3.= " GROUP by Status) as a";
                //echo $sql3;
                $row3 = getRecord($sql3);
                
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE FrId='$BillSoftFrId' AND ProdId='".$result['ProdId']."' AND ProdType=0 ";
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
                
                $sql4 = "SELECT SUM(Qty) AS PurchaseQty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='".$result['ProdId']."' AND Status='Cr' AND FrId='$BillSoftFrId' AND ProdType=0";
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql4.= " AND StockDate>='$FromDate'";
                }
                if($_REQUEST['ToDate']){
                    $ToDate = $_REQUEST['ToDate'];
                    $sql4.= " AND StockDate<='$ToDate'";
                }
                //echo $sql4;
                $row4 = getRecord($sql4);
                
                $sql5 = "SELECT SUM(Qty) AS PurchaseQty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='".$result['ProdId']."' AND Status='Dr' AND FrId='$BillSoftFrId' AND ProdType=0";
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql5.= " AND StockDate>='$FromDate'";
                }
                if($_REQUEST['ToDate']){
                    $ToDate = $_REQUEST['ToDate'];
                    $sql5.= " AND StockDate<='$ToDate'";
                }
                //echo $sql5;
                $row5 = getRecord($sql5);
               
            ?>
            <tr>
                <td><?php echo $result['ProdId']; ?></td>
                <td><?php echo $result['ProductName']; ?></td>
                <td><?php echo $result['CatName']; ?></td>
                
                <td><?php echo $row3['balqty']; ?></td>
                <td><?php echo $result['MinPrice']; ?></td>
                <td><?php echo $row3['balqty']*$result['MinPrice']; ?></td>
                
                <td><?php echo $row4['PurchaseQty']; ?></td>
                <td><?php echo $result['PurchasePrice']; ?></td>
                <td><?php echo $row4['PurchaseQty']*$result['PurchasePrice']; ?></td>
                
                <td><?php echo $row5['PurchaseQty']; ?></td>
                <td><?php echo $result['MinPrice']; ?></td>
                <td><?php echo $row5['PurchaseQty']*$result['MinPrice']; ?></td>
                
                <td><?php echo $row3['balqty']+$row4['PurchaseQty']-$row5['PurchaseQty']; ?></td>
                <td><?php echo $result['PurchasePrice']; ?></td>
                <td><?php echo $result['PurchasePrice']*($row3['balqty']+$row4['PurchaseQty']-$row5['PurchaseQty']); ?></td>
            </tr>
          <?php $i++;} 
          ?>

          
        </tbody>
    </table>
</div>
<?php } ?>
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
