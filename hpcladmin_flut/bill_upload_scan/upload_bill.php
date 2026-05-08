<?php
require 'vendor/autoload.php'; // for Tesseract OCR via thiagoalessio/tesseract_ocr

use thiagoalessio\TesseractOCR\TesseractOCR;

// File Upload
if (isset($_FILES['bill_file'])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["bill_file"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["bill_file"]["tmp_name"], $targetFile)) {
        // OCR to extract text
        $text = (new TesseractOCR($targetFile))->run();

        // Process Extracted Text
        include 'process_bill_text.php';
        processBillText($text);
    } else {
        echo "File upload failed.";
    }
}
?>
