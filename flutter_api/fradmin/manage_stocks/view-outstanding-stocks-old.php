<?php
include '../config.php';
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Total Outstanding Records - Distributor Panel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
  /* ===== Base Styling ===== */
  body {
    background-color: #fff;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    color: #2f2f2f;
    padding: 0px;
  }

  .card {
    background-color: #fff;
    border: none;
    border-radius: 16px;
    box-shadow: 0 3px 12px rgba(90, 60, 200, 0.08);
    padding: 20px 24px;
  }

  .page-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #5b3cc4;
    text-align: center;
    margin-bottom: 18px;
  }

  /* ===== Search Bar ===== */
  #searchBox {
    border-radius: 10px;
    border: 1px solid #d8d1ff;
    font-size: 13px;
    padding: 8px 12px;
  }
  #searchBox:focus {
    border-color: #7a5fff;
    box-shadow: 0 0 0 0.15rem rgba(124, 91, 255, 0.2);
  }

  .btn-outline-secondary {
    color: #6a4fe0;
    border-color: #6a4fe0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    padding: 6px 14px;
  }
  .btn-outline-secondary:hover {
    background-color: #6a4fe0;
    color: #fff;
  }

  /* ===== Table Design ===== */
  .table {
    border-collapse: collapse !important;
    width: 100%;
    background-color: #fff;
  }

  .table th, .table td {
    border: 1px solid #e3dfff !important;
    padding: 10px 8px !important;
    text-align: center;
    vertical-align: middle;
  }

  .table thead th {
    background-color: #6a4fe0 !important;
    color: #fff !important;
    font-size: 13px;
    font-weight: 600;
  }

  .table td {
    font-size: 13px;
    color: #333;
  }

  .table tbody tr:hover {
    background-color: #f8f5ff !important;
  }

  /* ===== Total Box ===== */
  .total-box {
    background-color: #f9f8ff;
    border: 1px solid #e0dafc;
    border-radius: 12px;
    padding: 10px 14px;
    font-weight: 700;
    color: #3fb984;
    text-align: right;
    font-size: 14px;
    margin-top: 10px;
  }

  /* ===== Pagination ===== */
  .pagination {
    justify-content: center;
    margin-top: 15px;
  }

  .pagination .page-item .page-link {
    border: 1px solid #d8d1ff;
    color: #6a4fe0;
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 8px;
    margin: 0 2px;
  }

  .pagination .page-item.active .page-link {
    background-color: #6a4fe0;
    color: #fff;
    border-color: #6a4fe0;
  }

  .no-results {
    color: #777;
    text-align: center;
    padding: 15px 0;
    font-size: 13px;
  }

  /* ===== Responsive Styling ===== */
  @media (max-width: 768px) {
    body { padding: 6px; }
    .card { padding: 14px !important; }
    .page-title { font-size: 1.1rem; margin-bottom: 15px; }
    #searchBox { width: 100%; font-size: 12.5px; }
    .btn { font-size: 12px !important; padding: 6px 10px !important; }
    table th, table td { font-size: 12px !important; padding: 8px 5px !important; }
    .total-box { text-align: center; font-size: 13px; margin-top: 12px; }
  }
</style>
</head>

<body>
<!--<div class="container-fluid py-2 px-2 px-md-3">
  <div class="card">-->
  <div style="background-color: white;">
  <div>
    <div class="page-title">💼 Total Outstanding Records</div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <input type="text" id="searchBox" class="form-control flex-grow-1" placeholder="Search by Product Name..." />
      <button id="clearSearch" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Clear</button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle table-hover" id="outstandingTable">
        <thead class="text-center">
          <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Total Credit (Qty)</th>
            <th>Total Debit (Qty)</th>
            <th>Balance (Qty)</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $FrId = $_GET['user_id'];

        $sql = "
          SELECT 
            p.ProductName,
            COALESCE(SUM(CASE WHEN t.Status = 'Cr' THEN t.Qty ELSE 0 END), 0) AS TotalCredit,
            COALESCE(SUM(CASE WHEN t.Status = 'Dr' THEN t.Qty ELSE 0 END), 0) AS TotalDebit
          FROM tbl_cust_prod_stock_2025 t
          LEFT JOIN tbl_cust_products_2025 p ON t.ProdId = p.id
          WHERE t.FrId = '$FrId' AND p.ProductName!=''
          GROUP BY t.ProdId
          ORDER BY p.ProductName ASC
        ";
        
        $result = $conn->query($sql);
        $i = 0;
        $totalCreditAll = $totalDebitAll = $balanceAll = 0;

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $i++;
            $ProductName = htmlspecialchars($row['ProductName'] ?? '—');
            $credit = (float)$row['TotalCredit'];
            $debit  = (float)$row['TotalDebit'];
            $balance = $credit - $debit;
            $totalCreditAll += $credit;
            $totalDebitAll  += $debit;
            $balanceAll     += $balance;
            ?>
            <tr class="text-center outstanding-row" data-product="<?= strtolower($ProductName) ?>">
              <td><?= $i ?></td>
              <td><?= $ProductName ?></td>
              <td class="text-success fw-semibold"><?= number_format($credit, 2) ?></td>
              <td class="text-danger fw-semibold"><?= number_format($debit, 2) ?></td>
              <td class="fw-bold <?= $balance >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($balance, 2) ?></td>
            </tr>
        <?php
          }
        } else {
          echo '<tr><td colspan="5" class="no-results">No outstanding records found.</td></tr>';
        }
        ?>
        </tbody>
      </table>
    </div>

    <nav>
      <ul class="pagination" id="pagination"></ul>
    </nav>

    <div class="total-box">
      Total Credit: <?= number_format($totalCreditAll, 2) ?> |
      Total Debit: <?= number_format($totalDebitAll, 2) ?> |
      <span class="<?= $balanceAll >= 0 ? 'text-success' : 'text-danger' ?>">Balance: <?= number_format($balanceAll, 2) ?></span>
    </div>

    <div id="noResultsMessage" class="no-results" style="display:none;">No matching products found.</div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
const rowsPerPage = 10;
let currentPage = 1;

function normalize(v){ return (v||'').toString().toLowerCase(); }

function applySearchFilter(term) {
  const q = normalize(term).trim();
  $('#outstandingTable tbody tr.outstanding-row').each(function() {
    const row = $(this);
    const product = normalize(row.data('product'));
    const matched = q === '' || product.includes(q);
    row.toggleClass('d-none', !matched);
  });
  paginateTable();
}

function paginateTable() {
  const rows = $('#outstandingTable tbody tr.outstanding-row:not(.d-none)');
  const totalRows = rows.length;
  const totalPages = Math.ceil(totalRows / rowsPerPage);

  rows.hide();
  const start = (currentPage - 1) * rowsPerPage;
  rows.slice(start, start + rowsPerPage).show();

  const pagination = $('#pagination');
  pagination.empty();
  if (totalPages > 1) {
    for (let i = 1; i <= totalPages; i++) {
      const active = i === currentPage ? 'active' : '';
      pagination.append(`<li class="page-item ${active}"><a class="page-link" href="#">${i}</a></li>`);
    }
  }

  $('#pagination .page-link').off('click').on('click', function(e) {
    e.preventDefault();
    currentPage = parseInt($(this).text());
    paginateTable();
  });

  $('#noResultsMessage').toggle(totalRows === 0);
}

$(document).ready(function() {
  $('#searchBox').on('input', function() {
    currentPage = 1;
    applySearchFilter($(this).val());
  });

  $('#clearSearch').on('click', function() {
    $('#searchBox').val('');
    currentPage = 1;
    applySearchFilter('');
  });

  applySearchFilter('');
});
</script>
</body>
</html>
