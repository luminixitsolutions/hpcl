<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Godown-Product-Stock-Report";
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
<h4 class="font-weight-bold py-3 mb-0">Godown Product Stock Report
  
</h4>

<div class="card" style="padding: 10px;">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-3">
    <label class="form-label">Godown <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" name="GodownId" id="GodownId" required>

<option selected="" value="all">All</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=93";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["GodownId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
    </div>


<div class="form-group col-md-3">
    <label class="form-label">Godown Product <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" name="GodownProdId" id="GodownProdId" required>

<option selected="" value="all">All</option>
<?php 
  $sql12 = "SELECT * FROM  tbl_cust_products2 WHERE ProdType=3 AND Status='1' ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["GodownProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
    </div>
  
<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off" required>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
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
               <th>Sr No</th>
               <th>Go-Down</th> 
               <th>Product Name</th> 
               <th>Credit</th> 
                <th>Debit</th> 
                <th>Balance</th>
           
              
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_users_bill WHERE Roll IN(93) AND BillSoftFrId=0 ";
            if($_POST['GodownId']){
                $GodownId = $_POST['GodownId'];
                if($GodownId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND id='$GodownId'";
                }
            }
           
            
            $sql.=" ORDER BY Fname ASC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql33 = "SELECT id,ProductName FROM tbl_cust_products2 WHERE ProdType=3";
                if($_POST['GodownProdId']){
                $ProdId = $_POST['GodownProdId'];
                if($ProdId == 'all'){
                    $sql33.= " ";
                }
                else{
                $sql33.= " AND id='$ProdId'";
                }
                }
                //echo $sql33;
                $row33 = getList($sql33);
                foreach($row33 as $result33){
                $ProdId = $result33['id'];
                $sql2 = "SELECT SUM(Qty) AS CrQty FROM tbl_godown_raw_prod_stock WHERE GodownId='".$row['id']."' AND Status='Cr' AND ProdId='$ProdId'";
                
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND StockDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND StockDate<='$ToDate'";
                }
                $row2 = getRecord($sql2);
                $CrQty = $row2['CrQty'];
                
                $sql22 = "SELECT SUM(Qty) AS DrQty FROM tbl_godown_raw_prod_stock WHERE GodownId='".$row['id']."' AND Status='Dr' AND ProdId='$ProdId'";
                
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql22.= " AND StockDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql22.= " AND StockDate<='$ToDate'";
                }
                $row22 = getRecord($sql22);
                $DrQty = $row22['DrQty'];
                $BalQty = $CrQty-$DrQty;
                
            
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['Fname']; ?></td>
                <td><?php echo $result33['ProductName']; ?></td>
               <td><?php echo $CrQty; ?></td>
                 <td><?php echo $DrQty; ?></td>
               <td><?php echo $CrQty-$DrQty; ?></td> 
             
              
            </tr>
           <?php $i++;} }  ?>
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
    });
});
</script>
</body>
</html>
