<?php
include '../config.php';
$query = isset($_POST['query']) ? $conn->real_escape_string($_POST['query']) : '';
$FrId = $_POST['FrId'];
$sql = "SELECT id, ProdId, ProductName, MinPrice 
        FROM tbl_cust_products_2025 
        WHERE checkstatus=1 AND Status=1 AND CreatedBy='$FrId' AND ProductName LIKE '%$query%' 
        LIMIT 10";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $count = 0;
  while ($row = $result->fetch_assoc()) {
    $count++;
    echo '
      <div class="suggestion-item" data-name="' . htmlspecialchars($row['ProductName']) . '" data-price="' . $row['MinPrice'] . '" data-id="' . $row['id'] . '" data-prodid="' . $row['ProdId'] . '">
        <div class="fw-semibold text-dark">' . htmlspecialchars($row['ProductName']) . '</div>
        <small class="text-muted">₹' . number_format($row['MinPrice'], 2) . '</small>
      </div>';
    // Add divider except after the last one
    if ($count < $result->num_rows) echo '<hr class="dropdown-divider my-1">';
  }
} else {
  echo '<div class="suggestion-item text-muted text-center py-2">No results found</div>';
}
$conn->close();
?>