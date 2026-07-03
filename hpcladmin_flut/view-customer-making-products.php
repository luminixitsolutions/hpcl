<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Products";
$listAction = $_GET['action'] ?? '';

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
    
    
$sql = "SELECT * FROM tbl_cust_products_2025 WHERE code is null";
    $row = getList($sql);
    foreach($row as $result){
        $n = 10;
        $Code = RandomStringGenerator($n); 
        $Code2 = $Code."".$result['id'];
        $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
        $sql2 = "UPDATE tbl_cust_products_2025 SET code='$Code2',modified_time='$modified_time' WHERE id='".$result['id']."'";
        $conn->query($sql2);
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
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete")
{
    $id = $_REQUEST["id"] ?? '';
    $sql = "SELECT * FROM tbl_cust_products_2025 WHERE checkstatus=1 AND ProdId='$id'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        echo "<script>alert('Ypu Cant Delete Product. This Product Already Assign To Franchise. For Delete Please Contact IT Deaprtment');window.location.href='view-customer-products.php';</script>";
    }
    else{
    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $sql11 = "DELETE FROM tbl_cust_products2 WHERE id = '$id'";
    $conn->query($sql11);
    $sql11 = "DELETE FROM tbl_cust_products_2025 WHERE ProdId = '$id'"; 
    $conn->query($sql11);
    echo "<script>alert('Deleted Successfully!');window.location.href='view-customer-products.php';</script>";
    }

} ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Making Product List
<?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
    <a href="view-customer-making-products.php?action=allocate" class="btn btn-success btn-round"><i class="ion ion-md-add mr-2"></i> Allocate Excel</a>
    
    <a href="view-customer-making-products.php?action=notallocate" class="btn btn-danger btn-round"><i class="ion ion-md-add mr-2"></i> Not Allocate Excel</a>
    
<a href="add-customer-making-product.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
<?php } ?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
              <!-- <th>Photo</th>
               <th>Barcode No</th>-->
                <th>Id</th>
                <th>Product name</th>
                 <th>Sell Qty</th>
                <th>Barcode No</th>
                <th>Category</th>
                <th>Sub Category</th>
             <th>Product Type</th>
             <th>Total Price</th>
             <th>Discount %</th>
             <th>Discount Amt</th>
                   <th>Sell Price</th>

                <th>Status</th>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if($listAction === 'allocate'){
             $sql = "SELECT p.*,c.Name As Category,cs.Name As SubCatName FROM tbl_cust_products2 p 
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.ProdType=0 AND p.ProdType2=2";
            $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             $sql3 = "SELECT SUM(Qty) AS SellQty FROM tbl_customer_invoice_details_2025 WHERE MainProdId='".$row['id']."' AND CreatedDate>='2025-09-01'";
             $row3 = getRecord($sql3);
             $sql2 = "SELECT * FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='".$row['id']."'";
             $rncnt2 = getRow($sql2);
              if($rncnt2 > 0){
                 $bgcolor = "background-color: #73c76e;";
             ?>
            <tr style="<?php echo $bgcolor;?>">
              
  
  <td><?php echo $row['id'];?></td>
                <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row3['SellQty'];?></td>
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
                <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
                  <td class="align-middle"><?php echo number_format($row["SubTotal"],2); ?></td>
               <td class="align-middle"><?php echo number_format($row["DiscPer"],2); ?></td>
                <td class="align-middle"><?php echo number_format($row["Discount"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
                <td>
    <?php if($row['Status']=='1'){ ?>
        <a href="javascript:void(0);" 
           data-toggle="modal" 
           data-target="#statusModal" 
           data-id="<?php echo $row['id']; ?>" 
           data-status="1" 
           class="openStatusModal" 
           style="color:green;">Publish</a>
    <?php } else { ?>
        <a href="javascript:void(0);" 
           data-toggle="modal" 
           data-target="#statusModal" 
           data-id="<?php echo $row['id']; ?>" 
           data-status="0" 
           class="openStatusModal" 
           style="color:red;">Not Publish</a>
    <?php } ?>
</td>

                   
                 
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                     <td>
                      <?php if(in_array("10", $Options)){?>
              <a href="add-customer-making-product.php?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
             &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this Product?\nNote : Delete all orders related this Product (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
              <?php } ?>
            </td><?php } ?>
         
            
            </tr>
            <?php     
            } }
            }
            else if($listAction === 'notallocate'){
             $sql = "SELECT p.*,c.Name As Category,cs.Name As SubCatName FROM tbl_cust_products2 p 
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.ProdType=0 AND p.ProdType2=2";
            $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             $sql3 = "SELECT SUM(Qty) AS SellQty FROM tbl_customer_invoice_details_2025 WHERE MainProdId='".$row['id']."' AND CreatedDate>='2025-08-01'";
             $row3 = getRecord($sql3);
             
             $sql2 = "SELECT * FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='".$row['id']."'";
             $rncnt2 = getRow($sql2);
              if($rncnt2 > 0){}
              else{
                
             ?>
            <tr style="<?php echo $bgcolor;?>">
              
  
  <td><?php echo $row['id'];?></td>
                <td><?php echo $row['ProductName']; ?></td>
                  <td><?php echo $row3['SellQty'];?></td>
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
                <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
                <td class="align-middle"><?php echo number_format($row["SubTotal"],2); ?></td>
               <td class="align-middle"><?php echo number_format($row["DiscPer"],2); ?></td>
                <td class="align-middle"><?php echo number_format($row["Discount"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
                <td>
    <?php if($row['Status']=='1'){ ?>
        <a href="javascript:void(0);" 
           data-toggle="modal" 
           data-target="#statusModal" 
           data-id="<?php echo $row['id']; ?>" 
           data-status="1" 
           class="openStatusModal" 
           style="color:green;">Publish</a>
    <?php } else { ?>
        <a href="javascript:void(0);" 
           data-toggle="modal" 
           data-target="#statusModal" 
           data-id="<?php echo $row['id']; ?>" 
           data-status="0" 
           class="openStatusModal" 
           style="color:red;">Not Publish</a>
    <?php } ?>
</td>

                   
                 
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                     <td>
                      <?php if(in_array("10", $Options)){?>
              <a href="add-customer-making-product.php?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
             &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this Product?\nNote : Delete all orders related this Product (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
              <?php } ?>
            </td><?php } ?>
         
            
            </tr>
            <?php     
            } }
            }
            else{
            $sql = "SELECT p.*,c.Name As Category,cs.Name As SubCatName FROM tbl_cust_products2 p 
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.ProdType=0 AND p.ProdType2=2";
            $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql3 = "SELECT SUM(Qty) AS SellQty FROM tbl_customer_invoice_details_2025 WHERE MainProdId='".$row['id']."' AND CreatedDate>='2025-08-01'";
             $row3 = getRecord($sql3);
             
             $sql2 = "SELECT * FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='".$row['id']."'";
             $rncnt2 = getRow($sql2);
              if($rncnt2 > 0){
                    $bgcolor = "background-color: #73c76e;";
                }
                else{
                    $bgcolor = "";
                }
             ?>
            <tr style="<?php echo $bgcolor;?>">
              
  
  <td><?php echo $row['id'];?></td>
                <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row3['SellQty'];?></td>
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
                <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
                <td class="align-middle"><?php echo number_format($row["SubTotal"],2); ?></td>
               <td class="align-middle"><?php echo number_format($row["DiscPer"],2); ?></td>
                <td class="align-middle"><?php echo number_format($row["Discount"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
                <td>
    <?php if($row['Status']=='1'){ ?>
        <a href="javascript:void(0);" 
           data-toggle="modal" 
           data-target="#statusModal" 
           data-id="<?php echo $row['id']; ?>" 
           data-status="1" 
           class="openStatusModal" 
           style="color:green;">Publish</a>
    <?php } else { ?>
        <a href="javascript:void(0);" 
           data-toggle="modal" 
           data-target="#statusModal" 
           data-id="<?php echo $row['id']; ?>" 
           data-status="0" 
           class="openStatusModal" 
           style="color:red;">Not Publish</a>
    <?php } ?>
</td>

                   
                 
               <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                     <td>
                      <?php if(in_array("10", $Options)){?>
              <a href="add-customer-making-product.php?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
            <!-- &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this Product?\nNote : Delete all orders related this Product (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>-->
              <?php } ?>
            </td><?php } ?>
         
            
            </tr>
           <?php } } ?>
        </tbody>
    </table>
</div>
</div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Change Status</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="modalRecordId">
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" id="modalStatus">
              <option value="1">Publish</option>
              <option value="0">Not Publish</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="saveStatusBtn" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
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
$(document).on("click", ".openStatusModal", function () {
    var id = $(this).data('id');
    var status = $(this).data('status');
    $("#modalRecordId").val(id);
    $("#modalStatus").val(status);
    $("#statusModal").modal('show');
});

$("#saveStatusBtn").click(function(){
    var id = $("#modalRecordId").val();
    var status = $("#modalStatus").val();

    $.ajax({
        url: "change_status.php",
        type: "POST",
        data: { id:id, status:status },
        success: function(response){
            // Update the text in the table instantly
            var rowLink = $('a[data-id="'+id+'"]');
            if(status == "1"){
                rowLink.text("Publish").css("color","green").data("status",1);
            } else {
                rowLink.text("Not Publish").css("color","red").data("status",0);
            }

            $("#statusModal").modal('hide');
        }
    });
});

	$(document).ready(function() {
    $('#example').DataTable({
      "scrollX": true,
        dom: 'Bfrtip',
        order: [[2, 'desc']],
        buttons: [
            'excelHtml5'
        ]
    });
});
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
