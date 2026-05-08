<?php
header('Content-Type: text/html; charset=utf-8');
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add New Stock - Distributor Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
  body {
    background-color: #fff;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    color: #2f2f2f;
    padding: 0px;
  }

  .page-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #5b3cc4;
    text-align: center;
    margin-bottom: 20px;
  }

  .table thead th {
    background-color: #6a4fe0 !important;
    color: #fff !important;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #d8d1ff;
    text-align: center;
    vertical-align: middle;
    padding: 10px;
  }

  .table td {
    border: 1px solid #e3dfff !important;
    font-size: 13px;
    text-align: center;
    padding: 9px 8px;
    vertical-align: middle;
    background-color: #fff;
  }

  input.qty-input {
    width: 80px;
    text-align: center;
    border-radius: 8px;
    border: 1px solid #d5cfff;
  }

  .total-box {
    background-color: #f9f8ff;
    border: 1px solid #e0dafc;
    border-radius: 12px;
    padding: 10px 14px;
    font-weight: 700;
    color: #3fb984;
    text-align: right;
    font-size: 14px;
    margin-top: 12px;
  }

  /* 🔥 BEAUTIFUL PAGINATION STYLE (Matches Your Screenshot) */
  .custom-pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 25px;
    padding-bottom: 20px;

    /* FIX bullet issue */
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}


  .custom-pagination .page-item .page-link {
      border: 1px solid #dcd4ff !important;
      border-radius: 10px !important;
      padding: 9px 17px !important;
      font-size: 14px;
      color: #6a4fe0 !important;
      background-color: #fff !important;
      transition: 0.25s;
      font-weight: 600;
  }

  .custom-pagination .page-item .page-link:hover {
      background-color: #f3ecff !important;
      color: #6a4fe0 !important;
  }

  .custom-pagination .page-item.active .page-link {
      background-color: #6a4fe0 !important;
      border-color: #6a4fe0 !important;
      color: #fff !important;
      font-weight: 700;
  }

  #pageLoader .spinner-border { color: #6a4fe0 !important; }
</style>
</head>

<!-- Loader -->
<div id="pageLoader" style="
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(255,255,255,0.9); display: flex; align-items: center;
  justify-content: center; z-index: 99999;">
  <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
</div>

<body>

<div style="background-color:white;">
<div>

  <div class="page-title">🛒 Add New Stock</div>

  <input type="hidden" id="FrId" value="<?php echo $_GET['user_id'] ?? 1; ?>">
  <input type="hidden" id="CreatedBy" value="<?php echo $_GET['user_id'] ?? 1; ?>">

  <!-- Search -->
  <div class="mb-3 px-2">
    <input type="text" id="searchInput" class="form-control" placeholder="Search product..." style="width: 50%;">
  </div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table align-middle table-hover" id="productTable">
      <thead>
        <tr>
          <th><input type="checkbox" id="selectAll"></th>
          <th>Product</th>
          <th>Category</th>
          <th>Sub Category</th>
          <th>Rate (₹)</th>
          <th>Total Stock</th>
          <th>Qty</th>
          <th>Total (₹)</th>
        </tr>
      </thead>
      <tbody>

<?php
$FrId = $_REQUEST['user_id'] ?? 1;

/* STOCK QUERY */
$stockArr = [];
$sqlStock = "
    SELECT t.ProdId,
    COALESCE(SUM(CASE WHEN t.Status = 'Cr' THEN t.Qty ELSE 0 END),0) AS Cr,
    COALESCE(SUM(CASE WHEN t.Status = 'Dr' THEN t.Qty ELSE 0 END),0) AS Dr
    FROM tbl_cust_prod_stock_2025 t
    LEFT JOIN tbl_cust_products_2025 p ON t.ProdId = p.id
    WHERE t.FrId='$FrId' AND p.ProductName!=''
    GROUP BY t.ProdId
";
$resStock = $conn->query($sqlStock);
while($s = $resStock->fetch_assoc()){
    $stockArr[$s['ProdId']] = $s['Cr'] - $s['Dr'];
}

/* PRODUCT QUERY */
$sql = "SELECT tp.id,tp.ProdId,tp.ProductName,tp.MinPrice,tc.Name AS CatName,tsc.Name AS SubCatName 
        FROM tbl_cust_products_2025 tp 
        LEFT JOIN tbl_cust_category_2025 tc ON tc.id=tp.CatId 
        LEFT JOIN tbl_cust_sub_category_2025 tsc ON tsc.id=tp.SubCatId
        WHERE tp.checkstatus=1 AND tp.Status=1 AND tp.CreatedBy='$FrId'
        ORDER BY tp.ProductName ASC";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        $pid = $r['id'];
        $stock = $stockArr[$pid] ?? 0;

echo "
<tr data-prodid='{$r['id']}' data-mainprodid='{$r['ProdId']}'>
  <td><input type='checkbox' class='row-check'></td>
  <td class='pname'>{$r['ProductName']}</td>
  <td class='pname'>{$r['CatName']}</td>
  <td class='pname'>{$r['SubCatName']}</td>
  <td class='rate'>{$r['MinPrice']}</td>
  <td class='stock'>{$stock}</td>
  <td><input type='number' class='qty-input' min='0' value='0'></td>
  <td class='total'>0.00</td>
</tr>";
    }
} else {
    echo "<tr class='no-data'><td colspan='6' class='text-center'>No products found.</td></tr>";
}
?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <nav>
    <ul class="custom-pagination" id="pagination"></ul>
  </nav>

  <div class="total-box">
    Total Amount: ₹<span id="grandTotal">0.00</span>
  </div>

  <div class="text-center mt-4 d-flex justify-content-center gap-2">
    <button class="btn btn-primary btn-custom" id="submitOrder">
      <i class="bi bi-cart-check-fill"></i> Submit
    </button>
    <button class="btn btn-secondary btn-custom" onclick="history.back()">
      <i class="bi bi-arrow-left"></i> Back
    </button>
  </div>

</div>
</div>
<br><br><br><br><br>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(window).on('load', function () {
    setTimeout(() => $("#pageLoader").fadeOut(300), 200);
});

const rowsPerPage = 10;
let currentPage = 1;

/* -------------------- FILTERED ROWS -------------------- */
function getFilteredRows() {
    const q = $('#searchInput').val().trim().toLowerCase();
    const rows = $('#productTable tbody tr').not('.no-data').not('.no-results');

    if (!q) return rows;

    return rows.filter(function () {
        let p = $(this).find(".pname").text().toLowerCase();
        return p.includes(q);
    });
}

/* -------------------- PAGINATION -------------------- */
function paginateTable() {
    const filtered = getFilteredRows();
    const totalRows = filtered.length;
    const totalPages = Math.max(1, Math.ceil(totalRows / rowsPerPage));

    if (currentPage > totalPages) currentPage = totalPages;

    $('#productTable tbody tr').hide();
    $('.no-results').remove();

    if (totalRows === 0) {
        $('#productTable tbody').append(
            `<tr class='no-results'>
                <td colspan='6' class='text-center'>No matching products.</td>
            </tr>`
        );
        $('#pagination').empty();
        return;
    }

    const start = (currentPage - 1) * rowsPerPage;
    filtered.slice(start, start + rowsPerPage).show();

    /* Build pagination */
    let pag = "";
    for (let i = 1; i <= totalPages; i++) {
        pag += `
        <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#">${i}</a>
        </li>`;
    }
    $("#pagination").html(pag);

    /* Page click */
    $(".page-link").off("click").on("click", function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).text());
        paginateTable();
    });
}

/* -------------------- SEARCH -------------------- */
$("#searchInput").on("input", function () {
    currentPage = 1;
    paginateTable();
});

/* -------------------- QTY INPUT -------------------- */
$(document).on("input", ".qty-input", function () {
    const row = $(this).closest("tr");
    const qty = parseFloat($(this).val()) || 0;
    const rate = parseFloat(row.find(".rate").text()) || 0;

    row.find(".total").text((rate * qty).toFixed(2));

    // qty > 0 → check row
    row.find(".row-check").prop("checked", qty > 0);

    calculateGrandTotal();
});

/* -------------------- INDIVIDUAL CHECKBOX -------------------- */
$(document).on("change", ".row-check", function () {
    const row = $(this).closest("tr");
    const qtyInput = row.find(".qty-input");

    if ($(this).is(":checked")) {
        if (parseFloat(qtyInput.val()) === 0) qtyInput.val(1);
    } else {
        qtyInput.val(0);
    }

    const qty = parseFloat(qtyInput.val()) || 0;
    const rate = parseFloat(row.find(".rate").text()) || 0;
    row.find(".total").text((qty * rate).toFixed(2));

    calculateGrandTotal();
});

/* -------------------- SELECT ALL -------------------- */
$('#selectAll').on('change', function () {
    const checked = $(this).is(':checked');

    const filtered = getFilteredRows();

    filtered.each(function () {
        const row = $(this);
        const cb = row.find('.row-check');
        const qtyInput = row.find('.qty-input');

        cb.prop('checked', checked);

        if (checked) {
            if (parseFloat(qtyInput.val()) === 0) qtyInput.val(1);
        } else {
            qtyInput.val(0);
        }

        const qty = parseFloat(qtyInput.val()) || 0;
        const rate = parseFloat(row.find('.rate').text()) || 0;
        row.find('.total').text((qty * rate).toFixed(2));
    });

    calculateGrandTotal();
    paginateTable(); // keep checkboxes visible & synced
});

/* -------------------- GRAND TOTAL -------------------- */
function calculateGrandTotal() {
    let grand = 0;

    $("#productTable tbody tr").each(function () {
        if ($(this).find(".row-check").is(":checked")) {
            grand += parseFloat($(this).find(".total").text()) || 0;
        }
    });

    $("#grandTotal").text(grand.toFixed(2));
}

/* -------------------- SUBMIT -------------------- */
$("#submitOrder").click(function () {
    const products = [];

    $("#productTable tbody tr .row-check:checked").each(function () {
        const row = $(this).closest("tr");
        const qty = parseFloat(row.find(".qty-input").val()) || 0;
        const price = parseFloat(row.find(".rate").text()) || 0;

        if (qty > 0) {
            products.push({
                prodId: row.data("prodid"),
                mainProdId: row.data("mainprodid"),
                qty: qty,
                price: price,
                total: qty * price
            });
        }
    });

    if (products.length === 0) {
        alert("Please select at least one product!");
        return;
    }

    $.post("save_stock.php", {
        FrId: $("#FrId").val(),
        CreatedBy: $("#CreatedBy").val(),
        products: JSON.stringify(products)
    }, function (res) {
        alert("Saved Successfully!");
        location.reload();
    }, "json");
});

/* -------------------- INIT -------------------- */
$(document).ready(function () {
    paginateTable();
});

</script>

</body>
</html>
