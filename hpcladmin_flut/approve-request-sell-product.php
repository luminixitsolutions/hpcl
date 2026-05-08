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



<?php 
if(isset($_POST['submit'])){
     $rncnt = $_POST['rncnt'];
     if($rncnt > 0){
        $number = count($_POST['CheckId']);
   $CreatedDate = date('Y-m-d H:i:s');
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $Inv_Id = addslashes(trim($_POST['InvId'][$i]));
                $ItemId = addslashes(trim($_POST['ItemId'][$i]));
                $sql = "SELECT ProdId,Qty,StockDate,Narration,FrId,PurchasePrice,SellPrice FROM tbl_cust_prod_stock_2025_temp WHERE id='$ItemId' AND InvId='$Inv_Id'";
                $row = getRecord($sql);
                $ProdId = $row['ProdId'];
                $Qty = $row['Qty'];
                $StockDate = $row['StockDate'];
                $Narration = $row['Narration'];
                $BillSoftFrId = $row['FrId'];
                $PurchasePrice = $row['PurchasePrice'];
                $SellPrice = $row['SellPrice'];

                $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
                $conn->query($qx);
                $InvId = mysqli_insert_id($conn);
       
                $qx = "INSERT INTO tbl_cust_prod_stock_2025_backup SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice',orgstockid='$InvId'";
                $conn->query($qx);
  
                // Fetch the inserted records
                $result = $conn->query("SELECT * FROM tbl_cust_prod_stock_2025 WHERE id = '$InvId'");
                $row = $result->fetch_assoc();

                // Create SQL Dump
                $sqlDump = "INSERT INTO tbl_cust_prod_stock_2025 (ProdId, Qty, CreatedBy, StockDate, Narration, Status, UserId, CreatedDate, FrId, PurchasePrice, SellPrice) 
                            VALUES ('" . implode("','", array_values($row)) . "');\n";

                file_put_contents('stock_backup/'.$BillSoftFrId.'_backup.sql', $sqlDump, FILE_APPEND | LOCK_EX);

  
                $sql = "UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
                $conn->query($sql);
                
                $sql = "UPDATE tbl_cust_prod_stock_2025_temp SET checkstatus=1 WHERE  id='$ItemId' AND InvId='$Inv_Id'";
                $conn->query($sql);
                $url = $_SERVER['REQUEST_URI'];
                $createddate = date('Y-m-d H:i:s');
                $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new customer product stock added',invid='$InvId',createddate='$createddate',roll='customer-product-stock'";
                $conn->query($sql);

                }
              }
            }
        }
    }
    
     echo "<script>alert('Product Stock Approved');window.location.href='view-request-sell-product-stock.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Approve Product Stock
</h4>
<?php 
    $id = $_GET['id'];
    $sql = "SELECT p.ProductName,p.BarcodeNo,tc.checkstatus,tc.id,tc.ProdId,p.ProdType2,p.MinPrice,c.Name As Category,cs.Name As SubCatName,tc.Qty FROM tbl_cust_prod_stock_2025_temp tc
            INNER JOIN tbl_cust_products_2025 p ON p.id=tc.ProdId
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE tc.InvId='$id'";
            $sql.= " ORDER BY p.id DESC";
    $rncnt = getRow($sql);
 ?>

<div class="card">
<div class="card-datatable table-responsive">
     <form id="validation-form" method="post" enctype="multipart/form-data">
         <input type="hidden" name="rncnt" value="<?php echo $rncnt;?>">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                
                <th>#</th>
                <th>Product Id</th>
                <th>Product name</th>
                 <th>Price</th>
             <th>Qty</th>
                <th>Barcode No</th>
                <th>Category</th>
                <th>Sub Category</th>
             <th>Product Type</th>
            

               
            </tr>
        </thead>
        <tbody>
            <?php 
           
            
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                if($row['checkstatus'] == 1){
                    
                }
                else{
                    
                }
             ?>
            <tr>
              
  
  <td><label class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)" <?php if($row['checkstatus'] == 1){?> checked disabled <?php } ?>>
                                                    <span class="custom-control-label">&nbsp;</span>
                                                </label></td>
                                                <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                                <input type="hidden" value="<?php echo $row['id']; ?>" name="ItemId[]">
                                                <input type="hidden" value="<?php echo $row['ProdId']; ?>" name="ProdId[]">
                                                <input type="hidden" value="<?php echo $_GET['id'];?>" name="InvId[]" id="InvId">
                                                  <td><?php echo $row['ProdId']; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                 <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
              <td><?php echo $row['Qty']; ?></td>
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
                <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
               
            
            </tr>
           <?php } ?>
        </tbody>
    </table>
    <div class="form-row">
                                    <div class="form-group col-md-3" style="padding-top:20px;">
                                        <button type="submit" name="submit" id="submit"
                                            class="btn btn-primary btn-finish">Approve</button>
                                    </div>

                                </div>
                                </form>
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
        ],
        "pageLength":500,
    });
});
</script>
<script>  

function featured(id) {
            if ($('#Check_Id' + id).prop('checked') == true) {
                $('#CheckId' + id).val(1);
                //saveCart(id);
            } else {
                $('#CheckId' + id).val(0);
                //delete_prod(id);
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
