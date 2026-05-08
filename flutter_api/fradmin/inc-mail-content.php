<?php
// mail_template.php
// Expected variables: $OrderNo, $distName, $items (array of product rows)

$date = date("d M Y h:i A");
$grandTotal = 0;
$i = 1;

// Start building email HTML
$html = "
<div style='font-family: Arial, sans-serif; background-color:#f8f9fa; padding:20px;'>
  <div style='max-width:700px; margin:auto; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden;'>
    
    <!-- Header -->
    <div style='background-color:#d32f2f; color:#fff; padding:15px 25px; text-align:center;'>
      <img src='http://hpclpos.com/hpcladmin/logo.jpg' alt='HPCL Logo' style='height:55px; margin-bottom:10px;'>
      <h2 style='margin:0;'>HPCL Order Notification</h2>
      <p style='margin:0; font-size:14px;'>Order No: <b>$OrderNo</b> | Date: $date</p>
    </div>

    <!-- Body -->
    <div style='padding:25px;'>
      <p style='font-size:16px;'>Dear <strong>$distName</strong>,</p>
      <p style='color:#444;'>You have received a new order with the following product details:</p>

      <table style='width:100%; border-collapse:collapse; font-size:14px; margin-top:10px;'>
        <thead>
          <tr style='background-color:#f1f1f1;'>
            <th style='border:1px solid #ddd; padding:8px;'>#</th>
            <th style='border:1px solid #ddd; padding:8px;'>Product Name</th>
            <th style='border:1px solid #ddd; padding:8px;'>Qty</th>
            <th style='border:1px solid #ddd; padding:8px;'>Price</th>
            <th style='border:1px solid #ddd; padding:8px;'>Total</th>
          </tr>
        </thead>
        <tbody>";

// Loop through products
foreach ($items as $item) {
  $pname = htmlspecialchars($item['ProductName']);
  $qty = number_format($item['Qty'], 2);
  $price = number_format($item['Price'], 2);
  $total = number_format($item['Total'], 2);
  $grandTotal += $item['Total'];

  $html .= "
          <tr>
            <td style='border:1px solid #ddd; padding:8px; text-align:center;'>$i</td>
            <td style='border:1px solid #ddd; padding:8px;'>$pname</td>
            <td style='border:1px solid #ddd; padding:8px; text-align:center;'>$qty</td>
            <td style='border:1px solid #ddd; padding:8px; text-align:right;'>₹$price</td>
            <td style='border:1px solid #ddd; padding:8px; text-align:right;'>₹$total</td>
          </tr>";
  $i++;
}

$html .= "
        </tbody>
      </table>

      <h3 style='text-align:right; margin-top:20px; color:#d32f2f;'>Grand Total: ₹" . number_format($grandTotal, 2) . "</h3>

      <p style='margin-top:25px; color:#333;'>Kindly process this order at the earliest convenience.</p>
      <p style='color:#555;'>Thank you,<br>
      <strong>HPCL Order System</strong><br>
      <small>Automated Notification – Please Do Not Reply</small></p>
    </div>

    <!-- Footer -->
    <div style='background-color:#f1f1f1; padding:15px; text-align:center; font-size:12px; color:#555;'>
      © " . date("Y") . " HPCL | Powered by <a href='https://kwickbill.in' style='color:#d32f2f; text-decoration:none;'>KwickBill</a>
    </div>
  </div>
</div>
";
?>
