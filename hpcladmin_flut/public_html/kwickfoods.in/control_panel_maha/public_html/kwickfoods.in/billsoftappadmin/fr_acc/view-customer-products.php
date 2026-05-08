<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products";
$Page = "View-Customer-Products";

function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 


$sql = "SELECT * FROM tbl_cust_products WHERE code=''";
$row = getList($sql);
foreach($row as $result){
    $n = 10;
    $Code2 = RandomStringGenerator($n); 
    $Code = $Code2."".$result['id'];
    $sql2 = "UPDATE tbl_cust_products SET code='$Code' WHERE id='".$result['id']."'";
    $conn->query($sql2);
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
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

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
    $id = $_REQUEST["id"];
    $modified_time = gmdate('Y-m-d H:i:s.').gettimeofday()['usec'];
    // $sql11 = "DELETE FROM tbl_cust_products WHERE id = '$id'";
    // $conn->query($sql11);
    // $sql3 = "DELETE FROM tbl_cust_product_images WHERE ProductId='$id'";
    // $conn->query($sql3); 
    $sql11 = "UPDATE tbl_cust_products SET delete_flag=1,modified_time='$modified_time',push_flag=1 WHERE id = '$id'";
    $conn->query($sql11);
    
    $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='customer product record deleted',invid='$id',createddate='$createddate',roll='customer-product'";
  $conn->query($sql);
  
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-customer-products.php";
    </script>
<?php } 

if($_REQUEST["action"]=="copy")
{
  $id = $_REQUEST["id"];
    $query2 = "SELECT * FROM tbl_cust_products WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();

    $ProductName = addslashes(trim($row2['ProductName']));
    $CatId = $row2['CatId'];
    $MinPrice = $row2['MinPrice'];
    $CreatedDate = date('Y-m-d');
    $Status = $row2['Status'];
    $SrNo = $row2['SrNo'];
    $CgstPer = $row2['CgstPer'];
    $SgstPer = $row2['SgstPer'];
    $IgstPer = $row2['IgstPer'];
    $GstAmt = $row2['GstAmt'];
    $ProdPrice = $row2['ProdPrice'];

    function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;
$Code = RandomStringGenerator($n); 


 $sql = "INSERT INTO tbl_cust_products SET ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',Photo='$Photo',CreatedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice'";
    $conn->query($sql);
    $ProdId = mysqli_insert_id($conn); 
$Code2 = $Code."".$ProdId; 
$sql2 = "UPDATE tbl_cust_products SET code='$Code2' WHERE id='$ProdId'";
$conn->query($sql2);
    
    ?>
    <script type="text/javascript">
      alert("Product Copied Successfully!");
      window.location.href="edit-customer-product.php?id=<?php echo $ProdId;?>";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Product List
<?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-customer-product.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
<?php } ?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
       <thead class="thead-light">
            <tr>
              <th>Photo</th>
            <!--   <th>Barcode No</th>-->
             <?php  if(in_array("11", $Options)){?>
            <th><button type="button" name="delete_all" id="delete_all" class="btn btn-danger btn-xs">Delete</button></th>
            <?php } ?>
                <th>Product name</th>
               
                <th>Category</th>
             
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
           
                   <th>Price</th>
           
                <th>Status</th>
                 <th>QR Display</th>
                <th>Register Date</th>
               <!--  <th>Push Flag</th>
             <th>Delete Flag</th>
             <th>Modified Time</th>-->
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT p.*,c.Name As Category FROM tbl_cust_products p 
                    LEFT JOIN tbl_cust_category c ON c.id=p.CatId WHERE p.CreatedBy='$BillSoftFrId' AND ProdType=0 AND p.delete_flag=0";
            $sql.= " ORDER BY p.id DESC";
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

<!--  <td><?php echo $row['BarcodeNo']; ?></td>-->
   <?php  if(in_array("11", $Options)){?>
 <td class="align-middle">
                                    <input type="checkbox" class="delete_checkbox" value="<?php echo $row["id"];?>" />
                                </td>
                                <?php } ?>
                <td class="align-middle"><?php echo $row['ProductName']; ?></td>
              
               
                <td class="align-middle"><?php echo $row['Category']; ?></td>
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                     <td class="table-action align-middle">
                      <?php if(in_array("10", $Options)){?>
              <a href="edit-customer-product.php?id=<?php echo $row['id']; ?>" class="btn btn-icon btn-outline-primary"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
               <?php } if(in_array("11", $Options)){?>
             <a onClick="return confirm('Are you sure you want delete this Product?\nNote : Delete all orders related this Product (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" class="btn btn-icon btn-outline-primary"><i class="lnr lnr-trash text-danger"></i></a>&nbsp;&nbsp;
            <?php } ?>
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=copy" onClick="return confirm('Are you sure you want copy of this Product?');" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy"  class="btn btn-icon btn-outline-primary"><i class="pe-7s-copy-file mr-2"></i></a>
            </td><?php } ?>
         
            
                <td class="align-middle">&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
           
                    
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

});  
</script>
</body>
</html>
