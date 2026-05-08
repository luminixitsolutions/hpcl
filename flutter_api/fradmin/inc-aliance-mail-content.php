<?php
$from = "From: MAHACHAI";
$subject = "Monthly Sales Figures - $prevMonthName $yearToUse";
$message = "Dear $ShopName,<br><br>";
$message .= "Please find below the sales figures for <strong>$prevMonthName, $yearToUse</strong> to raise the revenue-sharing invoice as per our agreement.<br><br>";
$message .= "<strong>Name of outlet:</strong> $ShopName<br>";
$message .= "<strong>Total Sales for $prevMonthName, $yearToUse:</strong> ₹$formattedNet<br>";
$message .= "<strong>Non GST Sales:</strong> ₹$formattedNongst<br><br>";
$message .= "Please upload the corresponding revenue-sharing invoice and Rent invoice using the link below:<br>";
$message .= "🔗 <a href='$url'>Upload Invoice Here</a><br><br>";
$message .= "If you need any additional information, please let us know.<br><br>";
$message .= "Best regards,<br>";
$message .= "Mahachai Private Limited.<br>";
?>
