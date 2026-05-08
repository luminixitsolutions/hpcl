<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Products";

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



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View CROSS Sale Product List
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
                <th>Barcode No</th>
                <th>Category</th>
                <th>Sub Category</th>
             <th>Product Type</th>
            <th>Purchase Price</th>
             <th>Total Price</th>
             <th>Discount %</th>
             <th>Discount Amt</th>
                   <th>Sell Price</th>

                <th>Status</th>
               
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT p.*,c.Name As Category,cs.Name As SubCatName FROM tbl_cust_products2 p 
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.CrossSell=1";
            $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
            <tr>
              
  
  <td><?php echo $row['id'];?></td>
                <td><?php echo $row['ProductName']; ?></td>
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
                <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
                <td class="align-middle"><?php echo number_format($row["PurchasePrice"],2); ?></td>
                 <td class="align-middle"><?php echo number_format($row["SubTotal"],2); ?></td>
               <td class="align-middle"><?php echo number_format($row["DiscPer"],2); ?></td>
                <td class="align-middle"><?php echo number_format($row["Discount"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
                <td><?php if($row['Status']=='1'){ echo "<span style='color:green;'>Publish</span>";} else { echo "<span style='color:red;'>Not Publish</span>";} ?></td>
                   
                 
              
         
            
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
        order: [[0, 'desc']],
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
