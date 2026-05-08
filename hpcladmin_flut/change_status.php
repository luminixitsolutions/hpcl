<?php
include 'config.php';

if(isset($_POST['id'], $_POST['status'])){
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    $sql = "UPDATE tbl_cust_products2 SET Status='$status' WHERE id='$id'";
    if(mysqli_query($conn, $sql)){
        echo "success";
    } else {
        echo "error";
    }
}
?>
