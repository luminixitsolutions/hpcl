<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "View-Godown-Products";
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
  $sql11 = "DELETE FROM tbl_godown_products WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-godown-products.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Godown Product List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-godown-product.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
    
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  <div class="form-group col-md-4">
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

  <div class="form-group col-md-4">
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
               <th>Sr No</th>
                <th>Product Name</th> 
              
               
               <th>Price</th>
               <!--  <th>Stock</th> -->
               <th>Qty</th>
               <th>Category</th>
               <th>Sub Category</th>
                <th>Status</th>
                <th>Register Date</th>
               
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tc.Name As CatName,tsc.Name As SubCatName FROM tbl_godown_products tp 
                    LEFT JOIN tbl_godown_prod_category tc ON tc.id=tp.CatId
                    LEFT JOIN tbl_godown_prod_sub_category tsc ON tsc.id=tp.SubCatId WHERE 1";
            if($_POST['CatId']){
                $CatId = $_POST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.CatId='$CatId'";
                }
            }
            
            if($_POST['SubCatId']){
                $SubCatId = $_POST['SubCatId'];
                if($SubCatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tp.SubCatId='$SubCatId'";
                }
            }
                     $sql.= " ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['ProductName']; ?></td> 
               
             
              
                 <td>&#8377;<?php echo $row['Price']; ?></td>
                   <td><?php echo $row['Qty']." ".$row['Unit']; ?></td>
                   <td><?php echo $row['CatName']; ?></td> 
                   <td><?php echo $row['SubCatName']; ?></td> 
         <!--<td><?php if($row['ProdType']=='0'){echo "<span style='color:green;'>Billing/Customer Product</span>";} else { echo "<span style='color:red;'>Raw Product</span>";} ?></td>-->
                    
                 <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="add-godown-product.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" ><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){
               
                ?>
              <!-- &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this product?\nNote : Delete all record related this product (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a> -->
             <?php } ?>
            </td> <?php } ?>
        
              
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
