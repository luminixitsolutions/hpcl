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


<style>
    #example_wrapper .dataTables_scrollBody {
    max-height: 400px !important;
}
</style>

<?php 
$frid = $_GET['frid'];
$sqlUser = "SELECT AllocateRawProd FROM tbl_users WHERE id='$frid'";
$rowUser = getRecord($sqlUser);
$AllocateRawProd = $rowUser['AllocateRawProd']; // e.g. "1,2,3"
$allocatedIds = array_map('trim', explode(',', $AllocateRawProd));
?>
<script>
 var preAllocated = <?php echo json_encode($allocatedIds); ?>; 
</script>
<?php 
$sql = "SELECT tp.id,tp.ProductName,tc.Name As CatName,tsc.Name As SubCatName 
        FROM tbl_cust_products2 tp 
        INNER JOIN tbl_cust_category_2025 tc ON tc.id=tp.CatId 
        LEFT JOIN tbl_cust_sub_category_2025 tsc ON tsc.id=tp.SubCatId 
        WHERE tp.Status=1 AND tp.OldNew=0 AND tp.ProdType=1";

$res = $conn->query($sql);

// Separate allocated and unallocated
$allocatedRows = [];
$unallocatedRows = [];

while($row = $res->fetch_assoc()) {
    if (in_array($row['id'], $allocatedIds)) {
        $allocatedRows[] = $row;
    } else {
        $unallocatedRows[] = $row;
    }
}

// Merge: allocated first, then unallocated
$allRows = array_merge($allocatedRows, $unallocatedRows);
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Allocate Products To <?php echo $_GET['ShopName'];?> Franchise
</h4>

  
  
<div class="card">
<div class="card-datatable table-responsive">
    <form id="validation-form" method="post" enctype="multipart/form-data" action="assign-raw-product.php">
        <textarea name="selected_ids_combined" id="selected_ids_combined" style="display:none;"></textarea>

<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                
                <th><label class="custom-control custom-checkbox">
      <input type="checkbox" id="checkAll" class="custom-control-input">
      <span class="custom-control-label">&nbsp;</span>
  </label></th>
                <th>Product name</th>
                
                <th>Category</th>
                <th>Sub Category</th>
           
               
            </tr>
        </thead>
       <tbody>
<?php foreach ($allRows as $row): ?>
    <tr>
        <td>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" 
                       id="Check_Id<?php echo $row['id']; ?>" 
                       value="1" 
                       class="custom-control-input is-valid rowCheckbox" 
                       onclick="featured(<?php echo $row['id']; ?>)" 
                       data-id="<?php echo $row['id']; ?>"
                       <?php if (in_array($row['id'], $allocatedIds)) echo "checked"; ?>>
                <span class="custom-control-label">&nbsp;</span>
            </label>
        </td>

        <input type="hidden" 
               value="<?php echo in_array($row['id'], $allocatedIds) ? 1 : 0; ?>" 
               name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">

        <input type="hidden" value="<?php echo $row['id']; ?>" name="ProdId[]">
        <input type="hidden" value="<?php echo $_GET['frid'];?>" name="frid" id="frid">

        <td><?php echo $row['ProductName']; ?></td>
        <td><?php echo $row['CatName']; ?></td>
        <td><?php echo $row['SubCatName']; ?></td>
    </tr>
<?php endforeach; ?>
</tbody>
    </table>
    <input type="hidden" value="<?php echo $frid;?>" name="frid" id="frid">
                                <div class="form-row">
                                    <div class="form-group col-md-3" style="padding-top:20px;">
                                        <button type="submit" name="submit" id="submit"
                                            class="btn btn-primary btn-finish">Assign</button>
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
var selectedIds = {};

function updateHiddenField() {
    document.getElementById("selected_ids_combined").value = Object.keys(selectedIds).join(",");
}

function toggleCheckbox(checkbox) {
    const id = checkbox.getAttribute("data-id");
    if (checkbox.checked) {
        selectedIds[id] = true;
    } else {
        delete selectedIds[id];
    }
    updateHiddenField();
}

$(document).ready(function () {
    // ✅ DataTable with all rows on one page
    var table = $('#example').DataTable({
    paging: false,
    info: false,
    searching: true,
    ordering: true,
    scrollY: "400px",   // fixed height
    scrollCollapse: true
});

    // ✅ Load preallocated IDs from PHP
    preAllocated.forEach(function (id) {
        selectedIds[id] = true;
    });
    updateHiddenField();

    // ✅ Handle row checkbox click → reorder if not "check all"
    $(document).on('change', '.rowCheckbox', function () {
        toggleCheckbox(this);

        if (!$('#checkAll').is(':checked')) {
            // Move checked rows to top
            var rows = $('#example tbody tr').get();
            rows.sort(function (a, b) {
                var aChecked = $(a).find('.rowCheckbox').prop('checked') ? 1 : 0;
                var bChecked = $(b).find('.rowCheckbox').prop('checked') ? 1 : 0;
                return bChecked - aChecked; // checked first
            });
            $.each(rows, function (index, row) {
                $('#example tbody').append(row);
            });
        }

        // Update master checkbox state
        $('#checkAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length);
    });

    // ✅ Handle "Check All" (check/uncheck all rows)
    $('#checkAll').on('change', function () {
        const isChecked = $(this).is(':checked');

        $('.rowCheckbox').each(function () {
            $(this).prop('checked', isChecked);
            const id = $(this).attr("data-id");

            if (isChecked) {
                selectedIds[id] = true;
            } else {
                delete selectedIds[id];
            }
        });

        updateHiddenField();
    });

    // ✅ Restore preallocated checkboxes on load
    $('.rowCheckbox').each(function () {
        const id = $(this).attr("data-id");
        if (selectedIds[id]) {
            $(this).prop('checked', true);
        }
    });

    // ✅ Sync master checkbox on load
    $('#checkAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length);
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
