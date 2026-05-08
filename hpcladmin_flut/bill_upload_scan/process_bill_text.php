<?php
function processBillText($text) {
    // Example format in bill text:
    // Product: Aloo, Qty: 5kg, Price: 200
    $lines = explode("\n", $text);
    foreach ($lines as $line) {
        if (preg_match("/Product:\s*(.+),\s*Qty:\s*(\d+)(kg|pcs|ltr),\s*Price:\s*(\d+)/i", $line, $matches)) {
            $productName = trim($matches[1]);
            $quantity = trim($matches[2]);
            $price = trim($matches[4]);

            // Call function to add/update inventory
            addToInventory($productName, $quantity, $price);
        }
    }
}

function addToInventory($name, $qty, $price) {
    $conn = new mysqli("localhost", "persistsolution_mahabuddy", "(e3Xm33qkIrZ", "persistsolution_mahabuddy");
    $name = $conn->real_escape_string($name);

    // Check if product exists
    $result = $conn->query("SELECT * FROM inventory WHERE product_name='$name'");
    if ($result->num_rows > 0) {
        // Update quantity
        $conn->query("UPDATE inventory SET quantity = quantity + $qty WHERE product_name = '$name'");
    } else {
        // Insert new product
        $conn->query("INSERT INTO inventory (product_name, quantity, price) VALUES ('$name', $qty, $price)");
    }

    echo "Product '$name' added/updated successfully.<br>";
}
?>
