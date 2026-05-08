<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Raw-Product-Stock-Report";
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
              
              
             
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,p.ProductName FROM tbl_fr_raw_stock ts 
                    INNER JOIN tbl_cust_products p ON ts.ProdId=p.id WHERE ts.FrId='$BillSoftFrId' AND ts.Status='Cr'";
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM 
                (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty 
                FROM `tbl_fr_raw_stock` WHERE ProdId='".$row['ProdId']."' GROUP by Status) as a";
                $row2 = getRecord($sql2);
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
              
                <td><?php echo $row2['creditqty']." ".$row['Unit']; ?></td>
                <td><?php echo $row2['debitqty']." ".$row['Unit']; ?></td>
                <td><?php echo $row2['balqty']." ".$row['Unit']; ?></td>
              
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
