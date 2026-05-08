<?php
include '../config.php';
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Request Orders - Distributor Panel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
  /* ===== Base Design ===== */
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

  /* ===== Search bar ===== */
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
    border-collapse: separate;
    border-spacing: 0 6px;
  }

  .table thead {
    background-color: #6a4fe0;
    color: #fff;
    font-size: 13px;
  }

  .table th {
    font-weight: 600;
    padding: 10px 6px;
    text-align: center;
    border: none;
  }

  .table td {
    font-size: 13px;
    padding: 10px 6px;
    text-align: center;
    vertical-align: middle;
    background-color: #fff;
    border-top: none;
  }

  .table-hover tbody tr:hover {
    background-color: #f5f2ff;
  }

  .order-row {
    background-color: #f9f8ff;
    font-weight: 500;
  }

  .btn-primary {
    background-color: #6a4fe0 !important;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 10px;
  }
  .btn-primary:hover {
    background-color: #5a41c6 !important;
  }

  .btn-success {
    background-color: #00b894 !important;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
  }
  .btn-success:hover {
    background-color: #00a184 !important;
  }

  .btn-secondary {
    background-color: #b2bec3 !important;
    border-radius: 8px;
    font-size: 12px;
  }

  /* ===== Badges ===== */
  .badge-received {
    background-color: #00b894;
    color: #fff;
    font-size: 12px;
    font-weight: 500;
  }
  .badge-pending {
    background-color: #ffe082;
    color: #000;
    font-size: 12px;
  }
  .badge-partial {
    background-color: #6c63ff;
    color: #fff;
    font-size: 12px;
  }

  /* ===== Collapsed Table ===== */
  .order-details .table thead {
    background-color: #f1edff;
    color: #4a3aa0;
  }
  .order-details td {
    background-color: #fff;
    font-size: 12.5px;
  }

  /* ===== Total Section ===== */
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

  .no-results {
    color: #777;
    text-align: center;
    padding: 15px 0;
    font-size: 13px;
  }

  /* ===== Mobile & Responsive ===== */
  @media (max-width: 768px) {
    body { padding: 6px; }
    .card { padding: 14px !important; }
    .page-title { font-size: 1.1rem; margin-bottom: 15px; }
    #searchBox { width: 100%; font-size: 12.5px; }
    .btn { font-size: 12px !important; padding: 6px 10px !important; }
    table th, table td { font-size: 12px !important; padding: 8px 5px !important; }
    .total-box { text-align: center; font-size: 13px; margin-top: 12px; }
  }
  
  /* === Proper Box Borders for Each Table Cell === */
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

.table tbody tr:hover {
  background-color: #f8f5ff !important;
}

/* === Inner Collapsed Table (Products) === */
.order-details .table {
  border-collapse: collapse !important;
  background-color: #faf9ff;
  margin: 5px 0;
}

.order-details .table th,
.order-details .table td {
  border: 1px solid #e3dfff !important;
  font-size: 12.5px;
  background-color: #fff;
}

/* === Rounded corners for first & last cells === */
.table tr:first-child th:first-child,
.table tr:first-child td:first-child {
  border-top-left-radius: 8px;
}

.table tr:first-child th:last-child,
.table tr:first-child td:last-child {
  border-top-right-radius: 8px;
}

/* === Mobile Optimization === */
@media (max-width: 768px) {
  .table th, .table td {
    font-size: 12px !important;
    padding: 8px 6px !important;
  }
}

</style>
</head>

<body>
<!--<div class="container-fluid py-2 px-2 px-md-3">
  <div class="card">-->
  <div style="background-color: white;">
  <div>
    <div class="page-title">📦 View Delivered Request Orders</div>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <input type="text" id="searchBox" class="form-control flex-grow-1" placeholder="Search by Order No, Date, Product, or Amount..." />
      <button id="clearSearch" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Clear</button>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-hover" id="ordersTable">
        <thead class="text-center">
          <tr>
            <!--<th>#</th>-->
            <th>Order No</th>
            <th>Total Qty</th>
            <th>Received Qty</th>
            <th>Pending Qty</th>
            <th>Total (₹)</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $FrId = $_GET['user_id'];
        $sql = "SELECT o.*
        FROM tbl_dealer_req_orders o
        WHERE o.FrId = '$FrId'
        AND EXISTS (
          SELECT 1
          FROM tbl_dealer_req_order_items i
          WHERE i.OrderId = o.OrderId
          AND i.ReceiveStatus IN(1,2)
        )
        ORDER BY o.OrderId DESC";

$result = $conn->query($sql);
$i = 0;
$grandTotal = 0.0;

if ($result && $result->num_rows > 0) {
  while ($order = $result->fetch_assoc()) {
            $i++;
            $OrderId = $order['OrderId'];
            $sqlItems = "SELECT SUM(Qty) AS TotalQty, SUM(ReceiveQty) AS ReceivedQty, SUM(Qty - ReceiveQty) AS PendingQty 
                         FROM tbl_dealer_req_order_items WHERE OrderId='$OrderId'";
            $r2 = $conn->query($sqlItems);
            $item = $r2->fetch_assoc();

            $TotalQty = $item['TotalQty'] ?? 0;
            $ReceivedQty = $item['ReceivedQty'] ?? 0;
            $PendingQty = $item['PendingQty'] ?? 0;
            $grandTotal += (float)$order['TotalAmount'];

            $orderNoEsc = htmlspecialchars($order['OrderNo']);
            $dateFormatted = date("d-M-Y h:i A", strtotime($order['CreatedDate']));
            $totalValueFormatted = number_format($order['TotalAmount'], 2);
            
            $sql = "SELECT * FROM tbl_dealer_req_order_items WHERE OrderId='$OrderId' AND ReceiveStatus=0 LIMIT 1";
            $rncnt = getRow($sql);
            if($rncnt > 0){} else{
            ?>
            <tr class="order-row text-center" id="order-row-<?= $OrderId ?>"
                data-orderno="<?= strtolower($orderNoEsc) ?>"
                data-date="<?= strtolower($dateFormatted) ?>"
                data-products=""
                data-amount="<?= strtolower($totalValueFormatted) ?>">
             <!-- <td><?= $i ?></td>-->
              <td><?= $orderNoEsc ?></td>
              <td><?= $TotalQty ?></td>
              <td class="text-success fw-semibold"><?= $ReceivedQty ?></td>
              <td class="text-danger fw-semibold"><?= $PendingQty ?></td>
              <td>₹<?= $totalValueFormatted ?></td>
              <td><?= $dateFormatted ?></td>
              <td>
                <button class="btn btn-sm btn-primary btn-toggle" data-bs-toggle="collapse" data-bs-target="#order<?= $OrderId ?>">
                  <i class="bi bi-eye"></i> View
                </button>
              </td>
            </tr>

            <tr class="order-details collapse" id="order<?= $OrderId ?>">
              <td colspan="8" class="p-0">
                <div class="table-responsive">
                  <table class="table table-sm table-bordered mb-0 text-center">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Rate (₹)</th>
                        <th>Qty</th>
                        <th>Receive</th>
                        <th>Total (₹)</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $j = 0;
                      $sqlProducts = "SELECT i.*, p.ProductName FROM tbl_dealer_req_order_items i 
                                      LEFT JOIN tbl_cust_products_2025 p ON i.ProdId = p.id 
                                      WHERE i.OrderId = '$OrderId'";
                      $resultItems = $conn->query($sqlProducts);
                      while ($prod = $resultItems->fetch_assoc()) {
                        $j++;
                        $displayReceiveQty = ($prod['ReceiveQty'] == 0) ? $prod['Qty'] : $prod['ReceiveQty'];
                        if ($prod['ReceiveStatus'] == 1 && $displayReceiveQty == $prod['Qty']) {
                          $statusBadge = '<span class="badge bg-success">Received</span>';
                          $showSave = false;
                        } elseif ($displayReceiveQty > 0 && $displayReceiveQty < $prod['Qty']) {
                          $statusBadge = '<span class="badge bg-info text-light">Partial</span>';
                          $showSave = true;
                        } else {
                          $statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                          $showSave = true;
                        }
                        ?>
                        <tr id="item-<?= $prod['ItemId'] ?>">
                          <td><?= $j ?></td>
                          <td><?= htmlspecialchars($prod['ProductName'] ?? '—') ?></td>
                          <td><?= number_format($prod['Price'], 2) ?></td>
                          <td><?= $prod['Qty'] ?></td>
                          <td>
                            <input type="number" min="0" max="<?= $prod['Qty'] ?>" value="<?= $displayReceiveQty ?>" 
                              class="form-control form-control-sm receive-qty-input"
                              data-id="<?= $prod['ItemId'] ?>" style="width:80px; margin:auto;" <?= (!$showSave ? 'readonly' : '') ?> />
                          </td>
                          <td><?= number_format($prod['Total'], 2) ?></td>
                          <td><?= $statusBadge ?></td>
                          <td>
                            <?php if ($showSave) { ?>
                              <button class="btn btn-success btn-sm save-receive" data-id="<?= $prod['ItemId'] ?>" data-max="<?= $prod['Qty'] ?>">
                                <i class="bi bi-save"></i> Save
                              </button>
                            <?php } else { ?>
                              <button class="btn btn-secondary btn-sm" disabled><i class="bi bi-check2-circle"></i> Done</button>
                            <?php } ?>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
        <?php } } } else { echo '<tr><td colspan="8" class="no-results">No delivered or partially delivered orders found.</td></tr>'; } ?>
        </tbody>
      </table>
    </div>

    <nav>
      <ul class="pagination" id="pagination"></ul>
    </nav>

    <div class="total-box">Total Value of All Orders: ₹<?= number_format($grandTotal, 2) ?></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const rowsPerPage = 10;
let currentPage = 1;

function normalize(v){ return (v||'').toString().toLowerCase(); }

function applySearchFilter(term) {
  const q = normalize(term).trim();
  $('#ordersTable tbody tr.order-row').each(function() {
    const row = $(this);
    const orderno = normalize(row.data('orderno'));
    const date = normalize(row.data('date'));
    const amount = normalize(row.data('amount'));
    const matched = q === '' || orderno.includes(q) || date.includes(q) || amount.includes(q);
    row.toggleClass('d-none', !matched);
  });
  paginateTable();
}

function paginateTable() {
  const rows = $('#ordersTable tbody tr.order-row:not(.d-none)');
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

  $(document).on('click', '.btn-toggle', function(e) {
    e.preventDefault();
    const btn = $(this);
    const targetSelector = btn.data('bs-target');
    const targetEl = document.querySelector(targetSelector);
    if (!targetEl) return;

    $('.order-details.show').each(function() {
      const openEl = this;
      const openSelector = '#' + $(openEl).attr('id');
      if (openSelector !== targetSelector) {
        const instOpen = bootstrap.Collapse.getInstance(openEl) || new bootstrap.Collapse(openEl, {toggle:false});
        instOpen.hide();
        $('button[data-bs-target="' + openSelector + '"]').html('<i class="bi bi-eye"></i> View');
      }
    });

    const inst = bootstrap.Collapse.getInstance(targetEl) || new bootstrap.Collapse(targetEl, {toggle:false});
    if (targetEl.classList.contains('show')) {
      inst.hide();
      btn.html('<i class="bi bi-eye"></i> View');
    } else {
      inst.show();
      btn.html('<i class="bi bi-eye-slash"></i> Hide');
    }
  });

  $(document).on('click', '.save-receive', function() {
    const btn = $(this);
    const itemId = btn.data('id');
    const maxQty = parseFloat(btn.data('max'));
    const row = $('#item-' + itemId);
    const input = row.find('.receive-qty-input');
    const qty = parseFloat(input.val()) || 0;
    if (qty > maxQty) {
      alert('⚠️ Receive quantity cannot exceed ordered quantity!');
      input.val(maxQty);
      return;
    }

    btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
    $.ajax({
      url: 'update_receive_status.php',
      type: 'POST',
      dataType: 'json',
      data: { itemId: itemId, qty: qty },
      success: function(res) {
        if (res.status === 'success') {
          let badge = '';
          if (qty === 0) badge = '<span class="badge bg-warning text-dark">Pending</span>';
          else if (qty < maxQty) badge = '<span class="badge bg-info text-light">Partial</span>';
          else badge = '<span class="badge bg-success">Received</span>';
          row.find('td:eq(6)').html(badge);
        } else {
          alert('❌ ' + (res.message || 'Unknown error'));
        }
        btn.prop('disabled', false).html('<i class="bi bi-save"></i> Save');
      },
      error: function(xhr) {
        alert('⚠️ Server Error:\n' + xhr.responseText);
        btn.prop('disabled', false).html('<i class="bi bi-save"></i> Save');
      }
    });
  });

  applySearchFilter('');
});
</script>
</body>
</html>
