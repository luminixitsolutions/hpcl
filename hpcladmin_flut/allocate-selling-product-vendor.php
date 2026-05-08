<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Raw-Products";
$Page = "Allocate-Raw-Products";
$sessionid = session_id();
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
    /*$frid = $_GET['frid'];
    $sql = "DELETE FROM tbl_temp_allocated_ved_prd WHERE FrId='$frid' AND sessionid='$sessionid'";
    $conn->query($sql);
    
    $sql = "SELECT * FROM tbl_cust_products2 WHERE ProdType=0 AND ProdType2=1";
    $row = getList($sql);
    foreach($row as $result){
        $sql = "INSERT INTO tbl_temp_allocated_ved_prd SET checkstatus=1,ProdId='".$result['id']."',FrId='$frid',sessionid='$sessionid'";
        $conn->query($sql);
    }*/
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Allocate Products To <?php echo $_GET['ShopName'];?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
    <form id="validation-form" method="post" enctype="multipart/form-data" action="assign-selling-product-vendor.php">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                
                <th>#</th>
                <th>Product name</th>
                
                <th>Category</th>
                <th>Sub Category</th>
           
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $frid = $_GET['frid'];
            $sql22 = "SELECT AllocateProd FROM tbl_users WHERE id='$frid'";
            $row22 = getRecord($sql22);
            $row22['AllocateProd'] = explode(',',$row22['AllocateProd']);
            
             $sql = "SELECT p.ProductName,p.BarcodeNo,p.checkstatus,p.id,p.ProdType2,p.MinPrice,c.Name As Category,cs.Name As SubCatName FROM tbl_cust_products2 p 
                    INNER JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId WHERE p.ProdType=0 AND p.ProdType2=1 ORDER BY p.ProductName";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
             if(in_array($row['id'],$row22['AllocateProd'])) { 
                 
                 $checkvalue = 1;
             }
             else{
                 $checkvalue = 0;
             }
             ?>
            <tr>
              
  
  <td><label class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="<?php echo $checkvalue;?>" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)" <?php if(in_array($row['id'],$row22['AllocateProd'])) { ?> checked="checked" <?php } ?>>
                                                    <span class="custom-control-label">&nbsp;</span>
                                                </label></td>
                                                <input type="hidden" value="<?php echo $checkvalue;?>" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                                <input type="hidden" value="<?php echo $row['id']; ?>" name="ProdId[]">
                                                <input type="hidden" value="<?php echo $_GET['frid'];?>" name="frid" id="frid">
                <td><?php echo $row['ProductName']; ?></td>
             
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
              
            
            </tr>
           <?php } ?>
        </tbody>
    </table>
    <input type="hidden" value="<?php echo $frid;?>" name="frid" id="frid">
                                <div class="form-row">
                                    <div class="form-group col-md-3" style="padding-top:20px;">
                                        <button type="submit" name="submit" id="submit"
                                            class="btn btn-primary btn-finish">Assign</button>
                                    </div>

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
 
	$(document).ready(function() {
    $('#example').DataTable({
      "scrollX": true,
        dom: 'Bfrtip',
        "pageLength":1000,
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
