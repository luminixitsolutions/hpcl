<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products-2025";
$Page = "View-Other-Products-2025";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Product List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Other Product List
</h4>

<div class="card">
    
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
  $sql12 = "SELECT * FROM tbl_cust_category_2025 WHERE Status='1' AND ProdType='0'";
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
  $sql12 = "SELECT * FROM tbl_cust_sub_category_2025 WHERE Status='1' AND CatId='".$_REQUEST['CatId']."' AND ProdType=0";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["SubCatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!--<div class="form-group col-lg-2">
<label class="form-label">Product Type<span class="text-danger">*</span></label>
<select class="form-control" name="ProdType2" required="">
    <option selected="" value="all">All</option>
<option value="1" <?php if($_REQUEST["ProdType2"]=='1') {?> selected <?php } ?>>MRP Product</option>
<option value="2" <?php if($_REQUEST["ProdType2"]=='2') {?> selected <?php } ?>>Making Product</option>
<option value="3" <?php if($_REQUEST["ProdType2"]=='3') {?> selected <?php } ?>>Other Product</option>
</select>
</div>-->


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
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
       <thead class="thead-light">
            <tr>
              <th>Photo</th>
                <th>Product name</th>
               <th>Barcode No</th>
                <th>Category</th>
                <th>Sub Category</th>
             
           <th>Product Type</th>
            <th>Purchase Price</th>
                   <th>Price</th>
           
                <th>Status</th>
                 <th>QR Display</th>
                <th>Register Date</th>
              
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT p.*,c.Name As Category,cs.Name As SubCatName FROM tbl_cust_products_2025 p 
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.CreatedBy='$BillSoftFrId' AND p.ProdType=0 AND p.ProdType2=3 AND p.delete_flag=0 AND p.checkstatus=1";
                    
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
            
            if($_POST['ProdType2']){
                $ProdType2 = $_POST['ProdType2'];
                if($ProdType2 == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND p.ProdType2='$ProdType2'";
                }
            }
            
            $sql.= " ORDER BY p.ProductName ASC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
             ?>
            <tr>
               <td> <?php if($row["Photo"] == '') {?>
                  <img src="../no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="img-fluid ui-w-40" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40" style="width: 40px;height: 40px;"> 
             <?php } ?></td>


  
                <td class="align-middle"><?php echo $row['ProductName']; ?></td>
              
               <td class="align-middle"><?php echo $row['BarcodeNo']; ?></td>
                <td class="align-middle"><?php echo $row['Category']; ?></td>
                  <td class="align-middle"><?php echo $row['SubCatName']; ?></td>
               
         
              <td class="align-middle"><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:green;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other Product</span>";} ?></td>
               <td class="align-middle"><?php echo number_format($row["PurchasePrice"],2); ?></td>
                <td class="align-middle"><?php echo number_format($row["MinPrice"],2); ?></td>
           
                    
                 <td class="align-middle"><?php if($row['Status']=='1'){ echo "<span style='color:green;'>Publish</span>";} else { echo "<span style='color:red;'>Not Publish</span>";} ?></td>
                 <td><select class="form-control" id="QrDisplay<?php echo $row['id']; ?>" onchange="changeQrStatus(<?php echo $row['id']; ?>,this.value)">
                     <option value="Yes" <?php if($row['QrDisplay']=='Yes'){?> selected <?php } ?>>Yes</option>
                     <option value="No" <?php if($row['QrDisplay']=='No'){?> selected <?php } ?>>No</option>
                 </select></td>
            <td class="align-middle"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
        <!-- <td><?php echo $row['push_flag']; ?></td>
            <td><?php echo $row['delete_flag']; ?></td>
            <td><?php echo $row['modified_time']; ?></td>-->
              
            </tr>
           <?php } ?>
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


<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


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

function changeQrStatus(id,status){
    var action = "changeQrStatus";
       $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,id:id,status:status},
  success: function(data){
      alert(data);
      }
});
}
</script>

<script>  
$(document).ready(function(){ 

    $('.delete_checkbox').click(function(){
        if($(this).is(':checked'))
        {
            $(this).closest('tr').addClass('removeRow');
        }
        else
        {
            $(this).closest('tr').removeClass('removeRow');
        }
    });

    $('#delete_all').click(function(){
        var checkbox = $('.delete_checkbox:checked');
        if(checkbox.length > 0)
        {
            if (confirm("Are you sure you want to delete Products?")) {
            var checkbox_value = [];
            $(checkbox).each(function(){
                checkbox_value.push($(this).val());
            });
       // alert(checkbox_value);exit();
            $.ajax({
                url:"ajax_files/all-cust-product-delete.php",
                method:"POST",
                data:{checkbox_value:checkbox_value},
                success:function()
                {
                    $('.removeRow').fadeOut(1500);
                }
            });
            }
        }
        else
        {
            alert("Select atleast one records");
        }
    });
    
    $(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getSubCat";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#SubCatId').html(data);
    }
    });

    

 });

});  
</script>
</body>
</html>
