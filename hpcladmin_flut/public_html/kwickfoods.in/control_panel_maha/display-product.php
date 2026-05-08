<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products";
$Page = "View-Customer-Products";
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


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Display Product List

</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
       <thead class="thead-light">
            <tr>
              <!-- <th>Photo</th>-->
            <!--   <th>Barcode No</th>-->
            <th><button type="button" name="delete_all" id="delete_all" class="btn btn-danger btn-xs">Save</button></th>
                <th>Product name</th>
               
                <th>Category</th>
             
               
           
                   <th>Price</th>
           
                <th>Status</th>
                <th>Register Date</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT p.*,c.Name As Category FROM tbl_cust_products p 
                    LEFT JOIN tbl_cust_category c ON c.id=p.CatId WHERE p.CreatedBy='$BillSoftFrId'";
            $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
            <tr>
               <!--<td> <?php if($row["Photo"] == '') {?>
                  <img src="../no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="img-fluid ui-w-40" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40" style="width: 40px;height: 40px;"> 
             <?php } ?></td>
-->
<!--  <td><?php echo $row['BarcodeNo']; ?></td>-->

 <td class="align-middle">
                                    <input type="checkbox" class="delete_checkbox" value="<?php echo $row["id"];?>" <?php if($row["Display"] == 1){?> checked <?php } ?>/>
                                </td>
                                
                <td class="align-middle"><?php echo $row['ProductName']; ?></td>
              
               
                <td class="align-middle"><?php echo $row['Category']; ?></td>
               
            
                <td class="align-middle">&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
           
                    
                 <td class="align-middle"><?php if($row['Status']=='1'){ echo "<span style='color:green;'>Publish</span>";} else { echo "<span style='color:red;'>Not Publish</span>";} ?></td>
            <td class="align-middle"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
         
              
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
        "pageLength": 1000,
      "scrollX": true
        
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
            if (confirm("Are you sure you want to display Products?")) {
            var checkbox_value = [];
            $(checkbox).each(function(){
                checkbox_value.push($(this).val());
            });
       //alert(checkbox_value);exit();
            $.ajax({
                url:"ajax_files/all-cust-product-display.php",
                method:"POST",
                data:{checkbox_value:checkbox_value},
                success:function(data)
                {
                    console.log(data); 
                    
                    //$('.removeRow').fadeOut(1500);
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
