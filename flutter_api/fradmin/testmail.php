<?php 
include 'config.php';
$OrderId = 17;
 // 5️⃣ Prepare Distributor-wise Email Sending
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
     /*$to = $user['EmailId'] ?? 'rajatdh07@gmail.com';
    if (empty($to)) {
      $to = 'rajatdh07@gmail.com';
    }*/
     $to = 'rajatdh07@gmail.com';
      $OrderNo = $OrderNo; // ensure available
      // Build email content
      include 'inc-mail-content.php'; // builds $html variable
      $subject = "New Order Received - $OrderNo";
      $message = $html;
      include 'sendmailsmtp.php'; // sends email using above vars
    }
  }
  
  ?>