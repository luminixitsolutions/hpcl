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



<?php
if($_REQUEST["action"]=="delete")
{
    $id = $_REQUEST["id"];
    $sql11 = "DELETE FROM tbl_product_group WHERE id = '$id'";
    $conn->query($sql11);
    echo "<script>alert('Deleted Successfully!');window.location.href='view-product-group.php';</script>";

} ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Product Groups
<?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="create-product-group.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
<?php } ?>
</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>Id</th>
      <th>Group Name</th>
      <th>Products</th>
      <th>Status</th>
      <?php if (in_array("10", $Options) || in_array("11", $Options)) { ?>
        <th>Action</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM tbl_product_group ORDER BY id DESC";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
    ?>
      <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['GroupName']; ?></td>
        <td>
          <a href="#" class="view-products" data-id="<?= $row['id']; ?>" data-name="<?= htmlspecialchars($row['GroupName']); ?>">
            View
          </a>
        </td>
        <td>
          <?= ($row['Status'] == '1')
            ? "<span style='color:green;'>Publish</span>"
            : "<span style='color:red;'>Not Publish</span>"; ?>
        </td>

        <?php if (in_array("10", $Options) || in_array("11", $Options)) { ?>
          <td>
            <?php if (in_array("10", $Options)) { ?>
              <a href="create-product-group.php?id=<?= $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
            <?php } ?>
            <?php if (in_array("11", $Options)) { ?>
              &nbsp;&nbsp;
              <a onClick="return confirm('Are you sure you want to delete this Product Group?\nNote: This will delete all related orders!');"
                href="<?= $_SERVER['PHP_SELF']; ?>?id=<?= $row['id']; ?>&action=delete">
                <i class="lnr lnr-trash text-danger"></i>
              </a>
            <?php } ?>
          </td>
        <?php } ?>
      </tr>
    <?php } ?>
  </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="productModalLabel">Assigned Products</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="productModalBody">
        <div class="text-center text-muted">Loading...</div>
      </div>
    </div>
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
        order: [[2, 'desc']],
        buttons: [
            'excelHtml5'
        ]
    });
    
      // Use delegated event so it works for dynamically loaded rows too
  $(document).on('click', '.view-products', function(e){
    e.preventDefault();
    var groupId = $(this).data('id');
    var groupName = $(this).data('name');

    $('#productModalLabel').text("Products in Group: " + groupName);
    $('#productModalBody').html('<div class="text-center text-muted">Loading...</div>');
    $('#productModal').modal('show');

    $.ajax({
      url: 'ajax_files/ajax-get-group-products.php',
      type: 'POST',
      data: {id: groupId},
      success: function(data){
        $('#productModalBody').html(data);
      },
      error: function(){
        $('#productModalBody').html('<div class="text-danger text-center">Error loading data.</div>');
      }
    });
  });
});
</script>

</body>
</html>
