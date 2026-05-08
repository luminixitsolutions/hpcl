<?php
require 'vendor/autoload.php'; // Load Composer dependencies
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database Connection
$host = 'localhost';
$dbname = 'persistsolution_mahabuddy';
$username = 'persistsolution_mahabuddy';
$password = '(e3Xm33qkIrZ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Stock Data
    $stmt = $pdo->query("SELECT * FROM tbl_units_2025");
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$stocks) {
        die("No stock data found.");
    }

    // Create Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set Headers
    $sheet->setCellValue('A1', 'Product Name');
    $sheet->setCellValue('B1', 'Quantity');
    $sheet->setCellValue('C1', 'Price');
    $sheet->setCellValue('D1', 'Last Updated');

    // Fill Data
    $row = 2;
    foreach ($stocks as $stock) {
        $sheet->setCellValue("A$row", $stock['Name']);
        $sheet->setCellValue("B$row", 10);
        $sheet->setCellValue("C$row", 100);
        $sheet->setCellValue("D$row", '06/02/2025');
        $row++;
    }

    // Save Excel File
    $filename = "stock_report_" . date('Y-m-d') . ".xlsx";
    $filepath = __DIR__ . '/' . $filename;
    $writer = new Xlsx($spreadsheet);
    $writer->save($filepath);

    // Send Email with Attachment
    sendEmailWithAttachment($filepath, $filename);

    // Remove file after sending
    unlink($filepath);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Function to Send Email
function sendEmailWithAttachment($filepath, $filename) {
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'mail.persistsolution.com'; // Change if needed
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@persistsolution.com'; // Your email
        $mail->Password = '5%t7tkJMYH%W'; // App password or actual password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Details
        $mail->setFrom('noreply@persistsolution.com', 'Stock Report');
        $mail->addAddress('rajatdh07@gmail.com'); // Receiver's email
        $mail->Subject = 'Daily Stock Report';
        $mail->Body = "Please find the attached stock report.";

        // Attach Excel file
        $mail->addAttachment($filepath, $filename);

        // Send Email
        $mail->send();
        echo "Stock report sent successfully.";
    } catch (Exception $e) {
        echo "Email error: " . $mail->ErrorInfo;
    }
}
?>
