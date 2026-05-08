<?php
include '../config.php';
header('Content-Type: application/json');

try {
  // --- Inputs ---
  $FrId = isset($_POST['FrId']) ? intval($_POST['FrId']) : 0;
  $CreatedBy = isset($_POST['CreatedBy']) ? intval($_POST['CreatedBy']) : 0;
  $products = isset($_POST['products']) ? json_decode($_POST['products'], true) : [];
     
  if (empty($products)) {
    echo json_encode(['status' => 'error', 'message' => 'No products found.']);
    exit;
  }

  mysqli_begin_transaction($conn);

  // --- Get all assigned distributor products ---
  $distributorProducts = [];
  $resAllowed = $conn->query("SELECT ProdId, CreatedBy FROM tbl_distributer_products WHERE checkstatus=1 AND Status=1");
  if ($resAllowed && $resAllowed->num_rows > 0) {
    while ($row = $resAllowed->fetch_assoc()) {
      $distributorProducts[intval($row['ProdId'])] = intval($row['CreatedBy']);
    }
  }

  // 1️⃣ Generate Unique Order Number
  $prefix = "HP" . date("Ymd") . "-";
  $result = $conn->query("SELECT COUNT(*) AS cnt FROM tbl_dealer_req_orders WHERE DATE(CreatedDate) = CURDATE()");
  $row = $result->fetch_assoc();
  $nextNo = str_pad(($row['cnt'] + 1), 4, '0', STR_PAD_LEFT);
  $OrderNo = $prefix . $nextNo;

  // 2️⃣ Calculate Total
  $TotalAmount = 0;
  foreach ($products as $p) {
    $TotalAmount += floatval($p['total'] ?? 0);
  }

  // 3️⃣ Insert Order Header
  $OrderNoEsc = $conn->real_escape_string($OrderNo);
  $sqlOrder = "
    INSERT INTO tbl_dealer_req_orders (OrderNo, FrId, DistId, TotalAmount, CreatedBy)
    VALUES ('$OrderNoEsc', '$FrId', 0, '$TotalAmount', '$CreatedBy')
  ";
  if (!$conn->query($sqlOrder)) {
    throw new Exception("Order header insert failed: " . $conn->error);
  }

  $OrderId = $conn->insert_id;
   
  // 4️⃣ Insert Each Product with proper DistId
  foreach ($products as $p) {
    $ProdId = intval($p['prodId'] ?? 0);
    $MainProdId = intval($p['mainProdId'] ?? 0);
    $Price = floatval($p['price'] ?? 0);
    $Qty = floatval($p['qty'] ?? 0);
    $Total = floatval($p['total'] ?? 0);

    $DistId = 0;
    if (isset($distributorProducts[$MainProdId])) {
      $DistId = $distributorProducts[$MainProdId];
    } elseif (isset($distributorProducts[$ProdId])) {
      $DistId = $distributorProducts[$ProdId];
    }

    $sqlItem = "
      INSERT INTO tbl_dealer_req_order_items 
      (OrderId, FrId, DistId, ProdId, MainProdId, Price, Qty, Total)
      VALUES ('$OrderId', '$FrId', '$DistId', '$ProdId', '$MainProdId', '$Price', '$Qty', '$Total')
    ";
    if (!$conn->query($sqlItem)) {
      throw new Exception("Product insert failed: " . $conn->error);
    }
  }

  mysqli_commit($conn);

  // 5️⃣ Prepare Distributor-wise Email Sending
  
  $result = $conn->query("SELECT ShopName,CustomerId,Phone,Address,EmailId FROM tbl_users WHERE id='$FrId'");
  $dealerDetails = $result->fetch_assoc();
  
  $resItems = $conn->query("
    SELECT i.*, p.ProductName 
    FROM tbl_dealer_req_order_items i
    LEFT JOIN tbl_cust_products_2025 p ON i.ProdId = p.id
    WHERE i.OrderId = '$OrderId' AND i.DistId > 0
  ");

  $distWise = [];
  while ($row = $resItems->fetch_assoc()) {
    $distWise[$row['DistId']][] = $row;
  }

  foreach ($distWise as $distId => $items) {
    $resUser = $conn->query("SELECT Fname, EmailId FROM tbl_users WHERE id='$distId' LIMIT 1");
    if ($resUser && $resUser->num_rows > 0) {
      $user = $resUser->fetch_assoc();
      $distName = $user['Fname'];
     $to = $user['EmailId'] ?? 'nileshgiradkar1@gmail.com';
    if (empty($to)) {
      $to = 'nileshgiradkar1@gmail.com';
    }
     //$to = 'rajatdh07@gmail.com';
      $OrderNo = $OrderNo; // ensure available
      // Build email content
      include 'inc-mail-content.php'; // builds $html variable
      $subject = "New Order Received - $OrderNo";
      $message = $html;
      include '../sendmailsmtp.php'; // sends email using above vars
    }
  }

  echo json_encode([
    'status' => 'success',
    'OrderNo' => $OrderNo,
    'message' => 'Order saved successfully. Distributor emails sent.'
  ]);

} catch (Exception $e) {
  mysqli_rollback($conn);
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
