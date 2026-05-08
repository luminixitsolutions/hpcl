<?php
include '../config.php';
session_start(); // If using session login for user id

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $deletedBy = $_POST['FrId'] ?? 0; // Current user id

    // Copy Stock Record into Log Table
    $sql_copy = "INSERT INTO tbl_cust_prod_log_2025 
                 SELECT *, '$deletedBy', NOW() 
                 FROM tbl_cust_products_2025 
                 WHERE id='$id'";
    
    if ($conn->query($sql_copy)) {
        
        // Now delete original record
        $sql_delete = "DELETE FROM tbl_cust_products_2025 WHERE id='$id'";
        if ($conn->query($sql_delete)) {
            echo "Stock record deleted successfully!";
        } else {
            echo "Error deleting record!";
        }

    } else {
        echo "Error logging deleted record!";
    }
}
?>
