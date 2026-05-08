<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "View-Godown-Stocks";
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



<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_godown_raw_prod_stock_2025 WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-godown-raw-prod-stock.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Godwon Stock List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-godown-raw-prod-stock.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Stock</a></span>
<?php } ?>
</h4>

<div class="card" style="padding: 10px;">
      <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       
<div class="form-group col-md-3">
<label class="form-label"> Godown <span class="text-danger">*</span></label>
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
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label">Godown Products</label>
 <select class="select2-demo form-control" name="ProdId" id="ProdId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_godown_products WHERE Status=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["ProdId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label">Category</label>
 <select class="select2-demo form-control" name="CatId" id="CatId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_godown_prod_category WHERE Status='1' AND CreatedBy='$BillSoftFrId'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label">Sub Category</label>
 <select class="select2-demo form-control" name="SubCatId" id="SubCatId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_godown_prod_sub_category WHERE Status='1' AND CatId='".$_REQUEST['CatId']."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["SubCatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
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
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               
               <th>Godown</th>
               <th>Vendor</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Stock In Qty</th>
              <th>Price</th>
              <th>Total Price</th>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,p.ProductName,tub.Fname,tu.Fname AS VedName FROM tbl_godown_raw_prod_stock_2025 ts 
                    INNER JOIN tbl_cust_products2 p ON ts.ProdId=p.id 
                    INNER JOIN tbl_users_bill tub ON ts.GodownId=tub.id 
                    LEFT JOIN tbl_users tu ON ts.VedId=tu.id WHERE ts.UserId='0' AND ts.Status='Cr'";
            if($_POST['ProdId']){
                $ProdId = $_POST['ProdId'];
                if($ProdId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.ProdId='$ProdId'";
                }
            }
            if($_POST['GodownId']){
                $GodownId = $_POST['GodownId'];
                if($GodownId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.GodownId='$GodownId'";
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
            
            if($_POST['SubCatId']){
                $SubCatId = $_POST['SubCatId'];
                if($SubCatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND p.SubCatId='$SubCatId'";
                }
            }
            
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.StockDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.StockDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i; ?></td>
              
               <td><?php echo $row['Fname']; ?></td>
                <td><?php echo $row['VedName'];?></td>
               <td><?php echo $row['ProductName']; ?></td>
              
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
              <td><?php echo $row['Qty']." ".$row['Unit']; ?></td>
              <td>&#8377;<?php echo $row['Price']; ?></td>
              <td>&#8377;<?php echo $row['TotalPrice']; ?></td>
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
             <td>
                 <?php if(in_array("10", $Options)){?>
                <a href="add-godown-raw-prod-stock-old.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
                <?php } if(in_array("11", $Options)){?>
                <a onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>
            <?php } ?>
            </td><?php } ?>
       
              
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
    
    $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            var action = "getGodownSubCat";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#SubCatId').html(data);
                  
                }
            });

        });
});
</script>
</body>
</html>
