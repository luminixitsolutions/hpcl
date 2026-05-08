<?php
include_once '../config.php';

$groupId = $_POST['id'] ?? 0;

$sql = "SELECT ProdId FROM tbl_product_group WHERE id='$groupId'";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
  $row = $res->fetch_assoc();
  $prodIds = trim($row['ProdId'], ',');
  
  if (!empty($prodIds)) {
    $sql2 = "SELECT id, ProductName, MinPrice FROM tbl_cust_products2 WHERE id IN ($prodIds) ORDER BY ProductName ASC";
    $res2 = $conn->query($sql2);

    echo "<table class='table table-bordered table-sm'>";
    echo "<thead><tr><th>ID</th><th>Product Name</th><th>Min Price</th></tr></thead><tbody>";

    if ($res2->num_rows > 0) {
      while ($p = $res2->fetch_assoc()) {
        echo "<tr>
                <td>{$p['id']}</td>
                <td>{$p['ProductName']}</td>
                <td>₹ {$p['MinPrice']}</td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='3' class='text-center text-muted'>No products found.</td></tr>";
    }
    echo "</tbody></table>";
  } else {
    echo "<div class='text-center text-muted'>No products assigned to this group.</div>";
  }
} else {
  echo "<div class='text-center text-muted'>Invalid group selected.</div>";
}
?>
