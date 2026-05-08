<?php
include '../config.php';
header('Content-Type: application/json');

$search = trim($_GET['search'] ?? '');
$where = '';

if ($search !== '') {
  $searchEsc = $conn->real_escape_string($search);
  $where = "WHERE (o.OrderNo LIKE '%$searchEsc%' 
            OR o.CreatedDate LIKE '%$searchEsc%'
            OR p.ProductName LIKE '%$searchEsc%')";
}

$sql = "SELECT DISTINCT o.*
        FROM tbl_dealer_req_orders o
        LEFT JOIN tbl_dealer_req_order_items i ON o.OrderId = i.OrderId
        LEFT JOIN tbl_cust_products_2025 p ON i.ProdId = p.id
        $where
        ORDER BY o.OrderId DESC";

$result = $conn->query($sql);
$i = 0;
$grandTotal = 0;
$output = '';

if ($result && $result->num_rows > 0) {
  $output .= '<table class="table table-bordered align-middle table-hover">
  <thead class="text-center">
    <tr>
      <th>#</th>
      <th>Order No</th>
      <th>Total Qty</th>
      <th>Received Qty</th>
      <th>Pending Qty</th>
      <th>Total Value (₹)</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
  </thead><tbody>';

  while ($order = $result->fetch_assoc()) {
    $i++;
    $OrderId = $order['OrderId'];
    $grandTotal += $order['TotalAmount'];

    $sqlItems = "SELECT SUM(Qty) AS TotalQty, SUM(ReceiveQty) AS ReceivedQty, SUM(Qty - ReceiveQty) AS PendingQty 
                 FROM tbl_dealer_req_order_items WHERE OrderId='$OrderId'";
    $r2 = $conn->query($sqlItems);
    $item = $r2->fetch_assoc();

    $TotalQty = $item['TotalQty'] ?? 0;
    $ReceivedQty = $item['ReceivedQty'] ?? 0;
    $PendingQty = $item['PendingQty'] ?? 0;

    $output .= '<tr class="order-row text-center">
      <td>' . $i . '</td>
      <td>' . htmlspecialchars($order['OrderNo']) . '</td>
      <td>' . $TotalQty . '</td>
      <td class="text-success fw-bold">' . $ReceivedQty . '</td>
      <td class="text-danger fw-bold">' . $PendingQty . '</td>
      <td>₹' . number_format($order['TotalAmount'], 2) . '</td>
      <td>' . date("d-M-Y h:i A", strtotime($order['CreatedDate'])) . '</td>
      <td><button class="btn btn-sm btn-primary btn-toggle" data-bs-toggle="collapse" data-bs-target="#order' . $OrderId . '"><i class="bi bi-eye"></i> View</button></td>
    </tr>';

    // product details for collapsible view
    $sqlProducts = "SELECT i.*, p.ProductName 
                    FROM tbl_dealer_req_order_items i 
                    LEFT JOIN tbl_cust_products_2025 p ON i.ProdId = p.id 
                    WHERE i.OrderId = '$OrderId'";
    $resultItems = $conn->query($sqlProducts);

    $output .= '<tr class="collapse" id="order' . $OrderId . '">
      <td colspan="10">
        <table class="table table-sm table-bordered mb-0 text-center">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Product Name</th>
              <th>Price (₹)</th>
              <th>Ordered Qty</th>
              <th>Receive Qty</th>
              <th>Total (₹)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead><tbody>';
    $j = 0;
    while ($prod = $resultItems->fetch_assoc()) {
      $j++;
      $displayReceiveQty = ($prod['ReceiveQty'] == 0) ? $prod['Qty'] : $prod['ReceiveQty'];
      if ($prod['ReceiveStatus'] == 1 && $displayReceiveQty == $prod['Qty']) $statusBadge = '<span class="badge badge-received">Received</span>';
      elseif ($displayReceiveQty > 0 && $displayReceiveQty < $prod['Qty']) $statusBadge = '<span class="badge badge-partial">Partial</span>';
      else $statusBadge = '<span class="badge badge-pending">Pending</span>';

      $output .= '<tr id="item-' . $prod['ItemId'] . '">
        <td>' . $j . '</td>
        <td>' . htmlspecialchars($prod['ProductName'] ?? '—') . '</td>
        <td>' . number_format($prod['Price'], 2) . '</td>
        <td>' . $prod['Qty'] . '</td>
        <td><input type="number" min="0" max="' . $prod['Qty'] . '" step="1" value="' . $displayReceiveQty . '" class="form-control form-control-sm receive-qty-input" data-id="' . $prod['ItemId'] . '" style="width:90px; margin:auto;"></td>
        <td>' . number_format($prod['Total'], 2) . '</td>
        <td>' . $statusBadge . '</td>
        <td><button class="btn btn-success btn-sm save-receive" data-id="' . $prod['ItemId'] . '" data-max="' . $prod['Qty'] . '"><i class="bi bi-save"></i> Save</button></td>
      </tr>';
    }
    $output .= '</tbody></table></td></tr>';
  }

  $output .= '</tbody></table>';
} else {
  $output = '<div class="text-center text-muted py-4">No request orders found.</div>';
}

echo json_encode([
  'table' => $output,
  'total' => 'Total Value of All Orders: ₹' . number_format($grandTotal, 2)
]);
?>
