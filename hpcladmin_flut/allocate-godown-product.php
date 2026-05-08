<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Allocate-Products";

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
    
if($_GET['action'] == 'yes'){
$sql = "SELECT * FROM tbl_cust_products2 WHERE ProdType=0 AND ProdType2=3 AND Status=1";
$row = getList($sql);
foreach($row as $result){
$Prod_Id = $result['id'];
$FrId = $_GET['frid'];
$ProdType2 = $result['ProdType2'];
$Status = $result['Status'];
    $sql = "SELECT * FROM tbl_cust_products_2025 WHERE ProdId='$Prod_Id' AND CreatedBy='$FrId'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        $row = getRecord($sql);
        $status2 = $row['Status'];
        if($status2 == 1){}
        else{
        $sql2 = "UPDATE tbl_cust_products_2025 SET Status='$Status' WHERE ProdId='$Prod_Id' AND CreatedBy='$FrId'";
        $conn->query($sql2);
        }
    }
    else{
      $sql = "INSERT INTO tbl_cust_products_2025 (ProdId, ProductName, BrandId, CatId, SubCatId, CgstPer, SgstPer, IgstPer, CgstAmt, SgstAmt, IgstAmt, GstAmt, ProdPrice, MinPrice, Status, SrNo, Photo, code, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, BarcodeNo, StockQty, TempPrdId, Display, push_flag, delete_flag, modified_time, ProdType, Qty, Unit, Transfer, Assets, QrDisplay, MinQty, ProdType2, PurchasePrice, checkstatus, tempstatus) SELECT '$Prod_Id', ProductName, BrandId, CatId, SubCatId, CgstPer, SgstPer, IgstPer, CgstAmt, SgstAmt, IgstAmt, GstAmt, ProdPrice, MinPrice, Status, SrNo, Photo, code, CreatedDate, ModifiedDate, '$FrId', ModifiedBy, BarcodeNo, StockQty, TempPrdId, Display, push_flag, delete_flag, modified_time, ProdType, Qty, Unit, Transfer, Assets, QrDisplay, MinQty, ProdType2, PurchasePrice, checkstatus, tempstatus FROM tbl_cust_products2 WHERE id='$Prod_Id'";
        $conn->query($sql);
        $ProdId = mysqli_insert_id($conn); 
        $n = 10;
         $Code = RandomStringGenerator($n); 
        $Code2 = $Code."".$result['id'];
        if($ProdType2 == 3){
           $checkstatus = 0;  
       }
       else{
           $checkstatus = 0;
       }
        
         $sql2 = "UPDATE tbl_cust_products_2025 SET checkstatus='$checkstatus',code='$Code2' WHERE id='$ProdId'";
        $conn->query($sql2);
    }
}
}

if($_GET['action'] == 'clearprod'){
    $FrId = $_GET['frid'];
    $ShopName = $_GET['ShopName'];
    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $sql = "UPDATE tbl_cust_products_2025 SET clearprod='Yes',checkstatus='0',delete_flag=1,modified_time='$modified_time' WHERE CreatedBy='$FrId' AND ProdType=0 AND ProdType2=3";
    $conn->query($sql);
    echo "<script>window.location.href='allocate-godown-product.php?frid=$FrId&ShopName=$ShopName';</script>";
}


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
<h4 class="font-weight-bold py-3 mb-0">Allocate Products To <?php echo $_GET['ShopName'];?> Franchise 
<?php if($user_id == 2){?>
<a href="allocate-godown-product.php?frid=<?php echo $_GET['frid'];?>&ShopName=<?php echo $_GET['ShopName'];?>&action=yes" class="badge badge-pill badge-warning" style="float:right;">Display Product</a>

<?php } ?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                
                <th>#</th>
                <th>Id</th>
                <th>Main Prod Id</th>
                <th>Product name</th>
                <th>Product Type</th>
                <th>Price</th>
                <!--<th>Sell Qty</th>
                <th>Last Sell Date</th>-->
                <th>Barcode No</th>
                <th>Category</th>
                <th>Sub Category</th>
             

               
            </tr>
        </thead>
        <tbody>
            <?php 
            $frid = $_GET['frid'];
            $sql = "SELECT p.ProductName,p.BarcodeNo,p.checkstatus,p.id,p.ProdType2,p.MinPrice,c.Name As Category,cs.Name As SubCatName,p.ProdId FROM tbl_cust_products_2025 p 
                    INNER JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.ProdType=0 AND p.ProdType2=3 AND p.CreatedBy='$frid' AND p.Status=1";
            //$sql.= " ORDER BY p.id DESC";
            $sql.= " ORDER BY p.checkstatus DESC, p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
              $sql3 = "SELECT SUM(Qty) AS SellQty FROM tbl_customer_invoice_details_2025 WHERE ProdId='".$row['id']."' AND CreatedDate>='2025-08-01'";
             $row3 = getRecord($sql3);
             
             $sql31 = "SELECT MAX(CreatedDate) AS LastDate FROM tbl_customer_invoice_details_2025 WHERE ProdId='".$row['id']."'";
             $row31 = getRecord($sql31);
             ?>
            <tr>
              
  
  <td><label class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)" <?php if($row['checkstatus'] == 1){?> checked <?php } ?>>
                                                    <span class="custom-control-label">&nbsp;</span>
                                                </label></td>
                                                <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                                <input type="hidden" value="<?php echo $row['id']; ?>" name="ProdId[]">
                                                <input type="hidden" value="<?php echo $_GET['frid'];?>" name="frid" id="frid">
                                                  <td><?php echo $row['id']; ?></td>
                                                  <td><?php echo $row['ProdId']; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                 <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
               <!--  <td><?php echo $row3['SellQty'];?></td>
                 <td><?php echo $row31['LastDate'];?></td>-->
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
               
              
            
            </tr>
           <?php } ?>
        </tbody>
    </table>
    <br><br>
    <?php if($user_id == 2){?>
    <a onClick="return confirm('Are you sure you want clear allocate product');"  href="allocate-godown-product.php?frid=<?php echo $_GET['frid'];?>&ShopName=<?php echo $_GET['ShopName'];?>&action=clearprod" class="badge badge-pill badge-danger" style="float:right;">Clear Allocate Product</a>&nbsp;&nbsp;
<?php } ?>
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
        order: [[0, 'desc']],
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
<script>  

function featured(id) {
            if ($('#Check_Id' + id).prop('checked') == true) {
                $('#CheckId' + id).val(1);
                saveCart(id);
            } else {
                $('#CheckId' + id).val(0);
                delete_prod(id);
            }
        }

        function saveCart(id) {
            var quantity = 1;
            var frid = $('#frid').val();
            var action="saveCart";
            $.ajax({
                url: "ajax_files/ajax_allocate_selling_product.php",
                type: "POST",
                data: {
                    action:action,
                    quantity: quantity,
                    id: id,
                    frid:frid
                },
                success: function(data) {
                    console.log(data);
                },

            });
        }

        function delete_prod(id) {
            var frid = $('#frid').val();
            var action="deletCart";
            $.ajax({
                url: "ajax_files/ajax_allocate_selling_product.php",
                type: "POST",
                data: {
                    action:action,
                    id: id,
                    frid:frid
                },
                success: function(data) {
                    //alert(data);
                },

            });
        }

        function error_toast() {
            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
            $.growl.error({
                title: 'Error',
                message: 'Record Not Saved. Please Try Again!!!',
                location: isRtl ? 'tl' : 'tr'
            });
        }

        function success_toast() {
            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
            $.growl.notice({
                title: 'Success',
                message: 'Product Allocated Successfully!',
                location: isRtl ? 'tl' : 'tr'
            });
        }
        
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
