<?php
header('Content-Type: text/html; charset=utf-8');
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Select Products - Distributor Panel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<style>

  body {
    background-color: #ffffff;
    font-family: 'Inter','Segoe UI', sans-serif;
    color: #2f2f2f;
  }

  .page-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #5b3cc4;
    text-align: center;
    margin-bottom: 20px;
  }

  /* SEARCH BOX */
  #searchInput {
    border: 1px solid #d5cfff;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    margin-bottom: 12px;
   /* max-width: 350px;*/
  }

  #searchInput:focus {
    border-color: #6a4fe0;
    box-shadow: 0 0 0 0.15rem rgba(106, 79, 224, 0.25);
  }

  /* TABLE DESIGN */
  .table thead th {
    background: #6a4fe0 !important;
    color: white !important;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #d8d1ff !important;
    text-align: center;
    vertical-align: middle;
    padding: 10px;
  }

  .table tbody tr td {
    border: 1px solid #e3dfff !important;
    background: #fff;
    font-size: 13px;
    padding: 9px;
    vertical-align: middle;
    color: #2f2f2f;
    text-align: center;
  }

  .table-hover tbody tr:hover {
    background-color: #f8f5ff !important;
  }

  /* Qty Input */
  .qty-input {
    width: 80px;
    text-align: center;
    border-radius: 10px;
    border: 1px solid #d5cfff;
    padding: 4px 8px;
  }

  .qty-input:focus {
    border-color: #6a4fe0;
    box-shadow: 0 0 0 0.12rem rgba(106,79,224,0.25);
  }

  /* TOTAL BOX */
  .total-box {
    background-color: #f9f8ff;
    border: 1px solid #e0dafc;
    border-radius: 12px;
    padding: 10px 14px;
    font-weight: 700;
    color: #3fb984;
    font-size: 14px;
    text-align: right;
    margin-top: 12px;
  }

  /* BUTTONS */
  .btn-primary {
    background-color: #6a4fe0 !important;
    border-radius: 10px !important;
    border: none;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
  }

  .btn-primary:hover {
    background-color: #5a41c6 !important;
  }

  .btn-secondary {
    background-color: #b2bec3 !important;
    border-radius: 10px !important;
    border: none;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
  }

  /* BEAUTIFUL PAGINATION */
  .pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    padding-bottom: 20px;
    list-style: none !important;
  }

  .pagination .page-link {
    border: 1px solid #dcd4ff !important;
    border-radius: 10px !important;
    color: #6a4fe0 !important;
    padding: 7px 16px !important;
    font-weight: 600;
    background: #fff;
  }

  .pagination .page-item.active .page-link {
    background-color: #6a4fe0 !important;
    color: #fff !important;
    border-color: #6a4fe0 !important;
  }

</style>
<style>
/* Match your original design */
.select2-container .select2-selection--single {
    height: 45px !important;
    border: 1px solid #dcd4ff !important;
    border-radius: 10px !important;
    padding: 8px 14px !important;
    font-size: 15px;
    color: #333 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px !important;
    padding-left: 0 !important;
}

/* Dropdown arrow alignment */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
    right: 10px !important;
}

/* Dropdown popup */
.select2-dropdown {
    border-radius: 10px !important;
    border: 1px solid #dcd4ff !important;
}

/* Search input inside dropdown */
.select2-search__field {
    border-radius: 8px !important;
    padding: 6px !important;
    border: 1px solid #dcd4ff !important;
}

/* FIX: Dropdown item hover not visible */
.select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #6a4fe0 !important;  /* your purple highlight */
    color: #fff !important;                 /* white readable text */
}

/* FIX: Remove faint overlay color from default theme */
.select2-results__option--highlighted {
    background-color: #6a4fe0 !important;
    color: white !important;
}

/* Ensure normal items remain visible */
.select2-results__option {
    color: #333 !important;
    padding: 10px 12px;
    font-size: 14px;
}

</style>

</head>

<body>
<?php $FrId = $_REQUEST['user_id'];?>
<div class="container-fluid pt-3">
    
    <div class="page-title">Select Products to Order</div>
    
<div class="row mb-3 px-2">

  <!-- VENDOR -->
  <div class="col-md-3 mb-2">
      <label class="form-label fw-bold mb-1">Vendor</label>
      <select id="vendorFilter" class="form-select">
          <option value="all">ALL Vendors</option>
          <?php
              $sqlV = "SELECT DISTINCT tu.id, tu.Fname 
                      FROM tbl_cust_products_2025 p
                      INNER JOIN tbl_users tu ON tu.id = p.BrandId
                      WHERE p.checkstatus=1 AND p.Status=1 AND p.CreatedBy='$FrId'
                      ORDER BY tu.Fname ASC";
              $rv = $conn->query($sqlV);
              while ($v = $rv->fetch_assoc()) {
                  echo "<option value='{$v['Fname']}'>{$v['Fname']}</option>";
              }
          ?>
      </select>
  </div>

  <!-- CATEGORY -->
  <div class="col-md-3 mb-2">
      <label class="form-label fw-bold mb-1">Category</label>
      <select id="categoryFilter" class="form-select">
          <option value="all">ALL Categories</option>
          <?php
              $sqlC = "SELECT DISTINCT c.Name 
                      FROM tbl_cust_products_2025 p
                      INNER JOIN tbl_cust_category_2025 c ON c.id=p.CatId
                      WHERE p.checkstatus=1 AND p.Status=1 AND p.CreatedBy='$FrId'
                      ORDER BY c.Name ASC";
              $rc = $conn->query($sqlC);
              while ($c = $rc->fetch_assoc()) {
                  echo "<option value='{$c['Name']}'>{$c['Name']}</option>";
              }
          ?>
      </select>
  </div>

  <!-- SUB CATEGORY -->
  <div class="col-md-3 mb-2">
      <label class="form-label fw-bold mb-1">Sub Category</label>
      <select id="subCategoryFilter" class="form-select">
          <option value="all">ALL Sub Categories</option>
          <?php
              $sqlSC = "SELECT DISTINCT cs.Name 
                        FROM tbl_cust_products_2025 p
                        LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId
                        WHERE p.checkstatus=1 AND p.Status=1 AND p.CreatedBy='$FrId' 
                        AND cs.Name!=''
                        ORDER BY cs.Name ASC";
              $rsc = $conn->query($sqlSC);
              while ($sc = $rsc->fetch_assoc()) {
                  echo "<option value='{$sc['Name']}'>{$sc['Name']}</option>";
              }
          ?>
      </select>
  </div>

  <!-- CLEAR BUTTON -->
  <div class="col-md-2 mb-2 d-flex align-items-end">
      <button id="clearFilters" class="btn btn-outline-secondary w-100">
          Clear Filters
      </button>
  </div>

</div>



 <div class="mb-3 px-2">
    <input type="text" id="searchInput" class="form-control" placeholder="Search product..." style="width: 50%;">
  </div>
    <input type="hidden" id="FrId" value="<?php echo $_GET['user_id'] ?? 1; ?>">
    <input type="hidden" id="CreatedBy" value="<?php echo $_GET['user_id'] ?? 1; ?>">

    <div class="table-responsive">
      <table class="table table-hover" id="productTable">
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Product</th>
            <th>Vendor</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Rate (Rs)</th>
            <th>Total Stock</th>
            <th>Qty</th>
            <th>Total (Rs)</th>
          </tr>
        </thead>
        <tbody>

<?php
$FrId = $_REQUEST['user_id'];

$sqlStock = "
    SELECT t.ProdId,
    COALESCE(SUM(CASE WHEN t.Status='Cr' THEN t.Qty ELSE 0 END),0) AS Cr,
    COALESCE(SUM(CASE WHEN t.Status='Dr' THEN t.Qty ELSE 0 END),0) AS Dr
    FROM tbl_cust_prod_stock_2025 t
    GROUP BY t.ProdId
";
$stockArr = [];
$resS = $conn->query($sqlStock);
while($s = $resS->fetch_assoc()){
    $stockArr[$s['ProdId']] = $s['Cr'] - $s['Dr'];
}

$sql = "SELECT p.id, p.ProdId, p.ProductName, p.MinPrice,tu.Fname,c.Name AS CatName,cs.Name AS SubCatName 
        FROM tbl_cust_products_2025 p 
        LEFT JOIN tbl_users tu on tu.id=p.BrandId 
        LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
        LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId 
        WHERE p.checkstatus=1 AND p.Status=1 AND p.CreatedBy='$FrId'
        ORDER BY p.ProductName ASC";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        $stock = $stockArr[$r['id']] ?? 0;

        echo "
        <tr data-prodid='{$r['id']}' data-mainprodid='{$r['ProdId']}'>
            <td><input type='checkbox' class='row-check'></td>
            <td class='pname'>{$r['ProductName']}</td>
            <td class='pname'>{$r['Fname']}</td>
            <td class='pname'>{$r['CatName']}</td>
            <td class='pname'>{$r['SubCatName']}</td>
            <td class='rate'>{$r['MinPrice']}</td>
            <td>{$stock}</td>
            <td><input type='number' class='qty-input' min='0' value='0'></td>
            <td class='total'>0.00</td>
        </tr>";
    }
}
?>

        </tbody>
      </table>
    </div>

    <ul class="pagination" id="pagination"></ul>

    <div class="total-box">
      Total Amount: Rs.<span id="grandTotal">0.00</span>
    </div>

    <div class="text-center mt-4 d-flex justify-content-center gap-2">
      <button class="btn btn-primary" id="submitOrder">
        <i class="bi bi-cart-check-fill"></i> Submit Order
      </button>
      <button class="btn btn-secondary" onclick="history.back()">
        <i class="bi bi-arrow-left"></i> Back
      </button>
    </div>

</div>
<br><br><br>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
/* Search + Dropdown Filter + Pagination + Select All Logic */

let rowsPerPage = 10;
let currentPage = 1;

/* ----------------- FILTER FUNCTION ------------------ */
function applyFilters() {
    currentPage = 1;
    paginateTable();
}

/* ----------------- GET FILTERED ROWS ------------------ */
function getFilteredRows() {
    let searchText = $("#searchInput").val().toLowerCase();
    let vendor = $("#vendorFilter").val().toLowerCase();
    let category = $("#categoryFilter").val().toLowerCase();
    let subcat = $("#subCategoryFilter").val().toLowerCase();

    return $("#productTable tbody tr").filter(function () {
        let row = $(this);

        let prod = row.find(".pname").eq(0).text().toLowerCase();
        let ven = row.find(".pname").eq(1).text().toLowerCase();
        let cat = row.find(".pname").eq(2).text().toLowerCase();
        let sc = row.find(".pname").eq(3).text().toLowerCase();

        let combined = prod + " " + ven + " " + cat + " " + sc;

        // SEARCH
        if (searchText && !combined.includes(searchText)) return false;

        // IGNORE FILTER IF "all" SELECTED
        if (vendor !== "all" && vendor !== "" && ven !== vendor) return false;
        if (category !== "all" && category !== "" && cat !== category) return false;
        if (subcat !== "all" && subcat !== "" && sc !== subcat) return false;

        return true;
    });
}

/* ----------------- PAGINATION ------------------ */
function paginateTable() {
    const rows = getFilteredRows();
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;

    if (currentPage > totalPages) currentPage = totalPages;

    $("#productTable tbody tr").hide();

    const start = (currentPage - 1) * rowsPerPage;
    rows.slice(start, start + rowsPerPage).show();

    let html = "";
    for (let i = 1; i <= totalPages; i++) {
        html += `
        <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#">${i}</a>
        </li>`;
    }
    $("#pagination").html(html);

    $(".page-link").click(function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).text());
        paginateTable();
    });
}

/* ----------------- INPUT SEARCH ------------------ */
$("#searchInput").on("input", function () {
    applyFilters();
});

/* ----------------- DROPDOWN FILTER EVENTS ------------------ */
$("#vendorFilter, #categoryFilter, #subCategoryFilter").on("change", function () {
    applyFilters();
});

/* ----------------- QUANTITY INPUT ------------------ */
$(document).on("input", ".qty-input", function () {
    let row = $(this).closest("tr");
    let qty = parseFloat($(this).val()) || 0;
    let rate = parseFloat(row.find(".rate").text()) || 0;
    row.find(".total").text((qty * rate).toFixed(2));

    row.find(".row-check").prop("checked", qty > 0);
    calculateGrandTotal();
});

/* ----------------- INDIVIDUAL CHECKBOX ------------------ */
$(document).on("change", ".row-check", function () {
    const row = $(this).closest("tr");
    const qtyInput = row.find(".qty-input");

    if ($(this).is(":checked")) {
        if (!qtyInput.val() || qtyInput.val() == 0) qtyInput.val(1);
    } else {
        qtyInput.val(0);
    }

    let qty = parseFloat(qtyInput.val());
    let rate = parseFloat(row.find(".rate").text());
    row.find(".total").text((qty * rate).toFixed(2));

    calculateGrandTotal();
});

/* ----------------- SELECT ALL ------------------ */
$("#selectAll").on("change", function () {
    const checked = $(this).is(":checked");
    const rows = getFilteredRows();

    rows.each(function () {
        const cb = $(this).find(".row-check");
        const qty = $(this).find(".qty-input");
        cb.prop("checked", checked);
        qty.val(checked ? 1 : 0);

        $(this).find(".total").text(
            (parseFloat($(this).find(".rate").text()) * (checked ? 1 : 0)).toFixed(2)
        );
    });

    calculateGrandTotal();
    paginateTable();
});

/* ----------------- GRAND TOTAL ------------------ */
function calculateGrandTotal() {
    let sum = 0;
    $("#productTable tbody tr").each(function () {
        if ($(this).find(".row-check").is(":checked")) {
            sum += parseFloat($(this).find(".total").text()) || 0;
        }
    });
    $("#grandTotal").text(sum.toFixed(2));
}

/* ----------------- SUBMIT ORDER ------------------ */
$("#submitOrder").click(function () {
    const arr = [];

    $("#productTable tbody tr .row-check:checked").each(function () {
        let row = $(this).closest("tr");

        arr.push({
            prodId: row.data("prodid"),
            mainProdId: row.data("mainprodid"),
            qty: parseFloat(row.find(".qty-input").val()),
            price: parseFloat(row.find(".rate").text()),
            total: parseFloat(row.find(".total").text())
        });
    });

    if (arr.length === 0) {
        alert("Please select at least one product!");
        return;
    }

    $.post(
        "save_request_order.php",
        {
            FrId: $("#FrId").val(),
            CreatedBy: $("#CreatedBy").val(),
            products: JSON.stringify(arr)
        },
        function (res) {
            alert("Order Saved!");
            location.reload();
        },
        "json"
    );
});

/* ----------------- INIT ------------------ */
$(document).ready(function () {
    paginateTable();

    $("#vendorFilter, #categoryFilter, #subCategoryFilter").select2({
        placeholder: "Select Option",
        allowClear: true,
        width: "100%"
    });
});

/* ----------------- CLEAR FILTER BUTTON ------------------ */
$("#clearFilters").click(function () {
    $("#vendorFilter").val("all").trigger("change");
    $("#categoryFilter").val("all").trigger("change");
    $("#subCategoryFilter").val("all").trigger("change");
    $("#searchInput").val("");

    applyFilters();
});
</script>



</body>
</html>
