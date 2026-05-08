<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Franchise-Report";
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
       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    <div class="form-group col-md-4">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="FrId" id="FrId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["FrId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
            <?php } ?>
        </select>
    </div>

     

<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div> 
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1">
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
               <th>Franchise Name</th>
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
                    INNER JOIN tbl_cust_products p ON ts.ProdId=p.id WHERE ts.Status='Cr' AND ts.FrId!=0";
            if($_POST['FrId']){
                $FrId = $_POST['FrId'];
                if($FrId == 'all'){
                    $sql.= " ";
                } 
                else{
                $sql.= " AND ts.FrId='$FrId'";
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
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM 
                (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty 
                FROM `tbl_fr_raw_stock` WHERE ProdId='".$row['ProdId']."' GROUP by Status) as a";
                $row2 = getRecord($sql2);

                $sql3 = "SELECT ShopName FROM tbl_users_bill WHERE id='".$row['FrId']."'";
                $row3 = getRecord($sql3);
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $row3['ShopName'];?></td>
               <td><?php echo $row['ProductName']; ?></td>
              
                <td><?php echo $row2['creditqty']." ".$row['Unit']; ?></td>
                <td><?php echo $row2['debitqty']." ".$row['Unit']; ?></td>
                <td><?php echo $row2['balqty']." ".$row['Unit']; ?></td>
              
            </tr>
           <?php $i++;} ?>

          
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
