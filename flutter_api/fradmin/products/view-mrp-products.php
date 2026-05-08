<?php
include '../config.php';
header('Content-Type: text/html; charset=utf-8');

// INPUT FILTERS (same logic as API)
$FrId      = $_GET['user_id'] ?? '';
$CatId     = $_GET['CatId'] ?? 'all';
$SubCatId  = $_GET['SubCatId'] ?? 'all';
$ProdType2 = $_GET['ProdType2'] ?? 'all';

if (empty($FrId)) {
    die("<h3 style='color:red;text-align:center;'>User ID Missing!</h3>");
}

/* -------------------------------
   BUILD QUERY (same as API logic)
---------------------------------*/
$sql = "SELECT p.*, c.Name AS Category, cs.Name AS SubCatName
        FROM tbl_cust_products_2025 p
        LEFT JOIN tbl_cust_category_2025 c ON c.id = p.CatId
        LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id = p.SubCatId
        WHERE p.CreatedBy = '$FrId'
          AND p.ProdType = 0
          AND p.ProdType2 = 1
          AND p.delete_flag = 0
          AND p.checkstatus = 1";

if ($CatId != 'all') {
    $sql .= " AND p.CatId = '$CatId'";
}
if ($SubCatId != 'all') {
    $sql .= " AND p.SubCatId = '$SubCatId'";
}
if ($ProdType2 != 'all') {
    $sql .= " AND p.ProdType2 = '$ProdType2'";
}

$sql .= " ORDER BY p.ProductName ASC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View MRP Products</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body { background: #fff; font-family: Inter, sans-serif; }
.card { border-radius: 16px; box-shadow: 0 3px 12px rgba(90,60,200,.08); }
.table thead th { background:#6a4fe0; color:#fff; font-size:13px; }
.table td { font-size:13px; }
#searchBox { border-radius:10px;border:1px solid #d8d1ff; }
.pagination .page-link { border-radius:8px; }
</style>
</head>

<body>

<div class="container-fluid mt-3">
<div class="card p-3">

<h4 class="text-center" style="color:#5b3cc4">📦 View MRP Products</h4>

<!-- SEARCH BAR -->
<div class="d-flex gap-2 mb-3">
<input type="text" id="searchBox" class="form-control" placeholder="Search product, category or subcategory...">
<button id="clearSearch" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Clear</button>
</div>

<div class="table-responsive">
<table class="table table-bordered table-hover" id="productTable">
<thead>
<tr>
    <th>#</th>
    <th>Product</th>
    <th>Barcode</th>
    <th>Category</th>
    <th>SubCategory</th>
    <th>Purchase (₹)</th>
    <th>Sell Price (₹)</th>
    <th>Status</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $i++;
        ?>
<tr class="product-row"
    data-name="<?= strtolower($row['ProductName']) ?>"
    data-barcode="<?= strtolower($row['BarcodeNo']) ?>"
    data-category="<?= strtolower($row['Category']) ?>"
    data-subcat="<?= strtolower($row['SubCatName']) ?>">

    <td><?= $i ?></td>
    <td><?= $row['ProductName'] ?></td>
    <td><?= $row['BarcodeNo'] ?></td>
    <td><?= $row['Category'] ?></td>
    <td><?= $row['SubCatName'] ?></td>
    <td><?= number_format($row['PurchasePrice'], 2) ?></td>
    <td><?= number_format($row['MinPrice'], 2) ?></td>
    <td><?= ($row['Status'] == 1 ? "Publish" : "Not Publish") ?></td>
        <?php if($row['allotstatus']==0){?>
    <td>
        <a href="add-customer-product.php?id=<?= $row['id']; ?>&user_id=<?php echo $FrId;?>" class="btn btn-sm btn-primary edit-product">
            <i class="bi bi-pencil-square"></i>
        </a>
    </td>

    <td>
        <button class="btn btn-sm btn-danger delete-product" data-id="<?= $row['id']; ?>">
            <i class="bi bi-trash"></i>
        </button>
    </td>
    <?php }else{ ?>
    <td></td>
    <?php } ?>
</tr>
<?php
    }
} else {
    echo '<tr><td colspan="10" class="text-center text-muted">No products found.</td></tr>';
}
?>
</tbody>
</table>
</div>

<ul class="pagination" id="pagination"></ul>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
const rowsPerPage = 10;
let currentPage = 1;

function normalize(v){ return (v||'').toString().toLowerCase(); }

function applySearchFilter(term) {
  const query = normalize(term);
  $('#productTable tbody tr.product-row').each(function() {
    const row = $(this);
    const name = normalize(row.data('name'));
    const barcode = normalize(row.data('barcode'));
    const cat = normalize(row.data('category'));
    const sub = normalize(row.data('subcat'));
    const matched = query === '' || name.includes(query) || barcode.includes(query) || cat.includes(query) || sub.includes(query);
    row.toggleClass('d-none', !matched);
  });
  paginateTable();
}

function paginateTable() {
  const rows = $('#productTable tbody tr.product-row:not(.d-none)');
  const totalRows = rows.length;
  const totalPages = Math.ceil(totalRows / rowsPerPage);

  rows.hide();
  const start = (currentPage - 1) * rowsPerPage;
  rows.slice(start, start + rowsPerPage).show();

  const pagination = $('#pagination');
  pagination.empty();
  for (let i = 1; i <= totalPages; i++) {
    pagination.append(`<li class="page-item ${i===currentPage?'active':''}">
                         <a class="page-link" href="#">${i}</a>
                       </li>`);
  }

  $('.page-link').off().on('click', function(e){
    e.preventDefault();
    currentPage = parseInt($(this).text());
    paginateTable();
  });
}

$(document).ready(function(){
  $('#searchBox').on('input', function(){ currentPage=1; applySearchFilter($(this).val()); });
  $('#clearSearch').click(function(){ $('#searchBox').val(''); currentPage=1; applySearchFilter(''); });
  applySearchFilter('');
  
    $(document).on('click', '.delete-product', function () {
    let id = $(this).data('id');
    let FrId = <?php echo $FrId;?>;
    if(confirm("Are you sure you want to delete this product record?")) {
        $.ajax({
            url: "delete_product.php",
            type: "POST",
            data: { id: id,FrId:FrId },
            success: function(response) {
                alert(response);
                location.reload(); // Refresh updated table
            },
            error: function() {
                alert("Failed to delete. Try again!");
            }
        });
    }
});

});
</script>
</body>
</html>
