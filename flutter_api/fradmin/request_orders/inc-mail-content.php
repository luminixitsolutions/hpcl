<?php
// mail_template.php
// Expected variables: $OrderNo, $distName, $dealerDetails (array), $items (array of product rows)
// Example $dealerDetails = ['DealerName'=>'RK Distributers','DealerCode'=>'D12345','Address'=>'Plot No. 7, MIDC, Nagpur - 440001','Contact'=>'Ramesh Patel','Mobile'=>'9876543210'];

$date = date("d M Y h:i A");
$grandTotal = 0;
$i = 1;

$html = "
<div style='font-family:Segoe UI, Arial, sans-serif; background-color:#f3f5f9; padding:20px;'>
  <div style='max-width:700px; margin:auto; background:#ffffff; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.08); overflow:hidden;'>

    <!-- Header -->
    <div style='background:linear-gradient(135deg, #1A237E, #283593); color:#fff; text-align:center; padding:25px 15px;'>
      <div style='background:#ffffff; border-radius:10px; display:inline-block; padding:10px 15px; margin-bottom:10px;'>
        <img src='http://hpclpos.com/hpcladmin/logo.jpg' alt='HPCL Logo' style='height:60px; display:block; margin:auto;'>
      </div>
      <h2 style='margin:10px 0 5px 0; font-size:22px; font-weight:600; letter-spacing:0.5px;'>HPCL Order Notification</h2>
      <p style='margin:0; font-size:14px; color:#e0e0e0;'>Order No: <b>$OrderNo</b> &nbsp; | &nbsp; Date: $date</p>
    </div>

    <!-- Body -->
    <div style='padding:30px 25px;'>
      <p style='font-size:16px; color:#222;'>Dear <strong>$distName</strong>,</p>
      <p style='font-size:15px; color:#444;'>You have received a new order with the following product details:</p>

      <table style='width:100%; border-collapse:collapse; font-size:14px; margin-top:15px;'>
        <thead>
          <tr style='background-color:#283593; color:#fff; text-align:center;'>
            <th style='padding:10px; border:1px solid #e0e0e0;'>#</th>
            <th style='padding:10px; border:1px solid #e0e0e0;'>Product Name</th>
            <th style='padding:10px; border:1px solid #e0e0e0;'>Qty</th>
            <th style='padding:10px; border:1px solid #e0e0e0;'>Price</th>
            <th style='padding:10px; border:1px solid #e0e0e0;'>Total</th>
          </tr>
        </thead>
        <tbody>";

foreach ($items as $item) {
  $pname = htmlspecialchars($item['ProductName']);
  $qty = number_format($item['Qty'], 2);
  $price = number_format($item['Price'], 2);
  $total = number_format($item['Total'], 2);
  $grandTotal += $item['Total'];

  $html .= "
          <tr style='background:#fafafa;'>
            <td style='border:1px solid #e0e0e0; padding:8px; text-align:center;'>$i</td>
            <td style='border:1px solid #e0e0e0; padding:8px;'>$pname</td>
            <td style='border:1px solid #e0e0e0; padding:8px; text-align:center;'>$qty</td>
            <td style='border:1px solid #e0e0e0; padding:8px; text-align:right;'>₹$price</td>
            <td style='border:1px solid #e0e0e0; padding:8px; text-align:right;'>₹$total</td>
          </tr>";
  $i++;
}

$html .= "
        </tbody>
      </table>

      <div style='text-align:right; margin-top:20px;'>
        <h3 style='color:#1A237E; font-size:18px;'>Grand Total: ₹" . number_format($grandTotal, 2) . "</h3>
      </div>

      <!-- Dealer & Delivery Details -->
      <div style='margin-top:30px; background:#f6f7fb; border-left:5px solid #1A237E; padding:20px; border-radius:6px;'>
        <h3 style='margin:0 0 10px 0; color:#1A237E; font-size:18px;'>Dealer & Delivery Details</h3>
        <table style='width:100%; font-size:14px; color:#333;'>
          <tr>
            <td style='padding:5px 0; width:40%;'><strong>Dealer Name:</strong></td>
            <td>" . htmlspecialchars($dealerDetails['ShopName']) . "</td>
          </tr>";

if (!empty($dealerDetails['DealerCode'])) {
  $html .= "
          <tr>
            <td style='padding:5px 0;'><strong>Dealer Code:</strong></td>
            <td>" . htmlspecialchars($dealerDetails['CustomerId']) . "</td>
          </tr>";
}

$html .= "
          <tr>
            <td style='padding:5px 0;'><strong>Delivery Address:</strong></td>
            <td>" . nl2br(htmlspecialchars($dealerDetails['Address'])) . "</td>
          </tr>
          <tr>
            <td style='padding:5px 0;'><strong>Mobile Number:</strong></td>
            <td>" . htmlspecialchars($dealerDetails['Phone']) . "</td>
          </tr>
          <tr>
            <td style='padding:5px 0;'><strong>Email Id:</strong></td>
            <td>" . htmlspecialchars($dealerDetails['EmailId']) . "</td>
          </tr>
        </table>
      </div>

      <p style='margin-top:25px; color:#333;'>Kindly process this order at the earliest convenience.</p>
      <p style='color:#555; font-size:14px; line-height:22px;'>
        Thank you,<br>
        <strong style='color:#1A237E;'>HPCL Order System</strong><br>
        <small>This is an automated notification — please do not reply.</small>
      </p>
    </div>

    <!-- Footer -->
    <div style='background-color:#e8eaf6; padding:15px; text-align:center; font-size:13px; color:#555; border-top:1px solid #c5cae9;'>
      © " . date("Y") . " Hindustan Petroleum Corporation Limited<br>
      <span style='font-size:12px;'>Powered by <a href='https://kwickbill.in' style='color:#1A237E; text-decoration:none; font-weight:600;'>KwickBill</a></span>
    </div>
  </div>
</div>
";
?>
